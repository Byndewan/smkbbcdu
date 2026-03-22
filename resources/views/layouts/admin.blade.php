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
        {{-- @if (!request()->routeIs('admin.landing') && !View::hasSection('no-sidebar'))
            @include('layouts.partials.sidebar')
        @endif --}}

        @if (!View::hasSection('no-sidebar'))
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

                            <div class="dropdown">
                                <button class="btn btn-light rounded-circle shadow-sm position-relative" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false" id="notifDropdownBtn">
                                    <i class="bi bi-bell"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
                                        id="notif-badge">
                                        0
                                    </span>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-0 rounded-4 overflow-hidden"
                                    style="width: 320px; max-height: 400px; overflow-y: auto;">
                                    <li
                                        class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold">Notifikasi</h6>
                                        <small class="text-primary" style="cursor: pointer !important"
                                            onclick="markAllRead()">Tandai Semua
                                            Dibaca</small>
                                    </li>

                                    <div id="notification-list">
                                        <li class="text-center p-4 text-muted" id="empty-notif">
                                            <i
                                                class="bi bi-bell-slash display-6 mb-2 d-block text-secondary opacity-25"></i>
                                            <small>Belum ada notifikasi baru</small>
                                        </li>
                                    </div>
                                </ul>
                            </div>

                            <div class="dropdown">
                                <a href="#"
                                    class="d-flex align-items-center text-decoration-none dropdown-toggle "
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

    <script type="module">
        // 1. Kita pastikan Echo ada di window
        // Karena pakai type="module", dia akan nunggu app.js selesai loading dulu

        console.log("⏳ Menunggu Echo siap...");

        // Fungsi inisialisasi listener
        const initEcho = () => {
            if (typeof window.Echo === 'undefined') {
                console.error("❌ Echo belum terload! Cek resources/js/app.js kamu.");
                return;
            }

            console.log("✅ Echo Siap! Subscribe ke admin-channel...");

            window.Echo.channel('admin-channel')
                .listen('.payment.received', (e) => {
                    // console.log('EVENT MASUK:', e);
                    let audio = new Audio('/notify.mp3');
                    audio.play().catch(error => console.log('Autoplay blocked:', error));

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
                    });

                    Toast.fire({
                        icon: 'info',
                        title: e.message
                    });
                    let currentTotalEl = document.getElementById('live-grand-total');
                    if (currentTotalEl && e.transaction.total_amount) {
                        let currentTotal = parseInt(currentTotalEl.innerText.replace(/[^0-9]/g, '')) || 0;
                        let newAmount = parseInt(e.transaction.total_amount);
                        let newTotal = currentTotal + newAmount;
                        currentTotalEl.innerText = new Intl.NumberFormat('id-ID').format(newTotal);
                        currentTotalEl.classList.add('text-success');
                        setTimeout(() => currentTotalEl.classList.remove('text-success'), 1000);
                    }

                    let currentCountEl = document.getElementById('live-paid-count');
                    if (currentCountEl && e.transaction.status === 'paid') {
                        let currentCount = parseInt(currentCountEl.innerText.replace(/[^0-9]/g, '')) || 0;
                        currentCountEl.innerText = currentCount + 1;
                    }
                });
        };
        setTimeout(initEcho, 1000);
    </script>

    <script>
        setTimeout(() => {
            if (typeof window.Echo === 'undefined') return;
            window.Echo.channel('admin-channel')
                .listen('.payment.received', (e) => {
                    const badge = document.getElementById('notif-badge');
                    if (badge) {
                        let currentCount = parseInt(badge.innerText) || 0;
                        badge.innerText = currentCount + 1;
                        badge.classList.remove('d-none');
                        badge.classList.add('animate__animated', 'animate__bounce');
                    }

                    const listContainer = document.getElementById('notification-list');
                    const emptyState = document.getElementById('empty-notif');

                    if (listContainer) {
                        if (emptyState) emptyState.style.display = 'none';

                        const timeNow = new Date().toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        const newItem = `
                <a href="#" class="list-group-item list-group-item-action p-3 border-bottom border-light notif-item" onclick="markOneRead(this, event)">
                    <div class="d-flex align-items-start">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 flex-shrink-0" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 small fw-bold text-dark">${e.message || 'Transaksi Baru'}</h6>
                            <small class="text-primary" style="font-size: 10px;">Baru saja • ${timeNow}</small>
                        </div>
                    </div>
                </a>
            `;

                        listContainer.insertAdjacentHTML('afterbegin', newItem);
                    }
                });
        }, 1000);

        window.markAllRead = function() {
            const badge = document.getElementById('notif-badge');
            if (badge) {
                badge.innerText = '0';
                badge.classList.add('d-none');
            }
            const allItems = document.querySelectorAll('.notif-item');
            allItems.forEach(item => {
                item.remove();
            });
            const emptyState = document.getElementById('empty-notif');
            if (emptyState) {
                emptyState.style.display = 'block';
            }
        }

        window.markOneRead = function(element, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            element.remove();
            const badge = document.getElementById('notif-badge');
            if (badge) {
                let currentCount = parseInt(badge.innerText) || 0;
                let newCount = currentCount - 1;
                if (newCount < 0) newCount = 0;
                badge.innerText = newCount;
                if (newCount === 0) {
                    badge.classList.add('d-none');
                }
            }
            const listContainer = document.getElementById('notification-list');
            const remainingItems = listContainer.querySelectorAll('.notif-item').length;
            if (remainingItems === 0) {
                const emptyState = document.getElementById('empty-notif');
                if (emptyState) {
                    emptyState.style.display = 'block';
                }
            }
        }

        document.getElementById('notifDropdownBtn').addEventListener('show.bs.dropdown', function() {
            // markAllRead();
        });
    </script>

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
