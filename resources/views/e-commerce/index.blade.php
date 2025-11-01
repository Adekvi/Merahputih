<x-layout.terminal title="E-Commerce">

    <div class="page-inner ecommerce-page">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-2 text-maroon">🛒 E-Commerce</h3>
                <p class="text-muted mb-0">Permintaan & Penawaran barang</p>
            </div>
            <div class="date-time mt-3 mt-md-0 text-md-end">
                <div id="tanggal" class="fw-semibold text-muted"></div>
                <div id="jam" class="fw-semibold text-muted"></div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body bg-light rounded-4">
                <form method="GET" action="{{ route('e-commerce.market') }}" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-6 col-md-3">
                        <label for="id_kecamatan" class="form-label fw-semibold small">Kecamatan</label>
                        <select name="id_kecamatan" id="id_kecamatan" class="form-select form-select-sm">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($kecamatan as $kec)
                                <option value="{{ $kec->id }}" {{ $id_kecamatan == $kec->id ? 'selected' : '' }}>
                                    {{ $kec->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label for="id_kelurahan" class="form-label fw-semibold small">Kelurahan</label>
                        <select name="id_kelurahan" id="id_kelurahan" class="form-select form-select-sm">
                            <option value="">-- Pilih Kelurahan --</option>
                            @foreach ($kelurahan as $kel)
                                <option value="{{ $kel->id }}" {{ $id_kelurahan == $kel->id ? 'selected' : '' }}>
                                    {{ $kel->nama_kelurahan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('e-commerce.market') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-sync"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Produk -->
        <div class="row g-4">
            <div class="card">
                <div class="card-body">
                    <div class="judul">
                        <h4 class="text-center fw-bold">Produk</h4>
                        <hr>
                        <form action="{{ route('e-commerce.market') }}" method="GET" enctype="multipart/form-data"
                            class="row g-3 align-items-end">
                            @csrf
                            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.2s"
                                style="max-width: 800px;">

                                <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
                                    <!-- Dropdown "Tampilkan" -->
                                    <div class="d-flex align-items-center">
                                        <label for="entries"
                                            class="form-label fw-semibold small me-2 mb-0">Tampilkan</label>
                                        <select name="entries" id="entries" class="form-select form-select-sm">
                                            <option value="10" {{ $entries == 10 ? 'selected' : '' }}>10</option>
                                            <option value="25" {{ $entries == 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ $entries == 50 ? 'selected' : '' }}>50</option>
                                        </select>
                                    </div>

                                    <!-- Input Search -->
                                    <div class="input-group" style="max-width: 350px;">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" class="form-control"
                                                placeholder="Nama, jenis, supplier..." value="{{ $search }}">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('e-commerce.market', array_merge(request()->query(), ['type' => 'supply'])) }}"
                                            class="btn {{ request('type') === 'supply' ? 'btn-success' : 'btn-outline-success' }} rounded-start">
                                            <i class="fas fa-box me-1"></i> Penawaran
                                        </a>
                                        <a href="{{ route('e-commerce.market', array_merge(request()->query(), ['type' => 'demand'])) }}"
                                            class="btn {{ request('type') === 'demand' ? 'btn-info' : 'btn-outline-info' }}">
                                            <i class="fas fa-shopping-cart me-1"></i> Permintaan
                                        </a>
                                        <a href="{{ route('e-commerce.market', request()->except('type')) }}"
                                            class="btn {{ !request('type') ? 'btn-secondary' : 'btn-outline-secondary' }} rounded-end">
                                            <i class="fas fa-th-large me-1"></i> Semua
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                    <div class="row g-4">
                        @if ($market->isEmpty())
                            <div class="alert alert-info d-flex align-items-center justify-content-center text-center"
                                style="gap: 10px;">
                                <i class="fas fa-info-circle" style="font-size: 35px; color: #ff9d00;"></i>
                                <div>
                                    @if ($search)
                                        <p style="font-size: 16px; margin: 0;">
                                            <strong>Data dengan kata kunci "{{ $search }}" tidak
                                                ditemukan.</strong><br>
                                            <small>Coba gunakan kata kunci lain atau hapus filter.</small>
                                        </p>
                                    @else
                                        <p style="font-size: 16px; margin: 0;">
                                            Tidak ada data barang yang tersedia saat ini.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <?php
                            if (!function_exists('rupiah')) {
                                function Rupiah($angka)
                                {
                                    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
                                }
                            }
                            ?>
                            @foreach ($market as $item)
                                @php
                                    // Tentukan sumber data
                                    $isSupply = isset($item->suply);
                                    $data = $isSupply ? $item->suply : $item->demand;

                                    // Tentukan label dan warna badge
                                    $label = $isSupply ? 'Penawaran (Supply)' : 'Permintaan (Demand)';

                                    // Tentukan path gambar dengan pengecekan yang benar
                                    if ($data->gambar) {
                                        if (Str::startsWith($data->gambar, ['storage/', 'aset/'])) {
                                            // Sudah lengkap (langsung bisa diakses lewat asset)
                                            $gambar = asset($data->gambar);
                                        } else {
                                            // Asumsikan file disimpan di dalam storage/app/public
                                            $gambar = asset('storage/' . $data->gambar);
                                        }
                                    } else {
                                        // Fallback image jika kosong
                                        $gambar = asset('aset/img/produk/keranjang.png');
                                    }
                                @endphp

                                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card product-card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                        <div class="position-relative">
                                            <img src="{{ $gambar }}" alt="{{ $data->nama_barang }}"
                                                class="card-img-top" style="height: 200px; object-fit: cover;"
                                                onerror="this.src='{{ asset('aset/img/produk/keranjang.png') }}'">
                                        </div>

                                        <div class="card-body d-flex flex-column">
                                            <p class="card-title" style="font-size: 14px">
                                                <span class="{{ $isSupply ? 'text-success' : 'text-primary' }}">
                                                    {{ $label }}
                                                </span>
                                            </p>
                                            <h5 class="card-title fw-bold text-maroon">{{ $data->nama_barang }}</h5>

                                            <p class="card-text text-muted mb-1">
                                                <i class="fas fa-map-marker-alt me-2 text-maroon"></i>
                                                {{ $data->kelurahan?->nama_kelurahan ?? ($data->kecamatan?->nama_kecamatan ?? 'Lokasi tidak diketahui') }}
                                            </p>

                                            <p class="card-text info-row">
                                                <span class="info-label">Jumlah</span>
                                                <span class="info-value">
                                                    : {{ $data->jumlah }}
                                                    {{ $data->satuanJumlah?->nama_satuan ?? '' }}
                                                </span>
                                            </p>

                                            <p class="card-text info-row">
                                                <span class="info-label">Harga</span>
                                                <span class="info-value">
                                                    : Rp {{ number_format($data->harga, 0, ',', '.') }}
                                                    / {{ $data->satuanHarga?->nama_satuan ?? '' }}
                                                </span>
                                            </p>

                                            @if ($isSupply)
                                                <p class="text-muted mb-2"><i
                                                        class="fas fa-user me-2 text-maroon"></i>
                                                    Supplier: {{ $data->nama_supplier ?? '-' }}</p>
                                            @else
                                                <p class="text-muted mb-2"><i
                                                        class="fas fa-users me-2 text-maroon"></i>
                                                    Pembeli: {{ $data->nama_pembeli ?? '-' }}</p>
                                            @endif

                                            <div class="mt-auto">
                                                <button class="btn btn-maroon text-white w-100 shadow-sm btn-produk"
                                                    data-produk='@json($data)'>
                                                    <i class="fas fa-shopping-cart me-2"></i>Detail Barang
                                                </button>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-outline-maroon w-100 shadow-sm btn-barter"
                                                    data-produk='@json($data)'>
                                                    <i class="fas fa-exchange-alt me-2"></i>Barter Sekarang
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3 mb-3">
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="barterModal" tabindex="-1" aria-labelledby="barterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-maroon text-white rounded-top-4">
                    <h5 class="modal-title text-dark" id="barterModalLabel">
                        <i class="fas fa-exchange-alt me-2"></i>Barter Barang
                    </h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="selectedProduct" class="mb-3 border rounded-3 p-3 bg-light">
                        <h6 class="fw-bold text-maroon mb-2">Barang yang Dipilih:</h6>
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img id="barterImg" src="" class="img-fluid rounded-3 shadow-sm"
                                    style="height: 150px; object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <p class="mb-1"><strong>Nama:</strong> <span id="barterNama"></span></p>
                                <p class="mb-1"><strong>Jumlah:</strong> <span id="barterJumlah"></span></p>
                                <p class="mb-1"><strong>Harga:</strong> <span id="barterHarga"></span></p>
                                {{-- <p class="mb-1"><strong>Kecamatan:</strong> <span id="barterKecamatan"></span></p>
                                <p class="mb-1"><strong>Kelurahan:</strong> <span id="barterKelurahan"></span></p> --}}
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold text-maroon mb-3">Pilih Barang Penukar Anda:</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle" id="barangPenukarTable">
                            <thead class="table-maroon text-white">
                                <tr>
                                    <th scope="col">Pilih</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-maroon text-white rounded-3" id="konfirmasiBarter">
                        <i class="fas fa-check-circle me-1"></i>Konfirmasi Barter
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            .info-row {
                display: flex;
                justify-content: space-between;
                font-size: 14px;
                margin-bottom: 4px;
            }

            .info-label {
                width: 60px;
                font-weight: bold;
            }

            .info-value {
                flex: 1;
            }

            .btn-maroon {
                background-color: #a83232;
                border: none;
                transition: all 0.3s ease;
            }

            .btn-maroon:hover {
                background-color: #8f2a2a;
                transform: scale(1.05);
            }

            .text-maroon {
                color: #a83232;
            }

            .product-card {
                border-radius: 16px;
                overflow: hidden;
                background-color: #fffaf9;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .product-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 8px 20px rgba(168, 50, 50, 0.15);
            }

            /* 🔲 Pastikan gambar seragam */
            .image-container {
                width: 100%;
                height: 150px;
                overflow: hidden;
                display: flex;
                justify-content: center;
                align-items: center;
                background: #f9f9f9;
            }

            .image-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s ease;
            }

            .image-container img:hover {
                transform: scale(1.08);
            }
        </style>
    @endpush

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                // Auto-submit saat ganti filter
                $('#id_kecamatan, #id_kelurahan, #entries').change(function() {
                    $('#filterForm').submit();
                });

                // Clear search
                window.clearSearch = function() {
                    $('#search').val('');
                    $('#filterForm').submit();
                };

                // Load kelurahan via AJAX
                $('#id_kecamatan').change(function() {
                    const kecamatanId = $(this).val();
                    const $kelurahan = $('#id_kelurahan');

                    $kelurahan.html('<option value="">-- Pilih Kelurahan --</option>');

                    if (!kecamatanId) return;

                    $.ajax({
                        url: '{{ route('getKelurahan') }}',
                        method: 'GET',
                        data: {
                            id_kecamatan: kecamatanId
                        },
                        success: function(data) {
                            $.each(data, function(i, item) {
                                const selected = {{ $id_kelurahan ?? 'null' }} == item.id ?
                                    'selected' : '';
                                $kelurahan.append(
                                    `<option value="${item.id}" ${selected}>${item.nama_kelurahan}</option>`
                                );
                            });
                        }
                    });
                });
            });

            // Detail
            document.querySelectorAll('.btn-produk').forEach(btn => {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.dataset.produk);

                    // Debugging
                    console.log('Data produk:', data);

                    // Gunakan data.gambar jika ada, kalau tidak gunakan fallback
                    const gambarSrc = data.gambar && data.gambar.trim() !== '' ?
                        data.gambar :
                        getFallbackImage(data.nama_barang);

                    Swal.fire({
                        title: data.nama_barang || 'Produk Tidak Dikenal',
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
                                    <h5 class="fw-bold mb-2 text-maroon">Detail Produk</h5>
                                    <hr>
                                    <table style="width:100%; border-collapse:collapse;">
                                        <tbody>
                                            <tr>
                                                <td style="font-weight:600; color:#a83232; width:110px;">Harga</td>
                                                <td style="width:15px; text-align:center;">:</td>
                                                <td style="color:#333;">
                                                    ${Number(data.harga || 0).toLocaleString('id-ID')} / ${data.satuan_harga?.nama_satuan ?? '-'}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:600; color:#a83232;">Jumlah</td>
                                                <td style="text-align:center;">:</td>
                                                <td style="color:#333;">
                                                    ${data.jumlah || 0} ${data.satuan_jumlah?.nama_satuan ?? '-'}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:600; color:#a83232;">
                                                    ${data.type === 'supply' ? 'Supplier' : 'Peminta'}
                                                </td>
                                                <td style="text-align:center;">:</td>
                                                <td style="color:#333;">
                                                    ${data.type === 'supply' ? (data.nama_supplier ?? '-') : (data.nama_peminta ?? '-')}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:600; color:#a83232;">Alamat</td>
                                                <td style="text-align:center;">:</td>
                                                <td style="color:#333;">${data.alamat ?? '-'}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:600; color:#a83232;">Lokasi</td>
                                                <td style="text-align:center;">:</td>
                                                <td style="color:#333;">${data.kecamatanId ?? '-'}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonText: 'Chat Supplier',
                        cancelButtonText: 'Tutup',
                        confirmButtonColor: '#a83232',
                        cancelButtonColor: '#6c757d',
                        background: '#fff',
                        width: '500px',
                        customClass: {
                            popup: 'rounded-4 shadow-lg border border-light',
                            confirmButton: 'px-4 py-2',
                            cancelButton: 'px-4 py-2'
                        }
                    }).then(result => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Masukkan Email Anda',
                                input: 'email',
                                inputPlaceholder: 'contoh@email.com',
                                confirmButtonText: 'Masuk ke Chat',
                                confirmButtonColor: '#a83232',
                                showCancelButton: true,
                                cancelButtonText: 'Batal',
                                preConfirm: (email) => {
                                    if (!email) {
                                        Swal.showValidationMessage('Email wajib diisi!');
                                    }
                                    return email;
                                }
                            }).then(res => {
                                if (res.isConfirmed) {
                                    const email = res.value;

                                    Swal.fire({
                                        title: `Chat dengan ${data.supplier || data.nama_supplier || 'Supplier'}`,
                                        html: `
                                            <style>
                                                .chat-bubble {
                                                    max-width: 75%; border-radius: 10px; padding: 10px 14px 16px 12px;
                                                    margin-bottom: 8px; display: inline-block; animation: fadeIn 0.2s ease-in;
                                                    position: relative; line-height: 1.4;
                                                }
                                                .chat-bubble .time {
                                                    font-size: 9px; color: #333; position: absolute; bottom: 4px; right: 10px;
                                                }
                                                .chat-user { background: #f2f2f2; color: #333; text-align: right; align-self: flex-end; }
                                                .chat-supplier { background: #f2f2f2; color: #333; text-align: left; align-self: flex-start; }
                                                .chat-message { display: flex; flex-direction: column; margin-bottom: 10px; }
                                                #chat-box {
                                                    height: 250px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px;
                                                    padding: 10px; background: #fffefc; margin-bottom: 10px; display: flex; flex-direction: column;
                                                }
                                                @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
                                            </style>
                                            <div id="chat-box">
                                                <div class="text-muted text-center" style="font-size:12px; margin-bottom:10px;">
                                                    Anda bergabung sebagai <b>${email}</b>
                                                </div>
                                            </div>
                                            <div class="d-flex" style="gap:8px;">
                                                <input id="chat-input" type="text" placeholder="Ketik pesan..." 
                                                    style="flex:1; padding:8px 10px; border:1px solid #ccc; border-radius:6px;">
                                                <button id="chat-send" class="btn btn-maroon" 
                                                    style="background:#a83232; color:white; border:none; border-radius:6px; padding:8px 14px;">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        `,
                                        showConfirmButton: false,
                                        showCancelButton: true,
                                        cancelButtonText: 'Tutup Chat',
                                        width: '500px',
                                        background: '#fff',
                                        didOpen: () => {
                                            const chatBox = document.getElementById(
                                                'chat-box');
                                            const input = document.getElementById(
                                                'chat-input');
                                            const sendBtn = document.getElementById(
                                                'chat-send');

                                            const formatTime = () => new Date()
                                                .toLocaleTimeString('id-ID', {
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                    hour12: false
                                                });

                                            const sendMessage = () => {
                                                const msg = input.value.trim();
                                                if (!msg) return;

                                                const userMsg = document
                                                    .createElement('div');
                                                userMsg.className =
                                                    'chat-message';
                                                userMsg.innerHTML =
                                                    `<div class="chat-bubble chat-user">${msg}<span class="time">${formatTime()}</span></div>`;
                                                chatBox.appendChild(userMsg);
                                                input.value = '';
                                                chatBox.scrollTop = chatBox
                                                    .scrollHeight;

                                                setTimeout(() => {
                                                    const reply =
                                                        document
                                                        .createElement(
                                                            'div');
                                                    reply.className =
                                                        'chat-message';
                                                    reply.innerHTML =
                                                        `<div class="chat-bubble chat-supplier">Terima kasih ${email.split('@')[0]}, produk ${data.nama_barang || 'ini'} masih tersedia!<span class="time">${formatTime()}</span></div>`;
                                                    chatBox.appendChild(
                                                        reply);
                                                    chatBox.scrollTop =
                                                        chatBox
                                                        .scrollHeight;
                                                }, 1000);
                                            };

                                            sendBtn.addEventListener('click',
                                                sendMessage);
                                            input.addEventListener('keypress',
                                                e => {
                                                    if (e.key === 'Enter')
                                                        sendMessage();
                                                });
                                        }
                                    });
                                }
                            });
                        }
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const barterButtons = document.querySelectorAll('.btn-barter');
                const modalElement = document.getElementById('barterModal');
                const modal = new bootstrap.Modal(modalElement);
                const defaultImg = "{{ asset('aset/img/produk/keranjang.png') }}";

                barterButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const produk = JSON.parse(this.dataset.produk);

                        // Isi data produk ke modal
                        const imgElement = document.getElementById('barterImg');
                        imgElement.src = produk.gambar ?
                            (produk.gambar.startsWith('http') ? produk.gambar :
                                `/storage/${produk.gambar}`) :
                            defaultImg;

                        document.getElementById('barterNama').textContent = produk.nama_barang;
                        document.getElementById('barterJumlah').textContent =
                            `${produk.jumlah} ${produk.satuan_jumlah?.nama_satuan ?? ''}`;
                        document.getElementById('barterHarga').textContent =
                            `Rp ${Number(produk.harga).toLocaleString('id-ID')}`;
                        // document.getElementById('barterKecamatan').textContent =
                        //     `${produk.kecamatan?.nama_kecamatan ?? ''}`;
                        // document.getElementById('barterKelurahan').textContent =
                        //     `${produk.kelurahan?.nama_kelurahan ?? ''}`;

                        // Tentukan tipe barter
                        const type = produk.supply_id ? 'suply' : 'demand';
                        const tbody = document.querySelector('#barangPenukarTable tbody');
                        tbody.innerHTML =
                            `<tr><td colspan="5" class="text-center text-muted">Memuat data...</td></tr>`;

                        // Ambil data barang penukar dari server
                        fetch(`/barter/options?type=${type}`)
                            .then(res => res.json())
                            .then(barangList => {
                                const tbody = document.querySelector('#barangPenukarTable tbody');
                                tbody.innerHTML = '';

                                barangList.forEach(item => {
                                    const gambarSrc = item.gambar ?
                                        `/storage/ecomerce/${item.gambar}` :
                                        '/aset/img/produk/jagung.jpg';

                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                        <td><input type="radio" name="barangPenukar" value="${item.id}"></td>
                                        <td>${item.nama_barang}</td>
                                        <td>${item.jumlah}</td>
                                        <td>Rp ${Number(item.harga).toLocaleString('id-ID')}</td>
                                        <td>${item.nama_kecamatan ?? '-'} - ${item.nama_kelurahan ?? '-'}</td>
                                        <td>${item.pemilik}</td>
                                    `;
                                    tbody.appendChild(row);
                                });
                            })
                            .catch(err => {
                                console.error('Gagal memuat data barter:', err);
                                tbody.innerHTML = `<tr>
                                    <td colspan="5" class="text-center text-danger">
                                        Terjadi kesalahan saat memuat data barang Anda.
                                    </td>
                                </tr>`;
                            });

                        // Simpan produk di tombol konfirmasi
                        const btnKonfirmasi = document.getElementById('konfirmasiBarter');
                        btnKonfirmasi.dataset.produk = JSON.stringify(produk);

                        modal.show();
                    });
                });

                // Saat klik konfirmasi barter
                document.getElementById('konfirmasiBarter').addEventListener('click', function() {
                    const produk = JSON.parse(this.dataset.produk);
                    const selectedRadio = document.querySelector(
                        'input[name="barangPenukar"]:checked'); // sesuaikan name input

                    if (!selectedRadio) {
                        // Jika belum memilih barang, tampilkan SweetAlert2
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'Silakan pilih barang penukar Anda terlebih dahulu!',
                            confirmButtonColor: '#d33',
                        });
                        return;
                    }

                    const barangPenukarId = selectedRadio.value;

                    // Contoh: tampilkan under construction saat sudah memilih barang
                    Swal.fire({
                        icon: 'info',
                        title: 'Under Construction',
                        text: `Permintaan barter untuk "${produk.nama_barang}" sedang dalam tahap pengembangan.`,
                        confirmButtonColor: '#3085d6',
                    });

                    // Jika ingin modal ditutup setelah klik konfirmasi
                    const modalElement = document.getElementById('barterModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();

                    // Log data (sementara)
                    console.log('Barter dikirim (simulasi):', {
                        produk_dipilih: produk,
                        barang_penukar_id: barangPenukarId,
                    });
                });
            });
        </script>
    @endpush

</x-layout.terminal>
