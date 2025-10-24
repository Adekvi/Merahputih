@php
    /**
     * resources/views/surveys/voice_create.blade.php
     * Blade view untuk fitur "Percakapan Suara — Metadata & Data Sensus"
     * Menggunakan <x-app-layout> sebagai wrapper.
     */
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Percakapan Suara — Pendataan Potensi</h2>
    </x-slot>

    <div class="py-8">
      <div class="max-w-4xl mx-auto px-4">

        <div class="bg-white dark:bg-gray-800 dark:text-gray-100 rounded-3xl shadow-soft p-6 md:p-8 transition-colors duration-200">
          <div class="flex items-start justify-between gap-6">
            <div class="flex-1 pr-0 md:pr-6">
              <h1 class="text-2xl md:text-3xl font-semibold mb-2">Percakapan Suara — Metadata & Data Sensus <span class="ml-3 badge-ver">v1.2</span></h1>
              <p class="text-sm md:text-base text-gray-600 dark:text-gray-300 mb-4">Sistem akan menanyakan metadata (Kecamatan, Desa, Jumlah Penduduk) lewat suara. Setelah itu kamu dapat meninjau & mengedit seluruh data sebelum menyimpan.</p>

              <div class="flex flex-wrap items-center gap-3 mb-4">
                <button id="startVoiceBtn" class="btn-purple px-4 py-3 rounded-lg shadow hover:opacity-95 focus:outline-none flex items-center gap-2 btn-text-wrap">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v11"/></svg>
                  <span class="font-medium whitespace-normal">Mulai Isi Metadata Lewat Suara</span>
                </button>

                <button id="pauseVoiceBtn" class="btn-yellow px-4 py-3 rounded-lg shadow hover:opacity-95 focus:outline-none flex items-center gap-2 btn-text-wrap" disabled>
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6M14 9v6"/></svg>
                  <span class="font-medium whitespace-normal">Jeda</span>
                </button>

                <a id="btnOpenReview" href="#" class="btn-blue px-4 py-3 rounded-lg shadow hover:opacity-95 focus:outline-none flex items-center gap-2 btn-text-wrap">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20l9-5-9-5-9 5 9 5z"/></svg>
                  <span class="font-medium whitespace-normal">Tinjau & Edit</span>
                </a>

                <button id="themeToggle" class="ml-auto md:ml-0 px-3 py-2 rounded border dark:border-gray-700 text-sm text-gray-700 dark:text-gray-200 focus:outline-none">Mode: <span id="themeLabel" class="font-semibold ml-1">Light</span></button>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                <div class="md:col-span-2">
                  <div class="text-sm text-gray-700 dark:text-gray-200 mb-2">Status: <span id="statusLabel" class="font-semibold text-green-600 dark:text-green-400">siap</span></div>
                  <div class="status-box dark:status-box" id="statusBox">
                    <div id="statusText" class="text-gray-700 dark:text-gray-100 break-words whitespace-normal">Siap. Tekan <strong>Mulai</strong> untuk memulai sesi voice.</div>
                  </div>

                  <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Ringkasan (preview cepat)</h3>
                    <div class="p-3 border rounded bg-gray-50 dark:bg-gray-900 text-sm text-gray-700 dark:text-gray-200" id="quickPreview">
                      <div class="break-words"><strong>Kecamatan:</strong> <span id="pv_kecamatan">-</span></div>
                      <div class="break-words"><strong>Desa:</strong> <span id="pv_desa">-</span></div>
                      <div class="break-words"><strong>Jumlah Penduduk:</strong> <span id="pv_jumlah_penduduk">-</span></div>
                      <div class="mt-2 break-words"><strong>Potensi Lahan:</strong>
                        <div id="pv_parcels" class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-300">(belum ada)</div>
                      </div>
                    </div>
                  </div>

                </div>

                <div>
                  <div class="text-sm text-gray-700 dark:text-gray-200 mb-2">Aktivitas</div>
                  <div class="log-box" id="activityLog" aria-live="polite">
                    <div class="text-gray-500 dark:text-gray-400">Siap. Tekan Mulai untuk memulai sesi voice.</div>
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
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto p-6 mt-12 modal-scroll" style="-webkit-overflow-scrolling: touch;">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-semibold dark:text-gray-100">Tinjau & Edit Data Sensus</h2>
              <button id="closeReview" class="text-gray-600 dark:text-gray-300">✕</button>
            </div>

            <form id="modalSubmitForm" action="{{ route('surveys.store') }}" method="POST">
              @csrf
              <input type="hidden" id="parcels_json" name="parcels_json" value="[]" />
              <input type="hidden" name="_from_voice" value="1" />

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Kecamatan</label>
                  <input id="rev_kecamatan" name="kecamatan" class="mt-1 block w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700" required />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Desa</label>
                  <input id="rev_desa" name="desa" class="mt-1 block w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700" required />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jumlah Penduduk</label>
                  <input id="rev_jumlah_penduduk" name="jumlah_penduduk" type="number" class="mt-1 block w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700" min="0" />
                </div>
              </div>

              <hr class="my-3 border-gray-200 dark:border-gray-700">

              <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold dark:text-gray-100">Potensi Lahan (Parcels)</h3>
                <div class="flex items-center gap-2">
                  <button type="button" id="btnAddParcelModal" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">+ Tambah Lahan</button>
                </div>
              </div>

              <div id="modalParcelsContainer" class="space-y-4 mb-4">
                {{-- Parcels will be injected --}}
              </div>

              <div class="flex justify-end gap-3">
                <button type="button" id="revCancelBtn" class="px-4 py-2 rounded border dark:border-gray-700">Batal</button>
                <button type="submit" id="revSubmitBtn" class="px-4 py-2 rounded bg-green-600 text-white">Simpan ke Database</button>
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
                    <button type="button" class="btnAddCropModal text-sm px-2 py-1 bg-indigo-600 text-white rounded">+ Tambah</button>
                  </div>

                  <div class="mt-3 mb-2">Livestocks (isi bila relevan)</div>
                  <div class="livestocksContainer space-y-2"></div>
                  <div class="mt-2">
                    <button type="button" class="btnAddLivestockModal text-sm px-2 py-1 bg-red-600 text-white rounded">+ Tambah Ternak</button>
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
              <input type="number" step="0.001" min="0" class="mt-1 block w-full border rounded p-1 crop-luas">
            </div>
            <div>
              <label class="text-xs">Produksi (ton)</label>
              <input type="number" step="0.001" min="0" class="mt-1 block w-full border rounded p-1 crop-produksi">
            </div>
            <div>
              <label class="text-xs">Satuan</label>
              <input type="text" class="mt-1 block w-full border rounded p-1 crop-satuan" placeholder="ton/kg">
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
              <input type="text" class="mt-1 block w-full border rounded p-1 liv-produksi" placeholder="mis. telur ~1500/minggu">
            </div>
          </div>

          <div class="ml-3 pl-3 border-l">
            <button type="button" class="btnRemoveLivestockModal text-sm text-red-600">Hapus</button>
          </div>
        </div>
      </template>

      {{-- Include original JS (voice flow + modal handling) --}}
<script>
/* ===== THEME HANDLING (LIGHT / DARK toggle) ===== */
(function(){
  const themeToggle = document.getElementById('themeToggle');
  const themeLabel = document.getElementById('themeLabel');
  const htmlEl = document.documentElement;
  const bodyRoot = document.getElementById('bodyRoot');

  function applyTheme(isDark){
    if(isDark){
      htmlEl.classList.add('dark');
      themeLabel.textContent = 'Dark';
      bodyRoot.classList.add('bg-gray-900');
    } else {
      htmlEl.classList.remove('dark');
      themeLabel.textContent = 'Light';
      bodyRoot.classList.remove('bg-gray-900');
    }
    localStorage.setItem('voice_theme_dark', isDark ? '1' : '0');
  }

  const saved = localStorage.getItem('voice_theme_dark');
  if(saved === null){
    const prefers = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(prefers);
  } else {
    applyTheme(saved === '1');
  }

  if(themeToggle){
    themeToggle.addEventListener('click', ()=> {
      const isDark = htmlEl.classList.contains('dark');
      applyTheme(!isDark);
    });
  }
})();
</script>

<script>
(function(){
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
  function $id(id){ return document.getElementById(id); }
  function escapeHtml(s){ return (s||'').toString().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  // DOM refs
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
  const rev_kecamatan = $id('rev_kecamatan');
  const rev_desa = $id('rev_desa');
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
  let paused = false;

  // Pause/recognition state
  let currentPrompt = null;      // current prompt text
  let lastPromptPhase = null;    // semantic id for current prompt
  let retryCountForPrompt = 0;   // single retry
  let currentRecognition = null; // active recognition instance

  // logging helper
  function log(msg){
    const now = new Date();
    const t = now.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit',second:'2-digit'});
    const line = document.createElement('div');
    line.innerHTML = `<span style="color:#6b7280">[${t}]</span> ${escapeHtml(msg)}`;
    if(activityLog) activityLog.prepend(line);
    else console.debug('[voice] activityLog missing,', msg);
  }

  function setStatus(text, state='siap'){
    if(statusText) statusText.textContent = text;
    if(statusLabel) statusLabel.textContent = state;
    if(state === 'siap' && statusLabel) statusLabel.style.color = '#10b981';
    else if(state === 'proses' && statusLabel) statusLabel.style.color = '#f59e0b';
    else if(state === 'gagal' && statusLabel) statusLabel.style.color = '#ef4444';
  }

  function speak(text, onend){
    if(!synth){ if(onend) setTimeout(onend,200); return; }
    const u = new SpeechSynthesisUtterance(text);
    u.lang = 'id-ID';
    u.rate = 0.95;
    u.onend = ()=> onend && onend();
    try{ synth.speak(u); }catch(e){ if(onend) setTimeout(onend,200); }
  }

  // captureOnceWithPause:
  // - returns string transcript, or null (no result), or {aborted:true} if paused during the question
  function captureOnceWithPause(prompt, timeout = 9000){
    return new Promise(resolve => {
      if(!SpeechRecognition){ log('SpeechRecognition tidak tersedia.'); resolve(null); return; }

      // abort previous recognition if any
      try { if(currentRecognition){ try{ currentRecognition.onresult = null; currentRecognition.onend = null; currentRecognition.onerror = null; currentRecognition.abort && currentRecognition.abort(); }catch(e){} } } catch(e){}

      const r = new SpeechRecognition();
      currentRecognition = r;
      r.lang = 'id-ID';
      r.interimResults = false;
      r.maxAlternatives = 1;
      let done = false;

      r.onresult = ev => {
        if(done) return;
        done = true;
        const t = ev.results[0][0].transcript;
        try{ r.stop(); }catch(e){}
        currentRecognition = null;
        resolve(String(t).trim());
      };

      r.onerror = ev => {
        if(done) return;
        done = true;
        try{ r.abort(); }catch(e){}
        currentRecognition = null;
        resolve(null);
      };

      r.onend = () => {
        if(done) return;
        done = true;
        currentRecognition = null;
        resolve(null);
      };

      // speak then start recognition
      speak(prompt, ()=> {
        if(paused){
          // user paused while TTS speaking
          currentRecognition = null;
          resolve({aborted:true});
          return;
        }
        try{ r.start(); }catch(e){ currentRecognition = null; resolve(null); }
      });

      // safety timeout
      setTimeout(()=> {
        if(done) return;
        try{ r.stop(); }catch(e){}
      }, timeout + 1500);
    });
  }

  // parse helpers
  function parseNumber(text){
    if(!text) return null;
    const t = text.toLowerCase().trim();
    if(t === '') return null;
    if(t.includes('tidak')) return null;
    const replaced = t.replace(/koma/g,'.').replace(/,/g,'.').replace(/ titik /g,'.');
    const m = replaced.match(/-?[\d]+(?:[.,]\d+)?/);
    if(m) return Number(m[0].replace(',','.'));
    const words = {'satu':1,'dua':2,'tiga':3,'empat':4,'lima':5,'enam':6,'tujuh':7,'delapan':8,'sembilan':9,'sepuluh':10,'sebelas':11};
    for(const k in words) if(replaced.indexOf(k)!==-1) return words[k];
    return null;
  }
  function isYes(text){ if(!text) return false; return /(ya|iya|oke|ok|yes|ada|betul)/i.test(text); }
  function isNo(text){ if(!text) return false; return /(tidak|nggak|engga|tak ada|no)/i.test(text); }

  // preview renderer
  function renderQuickPreview(){
    if(pv_kecamatan) pv_kecamatan.textContent = surveyData.kecamatan || '-';
    if(pv_desa) pv_desa.textContent = surveyData.desa || '-';
    if(pv_jumlah_penduduk) pv_jumlah_penduduk.textContent = (surveyData.jumlah_penduduk === '' || surveyData.jumlah_penduduk === undefined) ? '-' : surveyData.jumlah_penduduk;
    if(!pv_parcels) return;
    pv_parcels.innerHTML = '';
    if(!surveyData.parcels || surveyData.parcels.length === 0){
      pv_parcels.textContent = '(belum ada)';
      return;
    }
    surveyData.parcels.forEach((p, idx) => {
      const d = document.createElement('div');
      d.className = 'text-sm';
      let txt = `${idx+1}. ${p.type || '(tipe)'} — `;
      if(p.crops && p.crops.length){
        txt += 'Tanaman: ' + p.crops.map(c=>c.nama_tanaman || '-').join(', ');
      } else if(p.livestocks && p.livestocks.length){
        txt += 'Ternak: ' + p.livestocks.map(l=>l.jenis_ternak || '-').join(', ');
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
  function labelForType(type){
    switch(type){
      case 'persawahan': return 'Jenis tanaman';
      case 'perkebunan': return 'Jenis tumbuhan';
      case 'tambak': return 'Jenis tambak';
      case 'peternakan': return 'Jenis ternak';
      case 'komoditas_lain': return 'Nama komoditas';
      default: return 'Nama tanaman / komoditas';
    }
  }

  function updateParcelUI(rootNode, type){
    // rootNode is the parcel-card or fragment that contains parcel fields
    try {
      const btnAddCrop = rootNode.querySelector('.btnAddCropModal');
      const btnAddLiv = rootNode.querySelector('.btnAddLivestockModal');
      const cropsContainer = rootNode.querySelector('.cropsContainer');

      if(type === 'peternakan'){
        if(btnAddLiv) btnAddLiv.style.display = '';
        if(btnAddCrop) btnAddCrop.style.display = 'none';
      } else {
        if(btnAddLiv) btnAddLiv.style.display = 'none';
        if(btnAddCrop) btnAddCrop.style.display = '';
      }

      // update existing crop labels to reflect meaning
      const labelText = labelForType(type);
      const cropItems = rootNode.querySelectorAll('.crop-item');
      cropItems.forEach(ci => {
        const lbl = ci.querySelector('label');
        if(lbl) lbl.textContent = labelText;
      });
    } catch(e){
      console.error('updateParcelUI', e);
    }
  }

  function createCropNode(crop = null, labelText = null){
    if(!tplCrop) return document.createElement('div');
    const frag = tplCrop.cloneNode(true);
    const root = frag.querySelector('.crop-item') || frag;
    // set label text if provided
    if(labelText){
      const lbl = root.querySelector('label');
      if(lbl) lbl.textContent = labelText;
    }
    if(crop){
      const namaEl = root.querySelector('.crop-nama');
      if(namaEl) namaEl.value = crop.nama_tanaman || '';
      const luasEl = root.querySelector('.crop-luas');
      if(luasEl && crop.luas_hektare !== undefined && crop.luas_hektare !== null) luasEl.value = crop.luas_hektare;
      const prodEl = root.querySelector('.crop-produksi');
      if(prodEl && crop.produksi_ton !== undefined && crop.produksi_ton !== null) prodEl.value = crop.produksi_ton;
      const satuanEl = root.querySelector('.crop-satuan');
      if(satuanEl) satuanEl.value = crop.satuan || '';
    }
    const btnRemove = root.querySelector('.btnRemoveCropModal');
    if(btnRemove){
      btnRemove.addEventListener('click', (ev)=> {
        const n = ev.target.closest('.crop-item'); if(n) n.remove();
      });
    }
    return root;
  }

  function createLivestockNode(liv = null){
    if(!tplLiv) return document.createElement('div');
    const frag = tplLiv.cloneNode(true);
    const root = frag.querySelector('.livestock-item') || frag;
    if(liv){
      const nama = root.querySelector('.liv-nama');
      if(nama) nama.value = liv.jenis_ternak || '';
      const jumlah = root.querySelector('.liv-jumlah');
      if(jumlah && liv.jumlah !== undefined && liv.jumlah !== null) jumlah.value = liv.jumlah;
      const prod = root.querySelector('.liv-produksi');
      if(prod) prod.value = liv.produksi || '';
    }
    const btnRemove = root.querySelector('.btnRemoveLivestockModal');
    if(btnRemove){
      btnRemove.addEventListener('click', (ev)=> {
        const n = ev.target.closest('.livestock-item'); if(n) n.remove();
      });
    }
    return root;
  }

  function createParcelNode(parcel = null){
    if(!tplParcel) return document.createElement('div');
    const frag = tplParcel.cloneNode(true);
    // frag is a DocumentFragment — find root parcel-card element
    const root = frag.querySelector('.parcel-card') || frag;
    const selType = root.querySelector('.parcel-type');
    const cropsContainer = root.querySelector('.cropsContainer');
    const livsContainer = root.querySelector('.livestocksContainer');
    const btnAddCrop = root.querySelector('.btnAddCropModal');
    const btnAddLiv = root.querySelector('.btnAddLivestockModal');
    const btnRemoveParcel = root.querySelector('.btnRemoveParcelModal');

    // set initial type if provided
    if(parcel && parcel.type && selType) selType.value = parcel.type;

    const currentType = selType ? selType.value : '';
    const nameLabel = labelForType(currentType);

    // populate existing crop/livestock items
    if(parcel && Array.isArray(parcel.crops) && parcel.crops.length){
      parcel.crops.forEach(c => {
        cropsContainer.appendChild(createCropNode(c, labelForType(selType ? selType.value : '')));
      });
    }
    if(parcel && Array.isArray(parcel.livestocks) && parcel.livestocks.length){
      parcel.livestocks.forEach(l => {
        livsContainer.appendChild(createLivestockNode(l));
      });
    }

    // initial UI
    updateParcelUI(root, selType ? selType.value : '');

    // when type changes, update UI and labels
    if(selType){
      selType.addEventListener('change', (ev) => {
        const t = selType.value;
        updateParcelUI(root, t);
        // update existing crop labels
        const label = labelForType(t);
        const cropItems = root.querySelectorAll('.crop-item');
        cropItems.forEach(ci => {
          const lbl = ci.querySelector('label');
          if(lbl) lbl.textContent = label;
        });
      });
    }

    // add crop handler
    if(btnAddCrop){
      btnAddCrop.addEventListener('click', ()=> {
        const label = labelForType(selType ? selType.value : '');
        cropsContainer.appendChild(createCropNode(null, label));
        // scroll modal to bottom so new item visible
        setTimeout(()=> {
          const mContent = reviewModal.querySelector('.max-w-5xl') || modalParcelsContainer;
          if(mContent) mContent.scrollIntoView({behavior:'smooth', block:'end'});
        }, 80);
      });
    }

    // add livestock handler
    if(btnAddLiv){
      btnAddLiv.addEventListener('click', ()=> {
        livsContainer.appendChild(createLivestockNode());
        setTimeout(()=> {
          const mContent = reviewModal.querySelector('.max-w-5xl') || modalParcelsContainer;
          if(mContent) mContent.scrollIntoView({behavior:'smooth', block:'end'});
        }, 80);
      });
    }

    // remove parcel
    if(btnRemoveParcel){
      btnRemoveParcel.addEventListener('click', (ev) => {
        const pCard = ev.target.closest('.parcel-card');
        if(pCard) pCard.remove();
      });
    }

    return frag;
  }

  // open/close modal helpers (ensure scrolling and body lock)
  function openReviewModal(autoFocus = true){
    if(!rev_kecamatan || !rev_desa || !rev_jumlah_penduduk || !modalParcelsContainer) return;
    rev_kecamatan.value = surveyData.kecamatan || '';
    rev_desa.value = surveyData.desa || '';
    rev_jumlah_penduduk.value = surveyData.jumlah_penduduk || '';

    modalParcelsContainer.innerHTML = '';
    if(!surveyData.parcels || surveyData.parcels.length === 0){
      const p = document.createElement('div'); p.className = 'text-sm text-gray-500'; p.textContent = 'Belum ada potensi lahan tercatat.'; modalParcelsContainer.appendChild(p);
    } else {
      surveyData.parcels.forEach(p => modalParcelsContainer.appendChild(createParcelNode(p)));
    }

    // show modal and lock body scroll
    reviewModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    // ensure modal content scroll position at top and focus first input
    setTimeout(()=> {
      const dialog = reviewModal.querySelector('.max-w-5xl') || reviewModal.querySelector('form') || modalParcelsContainer;
      if(dialog) { dialog.scrollTop = 0; }
      if(autoFocus){
        const first = reviewModal.querySelector('input, select, textarea, button');
        if(first) first.focus();
      }
    }, 80);
  }

  function closeReviewModal(){
    reviewModal.classList.add('hidden');
    // restore body scroll
    document.body.style.overflow = '';
  }

  // modal add/remove handlers
  if(btnAddParcelModal){
    btnAddParcelModal.addEventListener('click', ()=> {
      if(modalParcelsContainer.children.length === 1 && modalParcelsContainer.children[0].textContent && modalParcelsContainer.children[0].textContent.includes('Belum ada')){
        modalParcelsContainer.innerHTML = '';
      }
      modalParcelsContainer.appendChild(createParcelNode());
      // scroll modal content to show new item
      setTimeout(()=> {
        const container = reviewModal.querySelector('.max-w-5xl') || modalParcelsContainer;
        if(container) container.scrollIntoView({behavior:'smooth', block:'end'});
      }, 60);
    });
  }
  if(closeReview) closeReview.addEventListener('click', closeReviewModal);
  if(revCancelBtn) revCancelBtn.addEventListener('click', closeReviewModal);

  // build parcels JSON on submit
  const modalForm = $id('modalSubmitForm');
  if(modalForm){
    modalForm.addEventListener('submit', (ev) => {
      if(rev_kecamatan) surveyData.kecamatan = rev_kecamatan.value;
      if(rev_desa) surveyData.desa = rev_desa.value;
      if(rev_jumlah_penduduk) surveyData.jumlah_penduduk = rev_jumlah_penduduk.value;

      const parcels = [];
      const cards = modalParcelsContainer.querySelectorAll('.parcel-card');
      cards.forEach(pc => {
        const type = pc.querySelector('.parcel-type').value || '';
        if(!type) return;
        const parcel = { type: type, crops: [], livestocks: [] };

        pc.querySelectorAll('.crop-item').forEach(cn => {
          const nama = cn.querySelector('.crop-nama')?.value || '';
          if(!nama) return;
          const luas = cn.querySelector('.crop-luas')?.value;
          const produksi = cn.querySelector('.crop-produksi')?.value;
          const satuan = cn.querySelector('.crop-satuan')?.value;
          parcel.crops.push({
            nama_tanaman: nama,
            luas_hektare: luas === '' ? null : (isNaN(Number(luas)) ? luas : Number(luas)),
            produksi_ton: produksi === '' ? null : (isNaN(Number(produksi)) ? produksi : Number(produksi)),
            satuan: satuan || null
          });
        });

        pc.querySelectorAll('.livestock-item').forEach(ln => {
          const jenis = ln.querySelector('.liv-nama')?.value || '';
          if(!jenis) return;
          const jumlah = ln.querySelector('.liv-jumlah')?.value;
          const prod = ln.querySelector('.liv-produksi')?.value;
          parcel.livestocks.push({
            jenis_ternak: jenis,
            jumlah: jumlah === '' ? null : (isNaN(Number(jumlah)) ? jumlah : Number(jumlah)),
            produksi: prod || null
          });
        });

        parcels.push(parcel);
      });

      if(parcels_json) parcels_json.value = JSON.stringify(parcels);
      log('Mempersiapkan data JSON untuk dikirim ke server...');
      // allow normal submit
      // before submit, unlock body scroll
      document.body.style.overflow = '';
    });
  }

  // --- Voice flow controller helpers ---
  async function askAndCapture(phaseId, prompt, opts = { timeout:9000 }){
    currentPrompt = prompt;
    lastPromptPhase = phaseId;
    retryCountForPrompt = 0;

    let answer = await captureOnceWithPause(prompt, opts.timeout);
    if(answer && typeof answer === 'object' && answer.aborted){
      return { aborted:true };
    }

    if(answer === null){
      // retry once
      if(retryCountForPrompt === 0){
        retryCountForPrompt = 1;
        log('Tidak mendapat jawaban — mengulangi pertanyaan sekali lagi.');
        await new Promise(r=>setTimeout(r,400));
        answer = await captureOnceWithPause(prompt, opts.timeout);
        if(answer && typeof answer === 'object' && answer.aborted){
          return { aborted:true };
        }
      } else {
        retryCountForPrompt = 0;
        return { value:null };
      }
    }

    retryCountForPrompt = 0;
    return { value: answer };
  }

  async function runVoiceFlow(){
    if(capturing) return;
    if(!SpeechRecognition){ alert('SpeechRecognition tidak tersedia. Gunakan Chrome/Edge desktop atau isi manual.'); return; }

    capturing = true;
    paused = false;
    if(startBtn) startBtn.disabled = true;
    if(pauseBtn){ pauseBtn.disabled = false; pauseBtn.textContent = '⏸ Jeda'; }
    setStatus('merekam...', 'proses');
    log('Mulai sesi voice');

    // 1) kecamatan
    let r = await askAndCapture('kecamatan','Sebutkan nama kecamatan.');
    if(r.aborted){ capturing=false; setStatus('siap','siap'); return; }
    if(r.value){ surveyData.kecamatan = r.value.trim(); log(`Kecamatan: ${surveyData.kecamatan}`); renderQuickPreview(); }

    // 2) desa
    r = await askAndCapture('desa','Sebutkan nama desa.');
    if(r.aborted){ capturing=false; setStatus('siap','siap'); return; }
    if(r.value){ surveyData.desa = r.value.trim(); log(`Desa: ${surveyData.desa}`); renderQuickPreview(); }

    // 3) jumlah penduduk
    r = await askAndCapture('jumlah','Sebutkan jumlah penduduk (rumah tangga). Jika tidak ada, katakan nol.');
    if(r.aborted){ capturing=false; setStatus('siap','siap'); return; }
    if(r.value){
      const num = parseNumber(r.value);
      if(num !== null){ surveyData.jumlah_penduduk = num; log(`Jumlah penduduk: ${num}`); }
      else { surveyData.jumlah_penduduk = r.value.trim(); log(`Jumlah penduduk (text): ${r.value}`); }
    }
    renderQuickPreview();

    // 4) potensi lahan?
    r = await askAndCapture('want_parcels','Apakah ingin menambahkan potensi lahan? Jawab ya atau tidak.');
    if(r.aborted){ capturing=false; setStatus('siap','siap'); return; }
    if(r.value && isYes(r.value)){
      log('Menangkap potensi lahan via voice...');
      const types = ['persawahan','perkebunan','tambak','peternakan','komoditas_lain'];
      for(const t of types){
        if(paused){ capturing=false; setStatus('siap','siap'); return; }
        const ansExist = await askAndCapture('exist_'+t, `Apakah terdapat ${t} di desa ini? Jawab ya atau tidak.`);
        if(ansExist.aborted){ capturing=false; setStatus('siap','siap'); return; }
        if(!ansExist.value) continue;
        if(isYes(ansExist.value)){
          const parcel = { type: t, crops: [], livestocks: [] };
          log(`Tambah lahan: ${t}`);

          if(t === 'peternakan'){
            while(true){
              const j1 = await askAndCapture('peternakan_jenis','Sebutkan jenis hewan ternak. Jika tidak ada lagi, katakan tidak.');
              if(j1.aborted){ capturing=false; setStatus('siap','siap'); return; }
              if(!j1.value || isNo(j1.value)) break;
              const jenis = j1.value.trim();

              const j2 = await askAndCapture('peternakan_jumlah', `Berapa jumlah ${jenis}?`);
              if(j2.aborted){ capturing=false; setStatus('siap','siap'); return; }
              const jumlah = (j2.value !== undefined && j2.value !== null) ? (parseNumber(j2.value) ?? j2.value) : '';

              const j3 = await askAndCapture('peternakan_prod', `Ada produksi khusus untuk ${jenis}? Jika ada sebutkan, atau katakan tidak.`);
              if(j3.aborted){ capturing=false; setStatus('siap','siap'); return; }
              const prod = (j3.value && !isNo(j3.value)) ? j3.value.trim() : '';

              parcel.livestocks.push({ jenis_ternak: jenis, jumlah: jumlah, produksi: prod });
              log(` - Ternak: ${jenis} — ${jumlah} — ${prod}`);
              const more = await askAndCapture('peternakan_more', 'Apakah ada jenis hewan lain? Jawab ya atau tidak.');
              if(more.aborted){ capturing=false; setStatus('siap','siap'); return; }
              if(!more.value || isNo(more.value)) break;
            }
          } else {
            while(true){
              const c1 = await askAndCapture('crop_nama','Sebutkan jenis tanaman atau komoditas. Jika tidak ada lagi, katakan tidak.');
              if(c1.aborted){ capturing=false; setStatus('siap','siap'); return; }
              if(!c1.value || isNo(c1.value)) break;
              const nama = c1.value.trim();

              const c2 = await askAndCapture('crop_luas', `Berapa luas lahan untuk ${nama} dalam hektar? Contoh: nol koma lima.`);
              if(c2.aborted){ capturing=false; setStatus('siap','siap'); return; }
              const luas = (c2.value !== undefined && c2.value !== null) ? (parseNumber(c2.value) ?? c2.value) : '';

              const c3 = await askAndCapture('crop_satuan', `Satuan panennya apa untuk ${nama}? Misal ton atau kg.`);
              if(c3.aborted){ capturing=false; setStatus('siap','siap'); return; }
              const satuan = (c3.value && !isNo(c3.value)) ? c3.value.trim() : '';

              const c4 = await askAndCapture('crop_produksi', `Berapa jumlah panen untuk ${nama} dalam ${satuan||'satuan'}? Jika tidak tahu, katakan nol.`);
              if(c4.aborted){ capturing=false; setStatus('siap','siap'); return; }
              const produksi = (c4.value !== undefined && c4.value !== null) ? (parseNumber(c4.value) ?? c4.value) : '';

              parcel.crops.push({ nama_tanaman: nama, luas_hektare: luas, produksi_ton: produksi, satuan: satuan });
              log(` - Tanaman: ${nama} — luas ${luas} — panen ${produksi} ${satuan}`);

              const more = await askAndCapture('crop_more', 'Apakah ada jenis tanaman/komoditas lain? Jawab ya atau tidak.');
              if(more.aborted){ capturing=false; setStatus('siap','siap'); return; }
              if(!more.value || isNo(more.value)) break;
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
    if(startBtn) startBtn.disabled = false;
    if(pauseBtn){ pauseBtn.disabled = true; pauseBtn.textContent = 'Jeda'; }
    setStatus('siap','siap');
    log('Selesai sesi voice — membuka Tinjau & Edit untuk koreksi / simpan.');
    // auto-open review modal:
    setTimeout(()=> openReviewModal(true), 350);
  }

  // Pause/resume with re-ask of current prompt
  if(startBtn) startBtn.addEventListener('click', ()=> {
    if(capturing) return;
    paused = false;
    if(pauseBtn){ pauseBtn.textContent = '⏸ Jeda'; pauseBtn.disabled = false; }
    runVoiceFlow().catch(e => { console.error(e); capturing=false; if(startBtn) startBtn.disabled=false; if(pauseBtn) pauseBtn.disabled=true; setStatus('gagal','gagal'); log('Terjadi error pada sesi voice'); });
  });

  if(pauseBtn) pauseBtn.addEventListener('click', ()=> {
    if(!capturing) return;
    paused = !paused;
    if(paused){
      try{ if(currentRecognition && currentRecognition.abort) currentRecognition.abort(); }catch(e){}
      pauseBtn.textContent = '▶ Lanjut';
      log('Sesi dijeda oleh user. Tekan Lanjut untuk mengulang pertanyaan saat ini.');
      setStatus('proses','proses');
    } else {
      pauseBtn.textContent = '⏸ Jeda';
      log('Melanjutkan sesi voice — mengulang pertanyaan terakhir jika ada.');
      setStatus('merekam...','proses');

      // if there is a currentPrompt, re-ask it once immediately to resume flow
      if(currentPrompt && lastPromptPhase && capturing){
        (async ()=>{
          await new Promise(r=>setTimeout(r,200));
          const res = await captureOnceWithPause(currentPrompt, 9000);
          if(res && typeof res === 'object' && res.aborted){
            return;
          }
          if(typeof res === 'string' && res.trim() !== ''){
            if(lastPromptPhase === 'kecamatan'){ surveyData.kecamatan = res.trim(); log(`Kecamatan: ${surveyData.kecamatan}`); renderQuickPreview(); }
            else if(lastPromptPhase === 'desa'){ surveyData.desa = res.trim(); log(`Desa: ${surveyData.desa}`); renderQuickPreview(); }
            else if(lastPromptPhase === 'jumlah'){ const n = parseNumber(res); if(n!==null){ surveyData.jumlah_penduduk = n; log(`Jumlah penduduk: ${n}`);} else { surveyData.jumlah_penduduk = res.trim(); log(`Jumlah penduduk (text): ${res}`);} renderQuickPreview(); }
            // for complex phases (parcels/crops), the main flow will continue asking as designed
          }
        })();
      }
    }
  });

  // fallback notice
  if(!SpeechRecognition){
    const warn = document.createElement('div');
    warn.className = 'max-w-4xl mx-auto mt-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded';
    warn.innerHTML = '<strong>Catatan:</strong> Speech Recognition tidak tersedia di browser ini. Gunakan Chrome/Edge (desktop) atau isi manual.';
    document.body.insertBefore(warn, document.body.firstChild.nextSibling);
  }

  // initial UI
  setStatus('siap','siap');
  log('Siap. Tekan Mulai untuk memulai sesi voice.');
  renderQuickPreview();

  // Flash handling (server session) - display green box in activityLog
  (function(){
    function insertSavedBox(msgTitle, msgSubtitle){
      try {
        const attempt = () => {
          const logEl = $id('activityLog');
          if(!logEl) return false;
          const card = document.createElement('div');
          card.className = 'p-3 rounded border bg-green-50 border-green-200 text-green-800 mb-2';
          const row = document.createElement('div'); row.className = 'flex items-start gap-3';
          const icon = document.createElement('div');
          icon.innerHTML = `<svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <circle cx="12" cy="12" r="10" fill="#16a34a" opacity="0.12"></circle>
              <path d="M7 13l3 3 7-8" stroke="#166534" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>`;
          const texts = document.createElement('div');
          texts.innerHTML = `<div class="font-semibold text-sm">${escapeHtml(msgTitle)}</div>
                             <div class="text-xs text-green-800/80 mt-1">${escapeHtml(msgSubtitle || '')}</div>`;
          row.appendChild(icon); row.appendChild(texts); card.appendChild(row); logEl.prepend(card);
          card.animate([{ transform: 'translateY(-6px)', opacity: 0 }, { transform: 'translateY(0)', opacity: 1 }], { duration: 220, easing: 'ease-out' });
          return true;
        };
        if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', () => { if(!attempt()) setTimeout(attempt,200); }); }
        else { if(!attempt()) setTimeout(attempt,200); }
      } catch(e){ console.error('insertSavedBox error', e); }
    }

    try {
      @if(session('success'))
        const _sr_msg = {!! json_encode(session('success')) !!};
      @else
        const _sr_msg = null;
      @endif

      @if(session('latest_no_id'))
        const _sr_id = {!! json_encode(session('latest_no_id')) !!};
      @else
        const _sr_id = null;
      @endif

      @if(session('error'))
        const _sr_err = {!! json_encode(session('error')) !!};
      @else
        const _sr_err = null;
      @endif

      if(_sr_msg){
        const title = _sr_id ? `${_sr_msg} No ID: ${_sr_id}` : _sr_msg;
        const subtitle = 'Siap. Tekan Mulai untuk memulai sesi voice.';
        insertSavedBox(title, subtitle);
        try { setStatus('tersimpan', 'siap'); } catch(e){}
        try { if(statusBox){ statusBox.classList.add('flash-highlight'); setTimeout(()=> statusBox.classList.remove('flash-highlight'), 1600); } } catch(e){}
      }
      if(_sr_err){
        (function(){ const attemptErr = ()=>{ const el = $id('activityLog'); if(!el) return false; const now = new Date(); const t = now.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit',second:'2-digit'}); const line = document.createElement('div'); line.innerHTML = `<span style="color:#6b7280">[${t}]</span> <span class="text-red-600">${escapeHtml(_sr_err)}</span>`; el.prepend(line); return true; }; if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', () => { if(!attemptErr()) setTimeout(attemptErr,200); }); } else { if(!attemptErr()) setTimeout(attemptErr,200); } })();
        try { setStatus('gagal','gagal'); } catch(e){}
      }
    } catch(e){ console.error('[voice] flash-handling error', e); }
  })();

  // wire review button
  if(btnOpenReview) btnOpenReview.addEventListener('click', ()=> openReviewModal(true));

})();
</script>
    </div>
</x-app-layout>