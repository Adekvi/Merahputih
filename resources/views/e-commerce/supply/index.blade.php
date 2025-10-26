<x-layout.terminal title="Supply">

    <div class="page-inner ecommerce-page">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-2 text-maroon">🛒 E-Commerce</h3>
                <p class="text-muted mb-0">Penawaran Barang</p>
            </div>
            <div class="date-time mt-3 mt-md-0 text-md-end">
                <div id="tanggal" class="fw-semibold text-muted"></div>
                <div id="jam" class="fw-semibold text-muted"></div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="text-center mb-4">
                <h4 class="fw-bold text-maroon">Produk</h4>
                <hr class="w-25 mx-auto">
            </div>
            <div class="card">
                <div class="card-body">

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body bg-light rounded-4">
                            <form method="GET" action="{{ route('e-commerce.suply') }}"
                                class="row gy-2 gx-3 align-items-end" id="filterForm">
                                <div class="col-md-2">
                                    <a href="{{ route('e-commerce.suply.create') }}" class="btn btn-primary rounded-3"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Data">
                                        <i class="fa fa-cart-plus" aria-hidden="true"></i> Tambah</i>
                                    </a>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="id_kecamatan"
                                        class="form-label fw-semibold small mb-1">Kecamatan</label>
                                    <select name="id_kecamatan" id="id_kecamatan" class="form-select form-select-sm">
                                        <option value="">-- Semua --</option>
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->id }}"
                                                {{ $id_kecamatan == $kec->id ? 'selected' : '' }}>
                                                {{ $kec->nama_kecamatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="id_kelurahan"
                                        class="form-label fw-semibold small mb-1">Kelurahan</label>
                                    <select name="id_kelurahan" id="id_kelurahan" class="form-select form-select-sm">
                                        <option value="">-- Semua --</option>
                                        @if ($id_kecamatan)
                                            @foreach ($kelurahan as $kel)
                                                <option value="{{ $kel->id }}"
                                                    {{ $id_kelurahan == $kel->id ? 'selected' : '' }}>
                                                    {{ $kel->nama_kelurahan }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('e-commerce.suply') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-sync"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body bg-light rounded-4">
                            <form action="{{ route('e-commerce.suply') }}" method="GET"
                                class="row gy-2 gx-3 align-items-end">
                                <div class="col-6 col-md-2">
                                    <label for="entries" class="form-label fw-semibold small mb-1">Tampil</label>
                                    <select name="entries" id="entries" class="form-select form-select-sm">
                                        <option value="10" {{ $entries == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $entries == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $entries == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-4 position-relative">
                                    <label for="search" class="form-label fw-semibold small mb-1">Cari Barang</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="search" id="search" class="form-control"
                                            placeholder="Nama, jenis, supplier..." value="{{ $search }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr class="w-100 mx-auto">

                    @if ($suply->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Tidak ada data barang yang ditemukan.
                        </div>
                    @else
                        <div class="row g-4">
                            <?php
                            if (!function_exists('rupiah')) {
                                function Rupiah($angka)
                                {
                                    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
                                }
                            }
                            ?>
                            <div class="row g-3 g-md-4">
                                @foreach ($suply as $item)
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="card product-card h-100 shadow-sm hover-shadow">
                                            <div class="image-container position-relative">
                                                <img src="{{ $item->gambar ? asset($item->gambar) : asset('aset/img/produk/jagung.jpg') }}"
                                                    class="card-img-top object-fit-cover w-100"
                                                    alt="{{ $item->nama_barang }}">
                                                <span
                                                    class="badge position-absolute top-0 end-0 m-2 {{ $item->jumlah > 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->jumlah > 0 ? 'Tersedia' : 'Habis' }}
                                                </span>
                                            </div>

                                            <div class="card-body d-flex flex-column p-3">
                                                <h6 class="fw-bold text-maroon mb-1 text-truncate"
                                                    title="{{ $item->nama_barang }}">
                                                    {{ Str::limit($item->nama_barang, 35) }}
                                                </h6>

                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-map-marker-alt text-maroon me-1"></i>
                                                    {{ $item->kelurahan->nama_kelurahan ?? 'Unknown' }}
                                                </p>

                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-store me-1"></i>
                                                    {{ $item->nama_supplier ?? 'Unknown Supplier' }}
                                                </p>

                                                <div
                                                    class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                                    <span class="text-success fw-bold">{{ $item->jumlah }}
                                                        {{ $item->satuanJumlah->nama_satuan ?? '-' }}</span>
                                                    <span class="text-primary fw-bold">{{ Rupiah($item->harga) }}
                                                        /{{ $item->satuanHarga->nama_satuan ?? '-' }}</span>
                                                </div>

                                                <div class="mt-auto d-flex flex-wrap gap-2">
                                                    @if ($item->status == 'ditawarkan')
                                                        <button
                                                            class="btn btn-success text-white flex-fill btn-sm shadow-sm d-flex align-items-center justify-content-center"
                                                            disabled>
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            <span>Ditawarkan</span>
                                                        </button>
                                                    @else
                                                        <button
                                                            class="btn btn-maroon text-white flex-fill btn-sm shadow-sm btn-tawarkan d-flex align-items-center justify-content-center"
                                                            data-id="{{ $item->id }}">
                                                            <i class="fas fa-bullhorn me-1"></i>
                                                            <span>Tawarkan</span>
                                                        </button>
                                                    @endif

                                                    <button
                                                        class="btn btn-outline-maroon flex-fill btn-sm shadow-sm btn-detail d-flex align-items-center justify-content-center"
                                                        data-produk='@json($item)'>
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        <span>Detail</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Paginasi -->
                        <div class="d-flex justify-content-center mt-5">
                            {{ $suply->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    @push('css')
        <style>
            .product-card {
                transition: all 0.3s ease;
                border-radius: 12px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                background-color: #fff;
                border: solid 0.2px #ededed;
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            }

            .hover-shadow {
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            }

            .image-container {
                width: 100%;
                aspect-ratio: 4/3;
                overflow: hidden;
            }

            .image-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s ease;
            }

            .product-card:hover .image-container img {
                transform: scale(1.08);
            }

            .badge {
                font-size: 0.7rem;
                padding: 0.35em 0.65em;
            }

            .text-maroon {
                color: #800000;
            }

            .btn-maroon {
                background-color: #800000;
                border-color: #800000;
            }

            .btn-maroon:hover {
                background-color: #660000;
                border-color: #660000;
            }

            .btn-outline-maroon {
                color: #800000;
                border-color: #800000;
            }

            .btn-outline-maroon:hover {
                background-color: #800000;
                color: #fff;
            }

            /* Efek animasi tombol saat berhasil ditawarkan */
            @keyframes pulse {
                0% {
                    transform: scale(1);
                    box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.6);
                }

                70% {
                    transform: scale(1.05);
                    box-shadow: 0 0 10px 5px rgba(25, 135, 84, 0.3);
                }

                100% {
                    transform: scale(1);
                    box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
                }
            }

            .pulse-animation {
                animation: pulse 1s ease-in-out;
            }

            /* Responsivitas teks dan tombol */
            @media (max-width: 576px) {
                .card-body h6 {
                    font-size: 0.9rem;
                }

                .btn-sm {
                    font-size: 0.75rem;
                    padding: 0.35rem 0.5rem;
                }

                .text-muted.small {
                    font-size: 0.7rem;
                }

                .image-container {
                    aspect-ratio: 1/1;
                }
            }
        </style>
    @endpush

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
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

            $(document).ready(function() {
                // Update kelurahan saat kecamatan berubah
                $('#id_kecamatan').change(function() {
                    const kecamatanId = $(this).val();
                    const $kelurahan = $('#id_kelurahan');

                    if (!kecamatanId) {
                        $kelurahan.html('<option value="">-- Semua --</option>');
                        return;
                    }

                    $.ajax({
                        url: '{{ route('getKelurahan') }}',
                        method: 'GET',
                        data: {
                            id_kecamatan: kecamatanId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            $kelurahan.html('<option value="">-- Semua --</option>');
                            $.each(data, function(i, item) {
                                $kelurahan.append(
                                    `<option value="${item.id}">${item.nama_kelurahan}</option>`
                                );
                            });
                        },
                        error: function() {
                            Swal.fire('Error', 'Gagal memuat kelurahan', 'error');
                        }
                    });
                });

                // Auto-submit saat entries berubah
                $('#entries').change(function() {
                    $('#filterForm').submit();
                });
            });

            // posting barang
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.btn-tawarkan').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const button = this;

                        Swal.fire({
                            title: 'Tawarkan Barang?',
                            text: 'Barang ini akan muncul di Market dan siap dipesan.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, tawarkan!',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#800000',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`{{ route('e-commerce.suply.posting', ['id' => ':id']) }}`
                                        .replace(':id', id), {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]').content,
                                                'Content-Type': 'application/json'
                                            }
                                        })

                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Ubah tampilan tombol
                                            button.innerHTML =
                                                '<i class="fas fa-check-circle me-1"></i> Ditawarkan';
                                            button.classList.remove('btn-maroon');
                                            button.classList.add('btn-success',
                                                'pulse-animation');
                                            button.disabled = true;

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: data.message,
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                        }
                                    })
                                    .catch(err => {
                                        Swal.fire('Error', 'Gagal menawarkan barang.',
                                            'error');
                                    });
                            }
                        });
                    });
                });

                document.querySelectorAll('.btn-detail').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const data = JSON.parse(this.dataset.produk);
                        Swal.fire({
                            title: data.nama_barang,
                            html: `
                                <div class="text-start">
                                    <div style="width:100%; height:250px; overflow:hidden; border-radius:12px; margin-bottom:15px;">
                                        <img src="${data.gambar ? '/storage/' + data.gambar : '/aset/img/produk/jagung.jpg'}" alt="${data.nama_barang}" 
                                            style="width:100%; height:100%; object-fit:cover; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                                    </div>

                                    <div style="background:#fff8f8; border-radius:12px; padding:14px 18px; box-shadow:inset 0 0 6px rgba(168,50,50,0.1); font-size:14px;">
                                        <h5 class="fw-bold mb-2 text-maroon">Detail Produk</h4>
                                        <hr>
                                        <table style="width:100%; border-collapse:collapse;">
                                            <tbody>
                                                <tr><td style="font-weight:600; color:#a83232; width:110px;">Harga</td><td style="width:15px; text-align:center;">:</td><td style="color:#333;">Rp ${Number(data.harga).toLocaleString('id-ID')}, ${data.satuan_harga?.nama_satuan ?? '-'}</td></tr>
                                                <tr><td style="font-weight:600; color:#a83232;">Stok</td><td style="text-align:center;">:</td><td style="color:#333;">${data.jumlah} ${data.satuan_jumlah?.nama_satuan ?? '-'}</td></tr>
                                                <tr><td style="font-weight:600; color:#a83232;">Desa</td><td style="text-align:center;">:</td><td style="color:#333;">${data.kelurahan?.nama_kelurahan ?? '-'}</td></tr>
                                                <tr><td style="font-weight:600; color:#a83232;">Supplier</td><td style="text-align:center;">:</td><td style="color:#333;">${data.nama_supplier ?? '-'}</td></tr>
                                                <tr><td style="font-weight:600; color:#a83232;">Alamat</td><td style="text-align:center;">:</td><td style="color:#333;">${data.kecamatan?.nama_kecamatan ?? '-'}</td></tr>
                                                <tr><td style="font-weight:600; color:#a83232;">No. Hp</td><td style="text-align:center;">:</td><td style="color:#333;">${data.no_hp ?? '-'}</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            `,
                            confirmButtonColor: '#800000'
                        });
                    });
                });

            });
        </script>
    @endpush

</x-layout.terminal>
