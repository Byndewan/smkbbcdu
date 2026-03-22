<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBC Pay - Sistem Pembayaran Sekolah Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif']
                    },
                    colors: {
                        bbc: {
                            500: 'oklab(55.91% 0.20543 0.09128)',
                            600: '#be123c'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .hero-pattern {
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .blob {
            position: absolute;
            filter: blur(40px);
            z-index: -1;
            opacity: 0.4;
        }
    </style>
</head>

<body class="font-sans text-slate-600 antialiased selection:bg-red-100 selection:text-red-600">

    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ $setting->logo ?? '' }}" alt="Logo" class="h-10 w-auto">
                    <span class="font-bold text-xl text-slate-800 tracking-tight hidden sm:block">BBC Pay</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#fitur" class="text-sm font-medium hover:text-[var(--bbc-primary)] transition">Fitur</a>
                    <a href="#panduan"
                        class="text-sm font-medium hover:text-[var(--bbc-primary)] transition">Panduan</a>
                    <a href="#faq" class="text-sm font-medium hover:text-[var(--bbc-primary)] transition">FAQ</a>
                </div>
                <div class="flex items-center gap-4">
                    @auth('student')
                        <a href="{{ route('student.dashboard') }}"
                            class="px-6 py-2.5 rounded-full bg-[var(--bbc-primary)] text-white font-bold text-sm hover:opacity-90 transition shadow-lg shadow-red-200">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition">
                            Login Siswa
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div
            class="hero-pattern absolute inset-0 -z-10 h-full w-full bg-white [mask-image:radial-gradient(100%_50%_at_top_center,white,transparent)]">
        </div>
        <div class="blob bg-red-200 w-96 h-96 rounded-full top-0 left-0 mix-blend-multiply animate-blob"></div>
        <div
            class="blob bg-blue-200 w-96 h-96 rounded-full top-0 right-0 mix-blend-multiply animate-blob animation-delay-2000">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 text-[var(--bbc-primary)] text-xs font-bold uppercase tracking-wider mb-8 border border-red-100">
                <span class="w-2 h-2 rounded-full bg-[var(--bbc-primary)] animate-pulse"></span>
                {{ $setting->hero_title ?? 'Sistem Pembayaran Digital' }}
            </span>
            <h1 class="text-5xl md:text-7xl font-[800] text-slate-900 tracking-tight mb-8 leading-tight">
                {{ $setting->hero_heading ?? 'Bayar Sekolah Jadi Lebih Mudah' }}
            </h1>
            <p class="text-xl text-slate-500 mb-10 max-w-2xl mx-auto leading-relaxed">
                {!! $setting->hero_sort_desc ??
                    'Platform pembayaran administrasi sekolah yang aman, cepat, dan terintegrasi langsung dengan sistem akademik.' !!}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth('student')
                    <a href="{{ route('student.dashboard') }}"
                        class="w-full sm:w-auto px-8 py-4 bg-[var(--bbc-primary)] text-white rounded-2xl font-bold text-lg hover:translate-y-[-2px] transition-all shadow-xl shadow-red-200">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-8 py-4 bg-[var(--bbc-primary)] text-white rounded-2xl font-bold text-lg hover:translate-y-[-2px] transition-all shadow-xl shadow-red-200">
                        Masuk Sekarang
                    </a>
                @endauth

                @if (!empty($setting->hero_button_link2))
                    <a href="#panduan"
                        class="w-full sm:w-auto px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-2xl font-bold text-lg hover:bg-slate-50 transition">
                        <i class="fa-regular fa-circle-play mr-2"></i> Panduan
                    </a>
                @endif
            </div>

            <div class="mt-20 grid grid-cols-2 md:grid-cols-3 gap-8 max-w-4xl mx-auto border-t border-slate-100 pt-10">
                <div>
                    <div class="text-4xl font-[900] text-slate-900 mb-1">{{ $studentCount }}+</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Siswa Aktif</div>
                </div>
                <div>
                    <div class="text-4xl font-[900] text-slate-900 mb-1">{{ $majorCount }}</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Jurusan</div>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <div class="text-4xl font-[900] text-slate-900 mb-1">24/7</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Akses Sistem</div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-[800] text-slate-900 mb-4">
                    {{ $setting->features_heading ?? 'Kenapa BBC Pay?' }}</h2>
                <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                    {{ $setting->features_sub_heading ?? 'Fitur canggih untuk kemudahan administrasi.' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($features as $f)
                    <div
                        class="group p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-2xl hover:shadow-slate-100 transition-all duration-300">
                        <div
                            class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center text-[var(--bbc-primary)] text-2xl mb-6 group-hover:scale-110 transition-transform">
                            @if (str_contains($f->features_image, 'fa-'))
                                <i class="{{ $f->features_image }}"></i>
                            @else
                                <img src="{{ asset($f->features_image) }}" class="w-8 h-8 object-contain">
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $f->features_card_heading }}</h3>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            {!! $f->features_card_sort_desc !!}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <img src="{{ $setting->logo ?? '' }}" class="h-8 grayscale opacity-50 hover:opacity-100 transition">
                <span class="font-bold text-slate-200">BBC Pay System</span>
            </div>
            <p class="text-sm">{!! $setting->footer_copyright ?? '© 2024 SMK Budi Bakti Ciwidey' !!}</p>
        </div>
    </footer>

</body>

</html>
