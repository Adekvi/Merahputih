<x-app-layout>
    <x-slot:title>
        Statistik Potensi
    </x-slot:title>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Statistik') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">

        {{-- BAGIAN 1: RINGKASAN PRODUKSI --}}
        <div
            class="max-w-5xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg transition-colors duration-300">
            <h1 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-gray-100">
                📊 Statistik Produksi Desa
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan</label>
                    <select id="selectKec"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach ($kecamatans as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Desa</label>
                    <select id="selectDesa"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100"
                        disabled>
                        <option value="">-- Pilih Desa --</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button id="btnLoadAll"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50 transition"
                        disabled>
                        Tampilkan Semua
                    </button>
                </div>
            </div>

            <div id="jenisContainer" class="mb-6 flex flex-wrap gap-2 justify-center"></div>

            <div id="resultSummary"
                class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-8 text-gray-800 dark:text-gray-100 transition">
                <p class="text-sm text-center text-gray-500 dark:text-gray-400">
                    Pilih kecamatan & desa untuk melihat ringkasan produksi.
                </p>
            </div>
        </div>

        {{-- BAGIAN 2: PETA POTENSI (MANDIRI) --}}
        <div class="max-w-6xl mx-auto mt-6 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg transition">
            <h2 class="text-xl font-semibold mb-4 text-center text-gray-800 dark:text-gray-100">
                🌾 Peta Potensi — Cari Desa Penghasil
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Potensi</label>
                    <select id="selectJenis"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                        <option value="">-- Pilih Jenis Potensi --</option>
                        <option value="persawahan">Pertanian / Persawahan</option>
                        <option value="perkebunan">Perkebunan</option>
                        <option value="peternakan">Peternakan</option>
                        <option value="tambak">Tambak</option>
                        <option value="komoditas_lain">Komoditas Lain</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Komoditas</label>
                    <select id="selectKomoditas"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100"
                        disabled>
                        <option value="">-- Pilih Komoditas --</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button id="btnLoadAreas"
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded disabled:opacity-50 transition"
                        disabled>
                        Tampilkan Desa
                    </button>
                </div>
            </div>

            <div id="resultAreas"
                class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-100 transition">
                <p class="text-sm text-center text-gray-500 dark:text-gray-400">
                    Pilih jenis potensi dan komoditas untuk melihat daftar desa penghasil.
                </p>
            </div>
        </div>


    </div>
</x-app-layout>

{{-- <x-layout.terminal title="Statistik">

    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Statistik</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-title">
                    Statistik Produksi Desa
                </div>
                <div class="card">
                    <div class="card-body">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan</label>
                            <select id="selectKec"
                                class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatans as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Desa</label>
                            <select id="selectDesa"
                                class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100"
                                disabled>
                                <option value="">-- Pilih Desa --</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button id="btnLoadAll"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50 transition"
                                disabled>
                                Tampilkan Semua
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-title">
                    Peta Potensi — Cari Desa Penghasil
                </div>
                <div class="card">
                    <div class="card-body">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Potensi</label>
                        <select id="selectJenis"
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                            <option value="">-- Pilih Jenis Potensi --</option>
                            <option value="persawahan">Pertanian / Persawahan</option>
                            <option value="perkebunan">Perkebunan</option>
                            <option value="peternakan">Peternakan</option>
                            <option value="tambak">Tambak</option>
                            <option value="komoditas_lain">Komoditas Lain</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Komoditas</label>
                        <select id="selectKomoditas"
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100"
                            disabled>
                            <option value="">-- Pilih Komoditas --</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button id="btnLoadAreas"
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded disabled:opacity-50 transition"
                            disabled>
                            Tampilkan Desa
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="resultAreas"
            class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-100 transition">
            <p class="text-sm text-center text-gray-500 dark:text-gray-400">
                Pilih jenis potensi dan komoditas untuk melihat daftar desa penghasil.
            </p>
        </div>
    </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Elemen
                const selectKec = document.getElementById('selectKec');
                const selectDesa = document.getElementById('selectDesa');
                const btnLoadAll = document.getElementById('btnLoadAll');
                const jenisContainer = document.getElementById('jenisContainer');
                const resultSummary = document.getElementById('resultSummary');

                const selectJenis = document.getElementById('selectJenis');
                const selectKomoditas = document.getElementById('selectKomoditas');
                const btnLoadAreas = document.getElementById('btnLoadAreas');
                const resultAreas = document.getElementById('resultAreas');

                // Helpers
                function formatNumberLocale(v) {
                    if (v === null || v === undefined || v === '') return '0';
                    const n = Number(v);
                    if (isNaN(n)) return String(v);
                    return n.toLocaleString('id-ID');
                }

                function capitalize(s) {
                    return s ? s.charAt(0).toUpperCase() + s.slice(1) : s;
                }

                async function safeFetchJson(url, opts = {}) {
                    try {
                        const res = await fetch(url, opts);
                        if (!res.ok) {
                            console.error('Fetch error', url, res.status);
                            return null;
                        }
                        const json = await res.json();
                        return json;
                    } catch (err) {
                        console.error('Fetch failed', url, err);
                        return null;
                    }
                }

                function escapeHtml(str) {
                    if (str === null || str === undefined) return '';
                    return String(str)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                /* ---------------- BAGIAN 1 ---------------- */
                if (selectKec) {
                    selectKec.addEventListener('change', async function() {
                        const kec = this.value;
                        // reset desa
                        if (!selectDesa) return;
                        selectDesa.innerHTML = '<option value="">-- Pilih Desa --</option>';
                        selectDesa.disabled = true;
                        if (btnLoadAll) btnLoadAll.disabled = true;
                        jenisContainer.innerHTML = '';

                        if (!kec) return;

                        const url = `/api/desas?kecamatan=${encodeURIComponent(kec)}`;
                        const desas = await safeFetchJson(url) || [];

                        // Support response array of strings OR array of objects {desa: '...'} or {name:'...'}
                        selectDesa.innerHTML = '<option value="">-- Pilih Desa --</option>';
                        desas.forEach(d => {
                            let val, label;
                            if (typeof d === 'string') {
                                val = label = d;
                            } else if (d && d.desa) {
                                val = label = d.desa;
                            } else if (d && d.name) {
                                val = label = d.name;
                            } else {
                                return;
                            }
                            const opt = document.createElement('option');
                            opt.value = val;
                            opt.textContent = label;
                            selectDesa.appendChild(opt);
                        });

                        selectDesa.disabled = false;
                        if (btnLoadAll) btnLoadAll.disabled = false;
                    });
                }

                if (selectDesa) {
                    selectDesa.addEventListener('change', async function() {
                        const kec = selectKec ? selectKec.value : '';
                        const desa = this.value;
                        jenisContainer.innerHTML = '';
                        if (!desa) {
                            if (btnLoadAll) btnLoadAll.onclick = null;
                            return;
                        }

                        const url =
                            `/api/jenis-potensi?kecamatan=${encodeURIComponent(kec)}&desa=${encodeURIComponent(desa)}`;
                        const jenis = await safeFetchJson(url) || [];

                        // expecting array of strings
                        jenisContainer.innerHTML = '';
                        if (!Array.isArray(jenis) || jenis.length === 0) {
                            jenisContainer.innerHTML =
                                '<div class="text-sm text-gray-500">Tidak ada jenis potensi terdaftar.</div>';
                            if (btnLoadAll) btnLoadAll.onclick = () => loadProduksi(kec, desa, '');
                            return;
                        }

                        jenis.forEach(j => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className =
                                'px-3 py-1 bg-gray-200 dark:bg-gray-600 rounded-lg text-gray-800 dark:text-gray-100 hover:bg-blue-500 hover:text-white transition mr-2 mb-2';
                            btn.textContent = capitalize(String(j).replace(/_/g, ' '));
                            btn.addEventListener('click', () => loadProduksi(kec, desa, j));
                            jenisContainer.appendChild(btn);
                        });

                        if (btnLoadAll) btnLoadAll.onclick = () => loadProduksi(kec, desa, '');
                    });
                }

                async function loadProduksi(kec, desa, type) {
                    if (!resultSummary) return;
                    resultSummary.innerHTML = '<p class="text-center text-gray-400">Memuat data...</p>';
                    const params = new URLSearchParams({
                        kecamatan: kec,
                        desa: desa
                    });
                    if (type) params.append('type', type);
                    const url = `/api/produksi?${params.toString()}`;
                    const data = await safeFetchJson(url) || [];
                    renderSummary(Array.isArray(data) ? data : []);
                }

                function renderSummary(data) {
                    if (!resultSummary) return;
                    resultSummary.innerHTML = '';

                    if (!Array.isArray(data) || data.length === 0) {
                        resultSummary.innerHTML = '<p class="text-center text-gray-400">Tidak ada data.</p>';
                        return;
                    }

                    // data grouped by type: [{ type: 'persawahan', crops: [...], livestocks: [...] }, ...]
                    data.forEach(group => {
                        const typeLabel = group.type ? capitalize(String(group.type).replace(/_/g, ' ')) :
                            'Umum';
                        const title = document.createElement('h3');
                        title.className =
                            'text-lg font-semibold text-blue-700 dark:text-blue-400 mt-3 capitalize';
                        title.textContent = typeLabel;
                        resultSummary.appendChild(title);

                        const container = document.createElement('div');
                        container.className = 'ml-3';

                        if (Array.isArray(group.crops) && group.crops.length) {
                            group.crops.forEach(c => {
                                const p = document.createElement('p');
                                p.innerHTML =
                                    `<strong>${escapeHtml(c.nama ?? c.nama_tanaman ?? '')}</strong>` +
                                    ` — ${formatNumberLocale(c.luas ?? c.luas_hektare)} ha` +
                                    ` — ${formatNumberLocale(c.produksi ?? c.produksi_ton)} ton` +
                                    (c.catatan ? ` ${escapeHtml('(' + c.catatan + ')')}` : '');
                                container.appendChild(p);
                            });
                        }

                        if (Array.isArray(group.livestocks) && group.livestocks.length) {
                            group.livestocks.forEach(l => {
                                const p = document.createElement('p');
                                p.innerHTML =
                                    `<strong>${escapeHtml(l.nama ?? l.jenis_ternak ?? '')}</strong>` +
                                    ` — ${formatNumberLocale(l.jumlah)} ekor` +
                                    (l.produksi_note || l.produksi ?
                                        ` ${escapeHtml('(' + (l.produksi_note ?? l.produksi) + ')')}` :
                                        '');
                                container.appendChild(p);
                            });
                        }

                        resultSummary.appendChild(container);
                    });
                }

                /* ---------------- BAGIAN 2 (MANDIRI) ---------------- */
                if (selectJenis) {
                    selectJenis.addEventListener('change', async function() {
                        const jenis = this.value;
                        if (selectKomoditas) {
                            selectKomoditas.innerHTML = '<option>Loading...</option>';
                            selectKomoditas.disabled = true;
                        }
                        if (btnLoadAreas) btnLoadAreas.disabled = true;
                        if (!jenis) {
                            if (selectKomoditas) {
                                selectKomoditas.innerHTML =
                                    '<option value="">-- Pilih Komoditas --</option>';
                                selectKomoditas.disabled = true;
                            }
                            return;
                        }

                        const url = `/api/komoditas?jenis=${encodeURIComponent(jenis)}`;
                        const data = await safeFetchJson(url) || [];

                        if (!selectKomoditas) return;
                        selectKomoditas.innerHTML = '<option value="">-- Pilih Komoditas --</option>';
                        (Array.isArray(data) ? data : []).forEach(k => {
                            const opt = document.createElement('option');
                            opt.value = (typeof k === 'string' ? k : (k.name ?? k.komoditas ?? ''));
                            opt.textContent = (typeof k === 'string' ? k : (k.name ?? k.komoditas ??
                                ''));
                            selectKomoditas.appendChild(opt);
                        });
                        selectKomoditas.disabled = false;
                    });
                }

                if (selectKomoditas) {
                    selectKomoditas.addEventListener('change', () => {
                        if (btnLoadAreas) btnLoadAreas.disabled = !selectKomoditas.value;
                    });
                }

                if (btnLoadAreas) {
                    btnLoadAreas.addEventListener('click', async () => {
                        const jenis = selectJenis ? selectJenis.value : '';
                        const komoditas = selectKomoditas ? selectKomoditas.value : '';
                        if (!komoditas) return;

                        if (resultAreas) resultAreas.innerHTML =
                            '<p class="text-center text-gray-400">Memuat data...</p>';
                        const url =
                            `/api/desa-by-komoditas?jenis=${encodeURIComponent(jenis)}&komoditas=${encodeURIComponent(komoditas)}`;
                        const data = await safeFetchJson(url) || [];
                        renderAreas(Array.isArray(data) ? data : [], jenis, komoditas);
                    });
                }

                function renderAreas(data, jenis, komoditas) {
                    if (!resultAreas) return;
                    resultAreas.innerHTML = '';
                    if (!Array.isArray(data) || data.length === 0) {
                        resultAreas.innerHTML =
                            `<p class="text-center text-gray-400">Belum ada desa penghasil ${escapeHtml(komoditas)} (${escapeHtml(jenis)}).</p>`;
                        return;
                    }

                    const h = document.createElement('h3');
                    h.className = 'text-lg font-semibold text-blue-700 dark:text-blue-400 mb-4 text-center';
                    h.textContent = `Daftar Desa Penghasil ${komoditas}`;
                    resultAreas.appendChild(h);

                    data.forEach(d => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'border-b border-gray-300 dark:border-gray-600 pb-2 mb-2';
                        const title = document.createElement('p');
                        title.className = 'font-medium';
                        title.innerHTML =
                            `${escapeHtml(d.desa ?? d.name ?? '')}, <span class="text-sm text-gray-500">${escapeHtml(d.kecamatan ?? '')}</span>`;
                        wrapper.appendChild(title);

                        const detail = document.createElement('p');
                        detail.className = 'text-sm ml-2';
                        if (jenis === 'peternakan' || jenis === 'livestocks') {
                            detail.textContent =
                                `Jumlah: ${formatNumberLocale(d.jumlah)} ekor${d.produksi_note ? ' | ' + d.produksi_note : ''}`;
                        } else {
                            detail.textContent =
                                `Luas: ${formatNumberLocale(d.luas)} ha | Produksi: ${formatNumberLocale(d.produksi)} ton`;
                        }
                        wrapper.appendChild(detail);

                        if (d.catatan) {
                            const note = document.createElement('p');
                            note.className = 'text-xs text-gray-400 ml-2';
                            note.textContent = d.catatan;
                            wrapper.appendChild(note);
                        }

                        resultAreas.appendChild(wrapper);
                    });
                }

            }); // DOMContentLoaded
        </script>
    @endpush

</x-layout.terminal> --}}
