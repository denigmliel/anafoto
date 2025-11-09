<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel Admin')</title>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        :root {
            color-scheme: light;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fb;
            color: #172033;
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
            background: linear-gradient(180deg, #b91c1c 0%, #7f1d1d 100%);
            color: #fff;
            padding: 24px 18px;
            display: flex;
            flex-direction: column;
            gap: 32px;
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
            margin: 0;
        }

        .sidebar-sections {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .sidebar-section {
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            background-color: rgba(255, 255, 255, 0.08);
            overflow: hidden;
        }

        .sidebar-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            padding: 12px 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
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
            padding: 9px 12px;
            border-radius: 10px;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-links a.active,
        .sidebar-links a:hover {
            background-color: rgba(255, 255, 255, 0.24);
            color: #fff;
        }

        .content {
            background-color: #f5f6fb;
            padding: 24px 28px;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            gap: 16px;
        }

        .user-info {
            font-weight: 600;
            color: #334155;
        }

        .logout-button {
            border: none;
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
            color: #fff;
            padding: 12px 18px;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.3px;
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

        .sidebar .logout-button {
            width: 100%;
        }

        .logout-button span {
            pointer-events: none;
        }

        .main-content {
            flex: 1;
            max-width: 1180px;
            width: 100%;
            margin: 0 auto;
        }

        .flash {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .flash-success {
            background-color: #ecfdf3;
            color: #047857;
            border: 1px solid #86efac;
        }

        .flash-error {
            background-color: #fef3f2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .page-title {
            margin: 0 0 16px;
            font-size: 24px;
            font-weight: 600;
            color: #111827;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
            border: 1px solid #e5e7eb;
        }

        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .muted {
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 1024px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                height: auto;
                overflow-y: visible;
                flex-direction: column;
                align-items: stretch;
                justify-content: flex-start;
                padding: 20px 18px;
                gap: 18px;
            }

            .sidebar-sections {
                gap: 12px;
            }

            .sidebar-links {
                padding: 0 14px 14px;
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
            <div class="top-bar">
                <div class="user-info">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </div>
            </div>

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
