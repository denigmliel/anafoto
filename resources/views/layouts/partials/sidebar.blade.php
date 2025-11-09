@php
    $sections = collect([
        [
            'key' => 'kasir',
            'label' => 'Modul Kasir',
            'links' => [
                ['route' => 'kasir.dashboard', 'label' => 'Dashboard', 'pattern' => 'kasir.dashboard'],
                ['route' => 'kasir.pos', 'label' => 'Point of Sale', 'pattern' => 'kasir.pos'],
                ['route' => 'kasir.transaction.history', 'label' => 'Riwayat Transaksi', 'pattern' => 'kasir.transaction.*'],
            ],
        ],
        [
            'key' => 'gudang',
            'label' => 'Modul Gudang',
            'links' => [
                ['route' => 'gudang.dashboard', 'label' => 'Dashboard', 'pattern' => 'gudang.dashboard'],
                ['route' => 'gudang.products.index', 'label' => 'Produk', 'pattern' => 'gudang.products.*'],
                ['route' => 'gudang.stock.movements', 'label' => 'Pergerakan Stok', 'pattern' => 'gudang.stock.*'],
                ['route' => 'gudang.reports.stock', 'label' => 'Laporan', 'pattern' => 'gudang.reports.*'],
            ],
        ],
        [
            'key' => 'admin',
            'label' => 'Modul Admin',
            'links' => [
                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'pattern' => 'admin.dashboard'],
            ],
        ],
    ])->map(function (array $section) {
        $isActive = collect($section['links'])
            ->contains(fn ($link) => request()->routeIs($link['pattern'] ?? $link['route']));

        return $section + ['is_active' => $isActive];
    });
@endphp

<aside class="sidebar">
    <div class="sidebar-top">
        <div class="brand">
            <span>ANA</span>
            <span>FOTOCOPY</span>
        </div>

        <div class="sidebar-sections">
            @foreach ($sections as $section)
                <details class="sidebar-section" @if ($section['is_active']) open @endif>
                    <summary class="sidebar-summary">
                        <span>{{ $section['label'] }}</span>
                        <span class="sidebar-caret" aria-hidden="true"></span>
                    </summary>

                    <nav class="sidebar-links">
                        @foreach ($section['links'] as $link)
                            <a
                                href="{{ route($link['route']) }}"
                                class="{{ request()->routeIs($link['pattern'] ?? $link['route']) ? 'active' : '' }}"
                            >
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </details>
            @endforeach
        </div>
    </div>

    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-button">
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
