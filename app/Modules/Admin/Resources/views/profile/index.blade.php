@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark">Pengaturan Akun Admin</h4>
        </div>

        <div class="row">

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-5">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d6efd&color=fff&size=128"
                            class="rounded-circle mb-3 shadow-sm" alt="Admin Avatar">

                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-3">{{ $user->email }}</p>

                        <span class="badge bg-primary px-3 py-2 rounded-pill">
                            {{ $user->getRoleNames()->first() ?? 'Admin' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                        <ul class="nav nav-tabs card-header-tabs" id="adminProfileTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-bold" id="biodata-tab" data-bs-toggle="tab"
                                    data-bs-target="#biodata" type="button">
                                    <i class="bi bi-person me-2"></i>Edit Profil
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold" id="password-tab" data-bs-toggle="tab"
                                    data-bs-target="#password" type="button">
                                    <i class="bi bi-shield-lock me-2"></i>Ganti Password
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content" id="adminProfileTabContent">

                            <div class="tab-pane fade show active" id="biodata">
                                <form action="{{ route('admin.profile.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $user->name) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Email Login</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $user->email) }}" required>
                                    </div>

                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="password">
                                <div class="alert alert-warning border-0 d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                    <div class="small">Demi keamanan, ganti password Anda secara berkala.</div>
                                </div>

                                <form action="{{ route('admin.profile.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Password Saat Ini</label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" class="form-control" required>
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePass(this)"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" required
                                                minlength="6">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePass(this)"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Konfirmasi Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" class="form-control"
                                                required minlength="6">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePass(this)"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>

                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-danger px-4 fw-bold">
                                            <i class="bi bi-key me-2"></i> Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function togglePass(btn) {
                let input = btn.previousElementSibling;
                let icon = btn.querySelector('i');

                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = "password";
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        </script>
        <style>
            #adminProfileTab .nav-link {
                color: #6c757d;
            }

            #adminProfileTab .nav-link:hover {
                color: #000;
            }

            #adminProfileTab .nav-link.active {
                color: #000 !important;
                border-bottom-color: #fff;
            }
        </style>
    @endpush

@endsection
