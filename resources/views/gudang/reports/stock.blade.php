@extends('layouts.gudang')

@section('title', 'Laporan Stok')

@push('styles')
    <style>
        .report-filter-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.2px;
            cursor: pointer;
            color: #ffffff;
            background-color: #2563eb;
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.22);
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        }

        .report-filter-button:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.26);
        }

        .report-filter-button:active {
            transform: translateY(0);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
        }
    </style>
@endpush

@section('content')
    <h1 class="page-title">Laporan Stok</h1>

    <div class="card">
        <form method="GET" action="{{ route('gudang.reports.stock') }}" style="margin-bottom: 16px;">
            <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                <div>
                    <label class="muted" for="category_id" style="display:block; margin-bottom:4px;">Kategori</label>
                    <select
                        id="category_id"
                        name="category_id"
                        style="padding:10px 12px; border-radius:10px; border:1px solid #d0d5dd; min-width:200px;"
                    >
                        <option value="">Semua</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="muted" for="status" style="display:block; margin-bottom:4px;">Status</label>
                    <select
                        id="status"
                        name="status"
                        style="padding:10px 12px; border-radius:10px; border:1px solid #d0d5dd; min-width:160px;"
                    >
                        <option value="all" @selected(request('status', 'all') === 'all')>Semua</option>
                        <option value="active" @selected(request('status') === 'active')>Aktif</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Tidak Aktif</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="report-filter-button">Terapkan</button>
                </div>

                <div style="margin-left:auto;">
                    <div class="card" style="margin:0; padding:12px 16px; background-color:#ecfdf3; border:1px solid #bbf7d0;">
                        <div><strong>Total Stok:</strong> {{ number_format($totalStock) }}</div>
                        <div><strong>Nilai Persediaan:</strong> Rp{{ number_format($totalValue, 0, ',', '.') }}</div>
                    </div>
                    <p class="muted" style="margin-top:8px; font-size:12px;">
                        Produk dengan stok tidak terbatas tidak dihitung dalam total.
                    </p>
                </div>
            </div>
        </form>

        @if ($products->isEmpty())
            <p class="muted">Tidak ada data stok untuk filter yang dipilih.</p>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>#{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ optional($product->category)->name ?? 'Tanpa Kategori' }}</td>
                            <td>
                                {{ $product->is_stock_unlimited ? 'Tidak terbatas' : $product->stock . ' ' . $product->unit }}
                            </td>
                            <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                @if ($product->is_stock_unlimited)
                                    -
                                @else
                                    Rp{{ number_format($product->stock * $product->price, 0, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
