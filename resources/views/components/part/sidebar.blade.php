<div class="sidebar" data-background-color="red">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="red">
            <a href="{{ route('dashboard') }}" class="logo">
                {{-- <img src="{{ asset('aset/img/kaiadmin/favicon.png') }}" alt="navbar brand" class="navbar-brand"
                    height="30" /> --}}
                <span class="text-light font-bold">Kop. Merah Putih</span>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item active">
                    <a href="{{ route('dashboard') }}" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/menu') }}" class="collapsed" aria-expanded="false">
                        <i class="fa-solid fa-bars"></i>
                        <p>Menu</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
                </li>
                <li class="nav-item {{ Request::is('stats.index') ? 'active' : '' }}">
                    <a href="{{ route('stats.index') }}">
                        <i class="fas fa-chart-line"></i>
                        <p>Statistik</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('surveys.index') ? 'active' : '' }}">
                    <a href="{{ route('surveys.index') }}">
                        <i class="fas fa-database"></i>
                        <p>Data Sensus</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('surveys.voicecreate') ? 'active' : '' }}">
                    <a href="{{ route('surveys.voicecreate') }}">
                        <i class="fas fa-microphone"></i>
                        <p>Voice</p>
                    </a>
                </li>
                {{-- <li class="nav-item {{ Request::is('profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}">
                        <i class="fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li> --}}
                <li class="nav-item {{ Request::is('e-commerce/market*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#ecomerce"
                        class="{{ Request::is('e-commerce/market*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ Request::is('e-commerce/market*') ? 'true' : 'false' }}">
                        <i class="fas fa-shopping-cart"></i>
                        <p>E-Commerce</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ Request::is('e-commerce/market*') ? 'show' : '' }}" id="ecomerce">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('e-commerce/market') ? 'active' : '' }}">
                                <a href="{{ url('e-commerce/market') }}">
                                    <span class="sub-item">Market</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('e-commerce/market/suply*') ? 'active' : '' }}">
                                <a href="{{ url('e-commerce/market/suply') }}">
                                    <span class="sub-item">Supply</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('e-commerce/market/demand*') ? 'active' : '' }}">
                                <a href="{{ url('e-commerce/market/demand') }}">
                                    <span class="sub-item">Demand</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#submenu">
                        <i class="fas fa-bars"></i>
                        <p>Menu Levels</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="submenu">
                        <ul class="nav nav-collapse">
                            <li>
                                <a data-bs-toggle="collapse" href="#subnav1">
                                    <span class="sub-item">Level 1</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="subnav1">
                                    <ul class="nav nav-collapse subnav">
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Level 2</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Level 2</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a data-bs-toggle="collapse" href="#subnav2">
                                    <span class="sub-item">Level 1</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="subnav2">
                                    <ul class="nav nav-collapse subnav">
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Level 2</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Level 1</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
            </ul>
        </div>
    </div>
</div>
