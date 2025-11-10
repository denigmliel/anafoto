@extends('layouts.gudang')

@section('title', 'Manajemen Produk')

@push('styles')
    <style>
        .product-console {
            border-radius: 22px;
            padding: 24px 26px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
            background-color: #ffffff;
        }

        .product-console__form {
            margin-bottom: 18px;
        }

        .product-console__row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: flex-end;
        }

        .product-console__field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .product-console__field label {
            font-size: 13px;
            font-weight: 600;
            color: #475467;
        }

        .product-console__field input,
        .product-console__field select {
            padding: 11px 14px;
            border-radius: 12px;
            border: 1px solid #d0d5dd;
            min-width: 190px;
            font-size: 14px;
            color: #111827;
            background-color: #f8fafc;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .product-console__field input:focus,
        .product-console__field select:focus {
            outline: none;
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .product-console__input-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .product-console__actions {
            margin-left: auto;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.2px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        }

        .button--primary {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.24);
        }

        .button--primary:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.26);
        }

        .button--primary:active {
            transform: translateY(0);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.22);
        }

        .button--success {
            background-color: #16a34a;
            color: #ffffff;
            box-shadow: 0 10px 22px rgba(22, 163, 74, 0.24);
        }

        .button--success:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(22, 163, 74, 0.28);
        }

        .button--success:active {
            transform: translateY(0);
            box-shadow: 0 8px 18px rgba(22, 163, 74, 0.2);
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

        @media (max-width: 640px) {
            .product-console__field {
                width: 100%;
            }

            .product-console__input-group {
                flex-direction: column;
                align-items: stretch;
            }

            .product-console__field input,
            .product-console__field select {
                min-width: unset;
            }

            .product-console__actions {
                width: 100%;
                justify-content: flex-start;
                margin-left: 0;
            }

            .product-console__actions .button {
                width: 100%;
            }

            .product-console__input-group .button {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <h1 class="page-title">Manajemen Produk</h1>

    <div class="card product-console">
        <form method="GET" action="{{ route('gudang.products.index') }}" class="product-console__form">
            <div class="product-console__row">
                <div class="product-console__field">
                    <label for="search">Pencarian</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Nama, kode atau ID produk"
                    >
                </div>

                <div class="product-console__field">
                    <label for="category_id">Kategori</label>
                    <select
                        name="category_id"
                        id="category_id"
                        data-searchable-select
                    >
                        <option value="">Semua</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="product-console__field">
                    <label for="status">Status</label>
                    <div class="product-console__input-group">
                        <select
                            name="status"
                            id="status"
                        >
                            <option value="">Semua</option>
                            <option value="active" @selected(request('status') === 'active')>Aktif</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Tidak Aktif</option>
                        </select>
                        <button type="submit" class="button button--primary">Filter</button>
                    </div>
                </div>

                <div class="product-console__actions">
                    <a
                        href="{{ route('gudang.products.low_stock') }}"
                        class="button button--primary"
                    >
                        Lihat Stok Menipis
                    </a>
                    <a
                        href="{{ route('gudang.products.create') }}"
                        class="button button--success"
                    >
                        Tambah Produk
                    </a>
                </div>
            </div>
        </form>

        @if ($products->isEmpty())
            <p class="muted">Belum ada produk yang tercatat.</p>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th style="width: 120px; text-align: center;">Stok</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>#{{ $product->id }}</td>
                            <td>{{ $product->code ?? '-' }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ optional($product->category)->name ?? 'Tanpa Kategori' }}</td>
                            <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td style="text-align: center; white-space: nowrap;">
                                {{ $product->is_stock_unlimited ? 'Tidak terbatas' : $product->stock }}
                            </td>
                            <td>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td style="text-align: right; display: flex; justify-content: flex-end; gap: 8px;">
                                <a href="{{ route('gudang.products.show', $product) }}" class="chip-button chip-button--yellow">Detail</a>
                                <a href="{{ route('gudang.products.edit', $product) }}" class="chip-button chip-button--blue">Edit</a>
                                <form
                                    method="POST"
                                    action="{{ route('gudang.products.destroy', $product) }}"
                                    onsubmit="return confirm('Hapus produk {{ $product->name }} secara permanen?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="chip-button chip-button--danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 16px;">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const RESET_DELAY = 800;
            const searchableSelects = document.querySelectorAll('select[data-searchable-select]');

            searchableSelects.forEach(function (selectElement) {
                let buffer = '';
                let timerId = null;

                function resetBuffer() {
                    buffer = '';
                    if (timerId) {
                        clearTimeout(timerId);
                        timerId = null;
                    }
                }

                selectElement.addEventListener('keydown', function (event) {
                    if (event.ctrlKey || event.altKey || event.metaKey) {
                        return;
                    }

                    if (event.key === 'Backspace') {
                        buffer = buffer.slice(0, -1);
                        event.preventDefault();
                    } else if (event.key === 'Escape') {
                        resetBuffer();
                        return;
                    } else if (event.key.length === 1) {
                        buffer += event.key;
                        event.preventDefault();
                    } else {
                        return;
                    }

                    if (timerId) {
                        clearTimeout(timerId);
                    }
                    timerId = setTimeout(resetBuffer, RESET_DELAY);

                    const searchTerm = buffer.trim().toLowerCase();
                    if (!searchTerm) {
                        return;
                    }

                    const match = Array.from(selectElement.options).find(function (option) {
                        return option.value !== '' && option.text.toLowerCase().includes(searchTerm);
                    });

                    if (match) {
                        selectElement.value = match.value;
                        selectElement.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });

                selectElement.addEventListener('blur', resetBuffer);
            });
        })();
    </script>
@endpush
