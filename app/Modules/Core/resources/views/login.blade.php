<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Access • BBC Pay</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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
            overflow-x: hidden;
        }

        .bg-noise {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            opacity: 0.03;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        .input-brutal {
            transition: all 0.3s ease;
        }
        .input-brutal:focus {
            box-shadow: 4px 4px 0px 0px #0a0a0a;
            transform: translate(-2px, -2px);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen relative p-6">

    <div class="bg-noise"></div>
    <div class="fixed top-[-10%] left-[-10%] w-[40vw] h-[40vw] bg-red-500/5 rounded-full blur-[80px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40vw] h-[40vw] bg-red-500/5 rounded-full blur-[80px] pointer-events-none z-0"></div>

    <div class="w-full max-w-md bg-white border border-black/10 rounded-3xl shadow-xl shadow-black/5 relative z-10 overflow-hidden">

        <div class="px-8 pt-10 pb-6 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-bbc-black text-white mb-6 shadow-lg">
                <i class="fa-solid fa-lock text-xl"></i>
            </div>
            <h1 class="font-display font-bold text-3xl mb-2 tracking-tight">Welcome</h1>
            <p class="text-black/50 text-sm">Masukan Email dan Kata Sansi anda di Kolom yang tersedia.</p>
        </div>

        @if ($errors->any())
            <div class="mx-8 mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-lg flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                <div>
                    <span class="font-bold block mb-1">Access Denied</span>
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="px-8 pb-10">
            @csrf

            <div class="mb-5">
                <label class="block text-xs font-bold uppercase tracking-widest text-black/40 mb-2">Email</label>
                <div class="relative">
                    <div class="absolute z-[100] inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-black/30">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <input type="text" name="email"
                           class="input-brutal w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-black/5 rounded-xl text-sm font-medium focus:outline-none focus:bg-white focus:border-black placeholder-black/30 text-black"
                           placeholder="Masukan Email Anda. . ." required autofocus>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold uppercase tracking-widest text-black/40 mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute z-[100] inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-black/30">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <input type="password" name="password" id="passwordInput"
                           class="input-brutal w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-black/5 rounded-xl text-sm font-medium focus:outline-none focus:bg-white focus:border-black placeholder-black/30 text-black"
                           placeholder="••••••••" required>
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-black/30 hover:text-black transition-colors cursor-pointer">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="group w-full py-4 bg-bbc-black text-white rounded-xl font-display font-bold text-lg tracking-wide hover:bg-bbc-accent transition-all duration-300 relative overflow-hidden">
                <span class="relative z-10 flex items-center justify-center gap-2">
                    LOGIN <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </span>
            </button>
        </form>

        <div class="px-8 py-4 bg-gray-50 border-t border-black/5 text-center">
            <p class="text-xs text-black/40">
                Mengalami Masalah?
                <a href="{{ route('landing') }}#guide" class="text-bbc-black font-bold hover:underline">Baca Panduaun</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
