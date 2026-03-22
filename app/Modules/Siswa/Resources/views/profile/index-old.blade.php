@extends('layouts.siswa')

@section('title', 'Profil Saya')

@push('styles')
    <style>
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="md:col-span-1 space-y-6">
                <div class="clay-card !p-8 text-center relative group">
                    <div class="relative inline-block mx-auto mb-4">
                        <img src="{{ $student->photo_path ? Storage::url($student->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=random' }}"
                            id="photo-preview-main"
                            class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-slate-700 shadow-lg">

                        <label for="photo-input"
                            class="absolute bottom-0 right-0 w-10 h-10 bg-[var(--bbc-hex)] text-white rounded-full flex items-center justify-center cursor-pointer hover:scale-110 transition shadow-md">
                            <i class="fa-solid fa-camera"></i>
                        </label>
                        <input type="file" id="photo-input" class="hidden" accept="image/*">
                    </div>

                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $student->name }}</h2>
                    <p class="text-slate-500 text-sm mb-4">{{ $student->nipd }}</p>

                    <div
                        class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs font-bold uppercase tracking-wide">
                        Siswa Aktif
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="clay-card !p-8">
                    <h3
                        class="font-bold text-lg text-slate-700 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-700 pb-2">
                        Informasi Pribadi
                    </h3>

                    <form action="{{ route('student.profile.update') }}" method="POST">
                        @csrf @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Email</label>
                                <input type="email" name="email" value="{{ $student->email }}"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-[var(--bbc-hex)] text-slate-700 dark:text-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">No. WhatsApp</label>
                                <input type="text" name="phone" value="{{ $student->phone }}"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-[var(--bbc-hex)] text-slate-700 dark:text-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Alamat</label>
                                <textarea name="address" rows="3"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-[var(--bbc-hex)] text-slate-700 dark:text-slate-200">{{ $student->address }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <button type="submit" class="btn-clay !py-2 !px-6 text-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                    <h3
                        class="font-bold text-lg text-slate-700 dark:text-white mt-10 mb-6 border-b border-slate-200 dark:border-slate-700 pb-2">
                        Keamanan
                    </h3>

                    <form action="{{ route('student.profile.password') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Password
                                        Baru</label>
                                    <input type="password" name="password"
                                        class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-red-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Konfirmasi</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-red-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Password Saat
                                    Ini</label>
                                <input type="password" name="current_password"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                        <div class="mt-6 text-right">
                            <button type="submit"
                                class="btn-clay-secondary !py-2 !px-6 text-sm hover:!bg-red-500 hover:!text-white">
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    @endpush

    @push('scripts')
        <script>
            const EI = document.getElementById('emailInput');
            const ER = document.getElementById('emailError');
            const SBS = document.getElementById('submitBtnSave');
            const phoneInput = document.getElementById('phoneInput');
            const CCPW = document.getElementById('currentPasswordInput');
            const PW = document.getElementById('passwordInput');
            const CPW = document.getElementById('confirmPasswordInput');
            const currentpwError = document.getElementById('CurrentpasswordError');
            const pwError = document.getElementById('passwordError');
            const cpwError = document.getElementById('confirmError');
            const SBU = document.getElementById('submitBtnUpdate');
            SBS.disabled = true;
            SBU.disabled = true;
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
                        if (cropper) {
                            cropper.destroy();
                        }

                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 1,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 1,
                            restore: false,
                            guides: false,
                            center: false,
                            highlight: false,
                            cropBoxMovable: false,
                            cropBoxResizable: false,
                            toggleDragModeOnDblclick: false,
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
                cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                }).toBlob((blob) => {
                    const formData = new FormData();
                    formData.append('photo', blob, 'profile.jpg');
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('_method', 'PUT');
                    closeCropModal();
                    loadingOverlay.classList.remove('hidden');
                    loadingOverlay.classList.add('flex');
                    fetch("{{ route('student.profile.update_photo') }}", {
                            method: 'POST',
                            body: formData,
                        })
                        .then(response => {
                            if (response.ok) {
                                const newImageUrl = URL.createObjectURL(blob);
                                photoPreviewMain.src = newImageUrl;
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops!',
                                    text: 'Gagal mengupload foto. Silakan coba lagi!',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops!',
                                text: 'Terjadi kesalahan sistem.',
                                confirmButtonText: 'OK'
                            });
                        })
                        .finally(() => {
                            loadingOverlay.classList.add('hidden');
                            loadingOverlay.classList.remove('flex');
                        });

                }, 'image/jpeg', 0.8);
            });

            window.uploadPhoto = function(input) {
                console.log("File dipilih:", input.files[0]);
                if (input.files && input.files[0]) {
                    const overlay = document.getElementById('loading-overlay');
                    if (overlay) {
                        overlay.classList.remove('hidden');
                        overlay.classList.add('flex');
                    } else {
                        console.warn('Overlay loading tidak ditemukan, tapi tetap submit.');
                    }
                    const form = document.getElementById('form-photo');
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Form dengan ID "form-photo" tidak ditemukan!');
                    }
                }
            }

            function setInvalid(input, errorEl) {
                input.classList.remove('border-gray-300', 'border-green-500', 'focus:border-blue-500', 'focus:ring-blue-200');
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-200');
                if (errorEl) errorEl.classList.remove('hidden');
            }

            function setValid(input, errorEl = null) {
                input.classList.remove('border-gray-300', 'border-red-500', 'focus:border-blue-500', 'focus:ring-blue-200');
                input.classList.add('border-green-500', 'focus:border-green-500', 'focus:ring-green-200');
                if (errorEl) errorEl.classList.add('hidden');
            }

            function setNormal(input, errorEl = null) {
                input.classList.remove('border-red-500', 'border-green-500', 'focus:border-red-500', 'focus:ring-red-200',
                    'focus:ring-green-200');
                input.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-200');
                if (errorEl) errorEl.classList.add('hidden');
            }

            document.getElementById('photo-input').onchange = function(evt) {
                var tgt = evt.target || window.event.srcElement,
                    files = tgt.files;
                if (FileReader && files && files.length) {
                    var fr = new FileReader();
                    fr.onload = function() {
                        document.getElementById('photo-preview').src = fr.result;
                    }
                    fr.readAsDataURL(files[0]);
                }
            }

            window.togglePass = function(btn) {
                const input = btn.previousElementSibling;
                const iconEye =
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
                const iconSlash =
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>';

                if (input.type === "password") {
                    input.type = "text";
                    btn.innerHTML = iconSlash;
                } else {
                    input.type = "password";
                    btn.innerHTML = iconEye;
                }
            }

            function checkBiodata() {
                const email = EI.value.trim().toLowerCase();
                let isEmailValid = true;
                if (!email) {
                    setNormal(EI, ER);
                    isEmailValid = false;
                } else if (email.endsWith('@siswa.bbc') || !EI.checkValidity()) {
                    setInvalid(EI, ER);
                    isEmailValid = false;
                } else {
                    setValid(EI, ER);
                    isEmailValid = true;
                }
                const phone = phoneInput.value.trim();
                let isPhoneValid = phone.length >= 10;
                SBS.disabled = !(isEmailValid && isPhoneValid);
            }

            EI.addEventListener('input', checkBiodata);
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                checkBiodata();
            });
            checkBiodata();

            function checkPassword() {
                let isCurrentValid = false;
                let isNewValid = false;
                let isConfirmValid = false;
                if (CCPW.value.length > 0) {
                    setNormal(CCPW, currentpwError);
                    isCurrentValid = true;
                } else {
                    setNormal(CCPW, currentpwError);
                    isCurrentValid = false;
                }
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

                if (isCurrentValid && isNewValid && isConfirmValid) {
                    SBU.disabled = false;
                    SBU.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    SBU.disabled = true;
                    SBU.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
            CCPW.addEventListener('input', checkPassword);
            PW.addEventListener('input', checkPassword);
            CPW.addEventListener('input', checkPassword);
            checkPassword();
        </script>
    @endpush
@endsection
