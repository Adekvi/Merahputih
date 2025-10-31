<div class="modal fade" id="supply" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="supplyLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header text-dark">
                <h5 class="modal-title" id="supplyLabel">
                    <i class="fa-solid fa-truck-field me-2"></i> Supply Barang
                </h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSupply" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_kecamatan" class="form-label fw-semibold">Kecamatan</label>
                            <select name="id_kecamatan" id="id_kecamatan" class="form-select" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id_kecamatan }}">{{ $kecamatan->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_kelurahan" class="form-label fw-semibold">Kelurahan</label>
                            <select name="id_kelurahan" id="id_kelurahan" class="form-select" required>
                                <option value="">-- Pilih Kelurahan --</option>
                            </select>
                        </div>

                        <!-- Supplier -->
                        <div class="col-md-6">
                            <label for="nama_supplier" class="form-label fw-semibold">Supplier</label>
                            <input type="text" name="nama_supplier" id="nama_supplier" class="form-control"
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
                            <label for="tgl_sup" class="form-label fw-semibold">Tanggal Supply</label>
                            <input type="date" name="tgl_sup" id="tgl_sup" class="form-control">
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
                                <select name="satuan_jumlah_id" id="satuan_jumlah_id" class="form-select same-width">
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach ($satuan as $sat)
                                        <option value="{{ $sat->id_satuan }}">{{ $sat->nama_satuan }}</option>
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
                                        <option value="{{ $uan->id_satuan }}">{{ $uan->nama_satuan }}</option>
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
                            <input type="file" name="gambar" id="gambar" class="form-control">
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
                </form>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-1"></i> Tutup
                </button>
                <button type="submit" form="formSupply" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('#id_kecamatan').on('change', function() {
                const kecamatanId = $(this).val();
                const $kelurahanSelect = $('#id_kelurahan');

                // Kosongkan dropdown kelurahan
                $kelurahanSelect.empty().append('<option value="">-- Pilih Kelurahan --</option>').prop(
                    'disabled', true);

                if (!kecamatanId) {
                    return;
                }

                $.ajax({
                    url: "{{ url('get-kelurahan') }}", // gunakan url() agar tidak error
                    type: 'GET',
                    data: {
                        id_kecamatan: kecamatanId
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            response.forEach(function(kel) {
                                $kelurahanSelect.append(
                                    `<option value="${kel.id}">${kel.nama_kelurahan}</option>`
                                );
                            });
                            $kelurahanSelect.prop('disabled', false);
                        } else {
                            $kelurahanSelect.append(
                                '<option value="">Tidak ada kelurahan</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Gagal mengambil data kelurahan. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
@endpush
