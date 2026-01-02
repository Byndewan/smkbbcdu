@extends('layouts.admin')

@section('title', 'Pilih Modul')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Selamat Datang, {{ Auth::user()->name }} 👋</h2>
        <p class="text-muted">Silakan pilih modul yang ingin Anda kelola hari ini.</p>
    </div>

    <div class="row justify-content-center g-4">
        <div class="col-md-4">
            <div class="card card-module h-100 border-0 shadow-sm" onclick="window.location='{{ url('admin/daftar-ulang/') }}'">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-card-checklist fs-1"></i>
                    </div>
                    <h4 class="card-title fw-bold">Daftar Ulang</h4>
                    <p class="card-text text-muted">Kelola tagihan, verifikasi bukti transfer manual, dan monitoring status daftar ulang siswa.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-module h-100 border-0 shadow-sm" onclick="window.location='{{ url('admin/core/dashboard') }}'">
                <div class="card-body p-4 text-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-database fs-1"></i>
                    </div>
                    <h4 class="card-title fw-bold">Master Data</h4>
                    <p class="card-text text-muted">Kelola data siswa, jurusan, tahun ajaran, dan manajemen user admin/petugas.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-module h-100 border-0 shadow-sm" onclick="window.location='{{ url('admin/keuangan/dashboard') }}'">
                <div class="card-body p-4 text-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-wallet2 fs-1"></i>
                    </div>
                    <h4 class="card-title fw-bold">Keuangan</h4>
                    <p class="card-text text-muted">Laporan keuangan masuk, pengaturan rekening bank, dan integrasi Payment Gateway.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-module h-100 border-0 shadow-sm" onclick="window.location='{{ url('admin/settings/front') }}'">
                <div class="card-body p-4 text-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-gear fs-1"></i>
                    </div>
                    <h4 class="card-title fw-bold">Pengaturan</h4>
                    <p class="card-text text-muted">Kelola pengaturan tampilan landing page untuk menyesuaikan konten</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
