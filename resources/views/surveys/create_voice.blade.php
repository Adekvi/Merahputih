<x-layout.terminal title="Voice">
    @push('css')
        <style>
            :root {
                --action-btn-w: 230px;
                --action-btn-h: 82px;
                --action-radius: 14px;
            }

            #voiceControls {
                display: flex;
                align-items: flex-end;
                /* pastikan tombol rata ke bawah */
                justify-content: center;
                gap: 1rem;
                flex-wrap: wrap;
                /* biarkan wrap agar responsif */
                margin-top: 1.5rem;
            }

            /* Tombol umum */
            #startVoiceBtn,
            #pauseVoiceBtn,
            #btnOpenReview {
                width: var(--action-btn-w) !important;
                height: var(--action-btn-h) !important;
                min-width: var(--action-btn-w) !important;
                min-height: var(--action-btn-h) !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: .6rem !important;
                padding: 0 .9rem !important;
                border: none !important;
                border-radius: var(--action-radius) !important;
                font-weight: 600 !important;
                font-size: .95rem !important;
                line-height: 1.1 !important;
                color: #fff !important;
                cursor: pointer !important;
                transition: transform .12s ease, box-shadow .12s ease, opacity .12s ease !important;
                box-shadow: 0 8px 22px rgba(2, 6, 23, 0.08) !important;
            }

            #startVoiceBtn {
                background: linear-gradient(90deg, #7e22ce, #a855f7) !important;
            }

            #pauseVoiceBtn {
                background: linear-gradient(90deg, #f59e0b, #fbbf24) !important;
                color: #111827 !important;
            }

            #btnOpenReview {
                background: linear-gradient(90deg, #2563eb, #60a5fa) !important;
            }

            #startVoiceBtn:hover,
            #pauseVoiceBtn:hover,
            #btnOpenReview:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 16px 36px rgba(2, 6, 23, 0.15) !important;
                opacity: .95 !important;
            }

            #startVoiceBtn[disabled],
            #pauseVoiceBtn[disabled],
            #btnOpenReview[disabled] {
                opacity: 0.6 !important;
                transform: none !important;
                box-shadow: none !important;
                cursor: not-allowed !important;
            }

            /* Khusus tombol Mulai (ikon di atas teks) */
            #startVoiceBtn {
                flex-direction: column !important;
                text-align: center !important;
                padding: 6px 0 !important;
            }

            #startVoiceBtn span {
                margin-top: 6px !important;
                font-size: .94rem !important;
            }

            /* Tombol Jeda & Review (ikon di samping teks) */
            #pauseVoiceBtn,
            #btnOpenReview {
                flex-direction: row !important;
                text-align: left !important;
                padding: 0 16px !important;
            }

            /* ==== Ikon ukuran dan posisi ==== */
            #pauseVoiceBtn svg,
            #pauseVoiceBtn i,
            #btnOpenReview svg,
            #btnOpenReview i {
                width: 28px !important;
                height: 28px !important;
                flex-shrink: 0 !important;
            }

            #pauseVoiceBtn span,
            #btnOpenReview span {
                line-height: 1 !important;
                display: inline-block !important;
            }

            /* Responsif — di HP tombol menumpuk vertikal */
            @media (max-width: 640px) {
                :root {
                    --action-btn-w: 100%;
                }

                #voiceControls {
                    flex-direction: column;
                    align-items: stretch;
                }

                #startVoiceBtn,
                #pauseVoiceBtn,
                #btnOpenReview {
                    width: 100% !important;
                    justify-content: center !important;
                }

                /* Ikon lebih kecil di HP */
                #pauseVoiceBtn svg,
                #pauseVoiceBtn i,
                #btnOpenReview svg,
                #btnOpenReview i {
                    width: 22px !important;
                    height: 22px !important;
                }
            }

            /* ============================
                                                                                                   MODAL — Lebar & Layout Dua Kolom
                                                                                                   ============================ */
            #reviewModal {
                position: fixed !important;
                inset: 0 !important;
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                background: rgba(8, 15, 34, 0.55);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
                padding: 1.5rem;
            }

            #reviewModal:not(.hidden) {
                display: flex !important;
            }

            #reviewModal .max-w-5xl {
                width: 100%;
                max-width: 1000px;
                border-radius: 16px;
                background: #fff;
                padding: 1.75rem 2rem;
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);
                position: relative;
                color: #0f172a;
                border: 1px solid rgba(8, 15, 34, 0.06);
                max-height: 90vh;
                overflow-y: auto;
            }

            #reviewModal h2 {
                font-size: 1.3rem;
                font-weight: 700;
                color: #111827;
                margin-bottom: 1rem;
            }

            /* Pastikan teks select & option di modal tampil hitam */
            #reviewModal .max-w-5xl select,
            #reviewModal .max-w-5xl select option {
                color: #0f172a !important;
                /* teks hitam tua */
                background-color: #ffffff !important;
                /* latar putih */
            }

            /* Override kalau ada parent dengan class dark (Tailwind/etc) */
            #reviewModal .max-w-5xl .dark select,
            #reviewModal .max-w-5xl .dark select option,
            #reviewModal .max-w-5xl.dark select,
            #reviewModal .max-w-5xl.dark select option {
                color: #0f172a !important;
                background-color: #ffffff !important;
            }

            /* Placeholder option (value="") dibuat abu-abu agar beda */
            #reviewModal .max-w-5xl select option[value=""] {
                color: #6b7280 !important;
            }

            #closeReview {
                position: absolute;
                top: 16px;
                right: 16px;
                background: transparent;
                border: none;
                color: #374151;
                font-size: 1.4rem;
                cursor: pointer;
            }

            #closeReview:hover {
                color: #111;
            }

            #reviewModal .grid-2col {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1rem 1.2rem;
            }

            #reviewModal label {
                display: block;
                font-size: .9rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: .35rem;
            }

            #reviewModal input[type="text"],
            #reviewModal input[type="number"],
            #reviewModal select,
            #reviewModal textarea {
                width: 100%;
                padding: .6rem .75rem;
                border: 1px solid rgba(15, 23, 42, 0.08);
                border-radius: .6rem;
                font-size: .95rem;
                background: #fff;
                color: #0f172a;
                transition: border-color .12s ease, box-shadow .12s ease;
            }

            #reviewModal input:focus,
            #reviewModal select:focus,
            #reviewModal textarea:focus {
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
                outline: none;
            }

            #reviewModal h3 {
                font-size: 1.1rem;
                font-weight: 700;
                margin-top: 1.2rem;
                margin-bottom: .8rem;
                color: #0b2545;
                border-bottom: 1px solid rgba(15, 23, 42, 0.06);
                padding-bottom: .3rem;
            }

            #btnAddParcelModal {
                background: linear-gradient(90deg, #2563eb, #60a5fa);
                color: #fff;
                font-weight: 600;
                border: none;
                padding: .55rem .9rem;
                border-radius: .6rem;
                cursor: pointer;
                box-shadow: 0 8px 18px rgba(37, 99, 235, 0.12);
            }

            #btnAddParcelModal:hover {
                transform: translateY(-2px);
            }

            #modalParcelsContainer {
                display: grid;
                gap: 1rem;
                margin-top: 1rem;
            }

            .parcel-card {
                background: linear-gradient(180deg, #ffffff, #f8fafc);
                border-radius: .75rem;
                border: 1px solid rgba(15, 23, 42, 0.08);
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
                padding: 1rem;
            }

            .parcel-card .grid-2col-inner {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: .8rem 1.2rem;
            }

            .btnAddCropModal,
            .btnAddLivestockModal {
                background: linear-gradient(90deg, #7e22ce, #a855f7);
                color: #fff;
                border: none;
                padding: .4rem .7rem;
                font-size: .85rem;
                border-radius: .45rem;
                cursor: pointer;
                transition: transform .12s ease;
            }

            .btnAddCropModal:hover,
            .btnAddLivestockModal:hover {
                transform: translateY(-2px);
            }

            .btnRemoveParcelModal {
                background: transparent;
                color: #ef4444;
                border: none;
                cursor: pointer;
                font-weight: 600;
            }

            #activityLog {
                margin-top: 1.75rem;
                /* 28px */
            }

            #reviewModal .modal-footer {
                display: flex;
                justify-content: flex-end;
                gap: .6rem;
                margin-top: 1.5rem;
            }

            #revCancelBtn {
                background: #fff;
                border: 1px solid rgba(15, 23, 42, 0.1);
                color: #111;
                font-weight: 600;
                padding: .55rem 1rem;
                border-radius: .5rem;
            }

            #revSubmitBtn {
                background: linear-gradient(90deg, #16a34a, #22c55e);
                border: none;
                color: #fff;
                font-weight: 600;
                padding: .55rem 1.2rem;
                border-radius: .5rem;
                box-shadow: 0 8px 18px rgba(16, 185, 129, 0.18);
                cursor: pointer;
            }

            #revSubmitBtn:hover {
                transform: translateY(-2px);
            }

            /* Grid data dasar */
            #basicInfoGrid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
                align-items: start;
            }

            #basicInfoGrid label {
                font-weight: 600;
                font-size: .9rem;
                color: #374151;
                margin-bottom: .3rem;
                display: block;
            }

            #basicInfoGrid input {
                width: 100%;
                padding: .55rem .7rem;
                border: 1px solid rgba(15, 23, 42, 0.08);
                border-radius: .6rem;
                background: #fff;
                font-size: .95rem;
                color: #0f172a;
                transition: border-color .12s ease, box-shadow .12s ease;
            }

            #basicInfoGrid input:focus {
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
                outline: none;
            }

            /* Responsif untuk modal */
            @media (max-width: 768px) {
                #basicInfoGrid {
                    grid-template-columns: 1fr;
                }

                #reviewModal .max-w-5xl {
                    max-width: 95%;
                    padding: 1.2rem;
                }

                #reviewModal .grid-2col,
                .parcel-card .grid-2col-inner {
                    grid-template-columns: 1fr;
                }
            }

            /* ============================
                                                                                                   SUCCESS BOX (insertSavedBox)
                                                                                                   ============================ */
            .saved-box svg {
                width: 48px;
                height: 48px;
            }

            .saved-box .text-xs {
                font-size: 0.8rem;
            }

            @media (max-width: 640px) {
                .saved-box svg {
                    width: 28px;
                    height: 28px;
                }

                .saved-box {
                    padding: 0.75rem !important;
                }

                .saved-box .font-semibold {
                    font-size: 0.85rem;
                }

                .saved-box .text-xs {
                    font-size: 0.7rem;
                }

                #quickPreview {
                    font-family: inherit;
                }

                #quickPreview .grid {
                    align-items: start;
                }

                #quickPreview .font-semibold {
                    min-width: 150px;
                }
            }
        </style>
    @endpush


    </style>
    <div class="page-inner ecommerce-page">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-2 text-maroon">Voice</h3>
                <p class="text-muted mb-0">Percakapan Suara — Pendataan Potensi</p>
            </div>
            <div class="date-time mt-3 mt-md-0 text-md-end">
                <div id="tanggal" class="fw-semibold text-muted"></div>
                <div id="jam" class="fw-semibold text-muted"></div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="voice">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h1 class="text-start">Percakapan Suara — Metadata & Data Sensus
                                <span class="ml-3 badge-ver">v1.2</span>
                            </h1>
                        </div>
                        <p class="text-sm md:text-base text-gray-600 dark:text-gray-300 mb-4">Sistem akan menanyakan
                            metadata (Kecamatan, Desa, Jumlah Penduduk dan Potensi) lewat suara. Setelah itu kamu dapat
                            meninjau
                            &
                            mengedit seluruh data sebelum menyimpan.</p>

                        <div id="voiceControls" class="flex flex-wrap items-end gap-3 mb-4 md:mt-6">
                            <button id="startVoiceBtn"
                                class="btn-purple px-4 py-3 rounded-lg shadow hover:opacity-95 focus:outline-none flex items-center gap-2 btn-text-wrap">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 1v11" />
                                </svg>
                                <span class="font-medium whitespace-normal">Mulai Isi Metadata Lewat Suara</span>
                            </button>

                            <button id="pauseVoiceBtn"
                                class="btn-yellow px-4 py-3 rounded-lg shadow hover:opacity-95 focus:outline-none flex items-center gap-2 btn-text-wrap"
                                disabled>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 9v6M14 9v6" />
                                </svg>
                                <span class="font-medium whitespace-normal">Jeda</span>
                            </button>

                            <a id="btnOpenReview" href="#"
                                class="btn-blue px-4 py-3 rounded-lg shadow hover:opacity-95 focus:outline-none flex items-center gap-2 btn-text-wrap">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 20l9-5-9-5-9 5 9 5z" />
                                </svg>
                                <span class="font-medium whitespace-normal">Tinjau & Edit</span>
                            </a>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                            <div class="md:col-span-2">
                                <div class="text-sm text-gray-700 dark:text-gray-200 mb-2">Status: <span
                                        id="statusLabel"
                                        class="font-semibold text-green-600 dark:text-green-400">siap</span></div>
                                <div class="status-box dark:status-box" id="statusBox">
                                    <div id="statusText"
                                        class="text-gray-700 dark:text-gray-100 break-words whitespace-normal">Siap.
                                        Tekan <strong>Mulai</strong> untuk memulai sesi voice.</div>
                                </div>

                                <div class="mt-4">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Ringkasan
                                        (preview cepat)</h3>

                                    <div class="p-3 border rounded bg-gray-50 dark:bg-gray-900 text-sm text-gray-700 dark:text-gray-200"
                                        id="quickPreview">
                                        <div class="grid grid-cols-[160px_auto] gap-x-2 gap-y-1">
                                            <div class="font-semibold">Kecamatan:</div>
                                            <div id="pv_kecamatan">-</div>

                                            <div class="font-semibold">Kelurahan:</div>
                                            <div id="pv_desa">-</div>

                                            <div class="font-semibold">Jumlah Penduduk:</div>
                                            <div id="pv_jumlah_penduduk">-</div>

                                            <div class="font-semibold">Potensi Lahan:</div>
                                            <div id="pv_parcels" class="space-y-1">
                                                (belum ada)
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>

                            <div class="mt-14 md:mt-16">
                                <div class="text-sm text-gray-700 dark:text-gray-200 mb-2">Aktivitas</div>
                                <div class="log-box" id="activityLog" aria-live="polite">
                                    <div class="text-gray-500 dark:text-gray-400">Siap. Tekan Mulai untuk memulai sesi
                                        voice.</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Review & Edit --}}
        <div id="reviewModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>

            <div class="absolute inset-0 flex items-start justify-center p-4 overflow-auto modal-scroll">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto p-6 mt-12 modal-scroll"
                    style="-webkit-overflow-scrolling: touch;">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold dark:text-gray-100">Tinjau & Edit Data Sensus</h2>
                        <button id="closeReview" class="text-gray-600 dark:text-gray-300">✕</button>
                    </div>

                    <form id="modalSubmitForm" action="{{ route('surveys.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="parcels_json" name="parcels_json" value="[]" />
                        <input type="hidden" name="_from_voice" value="1" />

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4" id="basicInfoGrid">
                            <div>
                                <label>Kecamatan</label>
                                <select id="rev_kecamatan_id" name="kecamatan_id"
                                    class="mt-1 block w-full border rounded p-2" required>
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($kecamatans as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label>Desa / Kelurahan</label>
                                <select id="rev_kelurahan_id" name="kelurahan_id"
                                    class="mt-1 block w-full border rounded p-2" required>
                                    <option value="">-- Pilih Desa --</option>
                                    {{-- options diisi via JS saat kecamatan dipilih --}}
                                </select>
                            </div>

                            <div>
                                <label>Jumlah Penduduk</label>
                                <input id="rev_jumlah_penduduk" name="jumlah_penduduk" type="number"
                                    class="mt-1 block w-full border rounded p-2" min="0" />
                            </div>
                        </div>


                        <hr class="my-3 border-gray-200 dark:border-gray-700">

                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold dark:text-gray-100">Potensi Lahan (Parcels)</h3>
                            <div class="flex items-center gap-2">
                                <button type="button" id="btnAddParcelModal"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm">+ Tambah Lahan</button>
                            </div>
                        </div>

                        <div id="modalParcelsContainer" class="space-y-4 mb-4">
                            {{-- Parcels will be injected --}}
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" id="revCancelBtn"
                                class="px-4 py-2 rounded border dark:border-gray-700">Batal</button>
                            <button type="submit" id="revSubmitBtn"
                                class="px-4 py-2 rounded bg-green-600 text-white">Simpan ke Database</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Templates for modal (parcel, crop, livestock) --}}
        <template id="templateParcel">
            <div class="parcel-card">
                <div class="flex justify-between items-start gap-4">
                    <div class="w-full">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium">Tipe Lahan</label>
                                <select class="mt-1 block w-full border rounded p-2 parcel-type" required>
                                    <option value="">-- Pilih tipe --</option>
                                    <option value="persawahan">Persawahan</option>
                                    <option value="perkebunan">Perkebunan</option>
                                    <option value="tambak">Tambak</option>
                                    <option value="peternakan">Peternakan</option>
                                    <option value="komoditas_lain">Komoditas_lain</option>
                                </select>
                            </div>
                            <div class="md:col-span-2 small-muted">
                                <div class="mb-2">Crops (isi bila relevan)</div>
                                <div class="cropsContainer space-y-2"></div>
                                <div class="mt-2">
                                    <button type="button"
                                        class="btnAddCropModal text-sm px-2 py-1 bg-indigo-600 text-white rounded">+
                                        Tambah</button>
                                </div>

                                <div class="mt-3 mb-2">Livestocks (isi bila relevan)</div>
                                <div class="livestocksContainer space-y-2"></div>
                                <div class="mt-2">
                                    <button type="button"
                                        class="btnAddLivestockModal text-sm px-2 py-1 bg-red-600 text-white rounded">+
                                        Tambah Ternak</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ml-3 pl-3 border-l">
                        <button type="button" class="btnRemoveParcelModal text-sm text-red-600">Hapus Lahan</button>
                    </div>
                </div>
            </div>
        </template>

        <template id="templateCrop">
            <div class="crop-item border rounded p-2 bg-white flex items-start justify-between">
                <div class="w-full grid grid-cols-1 md:grid-cols-4 gap-2">
                    <div>
                        <label class="text-xs">Nama tanaman / komoditas</label>
                        <input type="text" class="mt-1 block w-full border rounded p-1 crop-nama" required>
                    </div>
                    <div>
                        <label class="text-xs">Luas (ha)</label>
                        <input type="number" step="0.001" min="0"
                            class="mt-1 block w-full border rounded p-1 crop-luas">
                    </div>
                    <div>
                        <label class="text-xs">Produksi (ton)</label>
                        <input type="number" step="0.001" min="0"
                            class="mt-1 block w-full border rounded p-1 crop-produksi">
                    </div>
                    <div>
                        <label class="text-xs">Satuan</label>
                        <input type="text" class="mt-1 block w-full border rounded p-1 crop-satuan"
                            placeholder="ton/kg">
                    </div>
                </div>

                <div class="ml-3 pl-3 border-l">
                    <button type="button" class="btnRemoveCropModal text-sm text-red-600">Hapus</button>
                </div>
            </div>
        </template>

        <template id="templateLivestock">
            <div class="livestock-item border rounded p-2 bg-white flex items-start justify-between">
                <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-2">
                    <div>
                        <label class="text-xs">Jenis ternak</label>
                        <input type="text" class="mt-1 block w-full border rounded p-1 liv-nama" required>
                    </div>
                    <div>
                        <label class="text-xs">Jumlah</label>
                        <input type="number" class="mt-1 block w-full border rounded p-1 liv-jumlah">
                    </div>
                    <div>
                        <label class="text-xs">Produksi (opsional)</label>
                        <input type="text" class="mt-1 block w-full border rounded p-1 liv-produksi"
                            placeholder="mis. telur ~1500/minggu">
                    </div>
                </div>

                <div class="ml-3 pl-3 border-l">
                    <button type="button" class="btnRemoveLivestockModal text-sm text-red-600">Hapus</button>
                </div>
            </div>
        </template>

    </div>

    <script>
        (function() {
            /* ===== THEME HANDLING (LIGHT / DARK toggle) ===== */
            (function() {
                const themeToggle = document.getElementById('themeToggle');
                const themeLabel = document.getElementById('themeLabel');
                const htmlEl = document.documentElement;
                const bodyRoot = document.getElementById('bodyRoot');

                function applyTheme(isDark) {
                    if (isDark) {
                        htmlEl.classList.add('dark');
                        if (themeLabel) themeLabel.textContent = 'Dark';
                        if (bodyRoot) bodyRoot.classList.add('bg-gray-900');
                    } else {
                        htmlEl.classList.remove('dark');
                        if (themeLabel) themeLabel.textContent = 'Light';
                        if (bodyRoot) bodyRoot.classList.remove('bg-gray-900');
                    }
                    try {
                        localStorage.setItem('voice_theme_dark', isDark ? '1' : '0');
                    } catch (e) {}
                }

                try {
                    const saved = localStorage.getItem('voice_theme_dark');
                    if (saved === null) {
                        const prefers = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)')
                            .matches;
                        applyTheme(prefers);
                    } else {
                        applyTheme(saved === '1');
                    }
                } catch (e) {
                    applyTheme(false);
                }

                if (themeToggle) {
                    themeToggle.addEventListener('click', () => {
                        const isDark = htmlEl.classList.contains('dark');
                        applyTheme(!isDark);
                    });
                }
            })();

            // -------------------------
            // In-memory survey data
            // -------------------------
            const surveyData = {
                kecamatan: '',
                desa: '',
                jumlah_penduduk: '',
                parcels: [] // each parcel: { type, crops: [], livestocks: [] }
            };

            // DOM helpers
            function $id(id) {
                return document.getElementById(id);
            }

            function escapeHtml(s) {
                return (s || '').toString().replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }

            // DOM refs (support both text inputs and select id variants)
            const startBtn = $id('startVoiceBtn');
            const pauseBtn = $id('pauseVoiceBtn');
            const statusText = $id('statusText');
            const statusLabel = $id('statusLabel');
            const statusBox = $id('statusBox');
            const activityLog = $id('activityLog');
            const pv_kecamatan = $id('pv_kecamatan');
            const pv_desa = $id('pv_desa');
            const pv_jumlah_penduduk = $id('pv_jumlah_penduduk');
            const pv_parcels = $id('pv_parcels');

            const btnOpenReview = $id('btnOpenReview');
            const reviewModal = $id('reviewModal');
            const closeReview = $id('closeReview');
            const revCancelBtn = $id('revCancelBtn');
            // support both naming schemes:
            const rev_kecamatan_input = $id('rev_kecamatan'); // optional text input
            const rev_desa_input = $id('rev_desa'); // optional text input
            const rev_kecamatan_select = $id('rev_kecamatan_id'); // optional select
            const rev_kelurahan_select = $id('rev_kelurahan_id'); // optional select
            const rev_jumlah_penduduk = $id('rev_jumlah_penduduk');
            const modalParcelsContainer = $id('modalParcelsContainer');
            const parcels_json = $id('parcels_json');
            const btnAddParcelModal = $id('btnAddParcelModal');

            // templates
            const tplParcel = $id('templateParcel') ? $id('templateParcel').content : null;
            const tplCrop = $id('templateCrop') ? $id('templateCrop').content : null;
            const tplLiv = $id('templateLivestock') ? $id('templateLivestock').content : null;

            // Speech API
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition || null;
            const synth = window.speechSynthesis || null;
            let capturing = false;
            let resumeResolver = null;

            function waitUntilResumed() {
                return new Promise(resolve => {
                    if (!paused) return resolve();
                    resumeResolver = resolve;
                });
            }

            let paused = false;

            // Pause/recognition state
            let currentPrompt = null; // current prompt text
            let lastPromptPhase = null; // semantic id for current prompt
            let retryCountForPrompt = 0; // single retry
            let currentRecognition = null; // active recognition instance

            // logging helper
            function log(msg) {
                const now = new Date();
                const t = now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                const line = document.createElement('div');
                line.innerHTML = `<span style="color:#6b7280">[${t}]</span> ${escapeHtml(msg)}`;
                if (activityLog) activityLog.prepend(line);
                else console.debug('[voice] activityLog missing,', msg);
            }

            function setStatus(text, state = 'siap') {
                if (statusText) statusText.textContent = text;
                if (statusLabel) {
                    statusLabel.textContent = state;
                    if (state === 'siap') statusLabel.style.color = '#10b981'; // hijau
                    else if (state === 'proses') statusLabel.style.color = '#f59e0b'; // kuning
                    else if (state === 'dijeda') statusLabel.style.color = '#3b82f6'; // biru
                    else if (state === 'gagal') statusLabel.style.color = '#ef4444'; // merah
                }
            }


            function speak(text, onend) {
                if (!synth) {
                    if (onend) setTimeout(onend, 200);
                    return;
                }
                const u = new SpeechSynthesisUtterance(text);
                u.lang = 'id-ID';
                u.rate = 1.0;
                u.onend = () => onend && onend();
                try {
                    synth.speak(u);
                } catch (e) {
                    if (onend) setTimeout(onend, 200);
                }
            }

            // captureOnceWithPause:
            async function captureOnceWithPause(prompt, timeout = 9000) {
                return new Promise(async resolve => {
                    if (!SpeechRecognition) {
                        log('SpeechRecognition tidak tersedia.');
                        resolve(null);
                        return;
                    }

                    // kalau sedang dijeda → tunggu tombol Lanjut
                    if (paused) {
                        log('⏸ Ditahan sementara karena mode jeda...');
                        await waitUntilResumed();
                        log('▶ Dilanjutkan...');
                    }

                    try {
                        if (currentRecognition) {
                            try {
                                currentRecognition.onresult = null;
                                currentRecognition.onend = null;
                                currentRecognition.onerror = null;
                                currentRecognition.abort && currentRecognition.abort();
                            } catch (e) {}
                        }
                    } catch (e) {}

                    const r = new SpeechRecognition();
                    currentRecognition = r;
                    r.lang = 'id-ID';
                    r.interimResults = false;
                    r.maxAlternatives = 1;
                    let done = false;

                    r.onresult = ev => {
                        if (done) return;
                        done = true;
                        const t = ev.results[0][0].transcript;
                        try {
                            r.stop();
                        } catch (e) {}
                        currentRecognition = null;
                        resolve(String(t).trim());
                    };

                    r.onerror = ev => {
                        if (done) return;
                        done = true;
                        try {
                            r.abort();
                        } catch (e) {}
                        currentRecognition = null;
                        resolve(null);
                    };

                    r.onend = () => {
                        if (done) return;
                        done = true;
                        currentRecognition = null;
                        resolve(null);
                    };

                    // Ucapkan prompt dulu, baru mulai dengarkan
                    speak(prompt, async () => {
                        // Kalau dijeda saat prompt dibacakan, tunggu sampai lanjut
                        if (paused) {
                            log('⏸ Jeda aktif saat pertanyaan, menunggu lanjut...');
                            await waitUntilResumed();
                            log('▶ Dilanjutkan setelah jeda.');
                        }

                        try {
                            r.start();
                        } catch (e) {
                            currentRecognition = null;
                            resolve(null);
                        }
                    });

                    // batas waktu maksimum (timeout)
                    setTimeout(() => {
                        if (done) return;
                        try {
                            r.stop();
                        } catch (e) {}
                    }, timeout + 1500);
                });
            }


            // -------------------------
            // parse helpers
            // -------------------------
            function parseNumber(text) {
                if (!text) return null;
                const t = text.toLowerCase().trim();
                if (t === '') return null;
                if (t.includes('tidak')) return null;
                const replaced = t.replace(/koma/g, '.').replace(/,/g, '.').replace(/ titik /g, '.');
                const m = replaced.match(/-?[\d]+(?:[.,]\d+)?/);
                if (m) return Number(m[0].replace(',', '.'));
                const words = {
                    'satu': 1,
                    'dua': 2,
                    'tiga': 3,
                    'empat': 4,
                    'lima': 5,
                    'enam': 6,
                    'tujuh': 7,
                    'delapan': 8,
                    'sembilan': 9,
                    'sepuluh': 10,
                    'sebelas': 11
                };
                for (const k in words)
                    if (replaced.indexOf(k) !== -1) return words[k];
                return null;
            }

            function isYes(text) {
                if (!text) return false;
                return /(ya|iya|oke|ok|yes|ada|betul)/i.test(text);
            }

            function isNo(text) {
                if (!text) return false;
                return /(tidak|nggak|engga|tak ada|no)/i.test(text);
            }

            // -------------------------
            // fuzzy match + fallback helpers for kec/kel
            // -------------------------
            function normalizeTextForMatch(s) {
                if (!s) return '';
                try {
                    const t = s.toString().toLowerCase().trim();
                    return t.normalize ? t.normalize('NFD').replace(/\p{Diacritic}/gu, '') : t;
                } catch (e) {
                    return s.toString().toLowerCase().trim();
                }
            }

            function fuzzyMatchOption(selectEl, spoken) {
                if (!selectEl || !spoken) return {
                    matched: false,
                    option: null
                };
                const spokenN = normalizeTextForMatch(spoken);
                const opts = Array.from(selectEl.options || []);
                // 1 exact
                for (const o of opts) {
                    if (!o.textContent) continue;
                    if (normalizeTextForMatch(o.textContent) === spokenN) return {
                        matched: true,
                        option: o
                    };
                }
                // 2 contains
                for (const o of opts) {
                    if (normalizeTextForMatch(o.textContent).includes(spokenN)) return {
                        matched: true,
                        option: o
                    };
                }
                // 3 option inside spoken
                for (const o of opts) {
                    if (spokenN.includes(normalizeTextForMatch(o.textContent))) return {
                        matched: true,
                        option: o
                    };
                }
                // 4 word-starts / loose
                for (const o of opts) {
                    const on = normalizeTextForMatch(o.textContent);
                    const words = on.split(/\s+/).filter(Boolean);
                    for (const w of words) {
                        if (spokenN.startsWith(w) || spokenN.includes(w)) return {
                            matched: true,
                            option: o
                        };
                    }
                }
                return {
                    matched: false,
                    option: null
                };
            }

            function ensureAndSetFallbackInputs({
                kecamatanText,
                kelurahanText
            } = {}) {
                const form = document.querySelector('form#modalSubmitForm') || document.querySelector('form');
                if (!form) return;
                let fKec = $id('rev_kecamatan_text');
                if (!fKec) {
                    fKec = document.createElement('input');
                    fKec.type = 'hidden';
                    fKec.id = 'rev_kecamatan_text';
                    fKec.name = 'kecamatan_text';
                    form.appendChild(fKec);
                }
                let fKel = $id('rev_kelurahan_text');
                if (!fKel) {
                    fKel = document.createElement('input');
                    fKel.type = 'hidden';
                    fKel.id = 'rev_kelurahan_text';
                    fKel.name = 'kelurahan_text';
                    form.appendChild(fKel);
                }
                if (typeof kecamatanText !== 'undefined') fKec.value = kecamatanText || '';
                if (typeof kelurahanText !== 'undefined') fKel.value = kelurahanText || '';
            }

            async function tryAutoSelectKecamatan(spokenName) {
                if (!spokenName) return false;
                if (typeof rev_kecamatan_select !== 'undefined' && rev_kecamatan_select) {
                    const res = fuzzyMatchOption(rev_kecamatan_select, spokenName);
                    if (res.matched && res.option) {
                        rev_kecamatan_select.value = res.option.value;
                        rev_kecamatan_select.dispatchEvent(new Event('change', {
                            bubbles: true
                        })); // triggers kelurahan load
                        ensureAndSetFallbackInputs({
                            kecamatanText: ''
                        });
                        return true;
                    }
                }
                ensureAndSetFallbackInputs({
                    kecamatanText: spokenName
                });
                return false;
            }

            async function tryAutoSelectKelurahan(spokenName, waitMs = 3000) {
                if (!spokenName) return false;
                const start = Date.now();
                while ((!rev_kelurahan_select || (rev_kelurahan_select.options && rev_kelurahan_select.options
                        .length <= 1)) && (Date.now() - start) < waitMs) {
                    await new Promise(r => setTimeout(r, 120));
                }
                if (typeof rev_kelurahan_select !== 'undefined' && rev_kelurahan_select) {
                    const res = fuzzyMatchOption(rev_kelurahan_select, spokenName);
                    if (res.matched && res.option) {
                        rev_kelurahan_select.value = res.option.value;
                        rev_kelurahan_select.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        ensureAndSetFallbackInputs({
                            kelurahanText: ''
                        });
                        return true;
                    }
                }
                ensureAndSetFallbackInputs({
                    kelurahanText: spokenName
                });
                return false;
            }

            // -------------------------
            // preview renderer
            // -------------------------
            function renderQuickPreview() {
                if (pv_kecamatan) pv_kecamatan.textContent = surveyData.kecamatan || '-';
                if (pv_desa) pv_desa.textContent = surveyData.desa || '-';
                if (pv_jumlah_penduduk) pv_jumlah_penduduk.textContent = (surveyData.jumlah_penduduk === '' ||
                    surveyData.jumlah_penduduk === undefined) ? '-' : surveyData.jumlah_penduduk;
                if (!pv_parcels) return;
                pv_parcels.innerHTML = '';
                if (!surveyData.parcels || surveyData.parcels.length === 0) {
                    pv_parcels.textContent = '(belum ada)';
                    return;
                }
                surveyData.parcels.forEach((p, idx) => {
                    const d = document.createElement('div');
                    d.className = 'text-sm';
                    let txt = `${idx+1}. ${p.type || '(tipe)'} — `;
                    if (p.crops && p.crops.length) {
                        txt += 'Tanaman: ' + p.crops.map(c => c.nama_tanaman || '-').join(', ');
                    } else if (p.livestocks && p.livestocks.length) {
                        txt += 'Ternak: ' + p.livestocks.map(l => l.jenis_ternak || '-').join(', ');
                    } else {
                        txt += '(detail kosong)';
                    }
                    d.textContent = txt;
                    pv_parcels.appendChild(d);
                });
            }

            // -------------------------
            // UI helpers for parcel/crop/livestock
            // -------------------------
            function labelForType(type) {
                switch (type) {
                    case 'persawahan':
                        return 'Jenis tanaman';
                    case 'perkebunan':
                        return 'Jenis tumbuhan';
                    case 'tambak':
                        return 'Jenis tambak';
                    case 'peternakan':
                        return 'Jenis ternak';
                    case 'komoditas_lain':
                        return 'Nama komoditas';
                    default:
                        return 'Nama tanaman / komoditas';
                }
            }

            function updateParcelUI(rootNode, type) {
                try {
                    const btnAddCrop = rootNode.querySelector('.btnAddCropModal');
                    const btnAddLiv = rootNode.querySelector('.btnAddLivestockModal');
                    const cropsContainer = rootNode.querySelector('.cropsContainer');

                    if (type === 'peternakan') {
                        if (btnAddLiv) btnAddLiv.style.display = '';
                        if (btnAddCrop) btnAddCrop.style.display = 'none';
                    } else {
                        if (btnAddLiv) btnAddLiv.style.display = 'none';
                        if (btnAddCrop) btnAddCrop.style.display = '';
                    }

                    const labelText = labelForType(type);
                    const cropItems = rootNode.querySelectorAll('.crop-item');
                    cropItems.forEach(ci => {
                        const lbl = ci.querySelector('label');
                        if (lbl) lbl.textContent = labelText;
                    });
                } catch (e) {
                    console.error('updateParcelUI', e);
                }
            }

            function createCropNode(crop = null, labelText = null) {
                if (!tplCrop) return document.createElement('div');
                const frag = tplCrop.cloneNode(true);
                const root = frag.querySelector('.crop-item') || frag;
                if (labelText) {
                    const lbl = root.querySelector('label');
                    if (lbl) lbl.textContent = labelText;
                }
                if (crop) {
                    const namaEl = root.querySelector('.crop-nama');
                    if (namaEl) namaEl.value = crop.nama_tanaman || '';
                    const luasEl = root.querySelector('.crop-luas');
                    if (luasEl && crop.luas_hektare !== undefined && crop.luas_hektare !== null) luasEl.value = crop
                        .luas_hektare;
                    const prodEl = root.querySelector('.crop-produksi');
                    if (prodEl && crop.produksi_ton !== undefined && crop.produksi_ton !== null) prodEl.value = crop
                        .produksi_ton;
                    const satuanEl = root.querySelector('.crop-satuan');
                    if (satuanEl) satuanEl.value = crop.satuan || '';
                }
                const btnRemove = root.querySelector('.btnRemoveCropModal');
                if (btnRemove) {
                    btnRemove.addEventListener('click', (ev) => {
                        const n = ev.target.closest('.crop-item');
                        if (n) n.remove();
                    });
                }
                return root;
            }

            function createLivestockNode(liv = null) {
                if (!tplLiv) return document.createElement('div');
                const frag = tplLiv.cloneNode(true);
                const root = frag.querySelector('.livestock-item') || frag;
                if (liv) {
                    const nama = root.querySelector('.liv-nama');
                    if (nama) nama.value = liv.jenis_ternak || '';
                    const jumlah = root.querySelector('.liv-jumlah');
                    if (jumlah && liv.jumlah !== undefined && liv.jumlah !== null) jumlah.value = liv.jumlah;
                    const prod = root.querySelector('.liv-produksi');
                    if (prod) prod.value = liv.produksi || '';
                }
                const btnRemove = root.querySelector('.btnRemoveLivestockModal');
                if (btnRemove) {
                    btnRemove.addEventListener('click', (ev) => {
                        const n = ev.target.closest('.livestock-item');
                        if (n) n.remove();
                    });
                }
                return root;
            }

            function createParcelNode(parcel = null) {
                if (!tplParcel) return document.createElement('div');
                const frag = tplParcel.cloneNode(true);
                const root = frag.querySelector('.parcel-card') || frag;
                const selType = root.querySelector('.parcel-type');
                const cropsContainer = root.querySelector('.cropsContainer');
                const livsContainer = root.querySelector('.livestocksContainer');
                const btnAddCrop = root.querySelector('.btnAddCropModal');
                const btnAddLiv = root.querySelector('.btnAddLivestockModal');
                const btnRemoveParcel = root.querySelector('.btnRemoveParcelModal');

                if (parcel && parcel.type && selType) selType.value = parcel.type;
                const currentType = selType ? selType.value : '';
                if (parcel && Array.isArray(parcel.crops) && parcel.crops.length) {
                    parcel.crops.forEach(c => {
                        cropsContainer.appendChild(createCropNode(c, labelForType(selType ? selType.value :
                            '')));
                    });
                }
                if (parcel && Array.isArray(parcel.livestocks) && parcel.livestocks.length) {
                    parcel.livestocks.forEach(l => {
                        livsContainer.appendChild(createLivestockNode(l));
                    });
                }

                updateParcelUI(root, selType ? selType.value : '');

                if (selType) {
                    selType.addEventListener('change', (ev) => {
                        const t = selType.value;
                        updateParcelUI(root, t);
                        const label = labelForType(t);
                        const cropItems = root.querySelectorAll('.crop-item');
                        cropItems.forEach(ci => {
                            const lbl = ci.querySelector('label');
                            if (lbl) lbl.textContent = label;
                        });
                    });
                }

                if (btnAddCrop) {
                    btnAddCrop.addEventListener('click', () => {
                        const label = labelForType(selType ? selType.value : '');
                        cropsContainer.appendChild(createCropNode(null, label));
                        setTimeout(() => {
                            const mContent = reviewModal ? reviewModal.querySelector('.max-w-5xl') :
                                modalParcelsContainer;
                            if (mContent) mContent.scrollIntoView({
                                behavior: 'smooth',
                                block: 'end'
                            });
                        }, 80);
                    });
                }

                if (btnAddLiv) {
                    btnAddLiv.addEventListener('click', () => {
                        livsContainer.appendChild(createLivestockNode());
                        setTimeout(() => {
                            const mContent = reviewModal ? reviewModal.querySelector('.max-w-5xl') :
                                modalParcelsContainer;
                            if (mContent) mContent.scrollIntoView({
                                behavior: 'smooth',
                                block: 'end'
                            });
                        }, 80);
                    });
                }

                if (btnRemoveParcel) {
                    btnRemoveParcel.addEventListener('click', (ev) => {
                        const pCard = ev.target.closest('.parcel-card');
                        if (pCard) pCard.remove();
                    });
                }

                return frag;
            }

            // open/close modal helpers (adapted)
            function openReviewModal(autoFocus = true) {
                try {
                    // if selects exist, mirror selected text into optional text inputs for editing fallback
                    if (rev_kecamatan_select && rev_kecamatan_select.value) {
                        if (rev_kecamatan_input) rev_kecamatan_input.value = rev_kecamatan_select.options[
                            rev_kecamatan_select.selectedIndex]?.text || rev_kecamatan_select.value;
                    }
                    if (rev_kelurahan_select && rev_kelurahan_select.value) {
                        if (rev_desa_input) rev_desa_input.value = rev_kelurahan_select.options[rev_kelurahan_select
                            .selectedIndex]?.text || rev_kelurahan_select.value;
                    }
                } catch (e) {}

                // Fill from surveyData if available
                if (rev_kecamatan_input) rev_kecamatan_input.value = surveyData.kecamatan || rev_kecamatan_input
                    .value || '';
                if (rev_desa_input) rev_desa_input.value = surveyData.desa || rev_desa_input.value || '';
                if (rev_jumlah_penduduk) rev_jumlah_penduduk.value = (surveyData.jumlah_penduduk !== undefined &&
                        surveyData.jumlah_penduduk !== '') ? surveyData.jumlah_penduduk : rev_jumlah_penduduk.value ||
                    '';

                // render parcels into modal
                if (modalParcelsContainer) {
                    modalParcelsContainer.innerHTML = '';
                    if (!surveyData.parcels || surveyData.parcels.length === 0) {
                        const p = document.createElement('div');
                        p.className = 'text-sm text-gray-500';
                        p.textContent = 'Belum ada potensi lahan tercatat.';
                        modalParcelsContainer.appendChild(p);
                    } else {
                        surveyData.parcels.forEach(p => modalParcelsContainer.appendChild(createParcelNode(p)));
                    }
                }

                if (reviewModal) {
                    reviewModal.classList.remove('hidden');
                    reviewModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    setTimeout(() => {
                        const dialog = reviewModal.querySelector('.max-w-5xl') || reviewModal.querySelector(
                            'form') || modalParcelsContainer;
                        if (dialog) {
                            dialog.scrollTop = 0;
                        }
                        if (autoFocus) {
                            const first = reviewModal.querySelector('input, select, textarea, button');
                            if (first) first.focus();
                        }
                    }, 80);
                }
            }

            function closeReviewModal() {
                if (reviewModal) {
                    reviewModal.classList.add('hidden');
                    reviewModal.style.display = '';
                }
                document.body.style.overflow = '';
            }

            // modal add/remove handlers
            if (btnAddParcelModal) {
                btnAddParcelModal.addEventListener('click', () => {
                    if (modalParcelsContainer.children.length === 1 && modalParcelsContainer.children[0]
                        .textContent && modalParcelsContainer.children[0].textContent.includes('Belum ada')) {
                        modalParcelsContainer.innerHTML = '';
                    }
                    modalParcelsContainer.appendChild(createParcelNode());
                    setTimeout(() => {
                        const container = reviewModal ? reviewModal.querySelector('.max-w-5xl') :
                            modalParcelsContainer;
                        if (container) container.scrollIntoView({
                            behavior: 'smooth',
                            block: 'end'
                        });
                    }, 60);
                });
            }
            if (closeReview) closeReview.addEventListener('click', closeReviewModal);
            if (revCancelBtn) revCancelBtn.addEventListener('click', closeReviewModal);

            // build parcels JSON on submit
            const modalForm = $id('modalSubmitForm');
            if (modalForm) {
                modalForm.addEventListener('submit', (ev) => {
                    // mirror values from selects (if present) or inputs into surveyData
                    if (rev_kecamatan_input) surveyData.kecamatan = rev_kecamatan_input.value;
                    else if (rev_kecamatan_select) surveyData.kecamatan = rev_kecamatan_select.options[
                        rev_kecamatan_select.selectedIndex]?.text || '';

                    if (rev_desa_input) surveyData.desa = rev_desa_input.value;
                    else if (rev_kelurahan_select) surveyData.desa = rev_kelurahan_select.options[
                        rev_kelurahan_select.selectedIndex]?.text || '';

                    if (rev_jumlah_penduduk) surveyData.jumlah_penduduk = rev_jumlah_penduduk.value;

                    const parcels = [];
                    const cards = modalParcelsContainer.querySelectorAll('.parcel-card');
                    cards.forEach(pc => {
                        const type = pc.querySelector('.parcel-type').value || '';
                        if (!type) return;
                        const parcel = {
                            type: type,
                            crops: [],
                            livestocks: []
                        };

                        pc.querySelectorAll('.crop-item').forEach(cn => {
                            const nama = cn.querySelector('.crop-nama')?.value || '';
                            if (!nama) return;
                            const luas = cn.querySelector('.crop-luas')?.value;
                            const produksi = cn.querySelector('.crop-produksi')?.value;
                            const satuan = cn.querySelector('.crop-satuan')?.value;
                            parcel.crops.push({
                                nama_tanaman: nama,
                                luas_hektare: luas === '' ? null : (isNaN(Number(
                                    luas)) ? luas : Number(luas)),
                                produksi_ton: produksi === '' ? null : (isNaN(Number(
                                    produksi)) ? produksi : Number(produksi)),
                                satuan: satuan || null
                            });
                        });

                        pc.querySelectorAll('.livestock-item').forEach(ln => {
                            const jenis = ln.querySelector('.liv-nama')?.value || '';
                            if (!jenis) return;
                            const jumlah = ln.querySelector('.liv-jumlah')?.value;
                            const prod = ln.querySelector('.liv-produksi')?.value;
                            parcel.livestocks.push({
                                jenis_ternak: jenis,
                                jumlah: jumlah === '' ? null : (isNaN(Number(jumlah)) ?
                                    jumlah : Number(jumlah)),
                                produksi: prod || null
                            });
                        });

                        parcels.push(parcel);
                    });

                    if (parcels_json) parcels_json.value = JSON.stringify(parcels);
                    log('Mempersiapkan data JSON untuk dikirim ke server...');
                    // before submit, unlock body scroll (form submission continues)
                    document.body.style.overflow = '';
                });
            }

            // --- Voice flow controller helpers ---
            async function askAndCapture(phaseId, prompt, opts = {
                timeout: 9000
            }) {
                currentPrompt = prompt;
                lastPromptPhase = phaseId;
                retryCountForPrompt = 0;

                let answer = await captureOnceWithPause(prompt, opts.timeout);
                if (answer && typeof answer === 'object' && answer.aborted) {
                    return {
                        aborted: true
                    };
                }

                if (answer === null) {
                    // retry once
                    if (retryCountForPrompt === 0) {
                        retryCountForPrompt = 1;
                        log('Tidak mendapat jawaban — mengulangi pertanyaan sekali lagi.');
                        await new Promise(r => setTimeout(r, 400));
                        answer = await captureOnceWithPause(prompt, opts.timeout);
                        if (answer && typeof answer === 'object' && answer.aborted) {
                            return {
                                aborted: true
                            };
                        }
                    } else {
                        retryCountForPrompt = 0;
                        return {
                            value: null
                        };
                    }
                }

                retryCountForPrompt = 0;
                return {
                    value: answer
                };
            }

            async function runVoiceFlow() {
                if (capturing) return;
                if (!SpeechRecognition) {
                    alert('SpeechRecognition tidak tersedia. Gunakan Chrome/Edge desktop atau isi manual.');
                    return;
                }

                capturing = true;
                paused = false;
                if (startBtn) startBtn.disabled = true;
                if (pauseBtn) {
                    pauseBtn.disabled = false;
                    pauseBtn.textContent = '⏸ Jeda';
                }
                setStatus('merekam...', 'proses');
                log('Mulai sesi voice');

                // 1) kecamatan
                let r = await askAndCapture('kecamatan', 'Sebutkan nama kecamatan.');
                if (r.aborted) {
                    capturing = false;
                    setStatus('siap', 'siap');
                    return;
                }
                if (r.value) {
                    surveyData.kecamatan = r.value.trim();
                    log(`Kecamatan: ${surveyData.kecamatan}`);
                    // try auto-select (await so kelurahan AJAX will be triggered before we ask desa)
                    try {
                        await tryAutoSelectKecamatan(surveyData.kecamatan);
                    } catch (e) {
                        console.error('tryAutoSelectKecamatan error', e);
                    }
                    renderQuickPreview();
                }

                // 2) desa
                r = await askAndCapture('desa', 'Sebutkan nama desa.');
                if (r.aborted) {
                    capturing = false;
                    setStatus('siap', 'siap');
                    return;
                }
                if (r.value) {
                    surveyData.desa = r.value.trim();
                    log(`Desa: ${surveyData.desa}`);
                    try {
                        // wait up to 3s for kelurahan select options to be loaded by AJAX after kecamatan change
                        await tryAutoSelectKelurahan(surveyData.desa, 3000);
                    } catch (e) {
                        console.error('tryAutoSelectKelurahan error', e);
                    }
                    renderQuickPreview();
                }

                // 3) jumlah penduduk
                r = await askAndCapture('jumlah',
                    'Sebutkan jumlah penduduk (rumah tangga) di dalam desa ');
                if (r.aborted) {
                    capturing = false;
                    setStatus('siap', 'siap');
                    return;
                }
                if (r.value) {
                    const num = parseNumber(r.value);
                    if (num !== null) {
                        surveyData.jumlah_penduduk = num;
                        log(`Jumlah penduduk: ${num}`);
                    } else {
                        surveyData.jumlah_penduduk = r.value.trim();
                        log(`Jumlah penduduk (text): ${r.value}`);
                    }
                }
                renderQuickPreview();

                // 4) potensi lahan?
                r = await askAndCapture('want_parcels',
                    'Apakah ingin menambahkan potensi lahan? Jawab ya atau tidak.');
                if (r.aborted) {
                    capturing = false;
                    setStatus('siap', 'siap');
                    return;
                }
                if (r.value && isYes(r.value)) {
                    log('Menangkap potensi lahan via voice...');
                    const types = ['persawahan', 'perkebunan', 'tambak', 'peternakan', 'komoditas_lain'];
                    for (const t of types) {
                        if (paused) {
                            capturing = false;
                            setStatus('siap', 'siap');
                            return;
                        }
                        const ansExist = await askAndCapture('exist_' + t,
                            `Apakah terdapat ${t} di desa ini? Jawab ya atau tidak.`);
                        if (ansExist.aborted) {
                            capturing = false;
                            setStatus('siap', 'siap');
                            return;
                        }
                        if (!ansExist.value) continue;
                        if (isYes(ansExist.value)) {
                            const parcel = {
                                type: t,
                                crops: [],
                                livestocks: []
                            };
                            log(`Tambah lahan: ${t}`);

                            if (t === 'peternakan') {
                                while (true) {
                                    const j1 = await askAndCapture('peternakan_jenis',
                                        'Sebutkan jenis hewan ternak. Jika tidak ada lagi, katakan tidak.');
                                    if (j1.aborted) {
                                        capturing = false;
                                        setStatus('siap', 'siap');
                                        return;
                                    }
                                    if (!j1.value || isNo(j1.value)) break;
                                    const jenis = j1.value.trim();

                                    const j2 = await askAndCapture('peternakan_jumlah', `Berapa jumlah ${jenis}?`);
                                    if (j2.aborted) {
                                        capturing = false;
                                        setStatus('siap', 'siap');
                                        return;
                                    }
                                    const jumlah = (j2.value !== undefined && j2.value !== null) ? (parseNumber(j2
                                        .value) ?? j2.value) : '';

                                    const j3 = await askAndCapture('peternakan_prod',
                                        `Ada produksi khusus untuk ${jenis}? Jika ada sebutkan, atau katakan tidak.`
                                    );
                                    if (j3.aborted) {
                                        capturing = false;
                                        setStatus('siap', 'siap');
                                        return;
                                    }
                                    const prod = (j3.value && !isNo(j3.value)) ? j3.value.trim() : '';

                                    parcel.livestocks.push({
                                        jenis_ternak: jenis,
                                        jumlah: jumlah,
                                        produksi: prod
                                    });
                                    log(` - Ternak: ${jenis} — ${jumlah} — ${prod}`);
                                    const more = await askAndCapture('peternakan_more',
                                        'Apakah ada jenis hewan lain? Jawab ya atau tidak.');
                                    if (more.aborted) {
                                        capturing = false;
                                        setStatus('siap', 'siap');
                                        return;
                                    }
                                    if (!more.value || isNo(more.value)) break;
                                }
                            } else {
                                while (true) {
                                    const c1 = await askAndCapture('crop_nama',
                                        'Sebutkan jenis tanaman atau komoditas. Jika tidak ada lagi, katakan tidak.'
                                    );
                                    if (c1.aborted) {
                                        capturing = false;
                                        setStatus('siap', 'siap');
                                        return;
                                    }
                                    if (!c1.value || isNo(c1.value)) break;
                                    const nama = c1.value.trim();

                                    const c2 = await askAndCapture('crop_luas',
                                        `Berapa luas lahan untuk ${nama} dalam hektar? Contoh: nol koma lima.`);
                                    if (c2.aborted) {
                                        capturing = false;
                                        setStatus('siap', 'siap');
                                        return;
                                    }
                                    const luas = (c2.value !== undefined && c2.value !== null) ? (parseNumber(c2
                                        .value) ?? c2.value) : '';

                                    const c3 = await askAndCapture('crop_satuan',
                                        `Satuan panennya apa untuk ${nama}? Misal ton atau KG.`);
                                    if (c3.aborted) {
                                        capturing = false;
                                        setStatus('siap', 'siap');
                                        return;
                                    }
                                    const satuan = (c3.value && !isNo(c3.value)) ? c3.value.trim() : '';

                                    const c4 = await askAndCapture('crop_produksi',
                                        `Berapa jumlah panen untuk ${nama} dalam ${satuan||'satuan'}? Jika tidak tahu, katakan nol.`
                                    );
                                    if (c4.aborted) {
                                        capturing = false;
                                        setStatus('siap', 'siap');
                                        return;
                                    }
                                    const produksi = (c4.value !== undefined && c4.value !== null) ? (parseNumber(c4
                                        .value) ?? c4.value) : '';

                                    parcel.crops.push({
                                        nama_tanaman: nama,
                                        luas_hektare: luas,
                                        produksi_ton: produksi,
                                        satuan: satuan
                                    });
                                    log(` - Tanaman: ${nama} — luas ${luas} — panen ${produksi} ${satuan}`);

                                    const more = await askAndCapture('crop_more',
                                        'Apakah ada jenis tanaman/komoditas lain? Jawab ya atau tidak.');
                                    if (more.aborted) {
                                        capturing = false;
                                        setStatus('siap', 'siap');
                                        return;
                                    }
                                    if (!more.value || isNo(more.value)) break;
                                }
                            }

                            surveyData.parcels.push(parcel);
                            renderQuickPreview();
                        } else {
                            log(`Tidak ada ${t}`);
                        }
                    }
                } else {
                    log('Lewati potensi lahan.');
                }

                // flow finished
                capturing = false;
                if (startBtn) startBtn.disabled = false;
                if (pauseBtn) {
                    pauseBtn.disabled = true;
                    pauseBtn.textContent = 'Jeda';
                }
                setStatus('siap', 'siap');
                log('Selesai sesi voice — membuka Tinjau & Edit untuk koreksi / simpan.');
                // auto-open review modal:
                setTimeout(() => openReviewModal(true), 350);
            }

            // Pause/resume with re-ask of current prompt
            if (startBtn) startBtn.addEventListener('click', () => {
                if (capturing) return;
                paused = false;
                if (pauseBtn) {
                    pauseBtn.textContent = '⏸ Jeda';
                    pauseBtn.disabled = false;
                }
                runVoiceFlow().catch(e => {
                    console.error(e);
                    capturing = false;
                    if (startBtn) startBtn.disabled = false;
                    if (pauseBtn) pauseBtn.disabled = true;
                    setStatus('gagal', 'gagal');
                    log('Terjadi error pada sesi voice');
                });
            });

            if (pauseBtn) pauseBtn.addEventListener('click', () => {
                if (!capturing) return;
                paused = !paused;

                if (paused) {
                    try {
                        if (currentRecognition && currentRecognition.abort) currentRecognition.abort();
                    } catch (e) {}

                    pauseBtn.textContent = '▶ Lanjut';
                    pauseBtn.style.background = 'linear-gradient(90deg, #3b82f6, #60a5fa)'; // biru saat jeda
                    log('⏸ Sesi dijeda. Tekan Lanjut untuk melanjutkan pertanyaan terakhir.');
                    setStatus('dijeda', 'dijeda');
                } else {
                    pauseBtn.textContent = '⏸ Jeda';
                    pauseBtn.style.background = 'linear-gradient(90deg, #f59e0b, #fbbf24)'; // kuning lagi
                    log('▶ Melanjutkan sesi voice...');
                    setStatus('merekam...', 'proses');

                    // lanjutkan proses voice yang sedang ditahan
                    if (resumeResolver) {
                        resumeResolver();
                        resumeResolver = null;
                    }
                }
            });

            // fallback notice
            if (!SpeechRecognition) {
                const warn = document.createElement('div');
                warn.className =
                    'max-w-4xl mx-auto mt-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded';
                warn.innerHTML =
                    '<strong>Catatan:</strong> Speech Recognition tidak tersedia di browser ini. Gunakan Chrome/Edge (desktop) atau isi manual.';
                document.body.insertBefore(warn, document.body.firstChild.nextSibling);
            }

            // wire review button (prevent default if anchor)
            if (btnOpenReview) {
                btnOpenReview.addEventListener('click', function(ev) {
                    if (ev && ev.preventDefault) ev.preventDefault();
                    openReviewModal(true);
                });
            }

            // initial UI
            setStatus('siap', 'siap');
            log('Siap. Tekan Mulai untuk memulai sesi voice.');
            renderQuickPreview();

            // Flash handling (server session) — keep original behaviour
            (function() {
                function insertSavedBox(msgTitle, msgSubtitle) {
                    try {
                        const attempt = () => {
                            const logEl = $id('activityLog');
                            if (!logEl) return false;
                            const card = document.createElement('div');
                            card.className =
                                'saved-box p-3 rounded border bg-green-50 border-green-200 text-green-800 mb-2';
                            const row = document.createElement('div');
                            row.className = 'flex items-start gap-3';
                            const icon = document.createElement('div');
                            icon.innerHTML = `<svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <circle cx="12" cy="12" r="10" fill="#16a34a" opacity="0.12"></circle>
              <path d="M7 13l3 3 7-8" stroke="#166534" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>`;
                            const texts = document.createElement('div');
                            texts.innerHTML = `<div class="font-semibold text-sm">${escapeHtml(msgTitle)}</div>
                             <div class="text-xs text-green-800/80 mt-1">${escapeHtml(msgSubtitle || '')}</div>`;
                            row.appendChild(icon);
                            row.appendChild(texts);
                            card.appendChild(row);
                            logEl.prepend(card);
                            card.animate([{
                                transform: 'translateY(-6px)',
                                opacity: 0
                            }, {
                                transform: 'translateY(0)',
                                opacity: 1
                            }], {
                                duration: 220,
                                easing: 'ease-out'
                            });
                            return true;
                        };
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', () => {
                                if (!attempt()) setTimeout(attempt, 200);
                            });
                        } else {
                            if (!attempt()) setTimeout(attempt, 200);
                        }
                    } catch (e) {
                        console.error('insertSavedBox error', e);
                    }
                }

                try {
                    @if (session('success'))
                        const _sr_msg = {!! json_encode(session('success')) !!};
                    @else
                        const _sr_msg = null;
                    @endif

                    @if (session('latest_no_id'))
                        const _sr_id = {!! json_encode(session('latest_no_id')) !!};
                    @else
                        const _sr_id = null;
                    @endif

                    @if (session('error'))
                        const _sr_err = {!! json_encode(session('error')) !!};
                    @else
                        const _sr_err = null;
                    @endif

                    if (_sr_msg) {
                        const title = _sr_id ? `${_sr_msg} No ID: ${_sr_id}` : _sr_msg;
                        const subtitle = 'Siap. Tekan Mulai untuk memulai sesi voice.';
                        insertSavedBox(title, subtitle);
                        try {
                            setStatus('tersimpan', 'siap');
                        } catch (e) {}
                        try {
                            if (statusBox) {
                                statusBox.classList.add('flash-highlight');
                                setTimeout(() => statusBox.classList.remove('flash-highlight'), 1600);
                            }
                        } catch (e) {}
                    }
                    if (_sr_err) {
                        (function() {
                            const attemptErr = () => {
                                const el = $id('activityLog');
                                if (!el) return false;
                                const now = new Date();
                                const t = now.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit'
                                });
                                const line = document.createElement('div');
                                line.innerHTML =
                                    `<span style="color:#6b7280">[${t}]</span> <span class="text-red-600">${escapeHtml(_sr_err)}</span>`;
                                el.prepend(line);
                                return true;
                            };
                            if (document.readyState === 'loading') {
                                document.addEventListener('DOMContentLoaded', () => {
                                    if (!attemptErr()) setTimeout(attemptErr, 200);
                                });
                            } else {
                                if (!attemptErr()) setTimeout(attemptErr, 200);
                            }
                        })();
                        try {
                            setStatus('gagal', 'gagal');
                        } catch (e) {}
                    }
                } catch (e) {
                    console.error('[voice] flash-handling error', e);
                }
            })();

            // End main IIFE
        })();

        document.addEventListener('DOMContentLoaded', function() {
            const selKec = document.getElementById('rev_kecamatan_id');
            const selKel = document.getElementById('rev_kelurahan_id');

            if (!selKec || !selKel) return;

            selKec.addEventListener('change', async function() {
                selKel.innerHTML = '<option>Memuat...</option>';
                const kecId = this.value;
                if (!kecId) {
                    selKel.innerHTML = '<option value="">-- Pilih Desa --</option>';
                    return;
                }

                try {
                    const res = await fetch(`/ajax/kecamatans/${kecId}/kelurahans`);
                    if (!res.ok) throw new Error('Gagal memuat desa');
                    const data = await res.json();
                    selKel.innerHTML = '<option value="">-- Pilih Desa --</option>';
                    data.forEach(k => {
                        const o = document.createElement('option');
                        o.value = k.id;
                        o.textContent = k.nama;
                        selKel.appendChild(o);
                    });
                } catch (e) {
                    selKel.innerHTML = '<option value="">-- Gagal memuat desa --</option>';
                    console.error(e);
                }
            });
            // === AUTO KAPITAL UNTUK SEMUA INPUT TEKS DI MODAL REVIEW ===
            document.addEventListener('input', function(e) {
                const modal = document.getElementById('reviewModal');
                if (!modal || !modal.contains(e.target)) return;

                const el = e.target;
                if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    const type = el.getAttribute('type');
                    if (type !== 'number' && type !== 'email' && type !== 'password') {
                        const start = el.selectionStart;
                        const end = el.selectionEnd;
                        el.value = el.value.toUpperCase();
                        if (start !== null && end !== null) el.setSelectionRange(start, end);
                    }
                }
            });

        });
    </script>

</x-layout.terminal>
