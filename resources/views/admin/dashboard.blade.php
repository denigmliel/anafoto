@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
    <style>
        .dashboard-hero {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 24px;
            border-radius: 18px;
            background: linear-gradient(135deg, #fee2e2 0%, #fff7ed 45%, #ecfeff 100%);
            border: 1px solid #fca5a5;
            box-shadow: 0 14px 36px rgba(15, 23, 42, 0.08);
            margin-bottom: 22px;
        }

        .hero-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
        }

        .hero-subtitle {
            margin: 6px 0 14px;
            color: #475569;
        }

        .pill-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pill-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #b91c1c;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.04);
        }

        .pill-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }

        .hero-highlight {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px;
            display: grid;
            gap: 8px;
            align-content: center;
        }

        .hero-highlight .value {
            font-size: 26px;
            font-weight: 800;
            color: #b91c1c;
        }

        .hero-highlight .label {
            color: #475569;
            font-weight: 600;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            margin-bottom: 20px;
        }

        .stat-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 16px;
            background: #fff;
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
            display: grid;
            gap: 8px;
        }

        .stat-label {
            color: #475569;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
        }

        .stat-sub {
            color: #6b7280;
            font-size: 13px;
        }

        .chart-layout {
            grid-template-columns: 2fr 1fr;
            align-items: start;
        }

        .chart-wrapper {
            position: relative;
            height: 320px;
        }

        .table-scroll {
            max-height: 320px;
            overflow: auto;
            margin-top: 8px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .compact-table {
            width: 100%;
            border-collapse: collapse;
        }

        .compact-table th,
        .compact-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        .compact-table thead th {
            position: sticky;
            top: 0;
            background: #f8fafc;
            z-index: 1;
            font-weight: 700;
            color: #0f172a;
        }

        .list-group {
            display: grid;
            gap: 12px;
            margin: 8px 0 0;
        }

        .list-item {
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-title {
            font-weight: 700;
            color: #0f172a;
        }

        .item-meta {
            color: #64748b;
            font-size: 13px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fef2f2;
            color: #b91c1c;
            border-radius: 999px;
            padding: 6px 10px;
            font-weight: 700;
            font-size: 12px;
        }

        .link-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #b91c1c;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.04);
            transition: transform 0.12s ease, box-shadow 0.12s ease;
        }

        .link-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.07);
        }

        @media (max-width: 960px) {
            .chart-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-hero">
        <div>
            <p class="tag" style="margin: 0 0 10px;" id="snapshot-clock">
                <span id="wib-clock">{{ $todayLabel }}</span>
            </p>
            <h1 class="hero-title">Dashboard Admin</h1>
            <p class="hero-subtitle">
                Pantau transaksi, tren penjualan, serta stok produk dalam satu tempat.
            </p>
            <div class="pill-row">
                <a href="{{ route('kasir.dashboard') }}" class="pill-link">
                    ➜ Buka Kasir
                </a>
                <a href="{{ route('gudang.dashboard') }}" class="pill-link" style="color: #0f172a;">
                    📦 Kelola Gudang
                </a>
            </div>
        </div>
        <div class="hero-highlight">
            <div class="label">Pendapatan Bulan Ini</div>
            <div class="value">Rp{{ number_format($metrics['monthSalesTotal'], 0, ',', '.') }}</div>
            <div class="stat-sub">Transaksi: {{ number_format($metrics['monthTransactionCount']) }}x</div>
        </div>
    </div>

    <div class="grid stats-grid">
        <div class="stat-card">
            <div class="stat-label">
                Transaksi Hari Ini
                <span class="tag">Live</span>
            </div>
            <div class="stat-value">{{ number_format($metrics['todayTransactionCount']) }}x</div>
            <div class="stat-sub">
                Total: Rp{{ number_format($metrics['todaySalesTotal'], 0, ',', '.') }} · Rata-rata: Rp{{ number_format($metrics['todayAvgOrder'], 0, ',', '.') }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Pendapatan Hari Ini</div>
            <div class="stat-value">Rp{{ number_format($metrics['todaySalesTotal'], 0, ',', '.') }}</div>
            <div class="stat-sub">Data diambil dari seluruh kasir aktif.</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Produk Aktif</div>
            <div class="stat-value">{{ number_format($metrics['activeProductCount']) }}</div>
            <div class="stat-sub">Total produk terdaftar: {{ number_format($metrics['productCount']) }} item.</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Stok Hampir Habis</div>
            <div class="stat-value">{{ number_format($metrics['lowStockCount']) }}</div>
            <div class="stat-sub">Ambang batas: ≤ {{ $lowStockThreshold }} stok.</div>
        </div>
    </div>

    <div class="grid chart-layout" style="gap: 20px; margin-bottom: 20px;">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <div>
                    <h2 style="margin: 0; font-size: 18px;">Performa Penjualan Bulan Ini</h2>
                    <p class="muted" style="margin: 4px 0 0;">Total & jumlah transaksi per hari.</p>
                </div>
                <div class="tag">Chart</div>
            </div>
            @if ($dailySalesLabels->isEmpty())
                <p class="muted" style="margin: 0;">Belum ada transaksi pada bulan ini.</p>
            @else
                <div class="chart-wrapper">
                    <canvas id="daily-sales-chart"></canvas>
                </div>
            @endif
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 18px;">Rekap Transaksi Bulanan</h2>
                <span class="tag">6 bulan</span>
            </div>
            @if ($monthlyRecap->isEmpty())
                <p class="muted" style="margin-top: 10px;">Belum ada data transaksi yang dapat diringkas.</p>
            @else
                <div class="table-scroll">
                    <table class="compact-table">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Total</th>
                                <th>Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monthlyRecap as $recap)
                                <tr>
                                    <td>{{ $recap['label'] }}</td>
                                    <td>Rp{{ number_format($recap['total'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($recap['transaction_count']) }}x</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                <h2 style="margin: 0; font-size: 18px;">Stok Hampir Habis</h2>
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: flex-end;">
                    <span class="tag">≤ {{ $lowStockThreshold }}</span>
                    <a href="{{ route('gudang.products.low_stock') }}" class="link-button">Lihat semua</a>
                </div>
            </div>
            @if ($lowStockProducts->isEmpty())
                <p class="muted" style="margin-top: 10px;">Semua stok aman.</p>
            @else
                <div class="list-group">
                    @foreach ($lowStockProducts as $product)
                        <div class="list-item">
                            <div>
                                <div class="item-title">{{ $product->name }}</div>
                                <div class="item-meta">Kode: {{ $product->code ?? '-' }}</div>
                            </div>
                            <div class="tag">{{ $product->stock }} stok</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 18px;">Transaksi Terbaru</h2>
                <span class="tag">5 terakhir</span>
            </div>
            @if ($recentTransactions->isEmpty())
                <p class="muted" style="margin-top: 10px;">Belum ada transaksi yang tercatat.</p>
            @else
                <div class="list-group">
                    @foreach ($recentTransactions as $transaction)
                        <div class="list-item">
                            <div>
                                <div class="item-title">#{{ $transaction->code }}</div>
                                <div class="item-meta">
                                    {{ optional($transaction->transaction_date)->format('d M Y H:i') }} ·
                                    Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="item-meta">
                                {{ $transaction->user->name ?? 'Kasir' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const clockEl = document.getElementById('wib-clock');

            const formatWib = (date) => new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            }).format(date) + ' WIB';

            if (clockEl) {
                const tick = () => {
                    clockEl.textContent = formatWib(new Date());
                };
                tick();
                setInterval(tick, 1000);
            }

            const canvas = document.getElementById('daily-sales-chart');
            const labels = @json($dailySalesLabels);
            const totals = @json($dailySalesTotals);
            const counts = @json($dailySalesTransactionCounts);

            if (!canvas || !labels.length) {
                return;
            }

            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 320);

            gradient.addColorStop(0, 'rgba(239, 68, 68, 0.28)');
            gradient.addColorStop(1, 'rgba(239, 68, 68, 0.02)');

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Total Penjualan',
                            data: totals,
                            fill: true,
                            backgroundColor: gradient,
                            borderColor: '#b91c1c',
                            borderWidth: 3,
                            tension: 0.32,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Jumlah Transaksi',
                            data: counts,
                            yAxisID: 'y1',
                            borderColor: '#94a3b8',
                            borderWidth: 2,
                            borderDash: [6, 6],
                            pointRadius: 3,
                            pointHoverRadius: 5,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => new Intl.NumberFormat('id-ID', {
                                    maximumFractionDigits: 0,
                                }).format(value),
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.25)',
                            },
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: '#94a3b8',
                            },
                        },
                        x: {
                            grid: {
                                display: false,
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            displayColors: false,
                            callbacks: {
                                title: (items) => items[0]?.label ?? '',
                                label: (item) => {
                                    if (item.datasetIndex === 0) {
                                        return 'Total: ' + new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            maximumFractionDigits: 0,
                                        }).format(item.parsed.y || 0);
                                    }

                                    return 'Transaksi: ' + new Intl.NumberFormat('id-ID').format(item.parsed.y || 0);
                                },
                            },
                        },
                    },
                },
            });
        });
    </script>
@endpush
