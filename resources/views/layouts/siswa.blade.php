<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Siswa')</title>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/student.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

    @stack('styles')
</head>

<body class="bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors duration-300">

    <nav class="sticky top-4 mx-4 z-50">
        <div
            class="clay-card !rounded-2xl !p-3 flex justify-between items-center shadow-lg /80 dark:bg-slate-800/80 backdrop-blur-md">

            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-2">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-[var(--bbc-primary)] to-pink-600 flex items-center justify-center text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="hidden md:block leading-tight">
                    <h1 class="font-bold text-lg text-slate-800 dark:text-white">BBC Pay</h1>
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Student Area</p>
                </div>
            </a>

            <div class="flex items-center gap-3">

                <button id="darkToggle"
                    class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-yellow-400 hover:scale-110 transition btn-squishy">
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                @php
                    $student = Auth::guard('student')->user();
                @endphp

                <a href="{{ route('student.profile.index') }}"
                    class="flex items-center gap-3 px-3 py-1.5 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    <img src="{{ $student->photo_path ? Storage::url($student->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) }}"
                        class="w-8 h-8 rounded-full border-2 border-white dark:border-slate-600 shadow-sm object-cover">
                    <span
                        class="hidden md:block font-bold text-sm truncate max-w-[100px]">{{ explode(' ', $student->name)[0] }}</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button
                        class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center hover:bg-red-200 transition btn-squishy">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-6 pb-24">
        @yield('content')
    </main>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    @stack('scripts')
</body>

</html>
