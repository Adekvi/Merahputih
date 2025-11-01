<x-layout.terminal title="Demand">

    <div class="page-inner ecommerce-page">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-2 text-maroon">🛒 E-Commerce</h3>
                <p class="text-muted mb-0">Permintaan Barang</p>
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
                            <form method="GET" action="{{ route('e-commerce.demand') }}"
                                class="row gy-2 gx-3 align-items-end" id="filterForm">
                                <div class="col-md-2">
                                    <a href="{{ route('e-commerce.demand.create') }}" class="btn btn-primary rounded-3"
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
                                    <a href="{{ route('e-commerce.demand') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-sync"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body bg-light rounded-4">
                            <form action="{{ route('e-commerce.demand') }}" method="GET"
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

                    @if ($demand->isEmpty())
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
                                @foreach ($demand as $item)
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="card demand-card h-100 shadow-sm hover-shadow">
                                            <div class="image-container position-relative">
                                                <img src="{{ $item->gambar ? Storage::url($item->gambar) : asset('aset/img/produk/jagung.jpg') }}"
                                                    class="card-img-top object-fit-cover w-100"
                                                    alt="{{ $item->nama_barang }}">
                                                <span
                                                    class="badge position-absolute top-0 end-0 m-2 {{ $item->jumlah > 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->jumlah > 0 ? 'Belum Terpenuhi' : 'Terpenuhi' }}
                                                </span>
                                            </div>

                                            <div class="card-body d-flex flex-column p-3">
                                                <h6 class="fw-bold text-dark mb-1 text-truncate"
                                                    title="{{ $item->nama_barang }}">
                                                    {{ Str::limit($item->nama_barang, 35) }}
                                                </h6>

                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-user text-maroon me-1"></i>
                                                    {{ $item->nama_demand ?? 'Pasar Umum' }}
                                                </p>

                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-map-marker-alt text-maroon me-1"></i>
                                                    {{ $item->kelurahan->nama_kelurahan ?? 'Lokasi tidak diketahui' }}
                                                </p>

                                                <div
                                                    class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                                    @if ($item->status == 'posting')
                                                        <span class="text-success fw-bold">{{ $item->jumlah ?? '-' }}
                                                            {{ $item->satuanJumlah->nama_satuan ?? '-' }}</span>
                                                        <span class="text-primary fw-bold">Rp
                                                            {{ number_format($item->harga ?? '-', 0, ',', '.') }}
                                                            \{{ $item->satuanHarga->nama_satuan ?? '-' }}
                                                        </span>
                                                    @else
                                                        <span class="text-success fw-bold">{{ $item->jumlah ?? '-' }}
                                                            {{ $item->satuanJumlah->nama_satuan ?? '-' }}</span>
                                                        <span class="text-primary fw-bold">Rp
                                                            {{ number_format($item->harga ?? '-', 0, ',', '.') }}
                                                            \{{ $item->satuanHarga->nama_satuan ?? '-' }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mt-auto d-flex flex-wrap gap-2">
                                                    @if ($item->status == 'posting')
                                                        <button
                                                            class="btn btn-success text-white flex-fill btn-sm shadow-sm d-flex align-items-center justify-content-center"
                                                            disabled>
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            <span>Posting</span>
                                                        </button>
                                                    @else
                                                        <button
                                                            class="btn btn-maroon text-white flex-fill btn-sm shadow-sm btn-penuhi d-flex align-items-center justify-content-center"
                                                            data-id="{{ $item->id }}"
                                                            data-produk='@json($item)'
                                                            data-satuan='@json($satuans)'>
                                                            <i class="fas fa-bullhorn me-1"></i>
                                                            <span>Request Barang</span>
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
                            {{ $demand->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    @push('css')
        <style>
            .demand-card {
                transition: all 0.3s ease;
                border-radius: 12px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                background-color: #fff;
                border: solid 0.2px #ededed;
            }

            .demand-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
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

            .demand-card:hover .image-container img {
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

            /* Efek tombol dipenuhi */
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

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.btn-penuhi').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const button = this;

                        const data = JSON.parse(this.dataset.produk);
                        const satuans = JSON.parse(this.dataset.satuan);

                        Swal.fire({
                            title: 'Edit Permintaan Barang',
                            html: `
                                <div class="text-start">
                                    <div class="row g-3">

                                        <!-- Nama Barang (read‑only, tapi tetap dikirim) -->
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">Nama Barang</label>
                                            <input id="nama_barang" type="text" class="form-control"
                                                value="${data.nama_barang ?? '-'}" readonly>
                                        </div>

                                        <!-- Jumlah + Satuan Jumlah -->
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">Jumlah</label>
                                            <div class="d-flex gap-2">
                                                <input id="jumlah" type="number" class="form-control"
                                                    value="${data.jumlah ?? 0}" min="1">
                                                <select id="satuan_jumlah_id" class="form-select" style="max-width:150px;">
                                                    ${satuans.map(opt => 
                                                        `<option value="${opt.id}" ${opt.id == data.satuan_jumlah_id ? 'selected' : ''}>${opt.nama_satuan}</option>`
                                                    ).join('')}
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Harga per Satuan + Satuan Harga -->
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">Harga per Satuan</label>
                                            <div class="d-flex gap-2">
                                                <div class="input-group" style="max-width:300px;">
                                                    <span class="input-group-text">Rp</span>
                                                    <input id="harga" type="number" class="form-control"
                                                        value="${Number(data.harga).toLocaleString('id-ID')}" min="0">
                                                </div>
                                                <select id="satuan_harga_id" class="form-select" style="max-width:150px;">
                                                    ${satuans.map(opt => 
                                                        `<option value="${opt.id}" ${opt.id == data.satuan_harga_id ? 'selected' : ''}>${opt.nama_satuan}</option>`
                                                    ).join('')}
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Simpan &amp; Posting',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#800000',
                            focusConfirm: false,
                            preConfirm: () => {
                                const nama_barang = document.getElementById('nama_barang')
                                    .value.trim();
                                const jumlah = document.getElementById('jumlah').value;
                                const satuan_jumlah_id = document.getElementById(
                                    'satuan_jumlah_id').value;
                                const harga = document.getElementById('harga').value;
                                const satuan_harga_id = document.getElementById(
                                    'satuan_harga_id').value;

                                if (!jumlah || !harga) {
                                    Swal.showValidationMessage(
                                        'Jumlah dan harga wajib diisi!');
                                    return false;
                                }
                                if (!nama_barang) {
                                    Swal.showValidationMessage('Nama barang wajib diisi!');
                                    return false;
                                }

                                return {
                                    nama_barang,
                                    stok: jumlah, // sesuai nama di controller
                                    satuan_jumlah_id,
                                    satuan_harga_id,
                                    harga
                                };
                            }
                        }).then(result => {
                            if (result.isConfirmed) {
                                const {
                                    nama_barang,
                                    stok,
                                    satuan_jumlah_id,
                                    satuan_harga_id,
                                    harga
                                } = result.value;

                                // Kirim data yang **EXACTLY** sama dengan validasi controller
                                fetch(`{{ route('e-commerce.demand.post', ['id' => ':id']) }}`
                                        .replace(':id', id), {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]').content,
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                nama_barang,
                                                stok, // <-- sesuai request->stok
                                                satuan_jumlah_id, // <-- tambahkan kedua satuan
                                                satuan_harga_id,
                                                harga
                                            })
                                        })
                                    .then(res => {
                                        if (!res.ok) throw new Error(
                                            'Network response was not ok');
                                        return res.json();
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            // Update UI tombol
                                            button.innerHTML =
                                                '<i class="fas fa-check-circle me-1"></i> Dipenuhi';
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
                                        } else {
                                            Swal.fire('Gagal', data.message ||
                                                'Terjadi kesalahan.', 'error');
                                        }
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        Swal.fire('Error', 'Gagal memproses permintaan.',
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
                                        <img 
                                            src="${data.gambar ? '/storage/' + data.gambar : '/aset/img/produk/jagung.jpg'}"
                                            alt="${data.nama_barang}" 
                                            onerror="this.src='/aset/img/produk/jagung.jpg';"
                                            style="width:100%; height:100%; object-fit:cover; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                                    </div>
                                    <div style="background:#fff8f8; border-radius:12px; padding:14px 18px; box-shadow:inset 0 0 6px rgba(168,50,50,0.1); font-size:14px;">
                                        <h5 class="fw-bold mb-2 text-maroon">Detail Permintaan</h5>
                                        <hr>
                                        <table style="width:100%; border-collapse:collapse;">
                                            <tbody>
                                                <tr><td style="font-weight:600; color:#a83232; width:110px;">Harga Maks</td><td style="width:15px; text-align:center;">:</td><td style="color:#333;">Rp ${Number(data.harga ?? '-').toLocaleString('id-ID')} /${data.satuan_harga?.nama_satuan ?? '-'}</td></tr>
                                                <tr><td style="font-weight:600; color:#a83232;">Kebutuhan</td><td style="text-align:center;">:</td><td style="color:#333;">${data.jumlah} ${data.satuan_jumlah?.nama_satuan}</td></tr>
                                                <tr><td style="font-weight:600; color:#a83232;">Peminta</td><td style="text-align:center;">:</td><td style="color:#333;">${data.nama_peminta ?? '-'}</td></tr>
                                                <tr><td style="font-weight:600; color:#a83232;">Lokasi</td><td style="text-align:center;">:</td><td style="color:#333;">${data.kelurahan?.nama_kelurahan ?? '-'}</td></tr>
                                                <tr><td style="font-weight:600; color:#a83232;">Kontak</td><td style="text-align:center;">:</td><td style="color:#333;">${data.no_hp ?? '-'}</td></tr>
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
