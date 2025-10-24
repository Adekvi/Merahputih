<x-layout.terminal title="E-Commerce">

    <div class="page-inner ecommerce-page">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-2 text-maroon">🛒 E-Commerce</h3>
                <p class="text-muted mb-0">Permintaan barang berdasarkan data statistik desa</p>
            </div>
            <div class="date-time mt-3 mt-md-0 text-md-end">
                <div id="tanggal" class="fw-semibold text-muted"></div>
                <div id="jam" class="fw-semibold text-muted"></div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body bg-light rounded-4">
                <form method="GET" action="" class="row g-3 align-items-end">
                    <div class="col-6 col-md-2">
                        <label for="entries" class="form-label fw-semibold small">Tampilkan</label>
                        <select name="entries" id="entries" class="form-select form-select-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="search" class="form-label fw-semibold small">Cari Barang</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" id="search" value="" class="form-control"
                                placeholder="Ketik nama barang...">
                            <button type="submit" class="btn btn-maroon text-white">
                                <i class="fa-solid fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <label for="kecamatan" class="form-label fw-semibold small">Kecamatan</label>
                        <select name="kecamatan" id="kecamatan" class="form-select form-select-sm">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label for="kelurahan" class="form-label fw-semibold small">Kelurahan</label>
                        <select name="kelurahan" id="kelurahan" class="form-select form-select-sm">
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Produk -->
        <div class="row g-4">
            @php
                $produk = [
                    [
                        'nama' => 'Beras Premium',
                        'jumlah' => '1.200 kg',
                        'harga' => 'Rp 13.000/kg',
                        'asal' => 'Desa Kedumulyo',
                        'gambar' => 'https://img.freepik.com/free-photo/white-rice-wooden-bowl_1150-42622.jpg?w=826',
                    ],
                    [
                        'nama' => 'Jagung Pipil',
                        'jumlah' => '800 kg',
                        'harga' => 'Rp 7.500/kg',
                        'asal' => 'Desa Tompe Gunung',
                        'gambar' => 'https://img.freepik.com/free-photo/corn-kernels-close-up_144627-26259.jpg?w=826',
                    ],
                    [
                        'nama' => 'Ketela Pohon',
                        'jumlah' => '600 kg',
                        'harga' => 'Rp 5.000/kg',
                        'asal' => 'Desa Cengkalsewu',
                        'gambar' => 'https://img.freepik.com/free-photo/fresh-cassava-roots_74190-6943.jpg?w=826',
                    ],
                    [
                        'nama' => 'Gula Merah',
                        'jumlah' => '500 kg',
                        'harga' => 'Rp 15.000/kg',
                        'asal' => 'Desa Kedungwinong',
                        'gambar' => 'https://img.freepik.com/free-photo/brown-sugar-bowl_1150-17412.jpg?w=826',
                    ],
                    [
                        'nama' => 'Kacang Tanah',
                        'jumlah' => '400 kg',
                        'harga' => 'Rp 9.000/kg',
                        'asal' => 'Desa Sanggrahan',
                        'gambar' => 'https://img.freepik.com/free-photo/peanuts-wooden-bowl_1150-17420.jpg?w=826',
                    ],
                    [
                        'nama' => 'Kopi Robusta',
                        'jumlah' => '250 kg',
                        'harga' => 'Rp 35.000/kg',
                        'asal' => 'Desa Pakem',
                        'gambar' => 'https://img.freepik.com/free-photo/coffee-beans-cup_144627-23656.jpg?w=826',
                    ],
                ];
            @endphp

            @foreach ($produk as $item)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        <img src="{{ $item['gambar'] }}" class="card-img-top" alt="{{ $item['nama'] }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-maroon">{{ $item['nama'] }}</h5>
                            <p class="card-text text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-2 text-maroon"></i>{{ $item['asal'] }}
                            </p>
                            <p class="card-text mb-1"><strong>Jumlah:</strong> {{ $item['jumlah'] }}</p>
                            <p class="card-text"><strong>Harga:</strong> {{ $item['harga'] }}</p>
                            <div class="mt-auto">
                                <button class="btn btn-maroon text-white w-100 shadow-sm">
                                    <i class="fas fa-shopping-cart me-2"></i>Pesan Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-3 mb-3">
        </div>
    </div>

    @push('css')
        <style>
            /* ===== Warna dan tema lembut merah ===== */
            .btn-maroon {
                background-color: #a83232;
                border-color: #a83232;
                transition: all 0.3s ease;
            }

            .btn-maroon:hover {
                background-color: #8f2a2a;
                border-color: #8f2a2a;
            }

            .text-maroon {
                color: #a83232;
            }

            .ecommerce-page {
                background: #faf8f8;
                border-radius: 12px;
                padding: 1rem;
                animation: fadeIn 0.6s ease;
            }

            .product-card {
                border-radius: 16px;
                overflow: hidden;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                background-color: #fffaf9;
            }

            .product-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 8px 20px rgba(168, 50, 50, 0.15);
            }

            .product-card img {
                height: 190px;
                object-fit: cover;
                border-bottom: 3px solid #a83232;
            }

            .card-body {
                padding: 1rem;
            }

            .date-time {
                line-height: 1.2;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsiveness */
            @media (max-width: 768px) {
                .ecommerce-page h3 {
                    font-size: 1.4rem;
                    text-align: center;
                }

                .date-time {
                    text-align: center;
                }
            }
        </style>
    @endpush

    @push('js')
        <script>
            // Jam & Tanggal Real-Time
            function updateClock() {
                const now = new Date();
                const tanggalOptions = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                document.getElementById('tanggal').innerText = now.toLocaleDateString('id-ID', tanggalOptions);
                document.getElementById('jam').innerText = now.toLocaleTimeString('id-ID');
            }
            setInterval(updateClock, 1000);
            updateClock();

            // Simulasi tombol Pesan
            document.querySelectorAll('.btn-maroon').forEach(btn => {
                btn.addEventListener('click', function() {
                    const nama = this.closest('.card').querySelector('.card-title').innerText;
                    alert(`Anda telah memilih: ${nama}\nSilakan lanjutkan pemesanan melalui admin.`);
                });
            });
        </script>
    @endpush

</x-layout.terminal>
