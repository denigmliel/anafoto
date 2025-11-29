<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" sizes="any" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

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
            --sidebar-width: 220px;
            --sidebar-transition: 0.25s ease;
        }

        body {
            margin: 0;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .layout {
            min-height: 100vh;
            display: flex;
            width: 100vw;
        }

        .layout.has-sidebar-open {
            overflow: hidden;
        }

        .sidebar {
            background: linear-gradient(180deg, #b91c1c 0%, #7f1d1d 100%);
            color: #fff;
            padding: 24px 18px;
            display: flex;
            flex-direction: column;
            gap: 32px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            width: var(--sidebar-width);
            z-index: 1001;
            transition: transform var(--sidebar-transition), box-shadow var(--sidebar-transition);
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

        .sidebar-section-single {
            padding: 0;
        }

        .sidebar-single-link {
            display: block;
            padding: 12px 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-single-link.active,
        .sidebar-single-link:hover {
            background-color: rgba(255, 255, 255, 0.24);
            color: #fff;
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
            padding: 24px clamp(18px, 3vw, 32px);
            display: flex;
            flex-direction: column;
            width: calc(100vw - var(--sidebar-width));
            max-width: calc(100vw - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            box-sizing: border-box;
            min-width: 0;
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

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.38);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity var(--sidebar-transition), visibility var(--sidebar-transition);
            z-index: 1000;
        }

        .layout.has-sidebar-open .sidebar-overlay {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .sidebar-toggle {
            position: fixed;
            top: 14px;
            right: 14px;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
            cursor: pointer;
            z-index: 1002;
            transition: transform 0.16s ease, box-shadow 0.16s ease, filter 0.16s ease;
        }

        .sidebar-toggle:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.22);
        }

        .sidebar-toggle:active {
            transform: translateY(0);
        }

        .sidebar-toggle__icon,
        .sidebar-toggle__icon::before,
        .sidebar-toggle__icon::after {
            display: block;
            width: 22px;
            height: 2px;
            background: currentColor;
            border-radius: 2px;
            position: absolute;
            left: 0;
            transition: transform 0.2s ease, opacity 0.2s ease;
            content: '';
        }

        .sidebar-toggle__icon {
            position: relative;
        }

        .sidebar-toggle__icon::before { top: -7px; }
        .sidebar-toggle__icon::after { top: 7px; }

        .layout.has-sidebar-open .sidebar-toggle__icon {
            background: transparent;
        }
        .layout.has-sidebar-open .sidebar-toggle__icon::before {
            transform: translateY(7px) rotate(45deg);
        }
        .layout.has-sidebar-open .sidebar-toggle__icon::after {
            transform: translateY(-7px) rotate(-45deg);
        }

        .main-content {
            flex: 1;
            width: 100%;
            max-width: none;
            margin: 0;
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

        @media (max-width: 1200px) {
            :root {
                --sidebar-width: 204px;
            }
        }

        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 190px;
            }

            .sidebar {
                padding: 20px 14px;
                gap: 20px;
            }

            .sidebar-sections {
                gap: 12px;
            }

            .sidebar-links {
                padding: 0 14px 14px;
            }

            .content {
                padding: 20px 16px;
                width: calc(100vw - var(--sidebar-width));
                max-width: calc(100vw - var(--sidebar-width));
                margin-left: var(--sidebar-width);
            }

            .main-content {
                margin: 0;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            :root {
                --sidebar-width: 178px;
            }

            body {
                overflow-x: hidden;
            }

            .layout {
                flex-direction: column;
                width: 100%;
                min-height: 100vh;
                height: auto;
            }

            .sidebar {
                width: min(82vw, 320px);
                max-width: 340px;
                height: 100vh;
                max-height: 100vh;
                padding: 18px 16px;
                overflow-y: auto;
                box-shadow: 0 16px 40px rgba(0, 0, 0, 0.24);
                transform: translateX(-100%);
            }

            .layout.has-sidebar-open .sidebar {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: inline-flex;
            }

            .content {
                margin-left: 0;
                width: 100%;
                max-width: 100%;
                min-height: auto;
                height: auto;
                overflow: visible;
                padding: 18px 14px 60px;
                margin-top: 62px;
            }

            .main-content {
                min-height: auto;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="layout">
        @include('layouts.partials.sidebar')

        <div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>
        <button
            type="button"
            class="sidebar-toggle"
            id="sidebar-toggle"
            aria-label="Buka menu"
            aria-expanded="false"
        >
            <span class="sidebar-toggle__icon" aria-hidden="true"></span>
        </button>

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const layout = document.querySelector('.layout');
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.getElementById('sidebar-toggle');
            const overlay = document.getElementById('sidebar-overlay');

            if (!layout || !sidebar || !toggle || !overlay) {
                return;
            }

            const setExpanded = (state) => toggle.setAttribute('aria-expanded', state ? 'true' : 'false');

            const closeSidebar = () => {
                layout.classList.remove('has-sidebar-open');
                setExpanded(false);
            };

            const openSidebar = () => {
                layout.classList.add('has-sidebar-open');
                setExpanded(true);
            };

            const toggleSidebar = () => {
                if (layout.classList.contains('has-sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            };

            toggle.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', closeSidebar);

            sidebar.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    closeSidebar();
                }
            });

            window.addEventListener('keyup', (event) => {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
