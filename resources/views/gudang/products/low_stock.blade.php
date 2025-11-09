@extends('layouts.gudang')

@section('title', 'Produk Stok Menipis')

@push('styles')
    <style>
        .low-stock-header {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 24px;
        }

        .low-stock-filter {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            padding: 20px;
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
            margin-bottom: 24px;
        }

        .low-stock-filter label {
            font-size: 13px;
            font-weight: 600;
            color: #475467;
            margin-bottom: 6px;
            display: inline-block;
        }

        .low-stock-filter input,
        .low-stock-filter select {
            width: 100%;
            padding: 11px 14px;
            border-radius: 12px;
            border: 1px solid #d0d5dd;
            font-size: 14px;
            color: #111827;
            background-color: #f8fafc;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .low-stock-filter input:focus,
        .low-stock-filter select:focus {
            outline: none;
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .low-stock-filter__actions {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .low-stock-filter__actions button,
        .low-stock-filter__actions a {
            padding: 12px 18px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .low-stock-filter__actions button {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.22);
        }

        .low-stock-filter__actions a {
            background-color: #f1f5f9;
            color: #0f172a;
            border: 1px solid #e2e8f0;
        }

        .low-stock-info {
            font-size: 14px;
            color: #475467;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            background-color: #fee2e2;
            color: #991b1b;
        }

        .stock-badge--warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .table-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        nav[aria-label="Pagination Navigation"] {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 24px;
            align-items: flex-end;
        }

        nav[aria-label="Pagination Navigation"] > div:first-child {
            display: none;
        }

        nav[aria-label="Pagination Navigation"] > div:last-child {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
        }

        nav[aria-label="Pagination Navigation"] p {
            margin: 0;
            font-size: 13px;
            color: #475467;
        }

        nav[aria-label="Pagination Navigation"] span > span,
        nav[aria-label="Pagination Navigation"] span > a,
        nav[aria-label="Pagination Navigation"] a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            background-color: #ffffff;
            border: 1px solid #d0d5dd;
            border-radius: 10px;
            text-decoration: none;
            min-width: 40px;
        }

        nav[aria-label="Pagination Navigation"] span[aria-current="page"] > span {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        nav[aria-label="Pagination Navigation"] a:hover {
            filter: brightness(0.97);
        }

        nav[aria-label="Pagination Navigation"] svg {
            width: 18px;
            height: 18px;
        }
    </style>
@endpush

@section('content')
    <div class="low-stock-header">
        <h1 class="page-title">Produk Stok Menipis</h1>
        <p class="low-stock-info">
            Menampilkan produk dengan stok &le; {{ $threshold }}.
            Ambang bawaan sistem adalah {{ $defaultThreshold }} pcs. Sesuaikan nilai ambang bila diperlukan.
        </p>
    </div>

    <form method="GET" class="low-stock-filter">
        <div>
            <label for="search">Pencarian</label>
            <input
                type="text"
                name="search"
                id="search"
                placeholder="Nama produk, kode, atau #ID"
                value="{{ request('search') }}"
            >
        </div>

        <div>
            <label for="category_id">Kategori</label>
            <select name="category_id" id="category_id">
                <option value="">Semua</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="threshold">Ambang Stok</label>
            <input
                type="number"
                min="0"
                name="threshold"
                id="threshold"
                value="{{ $threshold }}"
            >
        </div>

        <div class="low-stock-filter__actions">
            <button type="submit">Terapkan Filter</button>
            <a href="{{ route('gudang.products.low_stock') }}">Reset</a>
        </div>
    </form>

    @if ($products->isEmpty())
        <div class="card">
            <p class="muted">
                Tidak ada produk yang berada di bawah ambang stok {{ $threshold }}.
            </p>
        </div>
    @else
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h2 style="margin: 0; font-size: 18px;">Daftar Produk</h2>
                <div>
                    <a href="{{ route('gudang.products.index') }}" class="chip-button chip-button--blue">
                        Kembali ke Manajemen Produk
                    </a>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Kode</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th style="width: 120px; text-align: center;">Stok</th>
                        <th style="width: 120px; text-align: center;">Target/Min</th>
                        <th>Status</th>
                        <th style="width: 130px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        @php
                            $isOut = $product->stock <= 0;
                            $isCritical = $product->stock > 0 && $product->stock <= $defaultThreshold;
                        @endphp
                        <tr>
                            <td>#{{ $product->id }}</td>
                            <td>{{ $product->code ?? '-' }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ optional($product->category)->name ?? 'Tanpa Kategori' }}</td>
                            <td style="text-align: center;">
                                @if ($isOut)
                                    <span class="stock-badge">Stok habis</span>
                                @elseif ($isCritical)
                                    <span class="stock-badge stock-badge--warning">{{ $product->stock }} pcs</span>
                                @else
                                    {{ $product->stock }} pcs
                                @endif
                            </td>
                            <td style="text-align: center;">
                                {{ $product->min_stock !== null ? $product->min_stock . ' pcs' : '-' }}
                            </td>
                            <td>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('gudang.products.show', $product) }}" class="chip-button chip-button--yellow">Detail</a>
                                    <a href="{{ route('gudang.products.edit', $product) }}" class="chip-button chip-button--blue">Restok</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 16px; display: flex; justify-content: flex-end;">
                {{ $products->links() }}
            </div>
        </div>
    @endif
@endsection
