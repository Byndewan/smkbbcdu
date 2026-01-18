<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - BBC Pay</title>

    <script>
        (function() {
            const getStoredTheme = () => localStorage.getItem('theme');
            const setTheme = theme => {
                document.documentElement.setAttribute('data-bs-theme', theme);
            }
            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme();
                if (storedTheme) return storedTheme;
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            setTheme(getPreferredTheme());
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (!getStoredTheme()) setTheme(getPreferredTheme());
            });
        })();
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    @vite(['resources/css/admin.scss', 'resources/js/app.js'])
    <style>
        body,
        .card,
        .sidebar {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="d-flex">
        <div class="flex-grow-1 d-flex flex-column min-vh-100 overflow-hidden">
            <div class="container-fluid p-4">
                <div class="card p-4">
                    <div class="card-body text-center">
                        @if ($hasTransaction)
                            <div class="icon-box icon-warning">
                                <i class="bi bi-bank2"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Tidak Bisa Dihapus!</h4>
                            <p class="text-muted small mb-4">
                                Siswa <strong>{{ $student->name }}</strong> memiliki riwayat transaksi keuangan yang
                                tercatat di sistem.
                                <br><br>
                                <span class="text-danger fw-bold">* Data keuangan tidak boleh dihapus demi integritas
                                    laporan, silahkan hubungin bagian keuangan terlebih dahulu.</span>
                            </p>
                            <div class="d-grid">
                                <button onclick="window.close()" class="btn btn-dark fw-bold py-2 text-white">Mengerti,
                                    Tutup</button>
                            </div>
                        @elseif($isActive)
                            <div class="icon-box icon-warning">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Siswa Masih Aktif!</h4>
                            <p class="text-muted small mb-4">
                                Siswa <strong>{{ $student->name }}</strong> statusnya masih <b>AKTIF</b>.
                                <br>
                                Silakan ubah status menjadi <b>Non-Aktif / Alumni</b> terlebih dahulu melalui menu Edit
                                sebelum menghapus data.
                            </p>
                            <div class="d-grid gap-2">
                                <button
                                    onclick="opener.location.href='{{ route('admin.core.students.edit', $student->id) }}'; window.close();"
                                    class="btn btn-warning text-white fw-bold py-2">
                                    <i class="bi bi-pencil me-2"></i> Ubah Status
                                </button>
                                <button onclick="window.close()" class="btn btn-light fw-bold py-2">Batal</button>
                            </div>
                        @else
                            <div class="icon-box icon-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>

                            <h4 class="fw-bold text-danger mb-2">Konfirmasi Hapus</h4>
                            <p class="text-muted small mb-4">
                                Anda akan menghapus data siswa <strong>{{ $student->name }}</strong>.
                            </p>

                            <form id="secureDeleteForm"
                                action="{{ route('admin.core.students.destroy', $student->id) }}">
                                @csrf
                                @method('DELETE')

                                <div class="text-start mb-3">
                                    <label class="form-label small fw-bold text-dark">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-key-fill text-muted"></i></span>
                                        <input type="password" name="password"
                                            class="form-control bg-light border-start-0"
                                            placeholder="Masukan Password..." required autofocus>
                                    </div>
                                </div>

                                <div class="text-start mb-4">
                                    <label class="form-label small fw-bold text-dark">Alasan Penghapusan <span
                                            class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control bg-light" rows="2" placeholder="Ketik Disini..." required
                                        minlength="5"></textarea>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-danger fw-bold py-2 shadow-sm" id="btnDelete">
                                        <i class="bi bi-trash-fill me-2"></i> Hapus
                                    </button>
                                    <button type="button" onclick="window.close()"
                                        class="btn btn-light fw-bold py-2 text-muted">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (!$hasTransaction && !$isActive)
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                $('#secureDeleteForm').on('submit', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let btn = $('#btnDelete');
                    let originalText = btn.html();
                    btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...');
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                if (window.opener && !window.opener.closed) {
                                    try {
                                        window.opener.$('#datatable').DataTable().ajax
                                            .reload(null, false);
                                    } catch (e) {
                                        console.log('Table reload failed:', e);
                                    }
                                }
                                window.close();
                            });
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).html(originalText);
                            let msg = xhr.responseJSON ? xhr.responseJSON.message :
                                'Terjadi kesalahan server.';
                            $('.card').addClass('animate__animated animate__shakeX');
                            setTimeout(() => {
                                $('.card').removeClass('animate__animated animate__shakeX');
                            }, 1000);
                            Swal.fire({
                                title: 'Gagal!',
                                text: msg,
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                });
            });
        </script>
    @endif
</body>

</html>
