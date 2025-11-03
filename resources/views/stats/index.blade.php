<x-layout.terminal title="Stats">
@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    <style>
    /* ==============================================
       LAYOUT & CARD
       ============================================== */
    .container-fluid{padding:1rem 2rem;max-width:1200px}
    .card{background:#fff;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,0.05);border:1px solid #f3f4f6;padding:1.25rem;margin-bottom:1.25rem}
    h1,h2{font-weight:700;color:#1f2937;text-align:left}

    /* FORM GRID */
    .form-grid{display:grid;grid-template-columns:1fr 1fr 220px;gap:1rem;align-items:end;margin-bottom:1rem}
    @media(max-width:767px){.form-grid{grid-template-columns:1fr}}
    label{font-weight:500;color:#374151;margin-bottom:.4rem;font-size:.9rem}
    select{border:1px solid #d1d5db;border-radius:8px;padding:.5rem .75rem;width:100%;background:#fff;font-size:.95rem;color:#111827}
    button{border:none;border-radius:8px;padding:.6rem 1rem;font-weight:600;cursor:pointer;transition:all .15s ease;color:#fff}

    #btnLoadAll{background:#10b981}
    #btnLoadAll:hover{background:#059669;transform:translateY(-2px)}
    #btnLoadAreas{background:#3b82f6}
    #btnLoadAreas:hover{background:#2563eb;transform:translateY(-2px)}
    button:disabled{opacity:.6;cursor:not-allowed}

    /* hasil */
    #resultSummary,#resultAreas{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:1rem;min-height:90px;color:#111827}
    .text-center{text-align:center}

    /* peta */
    #map{width:100%;height:480px;border-radius:10px;margin-top:8px}

    /* table ringkasan */
    .potensi-table{width:100%;border-collapse:collapse}
    .potensi-table th,.potensi-table td{padding:10px;border:1px solid #e5e7eb;vertical-align:top}
    .potensi-table thead th{background:#f3f4f6;color:#111827;text-align:left}
    </style>
@endpush

<div class="page-inner ecommerce-page">
  <div class="container-fluid">
    <!-- Statistik Produksi Desa (Potensi) -->
    <div class="card">
      <h1>📊 Potensi — Statistik Produksi Desa & Pertanian</h1>

      <div class="card-body">
        <div class="form-grid">
          <div>
            <label>Kecamatan</label>
            <select id="selectKec">
              <option value="">-- Pilih Kecamatan --</option>
              @foreach ($kecamatans as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label>Desa</label>
            <select id="selectDesa" disabled>
              <option value="">-- Pilih Desa --</option>
            </select>
          </div>

          <div>
            <label>&nbsp;</label>
            <div style="display:flex;gap:.5rem">
              <button id="btnLoadAll" disabled>Tampilkan Semua</button>
            </div>
          </div>
        </div>

        <div id="jenisContainer" style="margin-bottom:12px"></div>

        <div id="resultSummary">
          <p class="text-center" style="color:#9ca3af">Pilih kecamatan & desa untuk melihat potensi produksi.</p>
        </div>
      </div>
    </div>

    <!-- Peta Potensi -->
    <div class="card">
      <h2>🌾 Peta Potensi — Cari Desa Penghasil</h2>

      <div class="form-grid" style="grid-template-columns: 1fr 1fr 220px;">
        <div>
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

        <div>
          <label>Komoditas</label>
          <select id="selectKomoditasMap" disabled>
            <option value="">-- Pilih Komoditas --</option>
          </select>
        </div>

        <div>
          <label>&nbsp;</label>
          <div style="display:flex;gap:.5rem">
            <button id="btnLoadAreas" disabled>Tampilkan Desa</button>
          </div>
        </div>
      </div>

      <div id="map"></div>
      <div id="resultAreas" style="margin-top:12px">
        <p class="text-center" style="color:#9ca3af">Pilih jenis potensi dan komoditas untuk melihat daftar desa penghasil.</p>
      </div>
    </div>
  </div>
</div>

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function(){

  // helpers
  const get = id => document.getElementById(id);
  const escapeHtml = s => String(s ?? '').replace(/[&<>"'\/]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;','/':'&#x2F;'}[c]));
  const numID = v => (v != null && !isNaN(v)) ? Number(v).toLocaleString('id-ID') : '0';
  const safeFetchJson = async (url) => {
    try {
      const r = await fetch(url, { credentials: 'same-origin', headers: {'Accept':'application/json'} });
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return await r.json();
    } catch(e) {
      console.error('Fetch error', url, e);
      return null;
    }
  };

  // elements
  const selectKec = get('selectKec');
  const selectDesa = get('selectDesa');
  const btnLoadAll = get('btnLoadAll');
  const jenisContainer = get('jenisContainer');
  const resultSummary = get('resultSummary');

  const selectJenis = get('selectJenis');
  const selectKomoditasMap = get('selectKomoditasMap');
  const btnLoadAreas = get('btnLoadAreas');
  const resultAreas = get('resultAreas');

  // MAP init
  let map = L.map('map').setView([-6.801,111.034], 11);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{ attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
  let markerCluster = L.markerClusterGroup({
    iconCreateFunction: function(cluster){
      const c = cluster.getChildCount();
      const size = 30 + Math.round(Math.log(c+1) * 10);
      return L.divIcon({
        html: `<div style="background:rgba(31,99,255,0.85);color:#fff;font-weight:700;border-radius:50%;width:${size}px;height:${size}px;display:flex;align-items:center;justify-content:center;border:2px solid #fff">${c}</div>`,
        className: 'marker-cluster-custom',
        iconSize: null
      });
    }
  });
  let desaLayer = L.layerGroup().addTo(map);

  function potensiColor(key){
    key = (key||'').toLowerCase();
    if(key.includes('sawah') || key.includes('persawahan')) return '#16a34a';
    if(key.includes('perkebunan')) return '#059669';
    if(key.includes('ternak') || key.includes('peternakan') || key.includes('livestocks')) return '#f59e0b';
    if(key.includes('tambak') || key.includes('perikanan')) return '#0ea5e9';
    return '#6b7280';
  }

  // -----------------------------
  // kecamatan -> load desa
  // -----------------------------
  if (selectKec) {
    selectKec.addEventListener('change', async function(){
      const kecId = this.value;
      selectDesa.innerHTML = '<option>Memuat...</option>';
      selectDesa.disabled = true;
      jenisContainer.innerHTML = '';
      resultSummary.innerHTML = '<p class="text-center" style="color:#9ca3af">Pilih desa untuk melihat ringkasan.</p>';
      btnLoadAll.disabled = true;
      if (!kecId) {
        selectDesa.innerHTML = '<option value="">-- Pilih Desa --</option>';
        selectDesa.disabled = true;
        return;
      }
      const res = await safeFetchJson(`/api/desas?kecamatan=${encodeURIComponent(kecId)}`);
      if (!res) { selectDesa.innerHTML = '<option value="">(gagal memuat)</option>'; return; }
      const list = Array.isArray(res) ? res : (Array.isArray(res.data) ? res.data : []);
      selectDesa.innerHTML = '<option value="">-- Pilih Desa --</option>';
      list.forEach(d => {
        const val = String(d.id ?? d.value ?? d.nama ?? d.desa ?? '').trim();
        const label = String(d.nama ?? d.nama_kelurahan ?? d.nama_desa ?? d.desa ?? val).trim();
        if(val) selectDesa.insertAdjacentHTML('beforeend', `<option value="${escapeHtml(val)}">${escapeHtml(label)}</option>`);
      });
      selectDesa.disabled = false;
    });
  }

  // -----------------------------
  // desa -> load jenis potensi (tombol)
  // -----------------------------
  if (selectDesa) {
    selectDesa.addEventListener('change', async function(){
      jenisContainer.innerHTML = '';
      resultSummary.innerHTML = '<p class="text-center" style="color:#9ca3af">Pilih jenis potensi.</p>';
      btnLoadAll.disabled = true;
      const desaVal = this.value;
      const kecId = selectKec ? selectKec.value : '';
      if (!desaVal) return;
      const res = await safeFetchJson(`/api/jenis-potensi?kecamatan=${encodeURIComponent(kecId)}&desa=${encodeURIComponent(desaVal)}`);
      if (!res) { jenisContainer.innerHTML = '<div style="color:#9ca3af">Gagal memuat jenis potensi.</div>'; return; }
      const list = Array.isArray(res) ? res : (Array.isArray(res.data) ? res.data : []);
      if (!list.length) {
        jenisContainer.innerHTML = '<div style="color:#9ca3af">(Tidak ada jenis potensi)</div>';
        btnLoadAll.disabled = false;
        btnLoadAll.onclick = () => loadProduksi(kecId, desaVal, '');
        return;
      }
      list.forEach(j => {
        const label = String(j).replace(/_/g,' ');
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'jenis-btn';
        btn.dataset.type = j;
        btn.style.margin = '6px 6px 6px 0';
        btn.style.padding = '8px 12px';
        btn.style.background = '#16a34a';
        btn.style.color = '#fff';
        btn.innerText = label.charAt(0).toUpperCase()+label.slice(1);
        btn.addEventListener('click', () => loadProduksi(kecId, desaVal, j));
        jenisContainer.appendChild(btn);
      });
      btnLoadAll.disabled = false;
      btnLoadAll.onclick = () => loadProduksi(kecId, desaVal, '');
    });
  }

  // -----------------------------
  // loadProduksi -> fetch /api/produksi
  // -----------------------------
  async function loadProduksi(kecId, desaValue, type) {
    resultSummary.innerHTML = '<p class="text-center" style="color:#9ca3af">Memuat...</p>';
    const params = new URLSearchParams({ kecamatan: kecId, desa: desaValue });
    if (type) params.append('type', type);
    const res = await safeFetchJson(`/api/produksi?${params.toString()}`);
    if (!res) { resultSummary.innerHTML = '<p class="text-center" style="color:#9ca3af">Gagal memuat data.</p>'; return; }
    const data = Array.isArray(res) ? res : (Array.isArray(res.data) ? res.data : []);
    renderSummary(data);
  }

  // -----------------------------
  // renderSummary: lengkap tabel Potensi
  // -----------------------------
  function renderSummary(data) {
    if (!data || !data.length) {
      resultSummary.innerHTML = '<p class="text-center" style="color:#9ca3af">Tidak ada data.</p>';
      return;
    }

    // Detect if data is grouped-by-type for ONE desa (common) or multiple desa rows
    const looksLikePerDesaRows = data.every(d => (d.desa || d.nama_kelurahan || d.nama_desa || d.nama_kecamatan || d.kecamatan));
    const rowsByDesa = {};
    const kecLabelSelected = selectKec ? (selectKec.options[selectKec.selectedIndex]?.text || '') : '';
    const desaLabelSelected = selectDesa ? (selectDesa.options[selectDesa.selectedIndex]?.text || '') : '';

    if (looksLikePerDesaRows) {
      data.forEach(d => {
        const kec = (d.kecamatan ?? d.nama_kecamatan ?? '') + '';
        const desa = (d.desa ?? d.nama_kelurahan ?? d.nama_desa ?? '') + '';
        const key = `${kec}||${desa}`.trim() || `__${Math.random()}`;
        if (!rowsByDesa[key]) rowsByDesa[key] = { kecamatan: kec||kecLabelSelected, desa: desa||desaLabelSelected, jumlah_penduduk: d.jumlah_penduduk ?? '-', potensi: {} };

        // determine potensi type
        const typeGuess = ( () => {
          if (d.jumlah_sum !== undefined || d.jumlah !== undefined) return 'Peternakan';
          if (d.luas !== undefined || d.produksi !== undefined) return 'Persawahan/Perkebunan/Tambak';
          return 'Lain';
        })();

        if (!rowsByDesa[key].potensi[typeGuess]) rowsByDesa[key].potensi[typeGuess] = { crops: [], livestocks: [] };

        if (d.produksi !== undefined || d.luas !== undefined) {
          rowsByDesa[key].potensi[typeGuess].crops.push({
            nama: d.nama_tanaman ?? d.nama ?? d.komoditas ?? '-',
            luas: d.luas ?? null,
            produksi: d.produksi ?? null,
            catatan: d.catatan ?? null
          });
        }
        if (d.jumlah_sum !== undefined || d.jumlah !== undefined) {
          rowsByDesa[key].potensi[typeGuess].livestocks.push({
            nama: d.jenis_ternak ?? d.nama ?? '-',
            jumlah: d.jumlah_sum ?? d.jumlah ?? 0,
            produksi_note: d.produksi_note ?? d.produksi ?? null
          });
        }
      });
    } else {
      // grouped by type for single desa
      const key = `${kecLabelSelected}||${desaLabelSelected}`.trim() || '__single';
      rowsByDesa[key] = { kecamatan: kecLabelSelected, desa: desaLabelSelected, jumlah_penduduk: '-', potensi: {} };
      data.forEach(group => {
        const type = (group.type ?? 'Umum') + '';
        if (!rowsByDesa[key].potensi[type]) rowsByDesa[key].potensi[type] = { crops: [], livestocks: [] };
        (group.crops || []).forEach(c => rowsByDesa[key].potensi[type].crops.push({
          nama: c.nama ?? '-', luas: c.luas ?? null, produksi: c.produksi ?? null, catatan: c.catatan ?? null
        }));
        (group.livestocks || []).forEach(l => rowsByDesa[key].potensi[type].livestocks.push({
          nama: l.nama ?? '-', jumlah: l.jumlah ?? 0, produksi_note: l.produksi_note ?? null
        }));
      });
    }

    // Build table HTML
    let html = `<div style="overflow:auto"><table class="potensi-table"><thead><tr>
      <th style="width:40px">No</th>
      <th style="width:200px">Kecamatan</th>
      <th style="width:200px">Desa</th>
      <th style="width:140px;text-align:right">Jumlah Penduduk</th>
      <th>Potensi Lahan</th>
    </tr></thead><tbody>`;

    let i = 1;
    for (const key in rowsByDesa) {
      const row = rowsByDesa[key];
      // potensi HTML
      let potHtml = '';
      for (const type in row.potensi) {
        const block = row.potensi[type];
        potHtml += `<div style="margin-bottom:8px"><strong>${escapeHtml(type)}</strong><ul style="margin:6px 0 6px 18px">`;
        (block.crops || []).forEach(c => {
          const prod = c.produksi !== null && c.produksi !== undefined ? ` — produksi: ${numID(c.produksi)}` : '';
          const luas = c.luas !== null && c.luas !== undefined ? ` — luas: ${numID(c.luas)} ha` : '';
          potHtml += `<li>${escapeHtml(c.nama)}${luas}${prod}${c.catatan ? ' — ' + escapeHtml(c.catatan) : ''}</li>`;
        });
        (block.livestocks || []).forEach(lv => {
          const jumlah = lv.jumlah !== null && lv.jumlah !== undefined ? ` — jumlah: ${numID(lv.jumlah)} ekor` : '';
          potHtml += `<li>${escapeHtml(lv.nama)}${jumlah}${lv.produksi_note ? ' — ' + escapeHtml(lv.produksi_note) : ''}</li>`;
        });
        potHtml += `</ul></div>`;
      }

      html += `<tr>
        <td style="vertical-align:top">${i}</td>
        <td style="vertical-align:top">${escapeHtml(row.kecamatan || '')}</td>
        <td style="vertical-align:top">${escapeHtml(row.desa || '')}</td>
        <td style="text-align:right;vertical-align:top">${row.jumlah_penduduk ? escapeHtml(String(row.jumlah_penduduk)) : '-'}</td>
        <td style="vertical-align:top">${potHtml || '-'}</td>
      </tr>`;
      i++;
    }

    html += `</tbody></table></div>`;
    resultSummary.innerHTML = html;
  }

  // -----------------------------
  // Peta: jenis -> load komoditas
  // -----------------------------
  if (selectJenis) {
    selectJenis.addEventListener('change', async function(){
      selectKomoditasMap.innerHTML = '<option>Memuat...</option>';
      selectKomoditasMap.disabled = true;
      btnLoadAreas.disabled = true;
      const jenis = this.value;
      if (!jenis) { selectKomoditasMap.innerHTML = '<option value="">-- Pilih Komoditas --</option>'; return; }
      const res = await safeFetchJson(`/api/komoditas?jenis=${encodeURIComponent(jenis)}`);
      if (!res) { selectKomoditasMap.innerHTML = '<option value="">(gagal)</option>'; return; }
      const list = Array.isArray(res) ? res : (Array.isArray(res.data) ? res.data : []);
      selectKomoditasMap.innerHTML = '<option value="">-- Pilih Komoditas --</option>';
      list.forEach(k => {
        const nama = typeof k === 'string' ? k : (k.nama ?? k);
        selectKomoditasMap.insertAdjacentHTML('beforeend', `<option value="${escapeHtml(nama)}">${escapeHtml(nama)}</option>`);
      });
      selectKomoditasMap.disabled = false;
      btnLoadAreas.disabled = false;
    });
  }

  // -----------------------------
  // Peta: tampilkan desa penghasil
  // -----------------------------
  if (btnLoadAreas) {
    btnLoadAreas.addEventListener('click', async function(){
      const jenis = selectJenis.value;
      const kom = selectKomoditasMap.value;
      const kec = selectKec.value || '';
      if (!kom) return alert('Pilih komoditas terlebih dahulu');

      resultAreas.innerHTML = '<p class="text-center" style="color:#9ca3af">Memuat...</p>';

      // minta GeoJSON bila backend mendukung
      const url = `/api/desa-by-komoditas?jenis=${encodeURIComponent(jenis)}&komoditas=${encodeURIComponent(kom)}&kecamatan=${encodeURIComponent(kec)}&geo=true`;
      const res = await safeFetchJson(url);
      if (!res) { resultAreas.innerHTML = '<p class="text-center">Gagal memuat daftar desa.</p>'; return; }

      let features = [];
      if (res.type === 'FeatureCollection' && Array.isArray(res.features)) features = res.features;
      else {
        const list = Array.isArray(res) ? res : (Array.isArray(res.data) ? res.data : []);
        features = list.map(item => {
          const lat = item.latitude ?? item.lat ?? item.center_lat ?? null;
          const lng = item.longitude ?? item.lng ?? item.center_lng ?? null;
          return { type:'Feature', geometry: lat && lng ? { type:'Point', coordinates:[Number(lng), Number(lat)] } : null, properties: item };
        }).filter(f => f.geometry);
      }

      markerCluster.clearLayers();
      desaLayer.clearLayers();

      if (!features.length) {
        resultAreas.innerHTML = `<p class="text-center">Belum ada desa penghasil ${escapeHtml(kom)}.</p>`;
        return;
      }

      const bounds = [];
      const listHtml = [`<h3 style="color:#1f6feb;text-align:center">Daftar Desa Penghasil ${escapeHtml(kom)}</h3>`];

      features.forEach(f => {
        const p = f.properties || {};
        const name = p.nama_kelurahan ?? p.desa ?? p.name ?? '-';
        const kecName = p.nama_kecamatan ?? p.kecamatan ?? '';
        const [lng, lat] = f.geometry.coordinates;
        const produksi = p.produksi ?? p.produksi_sum ?? p.jumlah_sum ?? p.luas ?? null;
        const popup = `<div style="min-width:200px"><strong>${escapeHtml(name)}</strong><br/><small>${escapeHtml(kecName)}</small><hr/>${produksi ? `<div><strong>Produksi:</strong> ${numID(produksi)}</div>` : ''}${p.catatan ? `<div style="font-size:.9rem">${escapeHtml(p.catatan)}</div>` : ''}</div>`;

        const radius = 14; // default besar
        const marker = L.circleMarker([lat, lng], {
          radius,
          fillColor: potensiColor(jenis),
          color: potensiColor(jenis),
          weight: 1,
          fillOpacity: 0.95
        }).bindPopup(popup);

        markerCluster.addLayer(marker);
        bounds.push([lat, lng]);

        listHtml.push(`<div style="padding:6px;border-bottom:1px solid #e5e7eb"><strong>${escapeHtml(name)}</strong><div style="font-size:.9rem;color:#6b7280">${escapeHtml(kecName)}${produksi ? ' — ' + numID(produksi) : ''}</div></div>`);
      });

      desaLayer.addLayer(markerCluster);
      try { if (bounds.length) map.fitBounds(bounds, { padding:[40,40] }); } catch(e){console.warn(e);}
      resultAreas.innerHTML = listHtml.join('');
    });
  }

  // initial state
  if (selectDesa) selectDesa.disabled = true;
  if (btnLoadAll) btnLoadAll.disabled = true;
  if (selectKomoditasMap) selectKomoditasMap.disabled = true;
  if (btnLoadAreas) btnLoadAreas.disabled = true;

}); // DOMContentLoaded
</script>
@endpush

</x-layout.terminal>
