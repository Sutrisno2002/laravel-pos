<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        @auth
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ auth()->user()->getAvatar() }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->getFullname() }}</a>
                </div>
            </div>
        @endauth

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
   
                    <!-- Check the user's level_id and display menu items accordingly -->
                    @if (auth()->user()->level_id == '1')
                        <!-- Admin Menu Items -->
                        <li class="nav-item has-treeview">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="nav-icon fas fa-home-alt"></i>
                                <p>{{ __('Beranda') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                                <i class="nav-icon fas fa-th-large"></i>
                                <p>{{ __('Produk') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('stok.index') }}" class="nav-link {{ activeSegment('stok') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Stok') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Penjualan') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('return.index') }}" class="nav-link {{ activeSegment('return') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Return') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="/admin/stok-masuk" class="nav-link {{ activeSegment('Laporan Stok Masuk') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Laporan Stok Masuk') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="/admin/return-masuk" class="nav-link {{ activeSegment('Laporan Return') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Laporan Return') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="laporanHarian" class="nav-link {{ activeSegment('Laporan Harian') }}">
                                <i class="nav-icon fa-solid fa-table"></i>
                                <p>{{ __('Laporan Harian') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="laporanBulanan" class="nav-link {{ activeSegment('Laporan Bulanan') }}">
                                <i class="nav-icon fa-solid fa-table"></i>
                                <p>{{ __('Laporan Bulanan') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('settings.index') }}" class="nav-link {{ activeSegment('settings') }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>{{ __('Pengaturan') }}</p>
                            </a>
                        </li>
                    @elseif (auth()->user()->level_id == '2')
                        <!-- Manager Menu Items -->
                        <li class="nav-item has-treeview">
                            <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Penjualan') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="/admin/laporanHarian" class="nav-link {{ activeSegment('Laporan Harian') }}">
                                <i class="nav-icon fa-solid fa-book"></i>
                                <p>{{ __('Laporan Harian') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="laporanBulanan" class="nav-link {{ activeSegment('Laporan Bulanan') }}">
                                <i class="nav-icon fa-solid fa-table"></i>
                                <p>{{ __('Laporan Bulanan') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('settings.index') }}" class="nav-link {{ activeSegment('settings') }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>{{ __('Pengaturan') }}</p>
                            </a>
                        </li>
                    @elseif (auth()->user()->level_id == '3')
                        <!-- Staff Menu Items -->
                        <li class="nav-item has-treeview">
                            <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                                <i class="nav-icon fas fa-th-large"></i>
                                <p>{{ __('Produk') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('stok.index') }}" class="nav-link {{ activeSegment('stok') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Stok') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('return.index') }}" class="nav-link {{ activeSegment('return') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Return') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="/admin/stok-masuk" class="nav-link {{ activeSegment('Laporan Stok Masuk') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Laporan Stok Masuk') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="/admin/return-masuk" class="nav-link {{ activeSegment('Laporan Return') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>{{ __('Laporan Return') }}</p>
                            </a>
                        </li>
                    @elseif (auth()->user()->level_id == '4')
                        <!-- Report Viewer Menu Items -->
                        <li class="nav-item has-treeview">
                            <a href="/admin/laporanHarian" class="nav-link {{ activeSegment('Laporan Harian') }}">
                                <i class="nav-icon fa-solid fa-book"></i>
                                <p>{{ __('Laporan Harian') }}</p>
                            </a>
                        </li>
                    @endif

                    <!-- Logout -->
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>{{ __('common.Logout') }}</p>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                            </form>
                        </a>
                    </li>

                <!-- Items for Non-Authenticated Users -->
                @if (auth()->user()->level_id == null)
                    <!-- Publicly Available Menu Items -->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                            <i class="nav-icon fas fa-cart-plus"></i>
                            <p>{{ __('Penjualan') }}</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
