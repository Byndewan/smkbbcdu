@extends('layouts.admin')

@section('title', 'Dashboard Sampah')

@push('styles')
    <style>
        .trash-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .trash-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .border-left-danger { border-left-color: #dc3545; }
        .border-left-warning { border-left-color: #ffc107; }

        .icon-box {
            width: 50px; height: 50px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px; font-size: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-danger mb-1"><i class="bi bi-trash3-fill me-2"></i>Dashboard Arsip & Sampah</h4>
                <small class="text-muted">Kelola data yang telah dihapus sementara (Soft Delete).</small>
            </div>
            <div class="alert alert-warning border-0 py-2 px-3 m-0 small d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span>Data di sini dapat <b>Dipulihkan</b> atau <b>Dihapus Permanen</b>.</span>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card trash-card border-left-danger border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger me-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-muted mb-1 text-uppercase small">Siswa Terhapus</h6>
                            <h3 class="fw-bold mb-0">{{ $deletedStudents ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card trash-card border-left-warning border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-muted mb-1 text-uppercase small">Transaksi Batal</h6>
                            <h3 class="fw-bold mb-0">{{ $deletedTrx ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-0">
                <ul class="nav nav-tabs card-header-tabs mx-4" id="trashTab" role="tablist">
                    <li class="nav-item">
                        <a href="{{ route('admin.trash.students') }}" class="nav-link active py-3 text-danger fw-bold border-bottom-0">
                            <i class="bi bi-person-x me-2"></i> Sampah Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link py-3 text-muted disabled">
                            <i class="bi bi-wallet2 me-2"></i> Sampah Transaksi (Soon)
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-arrow-up-left-circle fs-1 mb-3 d-block opacity-25"></i>
                    <p>Silakan pilih menu di atas atau gunakan sidebar untuk mengelola jenis sampah spesifik.</p>
                    <a href="{{ route('admin.trash.students') }}" class="btn btn-outline-danger rounded-pill px-4">
                        Kelola Sampah Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
