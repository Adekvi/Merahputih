<x-layout.terminal title="Stats">
    @push('css')
        <style>
            /* =========================== ROOT & UTILITIES =========================== */
            :root {
                --action-btn-w: 220px;
                --action-btn-h: 72px;
                --action-radius: 14px;
                --primary: #3b82f6;
                --success: #2fcc1a;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --gray-900: #111827;
                --white: #ffffff;
                --dark-bg: #1a202c;
                --dark-card: #2d3748;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: var(--gray-100);
                color: var(--gray-800);
                margin: 0;
                padding: 0;
                transition: background-color 0.3s, color 0.3s;
            }

            .dark body {
                background-color: #111827;
                color: #e5e7eb;
            }

            /* =========================== LAYOUT =========================== */
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 1.5rem;
            }

            @media (min-width: 1025px) {
                .container {
                    margin-left: 270px;
                }
            }

            @media (max-width: 1024px) {
                .container {
                    margin-left: 0;
                    padding: 1rem;
                }
            }

            .card {
                background: var(--white);
                border-radius: 16px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                border: 1px solid rgba(0, 0, 0, 0.04);
            }

            .dark .card {
                background: var(--dark-card);
                border-color: rgba(255, 255, 255, 0.1);
            }

            h1,
            h2 {
                text-align: center;
                margin-bottom: 1.5rem;
                font-weight: 700;
            }

            h1 {
                font-size: 1.75rem;
                color: var(--gray-800);
            }

            .dark h1,
            .dark h2 {
                color: #f3f4f6;
            }

            /* =========================== FORM GRID =========================== */
            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr 220px;
                gap: 1rem;
                align-items: end;
                margin-bottom: 1.5rem;
            }

            @media (max-width: 767px) {
                .form-grid {
                    grid-template-columns: 1fr;
                }
            }

            .form-group {
                display: flex;
                flex-direction: column;
            }

            label {
                font-size: 0.875rem;
                font-weight: 500;
                margin-bottom: 0.5rem;
                color: var(--gray-700);
            }

            .dark label {
                color: #d1d5db;
            }

            select,
            input {
                padding: 0.5rem 0.75rem;
                border: 1px solid var(--gray-300);
                border-radius: 8px;
                background: var(--white);
                color: var(--gray-800);
                font-size: 1rem;
            }

            .dark select,
            .dark input {
                background: #374151;
                color: #e5e7eb;
                border-color: #4b5563;
            }

            select:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            /* =========================== BUTTONS =========================== */
            button {
                padding: 0.75rem 1rem;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                white-space: nowrap;
            }

            #btnLoadAll {
                background-color: var(--success);
                color: white;
            }

            #btnLoadAll:hover:not(:disabled) {
                background-color: #28b816;
                transform: translateY(-2px);
            }

            #btnLoadAreas {
                background-color: #10b981;
                color: white;
            }

            #btnLoadAreas:hover:not(:disabled) {
                background-color: #059669;
                transform: translateY(-2px);
            }

            button:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            /* =========================== JENIS CHIPS =========================== */
            #jenisContainer {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                justify-content: flex-start;
                margin: 1rem 0;
            }

            .jenis-btn {
                padding: 0.5rem 0.85rem;
                border-radius: 0.5rem;
                border: 1px solid rgba(0, 0, 0, 0.1);
                font-weight: 600;
                cursor: pointer;
                transition: all 0.12s ease;
                color: #0f172a;
                background: #cdcecf;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
                white-space: nowrap;
                max-width: 240px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .jenis-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(15, 23, 42, 0.15);
            }

            .jenis-btn.active {
                color: #ffffff;
                border-color: rgba(0, 0, 0, 0.12);
                box-shadow: 0 8px 24px rgba(2, 6, 23, 0.25);
            }

            /* Warna gradient per jenis */
            .jenis-btn:nth-child(1) {
                background: linear-gradient(90deg, #fde68a, #fca5a5);
            }

            .jenis-btn:nth-child(2) {
                background: linear-gradient(90deg, #bbf7d0, #86efac);
            }

            .jenis-btn:nth-child(3) {
                background: linear-gradient(90deg, #bfdbfe, #93c5fd);
            }

            .jenis-btn:nth-child(4) {
                background: linear-gradient(90deg, #fbcfe8, #fda4af);
            }

            .jenis-btn:nth-child(5) {
                background: linear-gradient(90deg, #c7d2fe, #a78bfa);
            }

            .jenis-btn:nth-child(6) {
                background: linear-gradient(90deg, #fdba74, #fb923c);
            }

            .jenis-btn:nth-child(n+7) {
                background: linear-gradient(90deg, #e2e8f0, #cbd5e1);
            }

            /* =========================== RESULT BOX =========================== */
            #resultSummary,
            #resultAreas {
                padding: 1.5rem;
                border-radius: 0.75rem;
                border: 1px solid rgba(2, 6, 23, 0.05);
                background: linear-gradient(180deg, #ffffff, #f9fafb);
                color: #0f172a;
                margin-top: 1rem;
                min-height: 80px;
            }

            .dark #resultSummary,
            .dark #resultAreas {
                background: #374151;
                color: #e5e7eb;
                border-color: #4b5563;
            }

            .text-center {
                text-align: center;
            }

            .text-sm {
                font-size: 0.875rem;
            }

            .text-gray-400 {
                color: #9ca3af;
            }

            .text-blue-700 {
                color: #1d4ed8;
            }

            .font-medium {
                font-weight: 500;
            }

            .ml-2 {
                margin-left: 0.5rem;
            }

            .border-b {
                border-bottom: 1px solid #d1d5db;
            }

            .pb-2 {
                padding-bottom: 0.5rem;
            }

            .mb-2 {
                margin-bottom: 0.5rem;
            }
        </style>
    @endpush

    <div class="page-inner ecommerce-page">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-2 text-maroon">🛒 Statistik</h3>
            </div>
            <div class="date-time mt-3 mt-md-0 text-md-end">
                <div id="tanggal" class="fw-semibold text-muted"></div>
                <div id="jam" class="fw-semibold text-muted"></div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- BAGIAN 1: Statistik Produksi Desa -->
            <div class="card">
                <h1>📊 Statistik Produksi Desa</h1>

                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <select id="selectKec">
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatans as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Desa</label>
                            <select id="selectDesa" disabled>
                                <option value="">-- Pilih Desa --</option>
                            </select>
                        </div>

                        <div>
                            <button id="btnLoadAll" disabled>Tampilkan Semua</button>
                        </div>
                    </div>

                    <div id="jenisContainer"></div>

                    <div id="resultSummary">
                        <p class="text-center text-sm text-gray-400">Pilih kecamatan & desa untuk melihat ringkasan
                            produksi.</p>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: Peta Potensi -->
            <div class="card">
                <h2>🌾 Peta Potensi — Cari Desa Penghasil</h2>

                <div class="form-grid" style="grid-template-columns: 1fr 1fr 220px;">
                    <div class="form-group">
                        <label>Jenis Potensi</label>
                        <select id="selectJenis">
                            <option value="">-- Pilih Jenis Potensi --</option>
                            <option value="persawahan">Pertanian / Persawahan</option>
                            <option value="perkebunan">Perkebunan</option>
                            <option value="livestocks">Peternakan</option>
                            <option value="tambak">Tambak</option>
                            <option value="komoditas_lain">Komoditas Lain</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Komoditas</label>
                        <select id="selectKomoditas" disabled>
                            <option value="">-- Pilih Komoditas --</option>
                        </select>
                    </div>

                    <div>
                        <button id="btnLoadAreas" disabled>Tampilkan Desa</button>
                    </div>
                </div>

                <div id="resultAreas">
                    <p class="text-center text-sm text-gray-400">Pilih jenis potensi dan komoditas untuk melihat daftar
                        desa penghasil.</p>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {
                const $ = id => document.getElementById(id);

                const selectKec = $('selectKec');
                const selectDesa = $('selectDesa');
                const btnLoadAll = $('btnLoadAll');
                const jenisContainer = $('jenisContainer');
                const resultSummary = $('resultSummary');

                const selectJenis = $('selectJenis');
                const selectKomoditas = $('selectKomoditas');
                const btnLoadAreas = $('btnLoadAreas');
                const resultAreas = $('resultAreas');

                // Helper: Safe fetch
                const safeFetchJson = async (url) => {
                    try {
                        const res = await fetch(url, {
                            credentials: 'same-origin'
                        });
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        return await res.json();
                    } catch (e) {
                        console.error("Fetch error:", url, e);
                        return [];
                    }
                };

                // Helper: Escape HTML
                const escapeHtml = (s) => String(s).replace(/[&<>"'\/]/g, c => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '/': '&#x2F;'
                })[c]);

                // Helper: Format number
                const numID = v => (v != null && !isNaN(v)) ? Number(v).toLocaleString('id-ID') : '0';
                const capital = s => s ? s.charAt(0).toUpperCase() + s.slice(1) : '';

                // Reset Functions
                const resetDesa = () => {
                    selectDesa.innerHTML = '<option value="">-- Pilih Desa --</option>';
                    selectDesa.disabled = true;
                };

                const resetJenis = () => {
                    jenisContainer.innerHTML = '';
                    btnLoadAll.disabled = true;
                };

                const resetSummary = () => {
                    resultSummary.innerHTML =
                        '<p class="text-center text-sm text-gray-400">Pilih desa untuk melihat ringkasan.</p>';
                };

                const resetKomoditas = () => {
                    selectKomoditas.innerHTML = '<option value="">-- Pilih Komoditas --</option>';
                    selectKomoditas.disabled = true;
                    btnLoadAreas.disabled = true;
                    resultAreas.innerHTML =
                        '<p class="text-center text-sm text-gray-400">Pilih jenis potensi dan komoditas.</p>';
                };

                // Di dalam change selectKec
                $(selectKec).on('change', async function() {
                    let kecId = this.value;
                    if (!kecId) return;

                    // Trim & pastikan string
                    kecId = String(kecId).trim();

                    resetDesa();
                    resetJenis();
                    resetSummary();
                    resetKomoditas();

                    const desas = await safeFetchJson(`/api/desas?kecamatan=${encodeURIComponent(kecId)}`);
                    if (!Array.isArray(desas) || desas.length === 0) {
                        selectDesa.innerHTML = '<option value="">(tidak ada desa)</option>';
                        return;
                    }

                    desas.forEach(d => {
                        const value = (d.id ?? d.nama ?? d.desa ?? d)?.toString().trim();
                        const label = (d.nama ?? d.nama_kelurahan ?? d.nama_desa ?? d.desa ??
                            value ?? '').toString().trim();
                        if (value) {
                            selectDesa.insertAdjacentHTML('beforeend',
                                `<option value="${escapeHtml(value)}">${escapeHtml(label)}</option>`
                                );
                        }
                    });
                    selectDesa.disabled = false;
                });

                // DESA CHANGE
                $(selectDesa).on('change', async function() {
                    resetJenis();
                    resetSummary();
                    resetKomoditas();
                    const kecId = selectKec.value;
                    const desa = this.value;
                    if (!desa) return;

                    const jenis = await safeFetchJson(
                        `/api/jenis-potensi?kecamatan=${encodeURIComponent(kecId)}&desa=${encodeURIComponent(desa)}`
                        );
                    if (!jenis || !jenis.length) {
                        jenisContainer.innerHTML =
                            '<div class="text-sm text-gray-500 mb-2">(Tidak ada jenis potensi)</div>';
                        btnLoadAll.onclick = () => loadProduksi(kecId, desa, '');
                        btnLoadAll.disabled = false;
                        return;
                    }

                    jenisContainer.innerHTML = '';
                    jenis.forEach(j => {
                        const label = capital(String(j).replace(/_/g, ' '));
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'jenis-btn';
                        btn.dataset.type = j;
                        btn.textContent = label;
                        btn.onclick = () => loadProduksi(kecId, desa, j);
                        jenisContainer.appendChild(btn);
                    });

                    btnLoadAll.onclick = () => loadProduksi(kecId, desa, '');
                    btnLoadAll.disabled = false;
                });

                // LOAD PRODUKSI
                async function loadProduksi(kecId, desa, type) {
                    resultSummary.innerHTML = '<p class="text-center text-sm text-gray-400">Memuat...</p>';
                    const params = new URLSearchParams({
                        kecamatan: kecId,
                        desa
                    });
                    if (type) params.append('type', type);
                    const data = await safeFetchJson(`/api/produksi?${params.toString()}`);
                    renderSummary(data);
                }

                function renderSummary(data) {
                    if (!data || !data.length) {
                        resultSummary.innerHTML = '<p class="text-center text-sm text-gray-400">Tidak ada data.</p>';
                        return;
                    }
                    resultSummary.innerHTML = data.map(group => `
                        <h3 class="text-lg font-semibold text-blue-700 mt-3 capitalize">${escapeHtml((group.type || 'Umum').replace(/_/g,' '))}</h3>
                        <div class="ml-3">
                            ${(group.crops || []).map(c => `<p><strong>${escapeHtml(c.nama ?? '')}</strong> — ${numID(c.luas)} ha — ${numID(c.produksi)} ton${c.catatan ? ' | '+escapeHtml(c.catatan) : ''}</p>`).join('')}
                            ${(group.livestocks || []).map(l => `<p><strong>${escapeHtml(l.nama ?? '')}</strong> — ${numID(l.jumlah)} ekor${l.produksi_note ? ' | '+escapeHtml(l.produksi_note) : ''}</p>`).join('')}
                        </div>
                    `).join('');
                }

                // JENIS CHANGE → KOMODITAS
                $(selectJenis).on('change', async function() {
                    resetKomoditas();
                    const jenis = this.value;
                    if (!jenis) return;

                    const kom = await safeFetchJson(`/api/komoditas?jenis=${encodeURIComponent(jenis)}`);
                    kom.forEach(k => {
                        const nama = typeof k === 'string' ? k : (k.nama ?? k);
                        selectKomoditas.insertAdjacentHTML('beforeend',
                            `<option value="${escapeHtml(nama)}">${escapeHtml(nama)}</option>`);
                    });
                    selectKomoditas.disabled = false;
                    btnLoadAreas.disabled = false;
                });

                // LOAD AREAS
                $(btnLoadAreas).on('click', async function() {
                    const jenis = selectJenis.value;
                    const komoditas = selectKomoditas.value;
                    const kecamatan = selectKec.value || '';

                    if (!komoditas) return alert('Pilih komoditas terlebih dahulu');

                    const jenisMap = {
                        'livestocks': 'peternakan',
                        'peternakan': 'peternakan'
                    };
                    const jenisNorm = jenisMap[jenis] || jenis;

                    resultAreas.innerHTML = '<p class="text-center text-sm text-gray-400">Memuat...</p>';
                    const url =
                        `/api/desa-by-komoditas?jenis=${encodeURIComponent(jenisNorm)}&komoditas=${encodeURIComponent(komoditas)}&kecamatan=${encodeURIComponent(kecamatan)}`;
                    const data = await safeFetchJson(url);
                    renderAreas(data, jenisNorm, komoditas);
                });

                function renderAreas(data, jenis, komoditas) {
                    if (!data || !data.length) {
                        resultAreas.innerHTML =
                            `<p class="text-center text-sm text-gray-400">Belum ada desa penghasil ${escapeHtml(komoditas)}.</p>`;
                        return;
                    }

                    resultAreas.innerHTML = `
                        <h3 class="text-lg font-semibold text-blue-700 mb-4 text-center">Daftar Desa Penghasil ${escapeHtml(komoditas)}</h3>
                        ${data.map(d => {
                            let info = '';
                            if (jenis === 'peternakan') {
                                info = `Jumlah: ${numID(d.jumlah_sum)} ekor`;
                            } else if (jenis === 'tambak') {
                                info = `
                    Luas: $ {
                        numID(d.luas ?? d.jumlah_sum)
                    }
                    ha$ {
                        d.produksi ? ' | Produksi: ' + numID(d.produksi) + ' ton' : ''
                    }
                    `;
                            } else {
                                info = `
                    Luas: $ {
                        numID(d.luas ?? d.jumlah_sum)
                    }
                    ha | Produksi: $ {
                        numID(d.produksi ?? d.produksi_note)
                    }
                    ton`;
                            }
                            return ` <
                    div class = "border-b border-gray-300 pb-2 mb-2" >
                    <
                    p class = "font-medium" > $ {
                        escapeHtml(d.desa)
                    }, < span class = "text-sm text-gray-500" > $ {
                        escapeHtml(d.kecamatan)
                    } < /span></p >
                    <
                    p class = "text-sm ml-2" > $ {
                        info
                    } < /p>
                    $ {
                        d.catatan ? `<p class="text-xs text-gray-400 ml-2">${escapeHtml(d.catatan)}</p>` : ''
                    } <
                    /div>
                    `;
                        }).join('')}
                    `;
                }
            });
        </script>
    @endpush
</x-layout.terminal>
