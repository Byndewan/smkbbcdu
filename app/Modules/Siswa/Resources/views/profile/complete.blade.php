<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile • BBC Pay</title>

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

        /* Noise Texture */
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

        /* Input Style (Brutalist Focus) */
        .input-brutal {
            transition: all 0.3s ease;
        }
        .input-brutal:focus {
            background-color: #ffffff;
            border-color: #0a0a0a;
            box-shadow: 4px 4px 0px 0px #0a0a0a;
            transform: translate(-2px, -2px);
        }

        /* Valid/Invalid States */
        .input-brutal.is-invalid {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        .input-brutal.is-valid {
            border-color: #22c55e;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4 md:p-8 relative">

    <div class="bg-noise"></div>
    <div class="fixed top-[-10%] right-[-10%] w-[40vw] h-[40vw] bg-red-500/5 rounded-full blur-[80px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[-10%] left-[-10%] w-[40vw] h-[40vw] bg-red-500/5 rounded-full blur-[80px] pointer-events-none z-0"></div>

    <div class="w-full max-w-lg bg-white border border-black/10 rounded-3xl shadow-xl shadow-black/5 relative z-10 overflow-hidden">

        <div class="px-8 pt-10 pb-6 text-center border-b border-black/5">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-bbc-black text-white mb-4 shadow-lg">
                <i class="fa-solid fa-user-shield text-2xl"></i>
            </div>
            <h1 class="font-display font-bold text-3xl mb-2 tracking-tight">Lengkapi Profil Anda</h1>
            <p class="text-black/50 text-sm max-w-xs mx-auto">Halo <span class="font-bold text-black">{{ $student->name }}</span>, demi keamanan mohon lengkapi data berikut.</p>
        </div>

        <form action="{{ route('student.profile.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-black/40 mb-2 ml-1">Email Pribadi</label>
                <div class="relative">
                    <input type="email" name="email" id="emailInput"
                        class="input-brutal w-full px-4 py-3.5 bg-gray-50 border border-black/5 rounded-xl text-sm font-medium focus:outline-none placeholder-black/30 text-black"
                        placeholder="Masukan Email Anda. . ." required>
                    <div id="emailError" class="hidden mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i> <span>Email sekolah tidak diizinkan.</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-black/40 mb-2 ml-1">No. WhatsApp <span class="text-red-500">(AKTIF)</span></label>
                <input type="text" name="phone" id="phoneInput" value="{{ $student->phone }}"
                    class="input-brutal w-full px-4 py-3.5 bg-gray-50 border border-black/5 rounded-xl text-sm font-medium focus:outline-none placeholder-black/30 text-black"
                    placeholder="08xxxxxxxxxx" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-black/40 mb-2 ml-1">Alamat Domisili <span class="text-red-500">(Lengkap/Jelas)</span></label>
                <textarea name="address" rows="3"
                    class="input-brutal w-full px-4 py-3.5 bg-gray-50 border border-black/5 rounded-xl text-sm font-medium focus:outline-none placeholder-black/30 text-black resize-none"
                    placeholder="Nama jalan, RT/RW, Kecamatan..." required>{{ $student->address }}</textarea>
            </div>

            <div class="pt-6 border-t border-black/5">
                <label class="block text-xs font-bold uppercase tracking-widest text-bbc-accent mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-lock"></i> Buat Kata Sandi Baru
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput"
                            class="input-brutal w-full px-4 py-3.5 bg-gray-50 border border-black/5 rounded-xl text-sm font-medium focus:outline-none placeholder-black/30 text-black pr-10"
                            placeholder="Masukan Kata Sandi Baru" required minlength="6">
                        <button type="button" class="toggle-password absolute right-3 top-3.5 text-black/30 hover:text-black transition" data-target="passwordInput">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    <div class="relative">
                        <input type="password" name="password_confirmation" id="confirmPasswordInput"
                            class="input-brutal w-full px-4 py-3.5 bg-gray-50 border border-black/5 rounded-xl text-sm font-medium focus:outline-none placeholder-black/30 text-black pr-10"
                            placeholder="Masukan Kembali Kata Sandi Baru" required minlength="6">
                        <button type="button" class="toggle-password absolute right-3 top-3.5 text-black/30 hover:text-black transition" data-target="confirmPasswordInput">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div id="passwordError" class="hidden mt-2 text-xs text-red-500 font-medium"><i class="fa-solid fa-xmark mr-1"></i> Minimal 6 karakter & tidak boleh hanya angka.</div>
                <div id="confirmError" class="hidden mt-2 text-xs text-red-500 font-medium"><i class="fa-solid fa-xmark mr-1"></i> Password tidak cocok.</div>
            </div>

            <button type="submit" id="submitBtn" class="group w-full py-4 bg-bbc-black text-white rounded-xl font-display font-bold text-lg tracking-wide hover:bg-bbc-accent transition-all duration-300 relative overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="relative z-10 flex items-center justify-center gap-2">
                    Simpan & Lanjutkan <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </span>
            </button>
        </form>
    </div>

    <script>
        const EI = document.getElementById('emailInput');
        const SB = document.getElementById('submitBtn');
        const ER = document.getElementById('emailError');
        function checkEmailDomain() {
            const email = EI.value.trim().toLowerCase();
            EI.classList.remove('is-invalid', 'is-valid');
            ER.classList.add('hidden');
            SB.disabled = false;

            if (email === '') {
                SB.disabled = true;
                return;
            }
            if (email.endsWith('@siswa.bbc') || email.endsWith('@smkbbc.sch.id')) {
                EI.classList.add('is-invalid');
                ER.classList.remove('hidden');
                ER.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> Gunakan email pribadi (Gmail/Yahoo).';
                SB.disabled = true;
                return;
            }
            if (!EI.checkValidity()) {
                EI.classList.add('is-invalid');
                SB.disabled = true;
                return;
            }
            EI.classList.add('is-valid');
        }
        EI.addEventListener('input', checkEmailDomain);
        const phoneInput = document.getElementById('phoneInput');
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        const PW = document.getElementById('passwordInput');
        const CPW = document.getElementById('confirmPasswordInput');
        const pwError = document.getElementById('passwordError');
        const cpwError = document.getElementById('confirmError');
        function validatePassword() {
            const password = PW.value;
            const confirm = CPW.value;
            let valid = true;
            PW.classList.remove('is-valid', 'is-invalid');
            CPW.classList.remove('is-valid', 'is-invalid');
            pwError.classList.add('hidden');
            cpwError.classList.add('hidden');
            SB.disabled = false;
            if (password.length > 0) {
                if (password.length < 6 || /^\d+$/.test(password)) {
                    PW.classList.add('is-invalid');
                    pwError.classList.remove('hidden');
                    valid = false;
                } else {
                    PW.classList.add('is-valid');
                }
            }
            if (confirm.length > 0) {
                if (password !== confirm) {
                    CPW.classList.add('is-invalid');
                    cpwError.classList.remove('hidden');
                    valid = false;
                } else {
                    CPW.classList.add('is-valid');
                }
            }
            if (!valid || password === '' || confirm === '') {
                SB.disabled = true;
            }
        }
        PW.addEventListener('input', validatePassword);
        CPW.addEventListener('input', validatePassword);
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const inputId = this.dataset.target;
                const input = document.getElementById(inputId);
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>

</body>
</html>
