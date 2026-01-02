@extends('layouts.siswa')

@section('title', 'Profil - Siswa')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <style>
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pengaturan Profil</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola informasi pribadi dan keamanan akun Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="md:col-span-1">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center h-full relative overflow-hidden">

                    <div id="loading-overlay"
                        class="absolute inset-0 bg-white/80 z-20 flex-col items-center justify-center hidden">
                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mb-2"></div>
                        <span class="text-sm font-semibold text-blue-600">Mengupload...</span>
                    </div>

                    <div class="relative inline-block mb-4 group">
                        <img src="{{ $student->photo_path ? Storage::url($student->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=random' }}"
                            alt="Profile"
                            class="w-36 h-36 rounded-full object-cover border-4 border-white shadow-md mx-auto"
                            id="photo-preview-main">

                        <label for="photo-input"
                            class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full shadow-lg cursor-pointer transition-transform hover:scale-110 duration-200"
                            title="Ganti Foto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </label>
                    </div>

                    <h2 class="text-xl font-bold text-gray-800">{{ $student->name }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ $student->nipd }}</p>

                    <input type="file" id="photo-input" class="hidden" accept="image/*">
                </div>
            </div>

            <div id="crop-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Sesuaikan Foto
                                    </h3>
                                    <div class="mt-4 w-full h-64 bg-gray-100 rounded-lg overflow-hidden relative">
                                        <img id="image-to-crop" class="max-w-full block" src="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" id="btn-crop-save"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" onclick="closeCropModal()"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ activeTab: 'biodata' }">

                    <div class="flex border-b border-gray-100 bg-gray-50/50 px-6 pt-4 gap-4">
                        <button @click="activeTab = 'biodata'"
                            :class="{ 'border-blue-600 text-blue-600': activeTab === 'biodata', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'biodata' }"
                            class="pb-3 px-2 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Biodata
                        </button>
                        <button @click="activeTab = 'password'"
                            :class="{ 'border-blue-600 text-blue-600': activeTab === 'password', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'password' }"
                            class="pb-3 px-2 text-sm font-semibold border-b-2 transition-colors duration-200 flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Ganti Password
                        </button>
                    </div>

                    <div class="p-6">
                        <div x-show="activeTab === 'biodata'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0">
                            <form action="{{ route('student.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Email
                                            Pribadi</label>
                                        <input type="email" name="email" id="emailInput"
                                            value="{{ old('email', $student->email) }}" required
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                                        <div id="emailError" class="form-text text-red-600 small hidden">
                                            *Email tidak boleh menggunakan domain sekolah (@siswa.bbc)
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">Digunakan untuk login dan notifikasi penting.
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nomor
                                            WhatsApp</label>
                                        <input type="text" name="phone" id="phoneInput"
                                            value="{{ old('phone', $student->phone) }}" required
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Alamat
                                            Domisili</label>
                                        <textarea name="address" rows="3" placeholder="Alamat lengkap..."
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">{{ old('address', $student->address) }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-8 text-right">
                                    <button type="submit" id="submitBtnSave"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow-md transition duration-200 flex items-center gap-2 ml-auto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div x-show="activeTab === 'password'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            {{--
                            <div class="bg-yellow-50 text-yellow-700 p-4 rounded-lg mb-6 flex items-start gap-3 text-sm">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p>Gunakan kombinasi huruf dan angka minimal 6 karakter untuk keamanan akun Anda.</p>
                            </div> --}}
                            <div>
                                <div id="CurrentpasswordError"
                                    class="bg-red-50 p-4 rounded-lg mb-3 form-text text-red-600 small hidden">
                                    Password tidak boleh hanya angka dan minimal 6 karakter.
                                </div>
                                <div id="passwordError"
                                    class="bg-red-50 p-4 rounded-lg mb-3 form-text text-red-600 small hidden">
                                    Password tidak boleh hanya angka.
                                </div>
                                <div id="confirmError"
                                    class="bg-red-50 p-4 rounded-lg mb-3 form-text text-red-600 small hidden">
                                    Konfirmasi password tidak sama.
                                </div>
                            </div>

                            <form action="{{ route('student.profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Password
                                            Saat Ini</label>
                                        <div class="relative">
                                            <input type="password" id="currentPasswordInput" name="current_password"
                                                required
                                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 password-input">
                                            <button type="button"
                                                class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600"
                                                onclick="togglePass(this)">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Password
                                            Baru</label>
                                        <div class="relative">
                                            <input type="password" id="passwordInput" name="password" required
                                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 password-input">
                                            <button type="button"
                                                class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600"
                                                onclick="togglePass(this)">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Konfirmasi
                                            Password</label>
                                        <div class="relative">
                                            <input type="password" id="confirmPasswordInput" name="password_confirmation"
                                                required
                                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 password-input">
                                            <button type="button"
                                                class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600"
                                                onclick="togglePass(this)">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 text-right">
                                    <button type="submit" id="submitBtnUpdate"
                                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-full shadow-md transition duration-200 flex items-center gap-2 ml-auto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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
