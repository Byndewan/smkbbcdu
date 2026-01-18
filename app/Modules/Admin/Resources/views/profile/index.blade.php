@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <div class="position-relative d-inline-block mx-auto mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=var(--bbc-primary)&color=fff&size=128"
                        class="rounded-circle shadow-sm" width="120" height="120">
                    <span
                        class="position-absolute bottom-0 end-0 bg-success p-2 border border-2 border-white rounded-circle"></span>
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                    {{ $user->roles->first()->name ?? 'Admin' }}
                </span>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom-0 pb-0">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold small text-uppercase" id="bio-tab" data-bs-toggle="tab"
                                data-bs-target="#bio">
                                <i class="bi bi-person me-2"></i> Biodata
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold small text-uppercase" id="pass-tab" data-bs-toggle="tab"
                                data-bs-target="#pass">
                                <i class="bi bi-key me-2"></i> Password
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="bio">
                            <form action="{{ route('admin.settings.profile.update') }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control bg-light border-0 py-2"
                                        value="{{ $user->name }}" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted">Email Login</label>
                                    <input type="email" name="email" class="form-control bg-light border-0 py-2"
                                        value="{{ $user->email }}" required>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pass">
                            <form action="{{ route('admin.settings.profile.password') }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Password Lama</label>
                                    <input type="password" name="current_password"
                                        class="form-control bg-light border-0 py-2" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Password Baru</label>
                                    <input type="password" name="password" class="form-control bg-light border-0 py-2"
                                        required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control bg-light border-0 py-2" required>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-danger px-4">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
