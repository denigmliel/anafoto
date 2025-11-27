@extends('layouts.gudang')

@section('title', 'Label QR Produk')

@push('styles')
    <style>
        .qr-page__header {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .qr-page__header h1 {
            margin: 0 0 4px;
        }

        .qr-page__header p {
            margin: 0;
        }

        .qr-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .qr-card__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            align-items: center;
        }

        .qr-label {
            background: linear-gradient(145deg, #f8fafc 0%, #e0f2fe 100%);
            border: 1px solid #d0d5dd;
            border-radius: 16px;
            padding: 16px 18px;
            display: grid;
            gap: 12px;
        }

        .qr-label__title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .qr-label__title strong {
            letter-spacing: 0.4px;
        }

        .qr-label__badge {
            display: inline-flex;
            padding: 6px 12px;
            background: #1d4ed8;
            color: #fff;
            border-radius: 999px;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .qr-label__name {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: #0f172a;
        }

        .qr-label__meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 10px 14px;
        }

        .qr-label__meta-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 12px;
        }

        .qr-label__meta-item span {
            display: block;
            font-size: 12px;
            color: #475467;
            margin-bottom: 4px;
        }

        .qr-label__meta-item strong {
            font-size: 15px;
            color: #0f172a;
        }

        .qr-figure {
            display: grid;
            gap: 10px;
            justify-items: center;
        }

        .qr-figure__box {
            background: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            padding: 14px;
            box-shadow: inset 0 1px 0 rgba(148, 163, 184, 0.2);
            width: 100%;
            max-width: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-figure__box svg {
            width: 100%;
            height: auto;
            max-width: 280px;
        }

        .qr-payload {
            width: 100%;
            background: #0f172a;
            color: #e2e8f0;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 12px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .qr-guides {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 8px;
        }

        .qr-guide {
            border: 1px dashed #d0d5dd;
            border-radius: 12px;
            padding: 10px 12px;
            background: #f8fafc;
        }

        .qr-guide strong {
            display: block;
            margin-bottom: 4px;
            color: #0f172a;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .sidebar,
            .qr-page__header,
            .qr-actions,
            .flash {
                display: none !important;
            }

            .content {
                margin: 0;
                padding: 12px;
                width: 100%;
                max-width: 100%;
            }

            .card {
                box-shadow: none;
                border: 1px solid #cbd5e1;
            }

            .qr-payload {
                white-space: normal;
                word-break: break-all;
            }
        }
    </style>
@endpush

@section('content')
    <div class="qr-page__header">
        <div>
            <h1 class="page-title">Label QR Produk</h1>
            <p class="muted">QR Code siap dipindai untuk mempercepat transaksi dan pengecekan stok.</p>
        </div>
        <div class="qr-actions">
            <button type="button" id="btn-print-label" class="chip-button chip-button--blue">Cetak Label</button>
            <a href="{{ route('gudang.products.show', $product) }}" class="chip-button chip-button--gray">Kembali ke Detail</a>
            <a href="{{ route('gudang.products.index') }}" class="chip-button chip-button--yellow">Ke Daftar Produk</a>
        </div>
    </div>

    <div class="card">
        <div class="qr-card__grid">
            <div class="qr-label">
                <div class="qr-label__title">
                    <strong>Kode Label: {{ $labelCode }}</strong>
                    <span class="qr-label__badge">Scan Ready</span>
                </div>
                <p class="qr-label__name">{{ $product->name }}</p>
                <div class="qr-label__meta">
                    <div class="qr-label__meta-item">
                        <span>SKU/Kode</span>
                        <strong>{{ $labelCode }}</strong>
                    </div>
                    <div class="qr-label__meta-item">
                        <span>Kategori</span>
                        <strong>{{ optional($product->category)->name ?? 'Tanpa Kategori' }}</strong>
                    </div>
                    <div class="qr-label__meta-item">
                        <span>Satuan</span>
                        <strong>{{ $product->unit }}</strong>
                    </div>
                    <div class="qr-label__meta-item">
                        <span>Harga Default</span>
                        <strong>Rp{{ number_format($product->price, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="qr-figure">
                <div class="qr-figure__box" aria-label="QR Code {{ $product->name }}">
                    {!! $qrSvg !!}
                </div>
                <div class="qr-payload" title="{{ $qrPayload }}">
                    {{ $qrPayload }}
                </div>
                <div class="qr-guides">
                    <div class="qr-guide">
                        <strong>Cetak rapi</strong>
                        Gunakan kertas minimal 5x5 cm, non-blur, dan hindari permukaan mengkilap agar scanner cepat membaca.
                    </div>
                    <div class="qr-guide">
                        <strong>Isi QR</strong>
                        Menyimpan ID, kode, nama, unit, harga, dan kategori produk sehingga mudah disinkronkan ke kasir.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const printButton = document.getElementById('btn-print-label');
            if (printButton) {
                printButton.addEventListener('click', function () {
                    window.print();
                });
            }
        });
    </script>
@endpush
