<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BBC Pay</title>
    @vite(['resources/css/admin.scss'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: var(--bbc-bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            background: var(--bs-card-bg);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
        }
        .form-control {
            padding: 0.8rem 1rem;
            border-radius: 12px;
            background-color: var(--bbc-bg-light);
            border: 1px solid transparent;
        }
        .form-control:focus {
            background-color: var(--bs-body-bg);
            border-color: var(--bbc-primary);
            box-shadow: 0 0 0 4px rgba(var(--bbc-primary), 0.1);
        }
        .btn-primary {
            padding: 0.8rem;
            border-radius: 12px;
            font-size: 1rem;
        }
    </style>
</head>
<body>

    <div class="login-card animate__animated animate__fadeInUp">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-wallet2 fs-2"></i>
            </div>
            <h3 class="fw-bold mb-1">Selamat Datang</h3>
            <p class="text-muted small">Silakan login untuk melanjutkan</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger d-flex align-items-center mb-4 rounded-3 p-3">
                <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                <div class="small fw-bold">{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light rounded-start-3 ps-3"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="text" name="email" class="form-control" placeholder="Isi email anda . . ." required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light rounded-start-3 ps-3"><i class="bi bi-key text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            {{-- <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                </div>
                <a href="#" class="small text-decoration-none fw-bold text-primary">Lupa Password?</a>
            </div> --}}

            <div class="d-grid">
                <button type="submit" class="btn btn-primary fw-bold">
                    MASUK SEKARANG <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted small mb-0">Belum Baca Panduan?</p>
            <a href="{{ route('landing') }}#howItWorksSection" class="text-decoration-none fw-bold small">Klik Disini</a>
        </div>
    </div>

</body>
</html>
