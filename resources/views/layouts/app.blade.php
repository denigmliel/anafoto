<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Sistem Kasir'))</title>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }
        :root {
            color-scheme: light;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            color: #1d2939;
            --sidebar-width: 220px;
        }

        body {
            margin: 0;
            background-color: #f4f6f8;
            overflow-x: auto;
        }

        .layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: var(--sidebar-width) minmax(0, 1fr);
        }

        .sidebar {
            background: linear-gradient(180deg, #b91c1c 0%, #7f1d1d 100%);
            color: #fff;
            padding: 22px 18px;
            display: flex;
            flex-direction: column;
            gap: 32px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            justify-content: space-between;
            width: var(--sidebar-width);
        }

        .brand {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1.05;
            color: #fff;
        }

        .brand span {
            display: block;
        }

        .sidebar-top {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .sidebar-bottom {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .sidebar-sections {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .sidebar-section {
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            background-color: rgba(255, 255, 255, 0.06);
            overflow: hidden;
        }

        .sidebar-section-single {
            padding: 0;
        }

        .sidebar-single-link {
            display: block;
            padding: 12px 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.92);
            text-decoration: none;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-single-link.active,
        .sidebar-single-link:hover {
            background-color: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        .sidebar-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            padding: 12px 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.92);
            list-style: none;
        }

        .sidebar-summary::-webkit-details-marker {
            display: none;
        }

        .sidebar-caret {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-right: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: rotate(45deg);
            transition: transform 0.2s ease;
            margin-left: 12px;
        }

        .sidebar-section[open] .sidebar-caret {
            transform: rotate(-135deg);
        }

        .sidebar-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 0 16px 16px;
        }

        .sidebar-links a {
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            font-weight: 500;
            padding: 9px 12px;
            border-radius: 10px;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-links a.active,
        .sidebar-links a:hover {
            background-color: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        .logout-form {
            margin: 0;
            width: 100%;
        }

        .logout-button {
            border: none;
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
            color: #fff;
            padding: 12px 18px;
            border-radius: 14px;
            font-weight: 600;
            letter-spacing: 0.3px;
            width: 100%;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            box-shadow: 0 10px 22px rgba(220, 38, 38, 0.28);
        }

        .logout-button:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 14px 28px rgba(220, 38, 38, 0.32);
        }

        .logout-button:active {
            transform: translateY(0);
            box-shadow: 0 8px 18px rgba(220, 38, 38, 0.26);
        }

        .logout-button span {
            pointer-events: none;
        }

        .content {
            background-color: #f4f6f8;
            padding: 24px clamp(18px, 3vw, 32px);
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .main-content {
            flex: 1;
            width: 100%;
            max-width: none;
            margin: 0;
        }

        .app-footer {
            text-align: center;
            padding: 18px;
            color: #475467;
            font-size: 13px;
        }

        .flash-message {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .flash-success {
            background-color: #ecfdf3;
            color: #027a48;
            border: 1px solid #86efac;
        }

        .flash-error {
            background-color: #fef3f2;
            color: #b42318;
            border: 1px solid #fda29b;
        }

        .flash-link {
            color: #027a48;
            text-decoration: underline;
            font-weight: 500;
        }

        .flash-link:hover {
            color: #016c3f;
        }

        .page-title {
            margin: 0 0 18px;
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.05);
            border: 1px solid #e4e7ec;
        }

        .card + .card {
            margin-top: 20px;
        }

        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.data-table thead {
            background-color: #f8fafc;
        }

        table.data-table th,
        table.data-table td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #e4e7ec;
            font-size: 14px;
        }

        table.data-table tbody tr:hover {
            background-color: #f9fbff;
        }

        .muted {
            color: #667085;
            font-size: 13px;
        }

        .form-control,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #d0d5dd;
            background-color: #fff;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #1e3c72;
            box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
        }

        nav[aria-label="Pagination Navigation"] {
            margin-top: 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            font-size: 14px;
        }

        nav[aria-label="Pagination Navigation"] > div {
            width: 100%;
        }

        nav[aria-label="Pagination Navigation"] > :first-child {
            display: none;
        }

        nav[aria-label="Pagination Navigation"] > :last-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        nav[aria-label="Pagination Navigation"] > :last-child > :last-child {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        nav[aria-label="Pagination Navigation"] .relative.inline-flex {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid #d0d5dd;
            background-color: #fff;
            color: #475467;
            text-decoration: none;
            min-width: 42px;
            font-weight: 600;
        }

        nav[aria-label="Pagination Navigation"] span[aria-current="page"] > span {
            background-color: #b91c1c;
            border-color: #b91c1c;
            color: #fff;
        }

        nav[aria-label="Pagination Navigation"] svg {
            width: 18px;
            height: 18px;
        }

        nav[aria-label="Pagination Navigation"] p {
            margin: 0;
            color: #475467;
        }

        @media (max-width: 640px) {
            nav[aria-label="Pagination Navigation"] > :first-child {
                display: flex;
                justify-content: space-between;
                gap: 12px;
            }

            nav[aria-label="Pagination Navigation"] > :last-child {
                display: none;
            }
        }

        @media (max-width: 1200px) {
            :root {
                --sidebar-width: 204px;
            }
        }

        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 192px;
            }

            .sidebar {
                padding: 18px 14px;
                gap: 20px;
            }

            .sidebar-top {
                gap: 18px;
            }

            .sidebar-sections {
                gap: 12px;
            }

            .sidebar-links {
                padding: 0 12px 12px;
            }

            .content {
                padding: 20px 18px;
            }
        }

        @media (max-width: 768px) {
            :root {
                --sidebar-width: 180px;
            }
        }

        @media (max-width: 640px) {
            :root {
                --sidebar-width: 172px;
            }

            .sidebar {
                padding: 18px 12px;
            }

            .content {
                padding: 18px 14px;
            }

            .main-content {
                max-width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="layout">
        @include('layouts.partials.sidebar')

        <div class="content">
            <main class="main-content">
                @if (session('success'))
                    <div class="flash-message flash-success">
                        {{ session('success') }}

                        @if (session('print_transaction_id'))
                            <div style="margin-top: 8px;">
                                <a href="{{ route('kasir.transaction.print', session('print_transaction_id')) }}" class="flash-link">
                                    Cetak struk transaksi terakhir
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                @if (session('error'))
                    <div class="flash-message flash-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="flash-message flash-error">
                        {{ __('Terjadi kesalahan. Periksa formulir Anda.') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="app-footer">
                &copy; {{ date('Y') }} {{ config('app.name', 'Sistem Kasir') }}. All rights reserved.
            </footer>
        </div>
    </div>

    @stack('scripts')

    @if (session('print_transaction_id'))
        <script>
            window.addEventListener('load', function () {
                const receiptUrl = "{{ route('kasir.transaction.print', session('print_transaction_id')) }}?auto_print=1";

                if (window.__lastPrintedReceipt === receiptUrl) {
                    return;
                }
                window.__lastPrintedReceipt = receiptUrl;

                const popup = window.open(
                    receiptUrl,
                    '_blank',
                    'noopener,noreferrer,width=420,height=720'
                );

                if (!popup) {
                    console.warn('Izinkan pop-up untuk mencetak struk otomatis.');
                }
            });
        </script>
    @endif
</body>
</html>
