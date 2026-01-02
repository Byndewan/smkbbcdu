<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - BBC Pay</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/admin.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            min-height: 100vh;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }

        .nav-link.active {
            background-color: #0d6efd;
            color: white !important;
        }

        .card-module:hover {
            transform: translateY(-5px);
            transition: 0.3s;
            cursor: pointer;
            border-color: #0d6efd;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="d-flex">
        @if (!request()->routeIs('admin.landing'))
            @include('layouts.partials.sidebar')
        @endif

        <div class="flex-grow-1 d-flex flex-column min-vh-100">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4">
                <div class="container-fluid">
                    <span class="navbar-brand fw-bold">BBC Pay System</span>

                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="userMenu"
                                data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}">Profil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>

            <footer class="mt-auto py-3 bg-white text-center text-muted border-top">
                <small>&copy; {{ date('Y') }} SMK Budi Bakti Ciwidey</small>
            </footer>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
