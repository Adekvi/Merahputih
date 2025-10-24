<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Buat Data Sensus - NgocehGo</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 py-8">
  <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-semibold mb-4">Buat Data Sensus Desa</h1>

    @if ($errors->any())
      <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
        <strong>Terjadi kesalahan:</strong>
        <ul class="list-disc pl-5 mt-2">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('surveys.store') }}" method="POST" id="surveyForm">
      @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
            <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
            <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="mt-1 block w-full border rounded p-2" required>
            </div>

            <div>
            <label class="block text-sm font-medium text-gray-700">Desa</label>
            <input type="text" name="desa" value="{{ old('desa') }}" class="mt-1 block w-full border rounded p-2" required>
            </div>

            <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah Penduduk</label>
            <input type="number" name="jumlah_penduduk" value="{{ old('jumlah_penduduk') }}" class="mt-1 block w-48 border rounded p-2" min="0">
            </div>
        </div>

      <hr class="my-4">

      <div class="flex items-center justify-between mb-3">
        <h2 class="text-lg font-semibold">Potensi Lahan (Parcels)</h2>
        <button type="button" id="btnAddParcel" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">+ Tambah Lahan</button>
      </div>

      <div id="parcelsContainer" class="space-y-4">
        {{-- Template akan digenerate via JS; jika ada old input, kita render via JS --}}
      </div>

      <div class="mt-6">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan Data</button>
        <a href="{{ route('surveys.index') }}" class="ml-2 text-gray-600">Batal</a>
      </div>
    </form>
  </div>

  {{-- Templates (tidak terlihat) --}}
  <template id="parcelTemplate">
    <div class="parcel-card border rounded p-4 bg-gray-50">
      <div class="flex justify-between items-start">
        <div class="w-full">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
              <label class="block text-sm font-medium">Tipe Lahan</label>
              <select name="__NAME_TYPE__" class="mt-1 block w-full border rounded p-2 parcel-type" required>
                <option value="">-- Pilih tipe --</option>
                <option value="persawahan">Persawahan</option>
                <option value="perkebunan">Perkebunan</option>
                <option value="tambak">Tambak</option>
                <option value="peternakan">Peternakan</option>
                <option value="komoditas_lain">Komoditas_lain</option>
              </select>
            </div>

            {{-- Kolom Luas dan Keterangan dihapus sesuai perubahan schema --}}

            <div class="col-span-1 md:col-span-3">
              {{-- Crops --}}
              <div class="mt-3">
                <div class="flex items-center justify-between mb-1">
                  <button type="button" class="btnAddCrop text-sm px-2 py-1 bg-indigo-600 text-white rounded">+ Tambah</button>
                </div>
                <div class="cropsContainer space-y-2">
                  {{-- crop items here --}}
                </div>
              </div>

              {{-- Livestocks --}}
              <div class="mt-3">
                <div class="flex items-center justify-between mb-1">
                  <button type="button" class="btnAddLivestock text-sm px-2 py-1 bg-red-600 text-white rounded">+ Tambah Ternak</button>
                </div>
                <div class="livestocksContainer space-y-2">
                  {{-- livestock items here --}}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="ml-3 pl-3 border-l">
          <button type="button" class="btnRemoveParcel text-sm text-red-600">Hapus Lahan</button>
        </div>
      </div>
    </div>
  </template>

  <template id="cropTemplate">
    <div class="crop-item border rounded p-2 bg-white flex items-start justify-between">
      <div class="w-full grid grid-cols-1 md:grid-cols-4 gap-2">
        <div>
          <label class="text-xs">Nama tanaman / komoditas</label>
          <input type="text" name="__NAME_CROP_NAMA__" class="mt-1 block w-full border rounded p-1" required>
        </div>
        <div>
          <label class="text-xs">Luas (ha)</label>
          <input type="number" step="0.001" min="0" name="__NAME_CROP_LUAS__" class="mt-1 block w-full border rounded p-1">
        </div>
        <div>
          <label class="text-xs">Produksi (ton)</label>
          <input type="number" step="0.001" min="0" name="__NAME_CROP_PRODUKSI__" class="mt-1 block w-full border rounded p-1">
        </div>
        <div>
          <label class="text-xs">Satuan</label>
          <input type="text" name="__NAME_CROP_SATUAN__" class="mt-1 block w-full border rounded p-1" placeholder="ton/kg">
        </div>
      </div>

      <div class="ml-3 pl-3 border-l">
        <button type="button" class="btnRemoveCrop text-sm text-red-600">Hapus</button>
      </div>
    </div>
  </template>

  <template id="livestockTemplate">
    <div class="livestock-item border rounded p-2 bg-white flex items-start justify-between">
      <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-2">
        <div>
          <label class="text-xs">Jenis ternak</label>
          <input type="text" name="__NAME_LIV_NAMA__" class="mt-1 block w-full border rounded p-1" required>
        </div>
        <div>
          <label class="text-xs">Jumlah</label>
          <input type="number" name="__NAME_LIV_JUMLAH__" class="mt-1 block w-full border rounded p-1">
        </div>
        <div>
          <label class="text-xs">Produksi (opsional)</label>
          <input type="text" name="__NAME_LIV_PRODUKSI__" class="mt-1 block w-full border rounded p-1" placeholder="mis. telur ~1500/minggu">
        </div>
      </div>

      <div class="ml-3 pl-3 border-l">
        <button type="button" class="btnRemoveLivestock text-sm text-red-600">Hapus</button>
      </div>
    </div>
  </template>

  {{-- Script to manage dynamic form --}}
 <script>
(function() {
  const parcelsContainer = document.getElementById('parcelsContainer');
  const parcelTpl = document.getElementById('parcelTemplate').content;
  const cropTpl = document.getElementById('cropTemplate').content;
  const livestockTpl = document.getElementById('livestockTemplate').content;
  const btnAddParcel = document.getElementById('btnAddParcel');

  // counters
  let parcelIndex = 0;

  function makeParcelElement(data = null) {
    const clone = document.importNode(parcelTpl, true);
    const parcelEl = clone.querySelector('.parcel-card');

    const currentIndex = parcelIndex++;
    // set name for type only
    const select = clone.querySelector('select.parcel-type');
    select.name = `parcels[${currentIndex}][type]`;

    // Fill value if provided
    if (data && data.type) select.value = data.type;

    // Find crop/livestock containers and controls
    const btnAddCrop = clone.querySelector('.btnAddCrop');
    const cropsContainer = clone.querySelector('.cropsContainer');
    const btnAddLivestock = clone.querySelector('.btnAddLivestock');
    const livestocksContainer = clone.querySelector('.livestocksContainer');

    // get the crops heading element (to change text)
    const cropsHeading = cropsContainer.parentElement.querySelector('h3') || null;

    // remove parcel
    clone.querySelector('.btnRemoveParcel').addEventListener('click', function () {
      parcelEl.remove();
    });

    // add crop
    btnAddCrop.addEventListener('click', function () {
      addCrop(cropsContainer, currentIndex);
    });

    // add livestock
    btnAddLivestock.addEventListener('click', function () {
      addLivestock(livestocksContainer, currentIndex);
    });

    // when type changes, update UI
    select.addEventListener('change', function () {
      updateParcelUI(select.value, {
        cropsContainer,
        btnAddCrop,
        livestocksContainer,
        btnAddLivestock,
        cropsHeading
      });
    });

    // if data contains crops, add them
    if (data && Array.isArray(data.crops)) {
      data.crops.forEach(c => addCrop(cropsContainer, currentIndex, c));
    }

    if (data && Array.isArray(data.livestocks)) {
      data.livestocks.forEach(l => addLivestock(livestocksContainer, currentIndex, l));
    }

    // initial UI update according to selected value (or default)
    // small timeout to ensure elements are in DOM when used by outer code
    setTimeout(() => updateParcelUI(select.value, {
      cropsContainer, btnAddCrop, livestocksContainer, btnAddLivestock, cropsHeading
    }), 0);

    return { parcelEl, clone };
  }

  function updateParcelUI(type, ctx) {
    // ctx: { cropsContainer, btnAddCrop, livestocksContainer, btnAddLivestock, cropsHeading }
    const { cropsContainer, btnAddCrop, livestocksContainer, btnAddLivestock, cropsHeading } = ctx;

    // normalize
    type = (type || '').toString();

    // hide/show logic
    if (type === 'peternakan') {
      // show livestock, hide crops
      if (btnAddLivestock) btnAddLivestock.style.display = '';
      if (livestocksContainer) livestocksContainer.style.display = '';
      if (btnAddCrop) btnAddCrop.style.display = 'none';
      if (cropsContainer) cropsContainer.style.display = 'none';
      if (cropsHeading) cropsHeading.style.display = 'none';
    } else {
      // show crops (for tambak, komoditas_lain, persawahan, perkebunan)
      if (btnAddLivestock) btnAddLivestock.style.display = 'none';
      if (livestocksContainer) livestocksContainer.style.display = 'none';
      if (btnAddCrop) btnAddCrop.style.display = '';
      if (cropsContainer) cropsContainer.style.display = '';
      if (cropsHeading) cropsHeading.style.display = '';

      // adjust crops heading text based on type
      if (cropsHeading) {
        if (type === 'tambak') {
          cropsHeading.textContent = 'Jenis Tambak';
        } else if (type === 'komoditas_lain') {
          cropsHeading.textContent = 'Komoditas Lain';
        } else if (type === 'perkebunan' || type === 'persawahan') {
          cropsHeading.textContent = 'Tanaman / Komoditas';
        } else {
          cropsHeading.textContent = 'Tanaman / Komoditas';
        }
      }
    }

    // optional: when hidden, remove "required" attribute from inputs inside hidden container,
    // and when shown, re-add required where appropriate.
    // handle crops inputs required flag
    if (cropsContainer) {
      const inputs = cropsContainer.querySelectorAll('input, select, textarea');
      inputs.forEach(inp => {
        if (cropsContainer.style.display === 'none') {
          inp.dataset._required = inp.required ? '1' : '0';
          inp.required = false;
        } else {
          if (inp.dataset._required === '1') inp.required = true;
        }
      });
    }
    if (livestocksContainer) {
      const inputsL = livestocksContainer.querySelectorAll('input, select, textarea');
      inputsL.forEach(inp => {
        if (livestocksContainer.style.display === 'none') {
          inp.dataset._required = inp.required ? '1' : '0';
          inp.required = false;
        } else {
          if (inp.dataset._required === '1') inp.required = true;
        }
      });
    }
  }

  function addCrop(container, parcelIdx, data = null) {
    const cclone = document.importNode(cropTpl, true);
    const cropEl = cclone.querySelector('.crop-item');

    // find inputs and set names
    const nama = cclone.querySelector('input[name="__NAME_CROP_NAMA__"]');
    const luas = cclone.querySelector('input[name="__NAME_CROP_LUAS__"]');
    const produksi = cclone.querySelector('input[name="__NAME_CROP_PRODUKSI__"]');
    const satuan = cclone.querySelector('input[name="__NAME_CROP_SATUAN__"]');

    // create index for crop: use timestamp + random to avoid collisions
    const cropIdx = Date.now().toString().slice(-6) + Math.floor(Math.random() * 1000);

    nama.name = `parcels[${parcelIdx}][crops][${cropIdx}][nama_tanaman]`;
    luas.name = `parcels[${parcelIdx}][crops][${cropIdx}][luas_hektare]`;
    produksi.name = `parcels[${parcelIdx}][crops][${cropIdx}][produksi_ton]`;
    satuan.name = `parcels[${parcelIdx}][crops][${cropIdx}][satuan]`;

    if (data) {
      if (data.nama_tanaman) nama.value = data.nama_tanaman;
      if (data.luas_hektare) luas.value = data.luas_hektare;
      if (data.produksi_ton) produksi.value = data.produksi_ton;
      if (data.satuan) satuan.value = data.satuan;
    }

    cclone.querySelector('.btnRemoveCrop').addEventListener('click', function () {
      cropEl.remove();
    });

    container.appendChild(cclone);
  }

  function addLivestock(container, parcelIdx, data = null) {
    const lclone = document.importNode(livestockTpl, true);
    const livEl = lclone.querySelector('.livestock-item');

    const nama = lclone.querySelector('input[name="__NAME_LIV_NAMA__"]');
    const jumlah = lclone.querySelector('input[name="__NAME_LIV_JUMLAH__"]');
    const produksi = lclone.querySelector('input[name="__NAME_LIV_PRODUKSI__"]');

    const livIdx = Date.now().toString().slice(-6) + Math.floor(Math.random() * 1000);

    nama.name = `parcels[${parcelIdx}][livestocks][${livIdx}][jenis_ternak]`;
    jumlah.name = `parcels[${parcelIdx}][livestocks][${livIdx}][jumlah]`;
    produksi.name = `parcels[${parcelIdx}][livestocks][${livIdx}][produksi]`;

    if (data) {
      if (data.jenis_ternak) nama.value = data.jenis_ternak;
      if (data.jumlah) jumlah.value = data.jumlah;
      if (data.produksi) produksi.value = data.produksi;
    }

    lclone.querySelector('.btnRemoveLivestock').addEventListener('click', function () {
      livEl.remove();
    });

    container.appendChild(lclone);
  }

  // add initial parcel by default
  btnAddParcel.addEventListener('click', function () {
    const { clone } = makeParcelElement();
    parcelsContainer.appendChild(clone);
  });

  // If old input exists (after validation fail), render it
  const old = @json(session()->getOldInput());
  if (old && old.parcels) {
    // render each old parcel (old keys might be numeric or non-numeric)
    Object.keys(old.parcels).forEach(function(key) {
      const pd = old.parcels[key];
      const { clone } = makeParcelElement(pd);
      parcelsContainer.appendChild(clone);
    });
  } else {
    // default one parcel
    const { clone } = makeParcelElement();
    parcelsContainer.appendChild(clone);
  }
  function updateParcelUI(type, ctx) {
  const { cropsContainer, btnAddCrop, livestocksContainer, btnAddLivestock, cropsHeading } = ctx;
  type = (type || '').toString();

  // Dapatkan label "Nama tanaman / komoditas"
  const labelNama = cropsContainer ? cropsContainer.querySelector('label.text-xs') : null;

  if (type === 'peternakan') {
    // tampilkan hanya peternakan
    if (btnAddLivestock) btnAddLivestock.style.display = '';
    if (livestocksContainer) livestocksContainer.style.display = '';
    if (btnAddCrop) btnAddCrop.style.display = 'none';
    if (cropsContainer) cropsContainer.style.display = 'none';
    if (cropsHeading) cropsHeading.style.display = 'none';
  } else {
    // tampilkan tanaman/komoditas
    if (btnAddLivestock) btnAddLivestock.style.display = 'none';
    if (livestocksContainer) livestocksContainer.style.display = 'none';
    if (btnAddCrop) btnAddCrop.style.display = '';
    if (cropsContainer) cropsContainer.style.display = '';
    if (cropsHeading) cropsHeading.style.display = '';

    // ubah judul & label berdasarkan tipe
    let headingText = 'Tanaman / Komoditas';
    let labelText = 'Nama tanaman / komoditas';

    if (type === 'tambak') {
      headingText = 'Jenis Tambak';
      labelText = 'Jenis Tambak';
    } else if (type === 'komoditas_lain') {
      headingText = 'Komoditas Lain';
      labelText = 'Jenis Komoditas Lain';
    } else if (type === 'persawahan' || type === 'perkebunan') {
      headingText = 'Jenis Tanaman';
      labelText = 'Jenis Tanaman';
    }

    if (cropsHeading) cropsHeading.textContent = headingText;
    if (labelNama) labelNama.textContent = labelText;
  }
}


})();
</script>

</body>
</html>
