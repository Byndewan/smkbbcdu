<!DOCTYPE html>
<html>

<head>
    <title>Login - BBC Pay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-danger text-white text-center py-4 rounded-top-4">
                        <h4 class="fw-bold mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Keamanan Akun</h4>
                        <p class="mb-0 small text-white-50">Mohon lengkapi data & ganti password untuk melanjutkan.</p>
                    </div>
                    <div class="card-body p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger small">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('student.profile.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light" value="{{ $student->name }}"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Email Pribadi (Aktif)</label>
                                <input type="email" id="emailInput" name="email" class="form-control" value=""
                                    required placeholder="contoh: nama@gmail.com">
                                <div id="emailError" class="form-text text-danger small d-none">
                                    *Email tidak boleh menggunakan domain sekolah (@siswa.bbc)
                                </div>
                                <div class="form-text text-danger small">*Wajib ubah dari email default sekolah.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Nomor WhatsApp</label>
                                <input type="text" name="phone" id="phoneInput" class="form-control"
                                    value="{{ $student->phone }}" required placeholder="08xxxxxxxxxx"
                                    inputmode="numeric" pattern="[0-9]*">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Alamat Domisili</label>
                                <textarea name="address" class="form-control" rows="2" required
                                    placeholder="Alamat lengkap tempat tinggal sekarang...">{{ $student->address }}</textarea>
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold text-danger mb-3"><i class="bi bi-key me-1"></i> Ganti Password Baru
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="passwordInput" name="password" class="form-control"
                                        required minlength="6">

                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="passwordInput">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>

                                <div id="passwordError" class="form-text text-danger small d-none">
                                    Password tidak boleh hanya angka dan minimal 6 karakter.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="confirmPasswordInput" name="password_confirmation"
                                        class="form-control" required minlength="6">

                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="confirmPasswordInput">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>

                                <div id="confirmError" class="form-text text-danger small d-none">
                                    Konfirmasi password tidak sama.
                                </div>
                            </div>


                            <button type="submit" id="submitBtn" class="btn btn-danger w-100 py-2 fw-bold">
                                Simpan & Login Ulang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
