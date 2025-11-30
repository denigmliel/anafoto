<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Exports\TransactionDetailExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardController extends Controller
{
    private const LOW_STOCK_THRESHOLD = 3;

    public function index(Request $request)
    {
        $today = Carbon::today();
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $todayTransactions = Transaction::whereDate('transaction_date', $today)->get();
        $monthTransactions = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth]);

        $todaySalesTotal = $todayTransactions->sum('total_amount');
        $todayTransactionCount = $todayTransactions->count();
        $todayAvgOrder = $todayTransactionCount > 0
            ? $todaySalesTotal / $todayTransactionCount
            : 0;

        $monthSalesTotal = (clone $monthTransactions)->sum('total_amount');
        $monthTransactionCount = (clone $monthTransactions)->count();

        $recapRange = $this->resolveRecapRange($request->query('range'));
        $recapLength = $this->resolveRecapLength($recapRange, $request->query('length'));
        $transactionRecap = $this->buildTransactionRecap($recapRange, $recapLength);
        [$recapHeading, $recapBadge] = $this->recapMeta($recapRange, $recapLength);
        $recapOptionList = $this->recapOptions($recapRange, $recapLength);
        $chartMeta = $this->chartMeta($recapRange);
        $chartData = $this->buildChartData($recapRange);

        $metrics = [
            'todaySalesTotal' => $todaySalesTotal,
            'todayTransactionCount' => $todayTransactionCount,
            'todayAvgOrder' => $todayAvgOrder,
            'monthSalesTotal' => $monthSalesTotal,
            'monthTransactionCount' => $monthTransactionCount,
            'productCount' => Product::count(),
            'activeProductCount' => Product::where('is_active', true)->count(),
            'lowStockCount' => Product::where('is_stock_unlimited', false)
                ->where('stock', '<=', self::LOW_STOCK_THRESHOLD)
                ->count(),
        ];

        $lowStockProducts = Product::where('is_stock_unlimited', false)
            ->where('stock', '<=', self::LOW_STOCK_THRESHOLD)
            ->orderBy('stock')
            ->orderBy('name')
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->latest('transaction_date')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'transactionRecap' => $transactionRecap,
            'recapRange' => $recapRange,
            'recapBadge' => $recapBadge,
            'recapHeading' => $recapHeading,
            'recapOptionList' => $recapOptionList,
            'recapLength' => $recapLength,
            'chartLabels' => $chartData['labels'],
            'chartTotals' => $chartData['totals'],
            'chartCounts' => $chartData['counts'],
            'chartHeading' => $chartMeta['heading'],
            'chartSubtitle' => $chartMeta['subtitle'],
            'chartBadge' => $chartMeta['badge'],
            'lowStockProducts' => $lowStockProducts,
            'recentTransactions' => $recentTransactions,
            'lowStockThreshold' => self::LOW_STOCK_THRESHOLD,
            'todayLabel' => $today->translatedFormat('d M Y'),
        ]);
    }

    public function exportRecap(Request $request)
    {
        $recapRange = $this->resolveRecapRange($request->query('range'));
        $recapLength = $this->resolveRecapLength($recapRange, $request->query('length'));
        [$start, $end] = $this->recapWindow($recapRange, $recapLength);

        $rangeLabel = match ($recapRange) {
            'weekly' => 'mingguan',
            'yearly' => 'tahunan',
            default => 'bulanan',
        };

        $timestamp = now()->format('Ymd_His');
        $filename = "transaksi-{$rangeLabel}-{$timestamp}.xlsx";

        return Excel::download(new TransactionDetailExport($start, $end, $rangeLabel), $filename);
    }

    private function resolveRecapRange(?string $range): string
    {
        $allowed = ['weekly', 'monthly', 'yearly'];

        return in_array($range, $allowed, true) ? $range : 'weekly';
    }

    private function resolveRecapLength(string $range, $length): int
    {
        // Lock each range to a single span as requested.
        return match ($range) {
            'weekly' => 1,
            'yearly' => 1,
            default => 1,
        };
    }

    private function recapMeta(string $range, int $length): array
    {
        return match ($range) {
            'weekly' => ['Rekap Transaksi Mingguan', '1 minggu'],
            'yearly' => ['Rekap Transaksi Tahunan', '1 tahun'],
            default => ['Rekap Transaksi Bulanan', '1 bulan'],
        };
    }

    private function buildTransactionRecap(string $range, int $length)
    {
        return match ($range) {
            'weekly' => $this->buildWeeklyRecap($length),
            'yearly' => $this->buildYearlyRecap($length),
            default => $this->buildMonthlyRecap($length),
        };
    }

    private function recapWindow(string $range, int $length): array
    {
        return match ($range) {
            'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
            'yearly' => [now()->startOfYear(), now()->endOfYear()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    private function buildChartData(string $range): array
    {
        return match ($range) {
            'weekly' => $this->buildWeeklyChart(),
            'yearly' => $this->buildYearlyChart(),
            default => $this->buildMonthlyChart(),
        };
    }

    private function buildMonthlyRecap(int $months)
    {
        $monthFormat = $this->monthFormatExpression();

        return Transaction::selectRaw($monthFormat . ' as month, SUM(total_amount) as total, COUNT(*) as transaction_count')
            ->where('transaction_date', '>=', now()->subMonths(max($months, 1) - 1)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                $label = Carbon::createFromFormat('Y-m', $row->month)->translatedFormat('M Y');

                return [
                    'month' => $row->month,
                    'label' => $label,
                    'total' => (float) $row->total,
                    'transaction_count' => (int) $row->transaction_count,
                ];
            });
    }

    private function buildMonthlyChart(): array
    {
        $monthFormat = $this->monthFormatExpression();
        $startRange = now()->subMonths(5)->startOfMonth();
        $endRange = now()->endOfMonth();

        $rows = Transaction::selectRaw($monthFormat . ' as bucket, SUM(total_amount) as total, COUNT(*) as transaction_count')
            ->whereBetween('transaction_date', [$startRange, $endRange])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();

        $labels = $rows->map(fn ($row) => Carbon::createFromFormat('Y-m', $row->bucket)->translatedFormat('M Y'));
        $totals = $rows->map(fn ($row) => (float) $row->total);
        $counts = $rows->map(fn ($row) => (int) $row->transaction_count);

        return [
            'labels' => $labels,
            'totals' => $totals,
            'counts' => $counts,
        ];
    }

    private function buildWeeklyRecap(int $weeks)
    {
        $startRange = now()->startOfWeek()->subWeeks(max($weeks, 1) - 1);
        $endRange = now()->endOfWeek();

        $transactions = Transaction::whereBetween('transaction_date', [$startRange, $endRange])->get();

        return $transactions
            ->groupBy(function ($transaction) {
                return Carbon::parse($transaction->transaction_date)->startOfWeek()->format('Y-m-d');
            })
            ->map(function ($group, $startOfWeek) {
                $start = Carbon::parse($startOfWeek);
                $end = (clone $start)->endOfWeek();

                return [
                    'period' => $start->format('Y-m-d'),
                    'label' => $start->translatedFormat('d M') . ' - ' . $end->translatedFormat('d M'),
                    'total' => (float) $group->sum('total_amount'),
                    'transaction_count' => (int) $group->count(),
                ];
            })
            ->sortBy('period')
            ->values();
    }

    private function buildWeeklyChart(): array
    {
        $startRange = now()->startOfWeek();
        $endRange = now()->endOfWeek();

        $rows = Transaction::selectRaw('DATE(transaction_date) as bucket, SUM(total_amount) as total, COUNT(*) as transaction_count')
            ->whereBetween('transaction_date', [$startRange, $endRange])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();

        $labels = $rows->map(fn ($row) => Carbon::parse($row->bucket)->translatedFormat('d M'));
        $totals = $rows->map(fn ($row) => (float) $row->total);
        $counts = $rows->map(fn ($row) => (int) $row->transaction_count);

        return [
            'labels' => $labels,
            'totals' => $totals,
            'counts' => $counts,
        ];
    }

    private function buildYearlyRecap(int $years)
    {
        $yearFormat = $this->yearFormatExpression();

        return Transaction::selectRaw($yearFormat . ' as year, SUM(total_amount) as total, COUNT(*) as transaction_count')
            ->where('transaction_date', '>=', now()->subYears(max($years, 1) - 1)->startOfYear())
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->map(function ($row) {
                return [
                    'period' => $row->year,
                    'label' => (string) $row->year,
                    'total' => (float) $row->total,
                    'transaction_count' => (int) $row->transaction_count,
                ];
            });
    }

    private function buildYearlyChart(): array
    {
        $yearFormat = $this->yearFormatExpression();
        $startRange = now()->subYears(4)->startOfYear();
        $endRange = now()->endOfYear();

        $rows = Transaction::selectRaw($yearFormat . ' as bucket, SUM(total_amount) as total, COUNT(*) as transaction_count')
            ->whereBetween('transaction_date', [$startRange, $endRange])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();

        $labels = $rows->map(fn ($row) => (string) $row->bucket);
        $totals = $rows->map(fn ($row) => (float) $row->total);
        $counts = $rows->map(fn ($row) => (int) $row->transaction_count);

        return [
            'labels' => $labels,
            'totals' => $totals,
            'counts' => $counts,
        ];
    }

    private function monthFormatExpression(): string
    {
        $driver = DB::connection()->getDriverName();

        return match ($driver) {
            'pgsql' => "to_char(transaction_date, 'YYYY-MM')",
            'sqlite' => "strftime('%Y-%m', transaction_date)",
            default => "DATE_FORMAT(transaction_date, '%Y-%m')",
        };
    }

    private function yearFormatExpression(): string
    {
        $driver = DB::connection()->getDriverName();

        return match ($driver) {
            'pgsql' => "to_char(transaction_date, 'YYYY')",
            'sqlite' => "strftime('%Y', transaction_date)",
            default => "DATE_FORMAT(transaction_date, '%Y')",
        };
    }

    private function chartMeta(string $range): array
    {
        return match ($range) {
            'weekly' => [
                'heading' => 'Performa Penjualan Mingguan',
                'subtitle' => 'Total & jumlah transaksi per hari (minggu ini).',
                'badge' => 'Harian',
            ],
            'yearly' => [
                'heading' => 'Performa Penjualan Tahunan',
                'subtitle' => 'Total & jumlah transaksi per tahun.',
                'badge' => 'Tahunan',
            ],
            default => [
                'heading' => 'Performa Penjualan Bulanan',
                'subtitle' => 'Total & jumlah transaksi per bulan.',
                'badge' => 'Bulanan',
            ],
        };
    }

    private function recapOptions(string $activeRange, int $activeLength): array
    {
        $options = [
            ['range' => 'weekly', 'length' => 1, 'label' => 'Mingguan'],
            ['range' => 'monthly', 'length' => 1, 'label' => 'Bulanan'],
            ['range' => 'yearly', 'length' => 1, 'label' => 'Tahunan'],
        ];

        return array_map(function ($option) use ($activeRange, $activeLength) {
            $option['active'] = $option['range'] === $activeRange && $option['length'] === $activeLength;

            return $option;
        }, $options);
    }
}
