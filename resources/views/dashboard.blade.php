<x-layout.terminal title="Dashboard">

    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard Potensi Pertanian & Peternakan — Kota</h3>
                <h6 class="op-7 mb-2">Ringkasan agregat per kota — Periode <span id="periode">2025</span></h6>
                <span class="fw-bold">
                        <small class="text-muted">Diperbarui pada: <span id="generatedAt"></span></small>
                    </span>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Export</a>
                <a href="#" class="btn btn-primary btn-round">Refresh Data</a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-sm-6 col-md-3 col-lg-2-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-seedling"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Produksi Pertanian</p>
                                    <h4 class="card-title"><span id="totalProdPertanian">0</span> ton</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3 col-lg-2-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-cow"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Populasi Ternak</p>
                                    <h4 class="card-title"><span id="totalTernak">0</span> ekor</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3 col-lg-2-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-leaf"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Produksi Perkebunan</p>
                                    <h4 class="card-title"><span id="totalPerkebunan">0</span> ton</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3 col-lg-2-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-carrot"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Komoditas Lain</p>
                                    <h4 class="card-title"><span id="totalHorti">0</span> ton</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3 col-lg-2-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Jumlah Petani</p>
                                    <h4 class="card-title"><span id="jumlahPetani">0</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <!-- Persawahan & Perkebunan -->
            <div class="col-md-8">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Ringkasan Persawahan</div>
                            <div class="card-tools">
                                <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                    <span class="btn-label"><i class="fa fa-file-export"></i></span> Export
                                </a>
                            </div>
                        </div>
                        <div class="card-category">Total: <span id="persawahanTotal">0</span> ton</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="min-height: 300px">
                            <canvas id="chartPersawahan"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card card-round mt-4">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Ringkasan Perkebunan</div>
                        </div>
                        <div class="card-category">Total: <span id="perkebunanTotal">0</span> ton</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="min-height: 300px">
                            <canvas id="chartPerkebunan"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peternakan & Hortikultura -->
            <div class="col-md-4">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">Ringkasan Peternakan</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="min-height: 300px">
                            <canvas id="chartPeternakan"></canvas>
                        </div>
                        <ul id="peternakanList" class="mt-3 text-sm"></ul>
                    </div>
                </div>

                <div class="card card-round mt-4">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Komoditas Lain</div>
                        </div>
                        <div class="card-category">Total: <span id="hortiTotal">0</span> ton</div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Jenis</th>
                                        <th class="text-end">Ton</th>
                                    </tr>
                                </thead>
                                <tbody id="hortiTable"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Table -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">Tabel Ringkasan Keseluruhan</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Komoditas / Jenis</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="summaryTable"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @endpush

    @push('js')
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const DATA = @json($DATA);
            const fmt = (v) => Number(v).toLocaleString('id-ID');

            document.addEventListener('DOMContentLoaded', () => {
                renderSummary();
                renderCharts();
                // feather.replace();
            });

            function renderSummary() {
                document.getElementById('periode').textContent = DATA.periode;
                const updateTime = () => {
                    document.getElementById('generatedAt').textContent = new Date().toLocaleString('id-ID');
                };
                updateTime();
                setInterval(updateTime, 1000);

                // Update kartu
                document.getElementById('totalProdPertanian').textContent = fmt(DATA.total_produksi_pertanian_ton);
                document.getElementById('totalTernak').textContent = fmt(DATA.total_populasi_ternak);
                document.getElementById('totalPerkebunan').textContent = fmt(DATA.total_produksi_perkebunan_ton);
                document.getElementById('totalHorti').textContent = fmt(DATA.total_hortikultura_ton);
                document.getElementById('jumlahPetani').textContent = fmt(DATA.jumlah_petani);

                // Total per kategori
                const totalPersawahan = DATA.persawahan.reduce((a, b) => a + b.ton, 0);
                const totalPerkebunan = DATA.perkebunan.reduce((a, b) => a + b.ton, 0);
                const totalHorti = DATA.hortikultura.reduce((a, b) => a + b.ton, 0);

                document.getElementById('persawahanTotal').textContent = fmt(totalPersawahan);
                document.getElementById('perkebunanTotal').textContent = fmt(totalPerkebunan);
                document.getElementById('hortiTotal').textContent = fmt(totalHorti);

                // Tabel Hortikultura
                document.getElementById('hortiTable').innerHTML = DATA.hortikultura.map(h =>
                    `<tr><td>${h.jenis}</td><td class="text-end">${fmt(h.ton)}</td></tr>`
                ).join('');

                // List Peternakan
                document.getElementById('peternakanList').innerHTML = DATA.peternakan.map(p =>
                    `<li class="d-flex justify-content-between"><span>${p.jenis}</span> <strong>${fmt(p.jumlah)} ekor</strong></li>`
                ).join('');

                // Tabel Ringkasan
                const rows = [];
                DATA.persawahan.forEach(p => rows.push(
                    `<tr><td>Persawahan</td><td>${p.jenis}</td><td>${fmt(p.ton)} ton</td></tr>`));
                DATA.perkebunan.forEach(p => rows.push(
                    `<tr><td>Perkebunan</td><td>${p.jenis}</td><td>${fmt(p.ton)} ton</td></tr>`));
                DATA.peternakan.forEach(p => rows.push(
                    `<tr><td>Peternakan</td><td>${p.jenis}</td><td>${fmt(p.jumlah)} ekor</td></tr>`));
                DATA.hortikultura.forEach(p => rows.push(
                    `<tr><td>Komoditas Lain</td><td>${p.jenis}</td><td>${fmt(p.ton)} ton</td></tr>`));
                document.getElementById('summaryTable').innerHTML = rows.join('');
            }

            // 🔹 Fungsi untuk membuat gradient
            function createGradient(ctx, colorStart, colorEnd) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, colorStart);
                gradient.addColorStop(1, colorEnd);
                return gradient;
            }

            function renderCharts() {
                const ctxSawah = document.getElementById('chartPersawahan').getContext('2d');
                const ctxKebun = document.getElementById('chartPerkebunan').getContext('2d');
                const ctxTernak = document.getElementById('chartPeternakan').getContext('2d');

                // 🌾 Chart Persawahan
                new Chart(ctxSawah, {
                    type: 'bar',
                    data: {
                        labels: DATA.persawahan.map(p => p.jenis),
                        datasets: [{
                            label: 'Produksi (ton)',
                            data: DATA.persawahan.map(p => p.ton),
                            backgroundColor: createGradient(ctxSawah, '#6366f1', '#a5b4fc'),
                            borderRadius: 10,
                            hoverBackgroundColor: '#4f46e5'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#fff',
                                bodyColor: '#e2e8f0',
                                borderColor: '#475569',
                                borderWidth: 1,
                                padding: 10
                            },
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#475569'
                                }
                            }
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeOutElastic'
                        }
                    }
                });

                // 🌴 Chart Perkebunan
                new Chart(ctxKebun, {
                    type: 'bar',
                    data: {
                        labels: DATA.perkebunan.map(p => p.jenis),
                        datasets: [{
                            label: 'Produksi (ton)',
                            data: DATA.perkebunan.map(p => p.ton),
                            backgroundColor: createGradient(ctxKebun, '#10b981', '#6ee7b7'),
                            borderRadius: 10,
                            hoverBackgroundColor: '#059669'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        animation: {
                            duration: 1200,
                            easing: 'easeOutQuart'
                        }
                    }
                });

                // 🐄 Chart Peternakan
                new Chart(ctxTernak, {
                    type: 'doughnut',
                    data: {
                        labels: DATA.peternakan.map(p => p.jenis),
                        datasets: [{
                            data: DATA.peternakan.map(p => p.jumlah),
                            backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                            borderWidth: 3,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#475569'
                                }
                            },
                            tooltip: {
                                backgroundColor: '#111827',
                                titleColor: '#f9fafb',
                                bodyColor: '#d1d5db'
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 1800,
                            easing: 'easeOutBack'
                        }
                    }
                });
            }
        </script>
    @endpush

</x-layout.terminal>
