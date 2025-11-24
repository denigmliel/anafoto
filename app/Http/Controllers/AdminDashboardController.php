<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    private const LOW_STOCK_THRESHOLD = 3;

    public function index()
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

        $dailySales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(total_amount) as total, COUNT(*) as transaction_count')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailySalesLabels = $dailySales
            ->map(fn ($row) => Carbon::parse($row->date)->translatedFormat('d M'))
            ->values();

        $dailySalesTotals = $dailySales
            ->map(fn ($row) => (float) $row->total)
            ->values();

        $dailySalesTransactionCounts = $dailySales
            ->map(fn ($row) => (int) $row->transaction_count)
            ->values();

        $monthlyRecap = $this->buildMonthlyRecap();

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
            'dailySalesLabels' => $dailySalesLabels,
            'dailySalesTotals' => $dailySalesTotals,
            'dailySalesTransactionCounts' => $dailySalesTransactionCounts,
            'monthlyRecap' => $monthlyRecap,
            'lowStockProducts' => $lowStockProducts,
            'recentTransactions' => $recentTransactions,
            'lowStockThreshold' => self::LOW_STOCK_THRESHOLD,
            'todayLabel' => $today->translatedFormat('d M Y'),
        ]);
    }

    private function buildMonthlyRecap()
    {
        $monthFormat = $this->monthFormatExpression();

        return Transaction::selectRaw($monthFormat . ' as month, SUM(total_amount) as total, COUNT(*) as transaction_count')
            ->where('transaction_date', '>=', now()->subMonths(5)->startOfMonth())
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

    private function monthFormatExpression(): string
    {
        $driver = DB::connection()->getDriverName();

        return match ($driver) {
            'pgsql' => "to_char(transaction_date, 'YYYY-MM')",
            'sqlite' => "strftime('%Y-%m', transaction_date)",
            default => "DATE_FORMAT(transaction_date, '%Y-%m')",
        };
    }
}
