<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard Siswa')</title>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/student.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

    <style>
        /* Global Brutalist Config */
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Syne', sans-serif;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Button Brutal Global */
        .btn-icon-brutal {
            transition: all 0.2s ease;
            border: 1px solid rgba(0,0,0,0.1);
        }
        .dark .btn-icon-brutal {
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-icon-brutal:hover {
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0px 0px rgba(0,0,0,1);
        }
        .dark .btn-icon-brutal:hover {
            box-shadow: 4px 4px 0px 0px rgba(255,255,255,1);
        }
        .btn-icon-brutal:active {
            transform: translate(0, 0);
            box-shadow: none;
        }
    </style>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #receipt-container,
            #receipt-container * {
                visibility: visible;
            }

            #receipt-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }

        .receipt-pattern {
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-white dark:bg-[#0a0a0a] text-slate-900 dark:text-slate-100 transition-colors duration-300 min-h-screen flex flex-col">

    <nav class="sticky top-0 z-50 w-full bg-white/80 dark:bg-[#0a0a0a]/80 backdrop-blur-md border-b border-black/10 dark:border-white/10">
        <div class="container mx-auto px-4 h-20 flex justify-between items-center">

            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-lg bg-black dark:bg-white text-white dark:text-black flex items-center justify-center text-lg shadow-sm group-hover:rotate-6 transition-transform duration-300">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div class="leading-none">
                    <h1 class="font-display font-bold text-xl tracking-tight">BBC PAY</h1>
                    <span class="text-[10px] font-mono font-medium text-slate-500 dark:text-slate-400 uppercase tracking-widest">Student Area</span>
                </div>
            </a>

            <div class="flex items-center gap-3 md:gap-4">

                <button id="darkToggle" class="w-10 h-10 rounded-lg bg-gray-50 dark:bg-slate-900 flex items-center justify-center text-slate-600 dark:text-yellow-400 btn-icon-brutal">
                    <i class="bi bi-moon-stars-fill hidden dark:block"></i>
                    <i class="bi bi-sun-fill block dark:hidden"></i>
                </button>

                @php
                    $student = Auth::guard('student')->user();
                @endphp

                <a href="{{ route('student.profile.index') }}" class="flex items-center gap-3 px-1 py-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-900 transition border border-transparent hover:border-black/5 dark:hover:border-white/10">
                    <img src="{{ $student->photo_path ? Storage::url($student->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) }}"
                        class="w-9 h-9 rounded-full object-cover border border-black/10 dark:border-white/20">
                    <div class="hidden md:block text-right pr-2">
                        <span class="block font-display font-bold text-sm leading-none">{{ explode(' ', $student->name)[0] }}</span>
                        <span class="text-[10px] text-slate-400 font-mono">{{ $student->nisn ?? 'Siswa' }}</span>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button class="w-10 h-10 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 flex items-center justify-center btn-icon-brutal border-red-200 dark:border-red-800" title="Keluar">
                        <i class="bi bi-box-arrow-right text-lg"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8 flex-grow">
        @yield('content')
    </main>

    <footer class="border-t border-black/5 dark:border-white/5 py-6 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p class="text-xs text-slate-400 font-mono">
                &copy; {{ date('Y') }} BBC Pay System. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        const darkToggle = document.getElementById('darkToggle');
        darkToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
        });
    </script>

    <script type="module">
        setTimeout(() => {
            if (typeof window.Echo === 'undefined') return;
            const studentId = "{{ Auth::guard('student')->id() }}";
            const channelName = `student.${studentId}`;

            console.log(`📡 Siswa Listening di channel: ${channelName}`);

            window.Echo.channel(channelName)
                .listen('.transaction.updated', (e) => {
                    console.log("🔔 Update Transaksi diterima:", e);

                    // Style SweetAlert biar match tema
                    const isDark = document.documentElement.classList.contains('dark');

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Status Diperbarui!',
                        text: 'Halaman akan dimuat ulang...',
                        showConfirmButton: false,
                        timer: 2000,
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#ffffff' : '#0f172a'
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                });
        }, 1000);
    </script>

    @stack('scripts')
</body>
</html>
