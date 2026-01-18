@extends('layouts.admin')

@section('title', 'Pilih Modul')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5 animate__animated animate__fadeInDown">
            <div class="d-inline-flex align-items-center justify-content-center bg-white p-2 rounded-circle shadow-sm mb-3">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=var(--bbc-primary)&color=fff"
                    class="rounded-circle" width="64" height="64">
            </div>
            <h2 class="fw-bold text-dark">Halo, {{ Auth::user()->name }}!</h2>
            <p class="text-muted">Akses modul manajemen sekolah dengan mudah.</p>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-md-6 col-lg-3">
                <a href="{{ url('admin/daftar-ulang/') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm text-center p-4 card-module transition-all">
                        <div class="card-body">
                            <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-4 mb-4 mx-auto d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-card-checklist fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Daftar Ulang</h5>
                            <p class="text-muted small mb-0">Kelola tagihan, transaksi, dan verifikasi siswa.</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="{{ url('admin/keuangan/dashboard') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm text-center p-4 card-module transition-all">
                        <div class="card-body">
                            <div class="icon-shape bg-success bg-opacity-10 text-success rounded-4 mb-4 mx-auto d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-wallet2 fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Keuangan</h5>
                            <p class="text-muted small mb-0">Laporan pemasukan dan verifikasi pembayaran.</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="{{ url('admin/core/dashboard') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm text-center p-4 card-module transition-all">
                        <div class="card-body">
                            <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-4 mb-4 mx-auto d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-database fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Master Data</h5>
                            <p class="text-muted small mb-0">Data siswa, kelas, jurusan, dan pengguna.</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="{{ url('admin/settings/front') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm text-center p-4 card-module transition-all">
                        <div class="card-body">
                            <div class="icon-shape bg-secondary bg-opacity-10 text-secondary rounded-4 mb-4 mx-auto d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-sliders fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Pengaturan</h5>
                            <p class="text-muted small mb-0">Atur tampilan landing page dan profil.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
