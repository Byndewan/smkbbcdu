<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - BBC Pay</title>

    <script>
        (function() {
            const getStoredTheme = () => localStorage.getItem('theme');
            const setTheme = theme => {
                document.documentElement.setAttribute('data-bs-theme', theme);
            }
            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme();
                if (storedTheme) return storedTheme;
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            setTheme(getPreferredTheme());
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (!getStoredTheme()) setTheme(getPreferredTheme());
            });
        })();
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/admin.scss', 'resources/js/app.js'])
    <style>
        body,
        .card,
        .sidebar {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="d-flex">
        @if (!request()->routeIs('admin.landing') && !View::hasSection('no-sidebar'))
            @include('layouts.partials.sidebar')
        @endif

        <div class="flex-grow-1 d-flex flex-column min-vh-100 overflow-hidden">

            @if (!View::hasSection('no-navbar'))
            <nav class="navbar navbar-expand-lg px-4 pt-3">
                <div class="container-fluid">
                    <button class="btn btn-light d-md-none me-2"><i class="bi bi-list"></i></button>

                    <div>
                        <h5 class="fw-bold mb-0 text-primary">@yield('title')</h5>
                        <small class="text-muted">{{ date('l, d F Y') }}</small>
                    </div>

                    <div class="ms-auto d-flex align-items-center gap-3">
                        <button class="btn btn-light rounded-circle shadow-sm" id="themeToggle" title="Ganti Tema">
                            <i class="bi bi-circle-half"></i>
                        </button>

                        <div class="position-relative">
                            <button class="btn btn-light rounded-circle shadow-sm">
                                <i class="bi bi-bell"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
                                    id="notif-badge">
                                    0
                                </span>
                            </button>
                        </div>

                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle "
                                data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=var(--bbc-primary)&color=fff"
                                    width="38" height="38" class="rounded-circle shadow-sm me-2">
                                <div class="d-none d-md-block text-start lh-sm">
                                    <div class="fw-bold small">{{ Auth::user()->name }}</div>
                                    <div class="text-muted" style="font-size: 10px;">
                                        {{ Auth::user()->roles->first()->name ?? 'Admin' }}</div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4">
                                <li><a class="dropdown-item rounded-3"
                                        href="{{ route('admin.settings.profile.index') }}"><i
                                            class="bi bi-person me-2"></i> Profil Saya</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded-3 text-danger"><i
                                                class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            @endif

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>

        <div class="modal fade" id="modal-master" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="height: 90vh;">
                <div class="modal-content h-100" id="modal-content">
                    <div class="p-5 text-center">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p>Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    </script>

    @stack('scripts')
</body>

</html>
