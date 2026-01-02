<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title' ?? 'Dashboard Siswa')</title>
    @vite(['resources/css/student.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-50 font-sans">
    <nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <a href="{{ route('student.dashboard') }}" class="font-bold text-xl text-blue-600 hover:text-blue-700 transition">
            Dashboard {{ Auth::guard('student')->user()->name }}
        </a>

        <div class="flex items-center gap-5">

            <a href="{{ route('student.history') }}"
                class="p-2 bg-gray-100 rounded-full text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition relative"
                title="Riwayat Pembayaran">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{-- Notifikasi Dot (Logic DB di Blade gapapa buat prototype, next pindah ke ViewComposer ya) --}}
                @if (DB::table('du_transactions')->where('student_id', $student->id)->where('status', 'rejected')->exists())
                    <span
                        class="absolute top-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white bg-red-500 animate-pulse"></span>
                @endif
            </a>

            <a href="{{ route('student.profile.index') }}" class="flex items-center gap-3 group">
                <div class="text-right hidden md:block leading-tight">
                    <div class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition">
                        {{ $student->name }}</div>
                    <div class="text-[11px] text-gray-500">{{ $student->nipd }}</div>
                </div>
                <img src="{{ $student->photo_path ? Storage::url($student->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=random' }}"
                    alt="Foto Profil"
                    class="w-10 h-10 rounded-full object-cover border border-gray-200 group-hover:border-blue-400 transition shadow-sm">
            </a>

            <div class="h-8 w-px bg-gray-300 mx-1 hidden md:block"></div>

            <form action="{{ route('logout') }}" class="mb-0" method="POST">
                @csrf
                <button class="text-gray-500 hover:text-red-600 transition" title="Keluar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                </button>
            </form>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.9/cdn.min.js" defer></script>
    @stack('scripts')

</body>

</html>
