@extends('layouts.admin')

@section('title', 'Dashboard Daftar Ulang')

@section('content')

    <ul class="nav nav-pills mb-4 gap-2" id="dashboardTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold px-4" id="ringkas-tab" data-bs-toggle="tab" data-bs-target="#ringkas-pane"
                type="button" role="tab">
                <i class="bi bi-grid-fill me-2"></i> Ringkasan
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-4" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail-pane"
                type="button" role="tab">
                <i class="bi bi-table me-2"></i> Rincian Detail
            </button>
        </li>
    </ul>

    <div class="tab-content" id="dashboardTabContent">

        <div class="tab-pane fade show active" id="ringkas-pane" role="tabpanel">
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 text-white overflow-hidden"
                        style="background: linear-gradient(135deg, var(--bbc-primary) 0%, #be123c 100%);">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start z-1 position-relative">
                                <div>
                                    <p class="mb-1 text-white-50 fw-bold small text-uppercase">Total Pemasukan</p>
                                    <h2 class="fw-bold mb-0">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h2>
                                    <span class="badge bg-white bg-opacity-25 mt-2 rounded-pill fw-normal"><i
                                            class="bi bi-arrow-up-short"></i> Realtime</span>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-3 p-2"><i class="bi bi-wallet2 fs-3"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 text-white overflow-hidden"
                        style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start z-1 position-relative">
                                <div>
                                    <p class="mb-1 text-white-50 fw-bold small text-uppercase">Siswa Lunas</p>
                                    <h2 class="fw-bold mb-0">{{ $totalPaidStudents }}</h2>
                                    <span class="text-white-50 small">Siswa Aktif</span>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-3 p-2"><i class="bi bi-check-circle fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 d-flex flex-column justify-content-center">
                            <h6 class="fw-bold text-dark">Status Sistem</h6>
                            <p class="text-muted small mb-3">Klik tombol di bawah untuk memverifikasi transaksi masuk.</p>
                            <a href="{{ route('admin.du.transactions.index') }}" class="btn btn-primary w-100"><i
                                    class="bi bi-list-check me-2"></i> Cek Transaksi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="detail-pane" role="tabpanel">

            <div id="skeleton-loader" class="p-4">
                <div class="row g-4">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 animate-pulse" style="height: 100px;"></div>
                        </div>
                    @endfor
                </div>
            </div>

            <div id="detail-content" class="d-none space-y-8">

                <div id="cards-container"></div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold m-0"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Grafik Perbandingan
                            Keuangan</h6>
                    </div>
                    <div class="card-body" style="height: 400px;">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold m-0 d-flex align-items-center">
                            <i class="bi bi-table me-2 text-primary"></i> Rincian Per Kelas
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover w-100">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4">Jurusan</th>
                                    <th>Kelas</th>
                                    <th>Progress Bayar</th>
                                    <th class="text-end">Potensi (Rp)</th>
                                    <th class="text-end pe-4">Real Masuk (Rp)</th>
                                </tr>
                            </thead>
                            <tbody id="detail-table-body"></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let isDetailLoaded = false;

            $('button[data-bs-target="#detail-pane"]').on('shown.bs.tab', function(e) {
                if (!isDetailLoaded) {
                    loadDetailData();
                }
            });

            function loadDetailData() {
                $.ajax({
                    url: "{{ route('admin.du.dashboard.detail') }}",
                    method: "GET",
                    success: function(response) {
                        // console.log(response);
                        // throw new Error('STOP HERE');
                        processData(response.data);
                        $('#skeleton-loader').addClass('d-none');
                        $('#detail-content').removeClass('d-none').addClass(
                            'animate__animated animate__fadeIn');
                        isDetailLoaded = true;
                    },
                    error: function() {
                        $('#skeleton-loader').html(
                            '<div class="text-center text-danger p-4">Gagal memuat data.</div>');
                    }
                });
            }
            function processData(data) {
                let grouped = {};
                let chartLabels = [];
                let chartPotential = [];
                let chartRealized = [];
                data.forEach(item => {
                    let grade = item.current_grade.split(' ')[0];

                    if (!grouped[grade]) {
                        grouped[grade] = {
                            grade: grade,
                            majors: {},
                            total_students: 0,
                            total_potential: 0,
                            total_realized: 0
                        };
                    }
                    if (!grouped[grade].majors[item.major_name]) {
                        grouped[grade].majors[item.major_name] = {
                            students: 0,
                            potential: 0,
                            realized: 0
                        };
                    }
                    grouped[grade].majors[item.major_name].students += parseInt(item.total_count);
                    grouped[grade].majors[item.major_name].potential += parseFloat(item.potential_income);
                    grouped[grade].majors[item.major_name].realized += parseFloat(item.realized_income);
                    grouped[grade].total_students += parseInt(item.total_count);
                    grouped[grade].total_potential += parseFloat(item.potential_income);
                    grouped[grade].total_realized += parseFloat(item.realized_income);
                });
                let cardsHtml = '';
                Object.keys(grouped).sort().forEach(gradeKey => {
                    let g = grouped[gradeKey];
                    cardsHtml += `<div class="mb-5">`;
                    cardsHtml +=
                        `<h5 class="fw-bold text-slate-700 mb-3 border-start border-4 border-primary ps-3">Data Kelas ${g.grade}</h5>`;
                    cardsHtml += `<div class="row g-3 mb-3">`;
                    Object.keys(g.majors).forEach(majorName => {
                        let m = g.majors[majorName];
                        cardsHtml += generateSmallCard(majorName, m.students, 'Siswa', 'bi-person',
                            'text-primary', 'bg-blue-50');
                    });
                    cardsHtml += generateSmallCard(`Total Siswa Kelas ${g.grade}`, g.total_students,
                        'Total', 'bi-people-fill', 'text-white', 'bg-primary text-white');
                    cardsHtml += `</div>`;
                    cardsHtml +=
                        `<div class="row g-3 mb-3"><div class="col-12"><small class="text-muted fw-bold uppercase">Target Pemasukan</small></div>`;
                    Object.keys(g.majors).forEach(majorName => {
                        let m = g.majors[majorName];
                        cardsHtml += generateMoneyCard(majorName, m.potential, 'text-info',
                            'border-info');
                    });
                    cardsHtml += generateMoneyCard(`Total Kelas ${g.grade}`, g.total_potential,
                        'text-primary', 'border-primary bg-primary bg-opacity-10');
                    cardsHtml += `</div>`;
                    cardsHtml +=
                        `<div class="row g-3"><div class="col-12"><small class="text-muted fw-bold uppercase">Realisasi (Masuk)</small></div>`;
                    Object.keys(g.majors).forEach(majorName => {
                        let m = g.majors[majorName];
                        cardsHtml += generateMoneyCard(majorName, m.realized, 'text-success',
                            'border-success');
                    });
                    cardsHtml += generateMoneyCard(`Total Kelas ${g.grade}`, g.total_realized,
                        'text-success', 'border-success bg-success bg-opacity-10');
                    cardsHtml += `</div>`;
                    cardsHtml += `</div><hr class="my-5 border-secondary opacity-10">`;
                    chartLabels.push(`Kelas ${g.grade}`);
                    chartPotential.push(g.total_potential);
                    chartRealized.push(g.total_realized);
                });

                $('#cards-container').html(cardsHtml);
                renderChart(chartLabels, chartPotential, chartRealized);
                renderDetailTable(data);
            }

            function generateSmallCard(title, value, sub, icon, textColor, bgColor) {
                return `
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 ${bgColor}">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <small class="opacity-75 d-block mb-1">${title}</small>
                                <h3 class="fw-bold mb-0 ${textColor}">${value}</h3>
                            </div>
                            <div class="rounded-circle p-2 bg-white bg-opacity-25 ${textColor}">
                                <i class="bi ${icon} fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>`;
            }

            function generateMoneyCard(title, amount, textColor, borderColor) {
                return `
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 border-start border-4 ${borderColor}">
                        <div class="card-body">
                            <small class="text-muted d-block mb-1">${title}</small>
                            <h5 class="fw-bold mb-0 ${textColor}">Rp ${new Intl.NumberFormat('id-ID').format(amount)}</h5>
                        </div>
                    </div>
                </div>`;
            }

            function renderDetailTable(data) {
                let html = '';
                data.forEach(item => {
                    let percent = item.total_count > 0 ? (item.paid_count / item.total_count) * 100 : 0;
                    let color = percent >= 80 ? 'bg-success' : (percent >= 50 ? 'bg-warning' : 'bg-danger');
                    html += `
                    <tr>
                        <td class="ps-4 fw-bold text-dark">${item.major_name}</td>
                        <td><span class="badge bg-light text-dark border border-secondary border-opacity-25">${item.current_grade}</span></td>
                        <td style="width: 30%;">
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar ${color}" style="width: ${percent}%"></div>
                                </div>
                                <span class="small fw-bold text-muted">${Math.round(percent)}%</span>
                            </div>
                        </td>
                        <td class="text-end text-muted font-monospace small">Rp ${new Intl.NumberFormat('id-ID').format(item.potential_income)}</td>
                        <td class="text-end fw-bold text-success pe-4 font-monospace">Rp ${new Intl.NumberFormat('id-ID').format(item.realized_income)}</td>
                    </tr>
                `;
                });
                $('#detail-table-body').html(html);
            }

            function renderChart(labels, potential, realized) {
                const ctx = document.getElementById('incomeChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Target (Potensi)',
                                data: potential,
                                backgroundColor: '#cbd5e1',
                                borderRadius: 5
                            },
                            {
                                label: 'Realisasi (Masuk)',
                                data: realized,
                                backgroundColor: '#10b981',
                                borderRadius: 5
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
@push('styles')
    <style>
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }

            100% {
                opacity: 1;
            }
        }

        .animate-pulse {
            animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .nav-pills .nav-link {
            color: #64748b;
            background: #fff;
            border: 1px solid #e2e8f0;
        }

        .nav-pills .nav-link.active {
            background: var(--bbc-primary);
            color: white;
            border-color: var(--bbc-primary);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
