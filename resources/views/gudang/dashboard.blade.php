@extends('layouts.gudang')

@section('title', 'Dashboard Gudang')

@section('content')
    <h1 class="page-title">Dashboard Gudang</h1>

    <div class="grid grid-3">
        <div class="card">
            <div class="muted">Total Produk</div>
            <div style="font-size: 30px; font-weight: 700; margin-top: 6px;">
                {{ number_format($stats['productCount']) }}
            </div>
            <div class="muted" style="margin-top: 6px;">
                Aktif: {{ number_format($stats['activeProductCount']) }}
            </div>
        </div>
        <div class="card">
            <div class="muted">Produk Stok Menipis (&le; {{ $lowStockThreshold }} pcs)</div>
            <div style="font-size: 30px; font-weight: 700; margin-top: 6px;">
                {{ number_format($stats['lowStockCount']) }}
            </div>
            <div class="muted" style="margin-top: 6px;">Perlu perhatian</div>
        </div>
    </div>

    <div class="grid" style="margin-top: 24px; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div>
                    <h2 style="margin: 0; font-size: 20px;">Produk Hampir Habis</h2>
                    <p class="muted" style="margin: 4px 0 0 0;">Stok &le; {{ $lowStockThreshold }} pcs</p>
                </div>
                <a href="{{ route('gudang.products.low_stock') }}" class="chip-button chip-button--blue">
                    Lihat Semua
                </a>
            </div>
            @if ($topLowStocks->isEmpty())
                <p class="muted">Tidak ada produk yang berada di bawah ambang stok {{ $lowStockThreshold }}.</p>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topLowStocks as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>
                                    {{ $product->is_stock_unlimited ? 'Tidak terbatas' : $product->stock . ' ' . $product->unit }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="card" style="margin-top: 24px;">
        <h2 style="margin: 0 0 12px; font-size: 20px;">Pergerakan Stok Terakhir</h2>
        @if ($recentMovements->isEmpty())
            <p class="muted">Belum ada pergerakan stok yang tercatat.</p>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentMovements as $movement)
                        <tr>
                            <td>{{ \Illuminate\Support\Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}</td>
                            <td>{{ optional($movement->product)->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($movement->type) }}</td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
