<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel Gudang')</title>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }
        :root {
            color-scheme: light;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: #1d2939;
        }

        body {
            margin: 0;
        }

        .layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 200px minmax(0, 1fr);
        }

        .sidebar {
            background: linear-gradient(195deg, #c53030 0%, #991b1b 55%, #7f1d1d 100%);
            color: #fff;
            padding: 22px 18px;
            display: flex;
            flex-direction: column;
            gap: 32px;
            justify-content: space-between;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
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

        .logout-form {
            width: 100%;
        }

        .sidebar-sections {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .sidebar-section {
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 16px;
            background-color: rgba(255, 255, 255, 0.07);
            overflow: hidden;
        }

        .sidebar-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            padding: 12px 18px;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.2px;
            color: rgba(255, 255, 255, 0.94);
            list-style: none;
        }

        .sidebar-summary::-webkit-details-marker {
            display: none;
        }

        .sidebar-caret {
            display: inline-block;
            width: 9px;
            height: 9px;
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
            gap: 10px;
            padding: 0 18px 18px;
        }

        .sidebar-links a {
            color: rgba(255, 255, 255, 0.88);
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-links a.active,
        .sidebar-links a:hover {
            background-color: rgba(255, 255, 255, 0.22);
            color: #fff;
        }

        .content {
            background-color: #f5f7fb;
            padding: 24px 28px;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .form-control,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #d0d5dd;
            font-size: 14px;
        }

        .form-textarea {
            min-height: 110px;
        }

        .input-error {
            color: #b42318;
            font-size: 13px;
            margin-top: 4px;
        }

        .logout-button {
            border: none;
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
            color: #fff;
            padding: 12px 18px;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            letter-spacing: 0.3px;
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

        .logout-button.logout-button--gray {
            --logout-bg: #6b7280;
            --logout-border: #4b5563;
            --logout-hover-bg: #4b5563;
        }

        .logout-button.logout-button--danger {
            --logout-bg: #dc2626;
            --logout-border: #b91c1c;
            --logout-hover-bg: #b91c1c;
        }

        .chip-button {
            padding: 8px 16px;
            border-radius: 999px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            min-width: 88px;
        }

        .chip-button:hover {
            transform: translateY(-1px);
        }

        .chip-button:active {
            transform: translateY(0);
            filter: brightness(0.95);
        }

        .chip-button--yellow {
            background-color: #f59e0b;
            color: #1f2937;
            box-shadow: 0 10px 18px rgba(245, 158, 11, 0.24);
        }

        .chip-button--yellow:hover {
            box-shadow: 0 12px 22px rgba(245, 158, 11, 0.3);
        }

        .chip-button--blue {
            background-color: #2563eb;
            color: #fff;
            box-shadow: 0 10px 18px rgba(37, 99, 235, 0.24);
        }

        .chip-button--blue:hover {
            box-shadow: 0 12px 22px rgba(37, 99, 235, 0.32);
        }

        .chip-button--danger {
            background-color: #dc2626;
            color: #fff;
            box-shadow: 0 10px 18px rgba(220, 38, 38, 0.24);
        }

        .chip-button--danger:hover {
            box-shadow: 0 12px 22px rgba(220, 38, 38, 0.32);
        }

        .chip-button--gray {
            background-color: #6b7280;
            color: #fff;
            box-shadow: 0 10px 18px rgba(107, 114, 128, 0.22);
        }

        .chip-button--gray:hover {
            box-shadow: 0 12px 22px rgba(107, 114, 128, 0.28);
        }

        .main-content {
            flex: 1;
            max-width: 1180px;
            width: 100%;
            margin: 0 auto;
        }

        .page-title {
            margin: 0 0 18px;
            font-size: 24px;
            font-weight: 600;
            color: #182230;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
            border: 1px solid #e5e7eb;
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

        .flash {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 18px;
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

        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.data-table th,
        table.data-table td {
            text-align: left;
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.data-table thead {
            background-color: #eef2ff;
        }

        .muted {
            color: #667085;
            font-size: 13px;
        }

        @media (max-width: 1024px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                height: auto;
                flex-direction: column;
                align-items: stretch;
                padding: 18px;
                gap: 18px;
            }

            .sidebar-top {
                gap: 18px;
            }

            .sidebar-bottom {
                width: 100%;
                margin-top: 0;
            }

            .sidebar-sections {
                gap: 12px;
            }

            .sidebar-links {
                padding: 0 14px 14px;
            }

            .logout-form {
                width: auto;
            }

            .logout-button {
                width: auto;
            }

            .content {
                padding: 20px 16px;
            }

            .main-content {
                margin: 0;
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
            <div class="main-content">
                @if (session('success'))
                    <div class="flash flash-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="flash flash-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="flash flash-error">
                        Terjadi kesalahan. Periksa kembali input Anda.
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
