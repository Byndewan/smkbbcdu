<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - BBC Pay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F2F4F8;
        }

        .clay-card {
            background: #F2F4F8;
            border-radius: 2rem;
            box-shadow: 8px 8px 16px #d1d9e6, -8px -8px 16px #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .btn-clay {
            background: oklab(55.91% 0.20543 0.09128);
            color: white;
            box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.3), 0 8px 15px rgba(230, 57, 70, 0.3);
            transition: all 0.2s;
        }

        .btn-clay:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">

    <div class="clay-card w-full max-w-lg p-8 relative overflow-hidden">

        <div class="text-center mb-8">
            <div
                class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <h1 class="text-2xl font-[900] text-slate-800">Amankan Akun Anda</h1>
            <p class="text-slate-500 text-sm mt-1">Halo {{ $student->name }}, mohon lengkapi data berikut sebelum
                melanjutkan.</p>
        </div>

        <form action="{{ route('student.profile.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="text-xs font-bold text-slate-400 uppercase ml-2">Email Pribadi</label>
                <input type="email" name="email"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-slate-100 border-none focus:ring-2 focus:ring-red-500"
                    placeholder="nama@gmail.com" required>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-400 uppercase ml-2">No. WhatsApp</label>
                <input type="text" name="phone" value="{{ $student->phone }}"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-slate-100 border-none focus:ring-2 focus:ring-red-500"
                    placeholder="08xxxxxxxxxx" required>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-400 uppercase ml-2">Alamat Domisili</label>
                <textarea name="address" rows="2"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-slate-100 border-none focus:ring-2 focus:ring-red-500"
                    placeholder="Alamat lengkap..." required>{{ $student->address }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-200">
                <label class="text-xs font-bold text-slate-400 uppercase ml-2">Buat Password Baru</label>
                <div class="grid grid-cols-2 gap-4 mt-1">
                    <input type="password" name="password"
                        class="w-full px-4 py-3 rounded-xl bg-slate-100 border-none focus:ring-2 focus:ring-red-500"
                        placeholder="Password Baru" required minlength="6">
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-3 rounded-xl bg-slate-100 border-none focus:ring-2 focus:ring-red-500"
                        placeholder="Ulangi Password" required minlength="6">
                </div>
                <p class="text-[10px] text-slate-400 mt-2 ml-2">* Minimal 6 karakter. Jangan gunakan password default.
                </p>
            </div>

            <button type="submit" class="btn-clay w-full py-4 rounded-xl font-bold text-lg mt-4">
                Simpan & Lanjutkan <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </form>
    </div>

    <script>
        const EI = document.getElementById('emailInput');
        const SB = document.getElementById('submitBtn');
        const ER = document.getElementById('emailError');

        function checkEmailDomain() {
            const email = EI.value.trim().toLowerCase();
            if (email === '') {
                EI.classList.remove('is-invalid', 'is-valid');
                ER.classList.add('d-none');
                SB.disabled = true;
                return;
            }
            if (email.endsWith('@siswa.bbc')) {
                EI.classList.add('is-invalid');
                EI.classList.remove('is-valid');
                ER.classList.remove('d-none');
                SB.disabled = true;
                return;
            }
            if (!EI.checkValidity()) {
                EI.classList.add('is-invalid');
                EI.classList.remove('is-valid');
                ER.classList.add('d-none');
                SB.disabled = true;
                return;
            }
            EI.classList.remove('is-invalid');
            EI.classList.add('is-valid');
            ER.classList.add('d-none');
            SB.disabled = false;
        }

        EI.addEventListener('input', checkEmailDomain);
        checkEmailDomain();

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
            pwError.classList.add('d-none');
            cpwError.classList.add('d-none');
            if (!password && !confirm) {
                SB.disabled = true;
                return;
            }
            if (password.length < 6) {
                PW.classList.add('is-invalid');
                pwError.classList.remove('d-none');
                valid = false;
            }
            if (/^\d+$/.test(password)) {
                PW.classList.add('is-invalid');
                pwError.classList.remove('d-none');
                valid = false;
            }
            if (confirm && password !== confirm) {
                CPW.classList.add('is-invalid');
                cpwError.classList.remove('d-none');
                valid = false;
            }
            if (valid && confirm) {
                PW.classList.add('is-valid');
                CPW.classList.add('is-valid');
                SB.disabled = false;
            } else {
                SB.disabled = true;
            }
        }

        PW.addEventListener('input', validatePassword);
        CPW.addEventListener('input', validatePassword);

        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
        });
    </script>

</body>

</html>
