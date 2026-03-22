<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBC Pay • Future Education Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif'],
                        display: ['"Syne"', 'sans-serif'],
                    },
                    colors: {
                        bbc: {
                            black: '#0a0a0a',
                            white: '#ffffff',
                            accent: 'oklab(55.91% 0.20543 0.09128)',
                            gray: '#f4f4f5'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #ffffff;
            color: #0a0a0a;
            overflow-x: hidden; /* Mencegah scroll samping di HP */
        }

        /* Noise Texture */
        .bg-noise {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 50;
            opacity: 0.03;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* SECTION STACKING MAGIC */
        .panel {
            width: 100%;
            /* Mobile: Min-height biar konten ga kepotong */
            min-height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            overflow: hidden;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.02);
            /* Tambahan padding untuk mobile biar ga mepet atas bawah */
            padding-top: 80px;
            padding-bottom: 40px;
        }

        /* Responsive Big Text */
        .big-text {
            font-size: clamp(3rem, 5vw, 9rem);
            line-height: 0.9;
            letter-spacing: -0.04em;
        }

        .tered {
            color: oklab(55.91% 0.20543 0.09128);
            text-transform: uppercase;
            font-style: italic;
            font-weight: bold;
        }

        /* Hide Scrollbar */
        ::-webkit-scrollbar { width: 0px; background: transparent; }
    </style>
</head>

<body>
    <div class="bg-noise"></div>

    <nav class="fixed top-0 left-0 w-full z-[100] px-6 py-6 mix-blend-difference text-white transition-all duration-300" id="navbar">
        <div class="flex justify-between items-center max-w-[1800px] mx-auto">
            <div class="flex items-center gap-2 z-[110] relative">
                <div class="w-3 h-3 bg-white rounded-full"></div>
                <span class="font-display font-bold text-lg tracking-tight">BBC PAY</span>
            </div>

            <div class="hidden md:flex gap-8 text-sm font-medium tracking-wide">
                <a href="#intro" class="hover:opacity-50 transition">01. INTRO</a>
                <a href="#features" class="hover:opacity-50 transition">02. SYSTEM</a>
                <a href="#guide" class="hover:opacity-50 transition">03. GUIDE</a>
                <a href="#faqs" class="hover:opacity-50 transition">04. FAQ</a>
            </div>

            <div class="flex items-center gap-4 z-[110] relative">
                @auth('student')
                    <a href="{{ route('student.dashboard') }}" class="hidden md:inline-block px-5 py-2 border border-white/30 rounded-full text-xs font-bold uppercase hover:bg-white hover:text-black transition duration-300">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline-block px-5 py-2 bg-white text-black rounded-full text-xs font-bold uppercase hover:scale-105 transition duration-300">Login</a>
                @endauth

                <button id="burgerBtn" class="md:hidden flex flex-col gap-1.5 w-8 group z-[120] relative">
                    <span class="block w-full h-[2px] bg-white transition-transform duration-300 group-[.active]:rotate-45 group-[.active]:translate-y-2"></span>
                    <span class="block w-full h-[2px] bg-white transition-opacity duration-300 group-[.active]:opacity-0"></span>
                    <span class="block w-full h-[2px] bg-white transition-transform duration-300 group-[.active]:-rotate-45 group-[.active]:-translate-y-2"></span>
                </button>
            </div>
        </div>
    </nav>

    <div id="mobileMenu" class="fixed inset-0 bg-bbc-black z-[100] flex flex-col justify-center items-center opacity-0 pointer-events-none transition-all duration-500">
        <button id="closeMenuBtn" class="absolute top-6 right-6 text-white/70 hover:text-white hover:rotate-90 transition-all duration-200 md:hidden z-[130]">
            <i class="fa-solid fa-xmark text-3xl"></i>
        </button>

        <div class="flex flex-col gap-6 text-center">
            <a href="#intro" class="mobile-link text-3xl font-display font-bold text-white hover:text-bbc-accent transition-colors translate-y-10 opacity-0">01. INTRO</a>
            <a href="#features" class="mobile-link text-3xl font-display font-bold text-white hover:text-bbc-accent transition-colors translate-y-10 opacity-0" style="transition-delay: 0.1s">02. SYSTEM</a>
            <a href="#guide" class="mobile-link text-3xl font-display font-bold text-white hover:text-bbc-accent transition-colors translate-y-10 opacity-0" style="transition-delay: 0.2s">03. GUIDE</a>
            <a href="#faqs" class="mobile-link text-3xl font-display font-bold text-white hover:text-bbc-accent transition-colors translate-y-10 opacity-0" style="transition-delay: 0.3s">04. FAQ</a>
        </div>

        <div class="mt-10 translate-y-10 opacity-0 mobile-link" style="transition-delay: 0.4s">
            @auth('student')
                <a href="{{ route('student.dashboard') }}" class="px-8 py-3 bg-white text-black rounded-full text-sm font-bold uppercase hover:bg-bbc-accent hover:text-white transition-all">DASHBOARD</a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-black rounded-full text-sm font-bold uppercase hover:bg-bbc-accent hover:text-white transition-all">LOGIN</a>
            @endauth
        </div>
    </div>

    <div class="fixed right-4 md:right-6 top-1/2 -translate-y-1/2 h-16 md:h-24 w-1 bg-gray-200 rounded-full z-[90] overflow-hidden hidden md:block">
        <div class="progress-bar w-full bg-black h-0 transition-all duration-100 ease-linear"></div>
    </div>

    <main class="scroll-container">

        <section id="intro" class="panel z-10">
            <div class="max-w-[1600px] mx-auto px-6 w-full h-full flex flex-col justify-center relative">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-[50vw] h-[50vw] bg-red-500/5 rounded-full blur-[80px] pointer-events-none"></div>

                <div class="mb-6 flex items-center gap-3 animate-fade-in">
                    <span class="px-3 py-1 border border-black/10 rounded-full text-[10px] font-bold uppercase tracking-widest text-black/60">
                        V.2.0 System Online
                    </span>
                </div>

                <h1 class="font-display font-bold text-bbc-black big-text mb-6 leading-none">
                    <div class="overflow-hidden"><span class="block reveal-text">SMK</span></div>
                    <div class="overflow-hidden"><span class="block reveal-text">BUDI BAKTI CIWIDEY</span></div>
                    <div class="overflow-hidden"><span class="block reveal-text text-bbc-accent">PAYMENT SYSTEM</span></div>
                </h1>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-end border-t border-black/10 pt-8 mt-4 md:mt-8 gap-6">
                    <p class="max-w-md text-md text-black/60 font-light leading-relaxed">
                        Sistem pembayaran sekolah yang melampaui standar - <span class="tered">cepat</span>, <span
                            class="tered">transparan</span>, <span class="tered">terintegrasi</span> penuh dengan
                        sistem akademik, serta bekerja sama langsung dengan perbankan <span class="tered">BNI</span>.
                    </p>
                    <div class="text-left md:text-right">
                        <div class="text-4xl md:text-5xl font-display font-bold">{{ $studentCount ?? '1500' }}+</div>
                        <div class="text-xs font-bold uppercase tracking-widest text-black/40">Active Students</div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="panel z-20">
            <div class="max-w-[1600px] mx-auto px-6 w-full h-full flex flex-col justify-center">

                <div class="md:hidden mb-6">
                    <span class="text-xs font-bold tracking-widest text-bbc-accent">THE ECOSYSTEM</span>
                    <h2 class="text-3xl font-display font-bold">SMART PAYMENTS</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 md:grid-rows-2 gap-4 h-auto md:h-[70vh]">

                    <div class="md:col-span-2 md:row-span-2 bg-gray-50 rounded-3xl p-6 md:p-10 flex flex-col justify-between border border-black/5 group hover:border-black/10 transition-all">
                        <div>
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-xl shadow-sm mb-6">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h3 class="font-display text-2xl md:text-4xl font-bold mb-4">Integrated<br>Ecosystem</h3>
                            <p class="text-black/60 max-w-sm text-sm md:text-base">
                                Satu pintu untuk semua urusan administrasi. Dari SPP, Uang Gedung, hingga Tabungan Siswa terhubung langsung ke database sekolah.
                            </p>
                        </div>
                        <div class="mt-8 flex gap-4 overflow-x-auto pb-2 no-scrollbar">
                            <span class="px-4 py-2 bg-white rounded-full text-xs font-bold shadow-sm">Real-time</span>
                            <span class="px-4 py-2 bg-white rounded-full text-xs font-bold shadow-sm">Auto-Verify</span>
                            <span class="px-4 py-2 bg-white rounded-full text-xs font-bold shadow-sm">PDF Invoice</span>
                        </div>
                    </div>

                    <div class="bg-bbc-black rounded-3xl p-6 md:p-8 relative overflow-hidden group text-white flex flex-col justify-center">
                        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1639322537228-f710d846310a?q=80&w=2000&auto=format&fit=crop')] bg-cover bg-center opacity-30 group-hover:scale-110 transition-transform duration-700"></div>
                        <div class="relative z-10">
                            <i class="fa-solid fa-shield-halved text-3xl mb-4 text-bbc-accent"></i>
                            <h3 class="font-display text-xl font-bold">Bank Grade Security</h3>
                            <p class="text-white/60 text-xs mt-2">Enkripsi 256-bit untuk keamanan data.</p>
                        </div>
                    </div>

                    <div class="bg-red-50 rounded-3xl p-6 md:p-8 border border-red-100 group hover:bg-red-100 transition-colors flex flex-col justify-center">
                        <i class="fa-brands fa-whatsapp text-3xl mb-4 text-green-600"></i>
                        <h3 class="font-display text-xl font-bold text-bbc-black">WhatsApp Notif</h3>
                        <p class="text-black/60 text-xs mt-2">Orang tua terima notifikasi instan.</p>
                    </div>

                </div>
            </div>
        </section>

        <section id="guide" class="panel z-30 bg-bbc-black text-white">
            <div class="max-w-[1600px] mx-auto px-6 w-full h-full flex flex-col justify-center">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-y border-white/10 divide-y md:divide-y-0 md:divide-x divide-white/10">

                    <div class="p-8 md:p-12 hover:bg-white/5 transition duration-500 group">
                        <span class="block text-xs font-bold text-white/40 mb-4 md:mb-6 tracking-widest">STEP 01</span>
                        <h3 class="font-display text-3xl md:text-4xl mb-4 group-hover:text-bbc-accent transition-colors">Log In</h3>
                        <p class="text-white/50 text-sm leading-relaxed">
                            Akses dashboard menggunakan NISN. Sistem langsung mengenali tagihan Anda.
                        </p>
                    </div>

                    <div class="p-8 md:p-12 hover:bg-white/5 transition duration-500 group">
                        <span class="block text-xs font-bold text-white/40 mb-4 md:mb-6 tracking-widest">STEP 02</span>
                        <h3 class="font-display text-3xl md:text-4xl mb-4 group-hover:text-bbc-accent transition-colors">Transfer</h3>
                        <p class="text-white/50 text-sm leading-relaxed">
                            Pilih metode pembayaran (Manual/VA). Upload bukti jika diperlukan.
                        </p>
                    </div>

                    <div class="p-8 md:p-12 hover:bg-white/5 transition duration-500 group">
                        <span class="block text-xs font-bold text-white/40 mb-4 md:mb-6 tracking-widest">STEP 03</span>
                        <h3 class="font-display text-3xl md:text-4xl mb-4 group-hover:text-bbc-accent transition-colors">Done</h3>
                        <p class="text-white/50 text-sm leading-relaxed">
                            Sistem memverifikasi otomatis. Invoice digital langsung terbit.
                        </p>
                    </div>
                </div>

                <div class="mt-12 md:mt-16 text-center">
                     <p class="text-white/30 text-xs md:text-sm mb-4">READY TO START?</p>
                     @auth('student')
                        <a href="{{ route('student.dashboard') }}" class="inline-block text-5xl md:text-8xl font-display font-bold hover:text-transparent hover:bg-clip-text hover:bg-col hover:bg-red-500 transition-all cursor-pointer">
                            DASHBOARD
                        </a>
                     @else
                        <a href="{{ route('login') }}" class="inline-block text-5xl md:text-8xl font-display font-bold hover:text-transparent hover:bg-clip-text hover:bg-red-500 transition-all cursor-pointer">
                            LOGIN NOW
                        </a>
                     @endauth
                </div>
            </div>
        </section>

        <section id="faqs" class="panel z-40">
            <div class="max-w-4xl mx-auto px-6 w-full">
                <h2 class="font-display text-4xl md:text-5xl font-bold mb-8 md:mb-12 text-center">COMMON QUESTIONS</h2>

                <div class="space-y-4">
                    <details class="group bg-gray-50 p-6 rounded-2xl cursor-pointer transition-all duration-300 open:bg-white open:shadow-lg open:border-l-4 open:border-bbc-accent">
                        <summary class="flex justify-between items-center font-display font-bold text-lg md:text-xl list-none">
                            Is it secure?
                            <span class="transition-transform duration-300 group-open:rotate-180"><i class="fa-solid fa-plus"></i></span>
                        </summary>
                        <p class="text-black/60 mt-4 leading-relaxed text-sm md:text-base">
                            Yes. We use standard AES-256 encryption. Your data is safer than manual paper records.
                        </p>
                    </details>

                     <details class="group bg-gray-50 p-6 rounded-2xl cursor-pointer transition-all open:bg-white open:shadow-lg open:border-l-4 open:border-bbc-accent duration-300">
                        <summary class="flex justify-between items-center font-display font-bold text-lg md:text-xl list-none">
                            Payment Methods?
                            <span class="transition-transform duration-300 group-open:rotate-180"><i class="fa-solid fa-plus"></i></span>
                        </summary>
                        <p class="text-black/60 mt-4 leading-relaxed text-sm md:text-base">
                            Currently supporting Manual Bank Transfer (BCA, BRI, Mandiri). Virtual Account integration is coming soon.
                        </p>
                    </details>
                </div>

                <div class="mt-16 md:mt-24 border-t border-black/10 pt-8 flex flex-col md:flex-row justify-between items-center text-xs font-bold tracking-widest text-black/40 gap-2">
                    <div>© 2024 BBC PAY</div>
                    <div>ENGINEERED IN CIWIDEY</div>
                </div>
            </div>
        </section>

    </main>

    <script>
        // REGISTER GSAP
        gsap.registerPlugin(ScrollTrigger);

        // 1. ANIMASI TEKS HERO
        gsap.utils.toArray('.reveal-text').forEach((text, i) => {
            gsap.from(text, {
                y: 100,
                opacity: 0,
                duration: 1.5,
                ease: "power4.out",
                delay: i * 0.2
            });
        });

        // 2. STACKING CARDS LOGIC
        // Gunakan matchMedia untuk memastikan animasi jalan mulus di HP
        let mm = gsap.matchMedia();

        mm.add("(min-width: 320px)", () => {
            const panels = gsap.utils.toArray('.panel');
            panels.forEach((panel, i) => {
                ScrollTrigger.create({
                    trigger: panel,
                    start: "top top",
                    pin: true,
                    pinSpacing: false,
                    scrub: true, // Tambahkan scrub biar animasi ngikutin scroll jari
                    onUpdate: (self) => {
                        if (panels[i+1]) {
                            gsap.to(panel, {
                                scale: 1 - (self.progress * 0.05), // Scale jangan terlalu kecil di HP
                                opacity: 1 - (self.progress * 0.8),
                                filter: `blur(${self.progress * 5}px)`,
                                overwrite: true
                            });
                        }
                    }
                });
            });
        });

        // 3. PROGRESS BAR
        window.addEventListener('scroll', () => {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            const progressBar = document.querySelector(".progress-bar");
            if(progressBar) progressBar.style.height = scrolled + "%";
        });

        // 4. MOBILE MENU LOGIC
        const burgerBtn = document.getElementById('burgerBtn');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileLinks = document.querySelectorAll('.mobile-link');
        let isMenuOpen = false;

        function toggleMenu() {
            isMenuOpen = !isMenuOpen;
            if (burgerBtn) burgerBtn.classList.toggle('active');

            if (isMenuOpen) {
                mobileMenu.style.pointerEvents = "auto";
                mobileMenu.style.opacity = "1";
                document.body.style.overflow = "hidden";
                mobileLinks.forEach((link, index) => {
                    link.style.transitionDelay = `${index * 0.1}s`;
                    link.style.transform = "translateY(0)";
                    link.style.opacity = "1";
                });
            } else {
                mobileMenu.style.pointerEvents = "none";
                mobileMenu.style.opacity = "0";
                document.body.style.overflow = "";
                mobileLinks.forEach(link => {
                    link.style.transitionDelay = "0s";
                    link.style.transform = "translateY(20px)";
                    link.style.opacity = "0";
                });
            }
        }

        if (burgerBtn) burgerBtn.addEventListener('click', toggleMenu);
        if (closeMenuBtn) closeMenuBtn.addEventListener('click', toggleMenu);
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (isMenuOpen) toggleMenu();
            });
        });
    </script>

</body>
</html>
