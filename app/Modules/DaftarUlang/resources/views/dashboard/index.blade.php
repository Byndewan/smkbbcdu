@extends('layouts.admin')

@section('title', 'Dashboard Daftar Ulang')

@section('content')
    <div class="container-fluid">

        {{-- <div class="d-flex justify-content-end align-items-center mb-4">
            <div>
                <button class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh Data
                </button>
            </div>
        </div> --}}

        <div class="row mb-4">
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-0 shadow-sm h-100 bg-primary text-white overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1 text-white-50 small text-uppercase fw-bold">Total Pemasukan ( {{ $major->abbreviation ?? 'Semua Jurusan' }} )<p>
                                <h2 class="fw-bold mb-0" id="live-grand-total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h2>
                            </div>
                            <i class="bi bi-wallet2 fs-1 text-white-50"></i>
                        </div>
                        <div class="position-absolute bottom-0 end-0 p-3 opacity-10">
                            <i class="bi bi-cash-coin display-1"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-0 shadow-sm h-100 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1 text-white-50 small text-uppercase fw-bold">Siswa Lunas</p>
                                <h2 class="fw-bold mb-0" id="live-paid-count">{{ $totalPaidStudents }} <span class="fs-6 fw-normal">Siswa</span>
                                </h2>
                            </div>
                            <i class="bi bi-people-fill fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-4 mb-3">
                <div class="card border-0 shadow-sm h-100 ">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h6 class="fw-bold ">Status Sistem</h6>
                            <p class="text-muted small mb-0">
                                Semua transaksi pending akan muncul notifikasi di sidebar. Pastikan rutin melakukan
                                verifikasi.
                            </p>
                            <a href="{{ route('admin.du.transactions.index') }}" class="btn btn-sm btn-primary mt-2">Cek
                                Transaksi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header  py-3">
                <h6 class="fw-bold m-0"><i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>Rincian Pemasukan per
                    Jurusan & Tingkat</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-secondary small text-uppercase">
                            <tr>
                                <th class="ps-4">Jurusan</th>
                                <th>Tingkat</th>
                                <th>Progress Siswa</th>
                                <th class="text-end">Target (Potensi)</th>
                                <th class="text-end pe-4">Uang Masuk (Real)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($breakdowns as $row)
                                <tr>
                                    <td class="ps-4 fw-bold ">{{ $row->major_name }}</td>
                                    <td>
                                        @if ($row->current_grade == 10)
                                            <span class="badge bg-info ">Kls 10 <i
                                                    class="bi bi-arrow-right-short"></i> 11</span>
                                        @elseif($row->current_grade == 11)
                                            <span class="badge bg-warning ">Kls 11 <i
                                                    class="bi bi-arrow-right-short"></i> 12</span>
                                        @else
                                            <span class="badge bg-secondary">Kelas {{ $row->current_grade }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                @php
                                                    $percent =
                                                        $row->total_count > 0
                                                            ? ($row->paid_count / $row->total_count) * 100
                                                            : 0;
                                                @endphp
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $percent }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $row->paid_count }}/{{ $row->total_count }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end text-muted">
                                        Rp {{ number_format($row->potential_income, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold text-success pe-4">
                                        Rp {{ number_format($row->realized_income, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="fw-bold">
                            <tr>
                                <td colspan="4" class="text-end text-uppercase small">Total Pemasukan:</td>
                                <td class="text-end pe-4 text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('scripts')
<script type="module">
    Echo.channel('admin-channel')
        .listen('.payment.received', (e) => {

            let audio = new Audio('/notify.mp3');
            audio.play().catch(error => console.log('Autoplay blocked'));

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: e.type,
                title: e.message
            });

            if (e.transaction.status === 'paid') {
                let currentTotalEl = document.getElementById('live-grand-total');
                if (currentTotalEl) {
                    let currentTotal = parseInt(currentTotalEl.innerText.replace(/\./g, ''));
                    let newAmount = parseInt(e.transaction.total_amount);
                    let newTotal = currentTotal + newAmount;
                    currentTotalEl.innerText = new Intl.NumberFormat('id-ID').format(newTotal);
                    currentTotalEl.classList.add('text-success');
                    setTimeout(() => currentTotalEl.classList.remove('text-success'), 1000);
                }

                let currentCountEl = document.getElementById('live-paid-count');
                if (currentCountEl) {
                    let currentCount = parseInt(currentCountEl.innerText);
                    currentCountEl.innerText = currentCount + 1;
                }
            }
        });
</script>
@endpush
