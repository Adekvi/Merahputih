<x-app-layout>
      <x-slot:title>
        Dashboard
    </x-slot:title>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Dashboard Potensi Pertanian & Peternakan — Kota
    </h2>
  </x-slot>

  <div class="max-w-7xl mx-auto p-6">
    <!-- Header Info -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Potensi Pertanian & Peternakan</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Ringkasan agregat per kota — Periode <span id="periode">2025</span>
        </p>
      </div>
      <div class="text-right">
        <p class="text-xs text-gray-400"></p>
        <p id="generatedAt" class="text-sm text-gray-700 dark:text-gray-300">&nbsp;</p>
      </div>
    </div>

    <!-- Top Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
      @php
        $cards = [
          ['label' => 'Total Produksi Pertanian', 'id' => 'totalProdPertanian', 'unit' => 'ton'],
          ['label' => 'Total Populasi Ternak', 'id' => 'totalTernak', 'unit' => 'ekor'],
          ['label' => 'Total Produksi Perkebunan', 'id' => 'totalPerkebunan', 'unit' => 'ton'],
          ['label' => 'Total Komoditas Lain', 'id' => 'totalHorti', 'unit' => 'ton'],
          ['label' => 'Jumlah Desa Terdata', 'id' => 'jumlahPetani', 'unit' => ''],
        ];
      @endphp

      @foreach($cards as $card)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4 transition-colors">
          <div class="text-sm text-gray-500 dark:text-gray-400">{{ $card['label'] }}</div>
          <div class="text-2xl font-bold mt-1 text-gray-900 dark:text-gray-100">
            <span id="{{ $card['id'] }}"></span> {{ $card['unit'] }}
          </div>
        </div>
      @endforeach
    </div>

    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 space-y-6">
        <!-- Persawahan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Ringkasan Persawahan</h2>
            <p class="text-sm text-gray-500">Total: <span id="persawahanTotal">0</span> ton</p>
          </div>
          <canvas id="chartPersawahan" height="120"></canvas>
        </div>

        <!-- Perkebunan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Ringkasan Perkebunan</h2>
            <p class="text-sm text-gray-500">Total: <span id="perkebunanTotal">0</span> ton</p>
          </div>
          <canvas id="chartPerkebunan" height="120"></canvas>
        </div>
      </div>

      <div class="space-y-6">
        <!-- Peternakan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4">
          <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-3">Ringkasan Peternakan</h2>
          <canvas id="chartPeternakan" height="180"></canvas>
          <ul id="peternakanList" class="mt-4 text-sm text-gray-600 dark:text-gray-300 space-y-1"></ul>
        </div>

        <!-- Komoditas Lain -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Komoditas Lain</h2>
            <p class="text-sm text-gray-500">Total: <span id="hortiTotal">0</span> ton</p>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="text-gray-600 dark:text-gray-400 border-b border-gray-300 dark:border-gray-700">
                <tr>
                  <th class="pb-2 text-left">Jenis</th>
                  <th class="pb-2 text-left">Produksi (ton)</th>
                </tr>
              </thead>
              <tbody id="hortiTable"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Ringkasan Keseluruhan -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4 mt-6">
      <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-3">Tabel Ringkasan Keseluruhan</h2>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="text-gray-600 dark:text-gray-400 border-b border-gray-300 dark:border-gray-700">
            <tr>
              <th class="py-2 text-left">Kategori</th>
              <th class="py-2 text-left">Komoditas / Jenis</th>
              <th class="py-2 text-left">Total</th>
            </tr>
          </thead>
          <tbody id="summaryTable"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    const DATA = @json($DATA);
    const fmt = (v) => Number(v).toLocaleString();

    function renderSummary() {
      document.getElementById('periode').textContent = DATA.periode;
      document.getElementById('generatedAt').textContent = new Date().toLocaleString();
setInterval(() => {
  document.getElementById('generatedAt').textContent = new Date().toLocaleString();
}, 1000);

      document.getElementById('totalProdPertanian').textContent = fmt(DATA.total_produksi_pertanian_ton);
      document.getElementById('totalTernak').textContent = fmt(DATA.total_populasi_ternak);
      document.getElementById('totalPerkebunan').textContent = fmt(DATA.total_produksi_perkebunan_ton);
      document.getElementById('totalHorti').textContent = fmt(DATA.total_hortikultura_ton);
      document.getElementById('jumlahPetani').textContent = fmt(DATA.jumlah_petani);

      // Tabel Komoditas Lain
      document.getElementById('hortiTable').innerHTML = DATA.hortikultura.map(h =>
        `<tr><td class="py-2">${h.jenis}</td><td class="py-2">${fmt(h.ton)}</td></tr>`).join('');

      // Peternakan list
      document.getElementById('peternakanList').innerHTML = DATA.peternakan.map(p =>
        `<li>${p.jenis}: <strong>${fmt(p.jumlah)}</strong> ekor</li>`).join('');

      // Summary table
      const rows = [];
      DATA.persawahan.forEach(p => rows.push(`<tr><td>Persawahan</td><td>${p.jenis}</td><td>${fmt(p.ton)} ton</td></tr>`));
      DATA.perkebunan.forEach(p => rows.push(`<tr><td>Perkebunan</td><td>${p.jenis}</td><td>${fmt(p.ton)} ton</td></tr>`));
      DATA.peternakan.forEach(p => rows.push(`<tr><td>Peternakan</td><td>${p.jenis}</td><td>${fmt(p.jumlah)} ekor</td></tr>`));
      DATA.hortikultura.forEach(p => rows.push(`<tr><td>Komoditas Lain</td><td>${p.jenis}</td><td>${fmt(p.ton)} ton</td></tr>`));
      document.getElementById('summaryTable').innerHTML = rows.join('');
    }

    // Charts
    function renderCharts() {
      new Chart(document.getElementById('chartPersawahan'), {
        type: 'bar',
        data: { labels: DATA.persawahan.map(x => x.jenis), datasets: [{ data: DATA.persawahan.map(x => x.ton), label: 'Ton' }] },
        options: { plugins: { legend: { display: false } } }
      });

      new Chart(document.getElementById('chartPerkebunan'), {
        type: 'bar',
        data: { labels: DATA.perkebunan.map(x => x.jenis), datasets: [{ data: DATA.perkebunan.map(x => x.ton), label: 'Ton' }] },
        options: { indexAxis: 'y', plugins: { legend: { display: false } } }
      });

      new Chart(document.getElementById('chartPeternakan'), {
        type: 'pie',
        data: { labels: DATA.peternakan.map(x => x.jenis), datasets: [{ data: DATA.peternakan.map(x => x.jumlah) }] }
      });
    }

    renderSummary();
    renderCharts();
  </script>
</x-app-layout>
