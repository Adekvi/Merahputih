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
            <div class="judul">
                <h4 class="text-center fw-bold">Produk</h4>
                <hr>
                <form action="" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end">
                    <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">

                        <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
                            <!-- Dropdown "Tampilkan" -->
                            <div class="d-flex align-items-center">
                                <label for="entries" class="form-label fw-semibold small me-2 mb-0">Tampilkan</label>
                                <select name="entries" id="entries" class="form-select rounded-3"
                                    style="width: 90px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>

                            <!-- Input Search -->
                            <div class="input-group" style="max-width: 350px;">
                                <input type="text" id="searchInput" class="form-control rounded-start-3"
                                    placeholder="Cari nama barang..." onkeyup="updateData()">
                                <button class="btn btn-maroon rounded-end-3 text-white"
                                    onclick="clearSearch()">Clear</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            @php
                $produk = [
                    [
                        'nama' => 'Beras Premium',
                        'jumlah' => '1.200 kg',
                        'harga' => 'Rp 13.000/kg',
                        'asal' => 'Desa Kedumulyo',
                        'stok' => 'Tersedia',
                        'supplier' => 'UD Maju Makmur',
                        'alamat' => 'Jl. Raya Kedumulyo No. 45, Sukolilo',
                        'lokasi' => 'Kecamatan Sukolilo, Kabupaten Pati',
                        'kontak' => '6281234567890',
                        'gambar' => '/aset/img/produk/beras.jpg',
                    ],
                    [
                        'nama' => 'Jagung Pipil',
                        'jumlah' => '800 kg',
                        'harga' => 'Rp 7.500/kg',
                        'asal' => 'Desa Tompe Gunung',
                        'stok' => 'Tersedia',
                        'supplier' => 'CV Tani Jaya',
                        'alamat' => 'Jl. Merdeka No. 88, Tompe Gunung',
                        'lokasi' => 'Kecamatan Sukolilo, Kabupaten Pati',
                        'kontak' => '6282287654321',
                        'gambar' => '/aset/img/produk/jagung-pipil.webp',
                    ],
                    [
                        'nama' => 'Gula Merah',
                        'jumlah' => '500 kg',
                        'harga' => 'Rp 15.000/kg',
                        'asal' => 'Desa Kedungwinong',
                        'stok' => 'Tersedia',
                        'supplier' => 'KWT Manis Sejahtera',
                        'alamat' => 'Jl. Desa Kedungwinong No. 7',
                        'lokasi' => 'Kecamatan Sukolilo, Kabupaten Pati',
                        'kontak' => '6285698877665',
                        'gambar' => '/aset/img/produk/gula-merah.jpg',
                    ],
                ];
            @endphp

            <div class="row g-4">
                @foreach ($produk as $item)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100">
                            <div class="image-container">
                                <img src="{{ $item['gambar'] }}" class="card-img-top" alt="{{ $item['nama'] }}">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-maroon">{{ $item['nama'] }}</h5>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-map-marker-alt me-2 text-maroon"></i>{{ $item['asal'] }}
                                </p>
                                <p class="card-text mb-1"><strong>Jumlah:</strong> {{ $item['jumlah'] }}</p>
                                <p class="card-text"><strong>Harga:</strong> {{ $item['harga'] }}</p>
                                <div class="mt-auto">
                                    <button class="btn btn-maroon text-white w-100 shadow-sm btn-produk"
                                        data-produk='@json($item)'>
                                        <i class="fas fa-shopping-cart me-2"></i>Pesan Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <div class="d-flex justify-content-center mt-3 mb-3">
        </div>
    </div>

    @push('css')
        <style>
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

            document.querySelectorAll('.btn-produk').forEach(btn => {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.dataset.produk);

                    Swal.fire({
                        title: data.nama,
                        html: `
                            <div class="text-start">
                                <div style="width:100%; height:250px; overflow:hidden; border-radius:12px; margin-bottom:15px;">
                                    <img src="${data.gambar}" alt="${data.nama}" 
                                        style="width:100%; height:100%; object-fit:cover; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                                </div>

                                <div style="background:#fff8f8; border-radius:12px; padding:14px 18px; box-shadow:inset 0 0 6px rgba(168,50,50,0.1); font-size:14px;">
                                    <h5 class="fw-bold mb-2 text-maroon">Detail Produk</h4>
                                    <hr>
                                    <table style="width:100%; border-collapse:collapse;">
                                        <tbody>
                                            <tr><td style="font-weight:600; color:#a83232; width:110px;">Harga</td><td style="width:15px; text-align:center;">:</td><td style="color:#333;">${data.harga}</td></tr>
                                            <tr><td style="font-weight:600; color:#a83232;">Stok</td><td style="text-align:center;">:</td><td style="color:#333;">${data.stok}</td></tr>
                                            <tr><td style="font-weight:600; color:#a83232;">Asal</td><td style="text-align:center;">:</td><td style="color:#333;">${data.asal}</td></tr>
                                            <tr><td style="font-weight:600; color:#a83232;">Supplier</td><td style="text-align:center;">:</td><td style="color:#333;">${data.supplier}</td></tr>
                                            <tr><td style="font-weight:600; color:#a83232;">Alamat</td><td style="text-align:center;">:</td><td style="color:#333;">${data.alamat}</td></tr>
                                            <tr><td style="font-weight:600; color:#a83232;">Lokasi</td><td style="text-align:center;">:</td><td style="color:#333;">${data.lokasi}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Chat Supplier',
                        cancelButtonText: 'Tutup',
                        confirmButtonColor: '#a83232',
                        cancelButtonColor: '#6c757d',
                        background: '#fff',
                        color: '#333',
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
                                    const userId = btoa(email);

                                    Swal.fire({
                                        title: `Chat dengan ${data.supplier}`,
                                        html: `
                                            <style>
                                                .chat-bubble {
                                                    max-width: 75%;
                                                    border-radius: 10px;
                                                    padding: 10px 14px 16px 12px;
                                                    margin-bottom: 8px;
                                                    display: inline-block;
                                                    animation: fadeIn 0.2s ease-in;
                                                    position: relative;
                                                    line-height: 1.4;
                                                }
                                                .chat-bubble .time {
                                                    font-size: 9px;
                                                    color: #333;
                                                    position: absolute;
                                                    bottom: 4px; /* jarak dari bawah */
                                                    right: 10px; /* jarak dari kanan */
                                                }
                                                .chat-user {
                                                    background: #f2f2f2;
                                                    color: #333;
                                                    align-self: flex-end;
                                                    text-align: right;
                                                }
                                                .chat-supplier {
                                                    background: #f2f2f2;
                                                    color: #333;
                                                    align-self: flex-start;
                                                    text-align: left;
                                                }
                                                .chat-message {
                                                    display: flex;
                                                    flex-direction: column;
                                                    margin-bottom: 10px;
                                                }
                                                #chat-box {
                                                    height: 250px;
                                                    overflow-y: auto;
                                                    border: 1px solid #ddd;
                                                    border-radius: 8px;
                                                    padding: 10px;
                                                    background: #fffefc;
                                                    margin-bottom: 10px;
                                                    display: flex;
                                                    flex-direction: column;
                                                }
                                                @keyframes fadeIn {
                                                    from { opacity: 0; transform: translateY(4px); }
                                                    to { opacity: 1; transform: translateY(0); }
                                                }
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

                                            const formatTime = () => {
                                                const now = new Date();
                                                return now.toLocaleTimeString(
                                                    'id-ID', {
                                                        hour: '2-digit',
                                                        minute: '2-digit',
                                                        hour12: false
                                                    });
                                            };

                                            sendBtn.addEventListener('click',
                                                () => {
                                                    const msg = input.value
                                                        .trim();
                                                    if (!msg) return;

                                                    const userMsg = document
                                                        .createElement('div');
                                                    userMsg.className =
                                                        'chat-message';
                                                    userMsg.innerHTML = `
                                                        <div class="chat-bubble chat-user">
                                                            ${msg}
                                                            <span class="time">${formatTime()}</span>
                                                        </div>
                                                    `;
                                                    chatBox.appendChild(
                                                        userMsg);
                                                    input.value = '';
                                                    chatBox.scrollTop = chatBox
                                                        .scrollHeight;

                                                    setTimeout(() => {
                                                        const reply =
                                                            document
                                                            .createElement(
                                                                'div');
                                                        reply
                                                            .className =
                                                            'chat-message';
                                                        reply
                                                            .innerHTML = `
                                                            <div class="chat-bubble chat-supplier">
                                                                Terima kasih ${email.split('@')[0]}, produk ${data.nama} masih tersedia!
                                                                <span class="time">${formatTime()}</span>
                                                            </div>
                                                        `;
                                                        chatBox
                                                            .appendChild(
                                                                reply);
                                                        chatBox
                                                            .scrollTop =
                                                            chatBox
                                                            .scrollHeight;
                                                    }, 1200);
                                                });

                                            input.addEventListener('keypress', (
                                                e) => {
                                                if (e.key === 'Enter')
                                                    sendBtn.click();
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush

</x-layout.terminal>
