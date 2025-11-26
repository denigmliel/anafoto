@extends('layouts.gudang')

@section('title', 'Dashboard Gudang')

@push('styles')
    <style>
        .dashboard-hero {
            background: linear-gradient(135deg, #eef2ff 0%, #eff6ff 35%, #f8fafc 100%);
            border-radius: 18px;
            padding: 20px 22px;
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
            box-shadow: 0 16px 42px rgba(59, 130, 246, 0.12);
        }

        .dashboard-hero .eyebrow {
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #475569;
            margin: 0 0 6px;
            font-weight: 700;
        }

        .dashboard-hero h1 {
            margin: 0;
        }

        .dashboard-hero p {
            margin: 6px 0 0;
            color: #475569;
        }

        .hero-chips {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #0f172a;
            font-weight: 600;
            font-size: 13px;
        }

        .chip--warn {
            background: #fff4e5;
            color: #b45309;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
            gap: 6px;
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .stat-card--accent {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            border: none;
            box-shadow: 0 18px 36px rgba(42, 82, 152, 0.25);
        }

        .stat-title {
            font-size: 14px;
            color: rgba(15, 23, 42, 0.7);
            margin: 0;
            font-weight: 600;
        }

        .stat-card--accent .stat-title {
            color: rgba(255, 255, 255, 0.85);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.1;
        }

        .stat-meta {
            color: rgba(15, 23, 42, 0.7);
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .stat-card--accent .stat-meta {
            color: rgba(255, 255, 255, 0.85);
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 10px;
            background: #f8fafc;
            color: #0f172a;
            font-weight: 600;
            font-size: 12px;
        }

        .stat-card--accent .stat-pill {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        .layout-panels {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .section-card {
            background: #fff;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .section-header h2 {
            margin: 0;
            font-size: 18px;
        }

        .section-subtitle {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 13px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .pill--blue {
            background: #eef2ff;
            color: #312e81;
        }

        .pill--green {
            background: #ecfdf3;
            color: #166534;
        }

        .pill--orange {
            background: #fff7ed;
            color: #9a3412;
        }

        .table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .table-modern th,
        .table-modern td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .table-modern th {
            background: #f5f7fb;
            font-size: 13px;
            letter-spacing: 0.02em;
            color: #1f2937;
        }

        .table-modern tr:last-child td {
            border-bottom: none;
        }

        .timeline {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .timeline-item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .timeline-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            color: #fff;
        }

        .timeline-icon.is-in {
            background: linear-gradient(135deg, #16a34a, #22c55e);
        }

        .timeline-icon.is-out {
            background: linear-gradient(135deg, #f97316, #f43f5e);
        }

        .timeline-title {
            font-weight: 700;
            margin: 0 0 4px;
            color: #0f172a;
        }

        .timeline-meta {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .timeline-note {
            font-size: 13px;
            color: #334155;
            margin: 0;
        }

        .list-modern {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .list-title {
            font-weight: 700;
            margin: 0 0 4px;
            color: #0f172a;
        }

        .list-meta {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-hero">
        <div>
            <div class="eyebrow">Gudang</div>
            <h1 class="page-title" style="margin: 0;">Dashboard Gudang</h1>
            <p>Ringkasan kondisi stok, pergerakan terbaru, dan produk yang perlu perhatian.</p>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-title">Total Produk</div>
            <div class="stat-value">{{ number_format($stats['productCount']) }}</div>
            <div class="stat-meta">
                <span>Aktif: {{ number_format($stats['activeProductCount']) }}</span>
                <span>Nonaktif: {{ number_format($stats['inactiveProductCount']) }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Produk Stok Menipis (&le; {{ $lowStockThreshold }} pcs)</div>
            <div class="stat-value">{{ number_format($stats['lowStockCount']) }}</div>
            <div class="stat-meta">
                <span class="stat-pill">Perlu restock</span>
                <span>Klik "Lihat Semua" untuk detail</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Kategori Terdaftar</div>
            <div class="stat-value">{{ number_format($stats['categoryCount']) }}</div>
            <div class="stat-meta">
                <span>Produk aktif: {{ number_format($stats['activeProductCount']) }}</span>
                <span>Produk nonaktif: {{ number_format($stats['inactiveProductCount']) }}</span>
            </div>
        </div>
        <div class="stat-card stat-card--accent">
            <div class="stat-title">Pergerakan Hari Ini</div>
            <div class="stat-value">{{ number_format($stats['stockInToday'] - $stats['stockOutToday']) }}</div>
            <div class="stat-meta">
                <span class="stat-pill">Masuk: {{ number_format($stats['stockInToday']) }}</span>
                <span class="stat-pill">Keluar: {{ number_format($stats['stockOutToday']) }}</span>
            </div>
        </div>
    </div>

    <div class="layout-panels" style="grid-template-columns: 1.1fr 0.9fr;">
        <div class="section-card">
            <div class="section-header">
                <div>
                    <h2>Produk Hampir Habis</h2>
                    <div class="section-subtitle">Stok &le; {{ $lowStockThreshold }} pcs</div>
                </div>
                <a href="{{ route('gudang.products.low_stock') }}" class="chip-button chip-button--blue">
                    Lihat Semua
                </a>
            </div>

            @if ($topLowStocks->isEmpty())
                <p class="muted">Tidak ada produk yang berada di bawah ambang stok {{ $lowStockThreshold }}.</p>
            @else
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width: 60%;">Produk</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topLowStocks as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->stock . ' ' . $product->unit }}</td>
                                <td>
                                    <span class="pill pill--orange">Menipis</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="section-card">
            <div class="section-header">
                <div>
                    <h2>Pergerakan Stok Terakhir</h2>
                    <div class="section-subtitle">8 aktivitas terbaru</div>
                </div>
            </div>

            @if ($recentMovements->isEmpty())
                <p class="muted">Belum ada pergerakan stok yang tercatat.</p>
            @else
                <div class="timeline">
                    @foreach ($recentMovements as $movement)
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $movement->type === 'in' ? 'is-in' : 'is-out' }}">
                                {{ strtoupper(substr($movement->type, 0, 1)) }}
                            </div>
                            <div>
                                <div class="timeline-title">{{ optional($movement->product)->name ?? 'Produk tidak ditemukan' }}</div>
                                <div class="timeline-meta">
                                    {{ \Illuminate\Support\Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}
                                    • {{ ucfirst($movement->type) }} {{ number_format($movement->quantity) }}
                                    • {{ optional($movement->user)->name ?? 'Sistem' }}
                                </div>
                                <p class="timeline-note">{{ $movement->notes ?: 'Tidak ada catatan' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">
            <div>
                <h2>Produk Terakhir Diperbarui</h2>
                <div class="section-subtitle">Pantau pembaruan stok & detail produk terbaru</div>
            </div>
        </div>

        @if ($recentlyUpdatedProducts->isEmpty())
            <p class="muted">Belum ada produk yang diperbarui akhir-akhir ini.</p>
        @else
            <div class="list-modern">
                @foreach ($recentlyUpdatedProducts as $product)
                    <div class="list-item">
                        <div>
                            <div class="list-title">{{ $product->name }}</div>
                            <p class="list-meta">
                                {{ $product->stock . ' ' . $product->unit }}
                                • Diperbarui {{ \Illuminate\Support\Carbon::parse($product->updated_at)->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route('gudang.products.show', $product) }}" class="chip-button chip-button--blue">Detail</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
