<x-layout.terminal title="Dashboard">

    <div class="page-inner">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between pt-2 pb-4 flex-wrap">
            <h3 class="fw-bold mb-3 text-primary">
                <i class="fa-solid fa-layer-group me-2"></i> Menu Utama
            </h3>
            {{-- 
            <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-outline-primary btn-round me-2">
                    <i class="fa-solid fa-file-export me-1"></i> Export
                </a>
                <a href="#" class="btn btn-primary btn-round">
                    <i class="fa-solid fa-rotate-right me-1"></i> Refresh Data
                </a>
            </div> 
            --}}
        </div>

        <!-- Menu Cards -->
        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ url('/dashboard') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 hover-card h-100">
                        <div class="card-body text-center">
                            <div class="image-container mb-3">
                                <img src="{{ asset('aset/img/produk/ngoceh.png') }}" alt="NgocehGo" class="menu-image">
                            </div>
                            <h1 class="fw-bold text-dark mb-2">Ngoceh Go</h1>
                            <p class="text-muted small mb-0">Aplikasi komunikasi interaktif dan real-time</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ url('e-commerce/market') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 hover-card h-100">
                        <div class="card-body text-center">
                            <div class="image-container mb-3">
                                <img src="{{ asset('aset/img/produk/barter.png') }}" alt="BarterGo" class="menu-image">
                            </div>
                            <h1 class="fw-bold text-dark mb-2">Barter Go</h1>
                            <p class="text-muted small mb-0">Platform tukar-menukar barang digital dan fisik</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Contoh tambahan -->
            {{-- <div class="col-md-6 col-lg-4">
                <a href="#" class="text-decoration-none">
                    <div class="card shadow-sm border-0 hover-card h-100">
                        <div class="card-body text-center">
                            <div class="image-container mb-3">
                                <img src="{{ asset('aset/img/produk/kasir.png') }}" alt="KasirGo" class="menu-image">
                            </div>
                            <h1 class="fw-bold text-dark mb-2">KasirGo</h1>
                            <p class="text-muted small mb-0">Sistem kasir modern dan laporan penjualan</p>
                        </div>
                    </div>
                </a>
            </div> --}}
        </div>
    </div>

    <style>
        /* Efek hover dan transisi */
        .hover-card {
            transition: all 0.3s ease-in-out;
            border-radius: 15px;
        }

        .hover-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .menu-image {
            width: 90px;
            height: 90px;
            object-fit: contain;
            transition: transform 0.3s ease-in-out;
        }

        .hover-card:hover .menu-image {
            transform: scale(1.1);
        }

        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 120px;
        }

        @media (max-width: 768px) {
            .menu-image {
                width: 70px;
                height: 70px;
            }
        }
    </style>

</x-layout.terminal>
