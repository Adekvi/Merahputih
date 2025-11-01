<x-layout.terminal title="Demand Barang">
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
            <form id="formSupply" action="{{ url('e-commerce/market/demand/store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="id_kecamatan" class="form-label fw-semibold">Kecamatan</label>
                                <select name="id_kecamatan" id="id_kecamatan" class="form-select">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($kecamatan as $matan)
                                        <option value="{{ $matan->id }}">{{ $matan->nama_kecamatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="id_kelurahan" class="form-label fw-semibold">Kelurahan</label>
                                <select name="id_kelurahan" id="id_kelurahan" class="form-select">
                                    <option value="">-- Pilih Kelurahan --</option>
                                </select>
                            </div>

                            <!-- Supplier -->
                            <div class="col-md-6">
                                <label for="nama_demand" class="form-label fw-semibold">Nama</label>
                                <input type="text" name="nama_demand" id="nama_demand" class="form-control"
                                    placeholder="Nama Supplier">
                            </div>

                            <!-- Nama Barang -->
                            <div class="col-md-6">
                                <label for="nama_barang" class="form-label fw-semibold">Nama Barang</label>
                                <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                                    placeholder="Nama Barang">
                            </div>

                            <!-- Kategori -->
                            <div class="col-md-6">
                                <label for="kategori" class="form-label fw-semibold">Kategori</label>
                                <input type="text" name="kategori" id="kategori" class="form-control"
                                    placeholder="Peternakan, Perkebunan, Pertanian, dll">
                            </div>

                            <!-- Tanggal -->
                            <div class="col-md-6">
                                <label for="tgl_dem" class="form-label fw-semibold">Tanggal Permintaan</label>
                                <input type="date" name="tgl_dem" id="tgl_dem" class="form-control">
                            </div>

                            <!-- Baris untuk jumlah, satuan jumlah, harga, dan satuan harga -->
                            <div class="row g-3 align-items-end">
                                <!-- Jumlah Barang -->
                                <div class="col-md-3">
                                    <label for="jumlah" class="form-label fw-semibold">Jumlah Barang</label>
                                    <input type="number" name="jumlah" id="jumlah" class="form-control same-width"
                                        placeholder="0" min="0">
                                </div>

                                <!-- Satuan Jumlah -->
                                <div class="col-md-3">
                                    <label for="satuan_jumlah_id" class="form-label fw-semibold">Satuan</label>
                                    <select name="satuan_jumlah_id" id="satuan_jumlah_id"
                                        class="form-select same-width">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($satuan as $sat)
                                            <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Harga -->
                                <div class="col-md-3">
                                    <label for="harga" class="form-label fw-semibold">Harga</label>
                                    <div class="input-group same-width">
                                        <span class="input-group-text bg-light"><b>Rp</b></span>
                                        <input type="number" value="{{ old('harga') }}" name="harga" id="harga"
                                            class="form-control" placeholder="0" min="0">
                                    </div>
                                    @error('harga')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Satuan Harga -->
                                <div class="col-md-3">
                                    <label for="satuan_harga_id" class="form-label fw-semibold">Satuan Harga</label>
                                    <select name="satuan_harga_id" id="satuan_harga_id" class="form-select same-width">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($satuan as $uan)
                                            <option value="{{ $uan->id }}">{{ $uan->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- No HP -->
                            <div class="col-md-6">
                                <label for="no_hp" class="form-label fw-semibold">No. HP</label>
                                <input type="text" name="no_hp" id="no_hp" class="form-control"
                                    placeholder="08123456789">
                            </div>

                            <!-- Foto Barang -->
                            <div class="col-md-6">
                                <label for="gambar" class="form-label fw-semibold">Foto Barang</label>

                                <div class="d-flex flex-column flex-sm-row align-items-center gap-3">
                                    <!-- Preview -->
                                    <div class="border rounded shadow-sm p-2 bg-light">
                                        <img src="{{ asset('admin/img/produk/keranjang.png') }}" alt="Preview Foto"
                                            id="uploadedAvatar" class="rounded"
                                            style="width: 120px; height: 120px; object-fit: cover;" />
                                    </div>

                                    <!-- Upload Controls -->
                                    <div class="d-flex flex-column">
                                        <label for="gambar" class="btn btn-primary mb-2">
                                            <span>Upload Foto</span>
                                            <input type="file" name="gambar" id="gambar" class="d-none"
                                                accept="image/png, image/jpeg, image/jpg"
                                                onchange="previewFoto(event)">
                                        </label>

                                        <button type="button" class="btn btn-outline-secondary mb-2"
                                            onclick="resetFoto()">
                                            Reset
                                        </button>

                                        <small class="text-muted">Format: JPG, JPEG, PNG. Maks. 2 MB</small>
                                        @error('gambar')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-md-6">
                                <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"
                                    placeholder="Tuliskan deskripsi barang..."></textarea>
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-6">
                                <label for="alamat" class="form-label fw-semibold">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control" rows="3"
                                    placeholder="Alamat lengkap supplier..."></textarea>
                            </div>
                        </div>
                        <div class="mt-3 justify-content-end">
                            <a href="{{ url('e-commerce/market/suply') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-xmark me-1"></i> Tutup
                            </a>
                            <button type="submit" form="formSupply" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('css')
    @endpush

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

            function previewFoto(event) {
                const input = event.target;
                const reader = new FileReader();

                reader.onload = function(e) {
                    const avatar = document.getElementById('uploadedAvatar');
                    avatar.src = e.target.result;
                };

                if (input.files && input.files[0]) {
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function resetFoto() {
                const input = document.getElementById('gambar');
                const avatar = document.getElementById('uploadedAvatar');

                input.value = '';
                avatar.src = "{{ asset('admin/img/produk/keranjang.png') }}"; // ✅ path sudah diperbaiki
            }

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
            });
        </script>
    @endpush
</x-layout.terminal>
