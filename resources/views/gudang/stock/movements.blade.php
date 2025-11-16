@extends('layouts.gudang')

@section('title', 'Pergerakan Stok')

@push('styles')
    <style>
        .form-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
            margin-bottom: 16px;
        }

        .form-inline select {
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #d0d5dd;
            min-width: 180px;
        }

        .stock-button {
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
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        }

        .stock-button--blue {
            background-color: #2563eb;
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.22);
        }

        .stock-button--blue:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.26);
        }

        .stock-button--blue:active {
            transform: translateY(0);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
        }

        .stock-button--green {
            background-color: #16a34a;
            box-shadow: 0 10px 22px rgba(22, 163, 74, 0.22);
        }

        .stock-button--green:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(22, 163, 74, 0.28);
        }

        .stock-button--green:active {
            transform: translateY(0);
            box-shadow: 0 8px 18px rgba(22, 163, 74, 0.2);
        }

        .form-inline .stock-button {
            align-self: stretch;
        }
    </style>
@endpush

@section('content')
    <h1 class="page-title">Pergerakan Stok</h1>

    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 20px;">
        <div class="card">
            <form method="GET" action="{{ route('gudang.stock.movements') }}" class="form-inline">
                <div>
                    <label for="type" class="muted" style="display:block; margin-bottom:4px;">Jenis</label>
                    <select name="type" id="type">
                        <option value="">Semua</option>
                        <option value="in" @selected(request('type') === 'in')>Masuk</option>
                        <option value="out" @selected(request('type') === 'out')>Keluar</option>
                        <option value="adjustment" @selected(request('type') === 'adjustment')>Penyesuaian</option>
                    </select>
                </div>

                <div>
                    <label for="product_id" class="muted" style="display:block; margin-bottom:4px;">Produk</label>
                    <select name="product_id" id="product_id">
                        <option value="">Semua</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="stock-button stock-button--blue">Filter</button>
                </div>
            </form>

            @if ($movements->isEmpty())
                <p class="muted">Belum ada catatan pergerakan stok.</p>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movements as $movement)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ optional($movement->product)->name ?? 'Produk tidak ditemukan' }}</td>
                                <td>{{ ucfirst($movement->type) }}</td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->notes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 16px;">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>

        <div class="card">
            <h2 style="margin:0 0 12px; font-size:20px;">Penyesuaian Stok Manual</h2>
            <form method="POST" action="{{ route('gudang.stock.adjustment') }}">
                @csrf
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div>
                        <label for="adjust_product_id" class="muted" style="display:block; margin-bottom:4px;">Produk</label>
                        <select
                            id="adjust_product_id"
                            name="product_id"
                            style="padding:10px 12px; border-radius:10px; border:1px solid #d0d5dd; width:100%;"
                            required
                        >
                            <option value="">Pilih produk</option>
                            @foreach ($products as $product)
                                @php($stockText = $product->is_stock_unlimited ? '-' : $product->stock)
                                <option value="{{ $product->id }}" @if($product->is_stock_unlimited) disabled @endif>
                                    {{ $product->name }} (Stok: {{ $stockText }}{{ $product->is_stock_unlimited ? ' - tidak dapat disesuaikan' : '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="muted" style="display:block; margin-bottom:4px;">Jenis Penyesuaian</label>
                        <select
                            name="direction"
                            style="padding:10px 12px; border-radius:10px; border:1px solid #d0d5dd; width:100%;"
                            required
                        >
                            <option value="increase">Tambah Stok</option>
                            <option value="decrease">Kurangi Stok</option>
                        </select>
                        @error('direction')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="muted" style="display:block; margin-bottom:4px;">Jumlah</label>
                        <input
                            type="number"
                            name="quantity"
                            min="1"
                            class="form-control"
                            required
                        >
                        @error('quantity')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="muted" style="display:block; margin-bottom:4px;">Catatan</label>
                        <textarea
                            name="notes"
                            class="form-textarea"
                            placeholder="Contoh: stok opname, barang rusak, dll"
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                <div>
                    <button type="submit" class="stock-button stock-button--green">Simpan Penyesuaian</button>
                </div>
                </div>
            </form>
        </div>
    </div>
@endsection
