@extends('layouts.admin')

@section('title', 'Menu Utama')

@section('content')
<style>
    .card-module:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        border-color: var(--bbc-primary, #0d6efd) !important;
    }
    .quick-action-btn {
        transition: all 0.2s;
        border: none;
    }
    .quick-action-btn:hover {
        transform: translateY(-2px);
        filter: brightness(0.95);
    }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-5 bg-white p-4 rounded-4 shadow-sm border border-light animate__animated animate__fadeInDown">
        <div class="d-flex align-items-center">
            <div class="me-4 shadow-sm rounded-circle p-1 border">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=000000&color=fff&bold=true" class="rounded-circle" width="64" height="64">
            </div>
            <div>
                <h3 class="fw-bold text-dark mb-1">Halo, {{ Auth::user()->name }}!</h3>
                <p class="text-muted mb-0">Selamat datang. Ada yang bisa dibantu hari ini?</p>
            </div>
        </div>
        {{-- <div class="text-end d-none d-md-block">
            <p class="text-muted small mb-1">Waktu Login</p>
            <h6 class="fw-bold text-primary">{{ now()->translatedFormat('l, d F Y - H:i') }}</h6>
        </div> --}}
    </div>

    {{-- KUMPULAN PINTASAN / SHORTCUTS (BANYAK!) --}}
    <div class="mb-5 animate__animated animate__fadeInUp">
        <h6 class="fw-bold text-muted text-uppercase mb-3" style="letter-spacing: 1px; font-size: 12px;">-- Akses Cepat (1-Click Actions)</h6>
        <div class="d-flex gap-3 flex-wrap">

            <a href="{{ route('admin.finance.index') }}" class="btn btn-warning rounded-pill px-4 py-2 fw-bold shadow-sm quick-action-btn text-dark">
                <i class="bi bi-check-circle-fill me-2"></i> Verifikasi Transfer
            </a>

            <a href="{{ route('admin.du.bills.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm quick-action-btn text-white">
                <i class="bi bi-receipt me-2"></i> Buat Tagihan Baru
            </a>

            <a href="{{ route('admin.core.students.index') }}" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm quick-action-btn text-white">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah Siswa Baru
            </a>

            <a href="{{ route('admin.du.transactions.index') }}" class="btn bg-info bg-opacity-10 text-info border border-info rounded-pill px-4 py-2 fw-bold shadow-sm quick-action-btn">
                <i class="bi bi-list-columns me-2"></i> Daftar Transaksi Siswa
            </a>

            <a href="{{ route('admin.finance.report') }}" class="btn bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-4 py-2 fw-bold shadow-sm quick-action-btn">
                <i class="bi bi-printer-fill me-2"></i> Cetak Laporan Keuangan
            </a>

            {{-- <a href="/archivist/filemanager?type=image" target="_blank" class="btn btn-danger rounded-pill px-4 py-2 fw-bold shadow-sm quick-action-btn text-white">
                <i class="bi bi-folder-fill me-2"></i> Buka File Manager
            </a> --}}

            <a href="{{ route('admin.settings.front.index') }}" class="btn btn-dark rounded-pill px-4 py-2 fw-bold shadow-sm quick-action-btn text-white">
                <i class="bi bi-browser-chrome me-2"></i> Edit Website
            </a>

        </div>
    </div>

    {{-- KOTAK MODUL --}}
    {{-- <h6 class="fw-bold text-muted text-uppercase mb-3" style="letter-spacing: 1px; font-size: 12px;">📦 Modul Utama</h6>
    <div class="row g-4 animate__animated animate__fadeInUp animate__delay-1s">

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.du.dashboard') }}" class="text-decoration-none">
                <div class="card h-100 border border-light shadow-sm text-center p-4 card-module transition-all rounded-4">
                    <div class="card-body">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-card-checklist fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Daftar Ulang</h5>
                        <p class="text-muted small mb-0">Kelola tagihan dan transaksi siswa.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.finance.dashboard') }}" class="text-decoration-none">
                <div class="card h-100 border border-light shadow-sm text-center p-4 card-module transition-all rounded-4">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-wallet2 fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Keuangan</h5>
                        <p class="text-muted small mb-0">Analytics dan verifikasi pembayaran.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.core.students.index') }}" class="text-decoration-none">
                <div class="card h-100 border border-light shadow-sm text-center p-4 card-module transition-all rounded-4">
                    <div class="card-body">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-database fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Master Data</h5>
                        <p class="text-muted small mb-0">Basis data siswa, kelas, dan jurusan.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.settings.front.index') }}" class="text-decoration-none">
                <div class="card h-100 border border-light shadow-sm text-center p-4 card-module transition-all rounded-4">
                    <div class="card-body">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-sliders fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Pengaturan</h5>
                        <p class="text-muted small mb-0">Atur Landing Page dan Role User.</p>
                    </div>
                </div>
            </a>
        </div>
    </div> --}}
</div>
@endsection
