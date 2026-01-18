@extends('layouts.admin')

@section('title', 'Detail Siswa')
@section('no-sidebar')
@endsection
@section('no-navbar')
@endsection

@section('content')
    <div class="profile-cover rounded-4 d-flex justify-content-between align-items-start p-4 bg-primary text-white position-relative overflow-hidden"
        style="min-height: 180px;">
        <div class="z-index-1 position-relative">
            <h5 class="fw-bold mb-1 text-white"><i class="bi bi-person-vcard me-2 text-white"></i>Detail Siswa</h5>
            <small class="opacity-75">Database Kesiswaan & Keuangan</small>
        </div>
        <button onclick="window.close()" class="btn btn-light btn-sm rounded-pill fw-bold shadow-sm z-index-1">
            <i class="bi bi-x-lg me-1"></i> Tutup
        </button>

        <div class="position-absolute opacity-50" style="pointer-events: none; top: -50px !important; right: 90px !important;">
            <i class="bi bi-mortarboard-fill"
                style="font-size: 9rem; transform: rotate(-15deg) translate(20px, -20px);"></i>
        </div>
    </div>

    <div class="container-fluid px-4" style="margin-top: -100px;">
        <div class="row g-4">

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm rounded-4 mb-4 text-center overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="mb-3 position-relative d-inline-block" style="margin-top: 0;">
                            <img src="{{ $student->photo_path ? asset('storage/' . $student->photo_path) : asset($student->photo_path) }}"
                                class="rounded-circle shadow bg-white border border-4 border-white"
                                style="width: 140px; height: 140px; object-fit: cover;">
                        </div>

                        <h4 class="fw-bold mb-1 text-dark">{{ $student->name }}</h4>
                        <p class="text-muted small mb-3">{{ $student->major->name ?? '-' }}</p>

                        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                            <span class="badge bg-light text-dark border copy-btn px-3 py-2 rounded-pill"
                                onclick="copyToClipboard('{{ $student->nipd }}', 'NIPD')" title="Klik untuk salin NIPD">
                                <i class="bi bi-card-heading me-1 text-primary"></i> {{ $student->nipd }}
                            </span>
                            @if ($student->nisn)
                                <span class="badge bg-light text-dark border copy-btn px-3 py-2 rounded-pill"
                                    onclick="copyToClipboard('{{ $student->nisn }}', 'NISN')" title="Klik untuk salin NISN">
                                    NISN: {{ $student->nisn }}
                                </span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            @if ($student->phone)
                                @php $wa = preg_replace('/^0/', '62', $student->phone); @endphp
                                <a href="https://wa.me/{{ $wa }}" target="_blank"
                                    class="btn btn-success fw-bold rounded-pill shadow-sm text-white">
                                    <i class="bi bi-whatsapp me-2 text-white"></i> Hubungi WhatsApp
                                </a>
                            @else
                                <button disabled class="btn btn-light text-muted fw-bold rounded-pill">
                                    <i class="bi bi-telephone-x me-2"></i> No Phone
                                </button>
                            @endif
                            <button
                                onclick="const w = screen.availWidth;const h = screen.availHeight;window.open('{{ route('admin.core.students.edit', $student->id) }}','editStudent',`width=${w},height=${h},left=0,top=0,resizable=yes,scrollbars=yes`);"
                                class="btn btn-outline-warning fw-bold rounded-pill">
                                <i class="bi bi-pencil-square me-2"></i> Edit Data
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h6 class="fw-bold text-uppercase text-muted small ls-1"><i class="bi bi-activity me-2"></i>Status
                            Data</h6>
                    </div>
                    <div class="card-body p-4">

                        <div
                            class="d-flex justify-content-between align-items-center mb-3">
                            <span class="small fw-bold text-dark">Status Siswa</span>
                            @if ($student->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> AKTIF
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                    <i class="bi bi-x-circle-fill me-1"></i> NON-AKTIF
                                </span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="small fw-bold text-dark">Data Siswa</span>
                            <span
                                class="badge {{ $student->is_profile_completed ? 'bg-primary' : 'bg-warning text-dark' }} px-3 py-1 rounded-pill">
                                {{ $student->is_profile_completed ? 'LENGKAP' : 'BELUM LENGKAP' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small fw-bold text-dark">Status Alumni</span>
                            <span
                                class="badge {{ $student->is_graduated ? 'bg-info' : 'bg-secondary bg-opacity-10 text-secondary' }} px-3 py-1 rounded-pill">
                                {{ $student->is_graduated ? 'LULUS' : 'BELUM LULUS' }}
                            </span>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                        <ul class="nav nav-pills nav-fill gap-2 p-1 bg-light rounded-pill" id="studentTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active rounded-pill fw-bold small py-2" data-bs-toggle="tab"
                                    data-bs-target="#tab-akademik">
                                    <i class="bi bi-mortarboard me-2"></i>Akademik
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill fw-bold small py-2" data-bs-toggle="tab"
                                    data-bs-target="#tab-pribadi">
                                    <i class="bi bi-person me-2"></i>Pribadi
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill fw-bold small py-2" data-bs-toggle="tab"
                                    data-bs-target="#tab-akun">
                                    <i class="bi bi-shield-lock me-2"></i>Akun
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content" id="studentTabContent">

                            <div class="tab-pane fade show active" id="tab-akademik">
                                <h6 class="fw-bold text-primary mb-4">Informasi Sekolah</h6>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                            <small class="text-muted d-block mb-1 text-uppercase"
                                                style="font-size: 0.7rem;">Kelas Saat Ini</small>
                                            <div class="fw-bold fs-5 text-dark">
                                                {{ $student->class->name ?? 'Belum ditentukan' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                            <small class="text-muted d-block mb-1 text-uppercase"
                                                style="font-size: 0.7rem;">Jurusan</small>
                                            <div class="fw-bold fs-5 text-primary">{{ $student->major->name ?? '-' }}</div>
                                            <small class="text-muted">{{ $student->major->abbreviation ?? '' }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light-subtle">
                                            <small class="text-muted d-block mb-1 text-uppercase"
                                                style="font-size: 0.7rem;">Tahun Masuk</small>
                                            <div class="fw-bold">{{ $student->schoolYear->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light-subtle">
                                            <small class="text-muted d-block mb-1 text-uppercase"
                                                style="font-size: 0.7rem;">Riwayat Kelas / Kelas Sebelumnya</small>
                                            <div class="fw-bold text-muted">
                                                {{ $student->prev_class_id ? \App\Models\SchoolClass::find($student->prev_class_id)->name : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-pribadi">
                                <h6 class="fw-bold text-primary mb-4">Biodata Diri</h6>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Jenis Kelamin</small>
                                            <div class="fw-bold">
                                                @if ($student->gender == 'L')
                                                    <span class="text-primary"><i class="bi bi-gender-male me-1"></i>
                                                        Laki-laki</span>
                                                @else
                                                    <span class="text-danger"><i class="bi bi-gender-female me-1"></i>
                                                        Perempuan</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Tempat, Tanggal Lahir</small>
                                            <div class="fw-bold">
                                                {{ $student->pob ?? '-' }},
                                                {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->translatedFormat('d F Y') : '-' }}
                                                @if ($student->dob)
                                                    <span
                                                        class="badge bg-secondary ms-1">{{ \Carbon\Carbon::parse($student->dob)->age }}
                                                        Thn</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Nomor Telepon</small>
                                            <div class="fw-bold font-monospace">{{ $student->phone ?? '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Alamat Lengkap</small>
                                            <div class="fw-bold">{{ $student->address ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-akun">
                                <h6 class="fw-bold text-primary mb-4">Informasi Sistem</h6>

                                <div class="alert alert-light border d-flex align-items-center mb-4">
                                    <i class="bi bi-shield-lock fs-3 me-3 text-primary"></i>
                                    <div>
                                        <div class="fw-bold text-dark">Akun Login Siswa</div>
                                        <small class="text-muted">Username menggunakan email sekolah. Password default
                                            sistem.</small>
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded-3 border mb-3">
                                    <label class="small text-muted fw-bold mb-1">USERNAME / EMAIL</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white border-end-0"
                                            value="{{ $student->email }}" readonly>
                                        <button class="btn btn-white border border-start-0 text-primary"
                                            onclick="copyToClipboard('{{ $student->email }}', 'Email')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row text-muted small mt-4">
                                    <div class="col-6">
                                        Dibuat:
                                        {{ $student->created_at ? $student->created_at->format('d M Y H:i') : '-' }}
                                    </div>
                                    <div class="col-6 text-end">
                                        Update:
                                        {{ $student->updated_at ? $student->updated_at->format('d M Y H:i') : '-' }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard(text, label) {
            navigator.clipboard.writeText(text).then(function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                Toast.fire({
                    icon: 'success',
                    title: label + ' berhasil disalin'
                })
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .ls-1 {
            letter-spacing: 1px;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .copy-btn {
            cursor: pointer;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background-color: var(--bs-primary) !important;
            color: white !important;
            border-color: var(--bs-primary) !important;
        }

        .copy-btn:hover i {
            color: white !important;
        }

        .nav-pills .nav-link.active {
            background-color: var(--bs-primary);
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav-pills .nav-link {
            color: var(--bs-gray-600);
            background-color: transparent;
        }

        .profile-cover {
            background: var(--bs-primary);
        }
    </style>
@endpush
