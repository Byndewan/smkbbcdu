@extends('layouts.siswa')

@section('title', 'Profil Saya')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .cropper-view-box, .cropper-face { border-radius: 50%; }

        /* Input Brutalist Style */
        .input-brutal {
            transition: all 0.3s ease;
            background-color: #f9fafb; /* Gray-50 */
            border: 1px solid #e5e7eb; /* Gray-200 */
        }
        .input-brutal:focus {
            background-color: #ffffff;
            border-color: #0a0a0a;
            box-shadow: 4px 4px 0px 0px #0a0a0a; /* Solid Shadow */
            transform: translate(-2px, -2px);
            outline: none;
        }
        /* Button Brutalist */
        .btn-brutal {
            transition: all 0.3s ease;
            box-shadow: 4px 4px 0px 0px #0a0a0a;
        }
        .btn-brutal:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px #0a0a0a;
        }
        .btn-brutal:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px 0px #0a0a0a;
        }
        .btn-brutal:disabled {
            box-shadow: none;
            transform: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
        /* States */
        .input-brutal.border-red-500 { border-color: #ef4444; background-color: #fef2f2; }
        .input-brutal.border-green-500 { border-color: #22c55e; }
    </style>
@endpush

@section('content')
    <div class="max-w-5xl mx-auto font-sans">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="md:col-span-1 space-y-6">
                <div class="bg-white border border-black/10 rounded-3xl p-8 text-center relative overflow-hidden shadow-sm">

                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-blue-500/10 to-purple-500/10 -z-0"></div>

                    <div class="relative z-10">
                        <div class="relative inline-block mx-auto mb-6">
                            <img src="{{ $student->photo_path ? Storage::url($student->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=random' }}"
                                id="photo-preview-main"
                                class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md mx-auto">

                            <label for="photo-input"
                                class="absolute bottom-0 right-0 w-10 h-10 bg-black text-white rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-600 transition shadow-lg border-2 border-white">
                                <i class="bi-solid bi-camera text-sm"></i>
                            </label>
                            <input type="file" id="photo-input" class="hidden" accept="image/*">
                        </div>

                        <h2 class="font-display font-bold text-xl text-black mb-1">{{ $student->name }}</h2>
                        <p class="text-gray-500 text-sm font-mono tracking-wide mb-4">{{ $student->nipd }}</p>

                        <div class="inline-flex items-center px-4 py-1.5 bg-green-100 text-green-700 border border-green-200 rounded-full text-xs font-bold uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                            Siswa Aktif
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-8">

                <div class="bg-white border border-black/10 rounded-3xl p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-black text-white flex items-center justify-center">
                            <i class="fa-regular fa-id-card"></i>
                        </div>
                        <h3 class="font-display font-bold text-lg text-black">Informasi Pribadi</h3>
                    </div>

                    <form action="{{ route('student.profile.update') }}" method="POST" id="form-biodata">
                        @csrf @method('PUT')

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                                <input type="email" name="email" id="emailInput" value="{{ $student->email }}"
                                    class="input-brutal w-full px-4 py-3 rounded-xl text-sm font-medium text-black">
                                <div id="emailError" class="hidden mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i> <span>Email sekolah tidak diizinkan.</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">No. WhatsApp</label>
                                <input type="text" name="phone" id="phoneInput" value="{{ $student->phone }}"
                                    class="input-brutal w-full px-4 py-3 rounded-xl text-sm font-medium text-black">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Alamat</label>
                                <textarea name="address" rows="3"
                                    class="input-brutal w-full px-4 py-3 rounded-xl text-sm font-medium text-black resize-none">{{ $student->address }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <a href="{{ route('student.dashboard') }}" class="btn-brutal inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl text-sm font-bold tracking-wide uppercase">
                                <i class="fa fa-arrow-left ml-2"></i> Kembali
                            </a>
                            <button type="submit" id="submitBtnSave" class="btn-brutal inline-flex items-center px-6 py-3 bg-black text-white rounded-xl text-sm font-bold tracking-wide uppercase">
                                Simpan Perubahan <i class="fa-solid fa-check ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white border border-black/10 rounded-3xl p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center border border-red-100">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h3 class="font-display font-bold text-lg text-black">Keamanan</h3>
                    </div>

                    <form action="{{ route('student.profile.password') }}" method="POST" id="form-password">
                        @csrf @method('PUT')

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Kata Sandi Saat Ini</label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="currentPasswordInput"
                                        class="input-brutal w-full px-4 py-3 rounded-xl text-sm font-medium text-black">
                                    <button type="button" class="toggle-password absolute right-3 top-3 text-gray-400 hover:text-black" data-target="currentPasswordInput">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                <div id="CurrentpasswordError" class="hidden mt-1 text-xs text-red-500">Wajib diisi.</div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-gray-100">
                                <div>
                                    <label class="block text-xs font-bold text-blue-600 uppercase tracking-widest mb-2 ml-1">Kata Sandi Baru</label>
                                    <div class="relative">
                                        <input type="password" name="password" id="passwordInput"
                                            class="input-brutal w-full px-4 py-3 rounded-xl text-sm font-medium text-black">
                                        <button type="button" class="toggle-password absolute right-3 top-3 text-gray-400 hover:text-black" data-target="passwordInput">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="passwordError" class="hidden mt-1 text-xs text-red-500">Min 6 karakter & kombinasi angka.</div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-blue-600 uppercase tracking-widest mb-2 ml-1">Masukan Kembali Kata Sandi Baru</label>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" id="confirmPasswordInput"
                                            class="input-brutal w-full px-4 py-3 rounded-xl text-sm font-medium text-black">
                                        <button type="button" class="toggle-password absolute right-3 top-3 text-gray-400 hover:text-black" data-target="confirmPasswordInput">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="confirmError" class="hidden mt-1 text-xs text-red-500">Password tidak sama.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <a href="{{ route('student.dashboard') }}" class="btn-brutal inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl text-sm font-bold tracking-wide uppercase">
                                <i class="fa fa-arrow-left ml-2"></i> Kembali
                            </a>
                            <button type="submit" id="submitBtnUpdate" class="btn-brutal inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-red-700">
                                Ganti Password <i class="fa-solid fa-lock ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div id="crop-modal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl overflow-hidden shadow-2xl max-w-lg w-full">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-display font-bold text-lg">Sesuaikan Foto</h3>
                <button onclick="closeCropModal()" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <div class="p-4 bg-gray-100 h-96">
                <img id="image-to-crop" src="" class="max-w-full h-full object-contain mx-auto">
            </div>
            <div class="p-6 flex justify-end gap-3 bg-white">
                <button onclick="closeCropModal()" class="px-6 py-2 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                <button id="btn-crop-save" class="btn-brutal px-6 py-2 rounded-xl text-sm font-bold bg-black text-white">Simpan Foto</button>
            </div>
        </div>
    </div>

    <div id="loading-overlay" class="fixed inset-0 z-[60] hidden bg-white/90 flex flex-col items-center justify-center">
        <div class="w-12 h-12 border-4 border-black border-t-transparent rounded-full animate-spin mb-4"></div>
        <p class="font-display font-bold text-black animate-pulse">Memproses...</p>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
        <script>
            // --- VARIABEL & ELEMENT ---
            const EI = document.getElementById('emailInput');
            const ER = document.getElementById('emailError');
            const SBS = document.getElementById('submitBtnSave'); // Submit Biodata
            const phoneInput = document.getElementById('phoneInput');

            const CCPW = document.getElementById('currentPasswordInput');
            const PW = document.getElementById('passwordInput');
            const CPW = document.getElementById('confirmPasswordInput');

            const currentpwError = document.getElementById('CurrentpasswordError');
            const pwError = document.getElementById('passwordError');
            const cpwError = document.getElementById('confirmError');

            const SBU = document.getElementById('submitBtnUpdate'); // Submit Password

            // Init State
            // SBS.disabled = false; // Biarkan enabled default agar user bisa edit salah satu saja
            SBU.disabled = true;

            // --- FUNGSI HELPER VISUAL ---
            function setInvalid(input, errorEl) {
                input.classList.remove('border-gray-200', 'border-green-500');
                input.classList.add('border-red-500');
                if (errorEl) errorEl.classList.remove('hidden');
            }

            function setValid(input, errorEl = null) {
                input.classList.remove('border-gray-200', 'border-red-500');
                input.classList.add('border-green-500');
                if (errorEl) errorEl.classList.add('hidden');
            }

            function setNormal(input, errorEl = null) {
                input.classList.remove('border-red-500', 'border-green-500');
                input.classList.add('border-gray-200');
                if (errorEl) errorEl.classList.add('hidden');
            }

            // --- VALIDASI BIODATA ---
            function checkBiodata() {
                const email = EI.value.trim().toLowerCase();
                let isEmailValid = true;

                if (!email) {
                    setNormal(EI, ER);
                    isEmailValid = false;
                } else if (email.endsWith('@siswa.bbc') || email.endsWith('@smkbbc.sch.id') || !EI.checkValidity()) {
                    setInvalid(EI, ER);
                    isEmailValid = false;
                } else {
                    setValid(EI, ER);
                    isEmailValid = true;
                }

                // Phone Validation (Simple)
                const phone = phoneInput.value.trim();
                let isPhoneValid = phone.length >= 10;

                // Logic tombol submit
                // Kita enable kalau tidak ada error merah.
                if (isEmailValid && isPhoneValid) {
                    SBS.disabled = false;
                } else {
                    SBS.disabled = true;
                }
            }

            EI.addEventListener('input', checkBiodata);
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Hanya angka
                checkBiodata();
            });

            // --- VALIDASI PASSWORD ---
            function checkPassword() {
                let isCurrentValid = false;
                let isNewValid = false;
                let isConfirmValid = false;

                // 1. Current Password
                if (CCPW.value.length > 0) {
                    setNormal(CCPW, currentpwError); // Kita ga bisa validasi isinya di frontend, cuma cek kosong/engga
                    isCurrentValid = true;
                } else {
                    // setInvalid(CCPW, currentpwError); // Jangan langsung merah, normal aja kalau kosong
                    setNormal(CCPW, currentpwError);
                    isCurrentValid = false;
                }

                // 2. New Password
                const val = PW.value;
                if (val.length === 0) {
                    setNormal(PW, pwError);
                    isNewValid = false;
                } else if (val.length < 6 || /^\d+$/.test(val)) {
                    setInvalid(PW, pwError);
                    isNewValid = false;
                } else {
                    setValid(PW, pwError);
                    isNewValid = true;
                }

                // 3. Confirm Password
                if (CPW.value.length === 0) {
                    setNormal(CPW, cpwError);
                    isConfirmValid = false;
                } else if (isNewValid && CPW.value === PW.value) {
                    setValid(CPW, cpwError);
                    isConfirmValid = true;
                } else {
                    setInvalid(CPW, cpwError);
                    isConfirmValid = false;
                }

                // Enable Button cuma kalau semua valid
                if (isCurrentValid && isNewValid && isConfirmValid) {
                    SBU.disabled = false;
                } else {
                    SBU.disabled = true;
                }
            }

            CCPW.addEventListener('input', checkPassword);
            PW.addEventListener('input', checkPassword);
            CPW.addEventListener('input', checkPassword);

            // --- TOGGLE MATA PASSWORD ---
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

            // --- CROPPER IMAGE LOGIC ---
            let cropper;
            const photoInput = document.getElementById('photo-input');
            const imageToCrop = document.getElementById('image-to-crop');
            const cropModal = document.getElementById('crop-modal');
            const loadingOverlay = document.getElementById('loading-overlay');
            const photoPreviewMain = document.getElementById('photo-preview-main');

            photoInput.addEventListener('change', function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    const file = files[0];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imageToCrop.src = e.target.result;
                        cropModal.classList.remove('hidden');
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 1,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 1,
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            window.closeCropModal = function() {
                cropModal.classList.add('hidden');
                photoInput.value = '';
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            }

            document.getElementById('btn-crop-save').addEventListener('click', function() {
                if (!cropper) return;
                cropper.getCroppedCanvas({ width: 300, height: 300 }).toBlob((blob) => {
                    const formData = new FormData();
                    formData.append('photo', blob, 'profile.jpg');
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('_method', 'PUT');

                    closeCropModal();
                    loadingOverlay.classList.remove('hidden');

                    // AJAX Upload
                    fetch("{{ route('student.profile.update_photo') }}", {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => {
                        if (response.ok) {
                            const newImageUrl = URL.createObjectURL(blob);
                            photoPreviewMain.src = newImageUrl;
                            // Optional: Munculkan SweetAlert Sukses disini
                        } else {
                            alert('Gagal upload foto.');
                        }
                    })
                    .catch(error => console.error('Error:', error))
                    .finally(() => {
                        loadingOverlay.classList.add('hidden');
                    });
                }, 'image/jpeg', 0.8);
            });

        </script>
    @endpush
@endsection
