@if ($trx->status === 'paid')
    <!DOCTYPE html>
    <html lang="id" class="light">

    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kwitansi - {{ $trx->trx_code }}</title>

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @vite(['resources/css/student.css', 'resources/js/app.js'])
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

        <style>
            .font-mono {
                font-family: 'Space Mono', monospace;
            }

            .ticket::after {
                content: "";
                position: absolute;
                bottom: -10px;
                left: 0;
                width: 100%;
                height: 20px;
                background: linear-gradient(45deg, transparent 50%, #F2F4F8 50%),
                    linear-gradient(-45deg, transparent 50%, #F2F4F8 50%);
                background-size: 20px 20px;
                background-repeat: repeat-x;
                transition: background 0.3s;
            }

            .dark .ticket::after {
                background: linear-gradient(45deg, transparent 50%, #1a1c23 50%),
                    linear-gradient(-45deg, transparent 50%, #1a1c23 50%);
            }

            @media print {
                body {
                    background: white;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .no-print {
                    display: none !important;
                }

                .ticket {
                    box-shadow: none;
                    border: 2px solid #000;
                    margin: 0;
                    width: 100%;
                    max-width: none;
                }

                .ticket::after {
                    display: none;
                }

                .dark .ticket {
                    background: white;
                    color: black;
                }

                nav {
                    display: none !important;
                }
            }
        </style>
    </head>

    <body class="bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors duration-300">

        {{-- <nav class="sticky top-4 mx-4 z-50">
        <div
            class="clay-card !rounded-2xl !p-3 flex justify-between items-center shadow-lg /80 dark:bg-slate-800/80 backdrop-blur-md">

        </div>
    </nav> --}}

        <main class="container mx-auto px-4 py-6 pb-24">
            <div class="w-full">

                <div
                    class="w-full max-w-2xl mx-auto flex justify-between items-center mb-8 no-print animate-fade-in-down">

                    <button type="button" onclick="window.close()"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-white dark:bg-[#24262d] text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition shadow-sm border border-slate-200 dark:border-slate-700">
                        <i class="bi bi-arrow-left"></i> <span class="hidden sm:inline">Kembali</span>
                    </button>

                    <button id="darkToggle"
                        class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-yellow-400 hover:scale-110 transition btn-squishy">
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>
                    <div class="flex items-center gap-3">
                        <button onclick="window.print()"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-[var(--bbc-hex)] text-white font-bold text-sm shadow-lg shadow-red-200 dark:shadow-none hover:brightness-110 transition hover:-translate-y-1">
                            <i class="bi bi-printer-fill"></i> <span class="hidden sm:inline">Cetak</span>
                        </button>
                    </div>
                </div>

                <div
                    class="ticket relative w-full max-w-2xl mx-auto bg-white dark:bg-[#24262d] rounded-3xl shadow-2xl overflow-hidden mb-10 border border-slate-100 dark:border-slate-700 transition-colors duration-300">

                    <div class="bg-[var(--bbc-hex)] p-8 text-center relative overflow-hidden">
                        <div
                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                            <span
                                class="text-9xl font-black text-white border-8 border-white p-4 rounded-xl -rotate-12 block uppercase tracking-widest">LUNAS</span>
                        </div>

                        <div class="relative z-10 text-white">
                            <div
                                class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-inner border border-white/30">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <h1 class="text-2xl font-[900] tracking-wide uppercase mb-1">Kwitansi Pembayaran</h1>
                            <p class="text-white/90 text-sm font-medium">SMK Budi Bakti Ciwidey</p>
                        </div>

                        <div class="absolute inset-0 opacity-20"
                            style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 10px 10px;">
                        </div>
                    </div>

                    <div class="p-8 sm:p-10">

                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-dashed border-slate-200 dark:border-slate-700 pb-6 mb-6 gap-4">
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Diterima Dari
                                </p>
                                <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ $trx->student->name }}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 font-mono">
                                    {{ $trx->student->nisn ?? 'NIPD: ' . $trx->student->nipd }}</p>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">No. Referensi
                                </p>
                                <span
                                    class="font-mono font-bold text-slate-700 dark:text-slate-200 text-lg bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg">
                                    {{ $trx->trx_code }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-8 text-sm">
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Tanggal Bayar
                                </p>
                                <p class="font-bold text-slate-700 dark:text-slate-300">
                                    {{ date('d F Y', strtotime($trx->payment_date)) }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">
                                    Pukul {{ date('H:i', strtotime($trx->created_at)) }} WIB
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Metode Bayar
                                </p>
                                <p class="font-bold text-slate-700 dark:text-slate-300 uppercase">
                                    {{ $trx->payment_method == 'manual' ? 'Transfer Bank' : 'Virtual Account' }}
                                </p>
                                @if ($trx->payment_method == 'manual')
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">{{ $trx->bank_sender }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div
                            class="bg-slate-50 dark:bg-slate-700/30 rounded-2xl p-5 border border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Pembayaran
                                    Untuk</p>
                                <p class="font-bold text-slate-800 dark:text-white text-lg">{{ $trx->bill->title }}</p>
                            </div>
                            <div class="text-right">
                                <span class="block text-2xl font-[900] text-[var(--bbc-hex)]">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="flex flex-col sm:flex-row justify-between items-end mt-10 pt-6 border-t border-slate-100 dark:border-slate-700 gap-6">
                            <div class="text-xs text-slate-400 text-center sm:text-left">
                                <p>Dicetak pada: {{ date('d M Y H:i') }}</p>
                                <p class="mt-1">Dokumen ini sah dan diterbitkan secara elektronik oleh sistem.</p>
                            </div>
                            <div class="text-center">
                                <div
                                    class="h-16 w-32 border-2 border-slate-300 dark:border-slate-600 rounded-lg flex items-center justify-center mb-2 mx-auto sm:mx-0 opacity-50 rotate-[-5deg]">
                                    <span
                                        class="font-mono font-bold text-slate-400 text-xs tracking-widest">VERIFIED</span>
                                </div>
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                                    Bagian
                                    Keuangan</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

        <script>
            const darkToggle = document.getElementById('darkToggle');
            darkToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.theme = 'dark';
                } else {
                    localStorage.theme = 'light';
                }
            });
        </script>

        @stack('scripts')
    </body>

    </html>
@else
    <!DOCTYPE html>
    <html lang="id" data-bs-theme="light">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Verifikasi Dokumen - {{ $trx->student->name }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

        <style>
            :root {
                --bbc-primary: oklab(55.91% 0.20543 0.09128);
            }

            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background: #f0f2f5;
                height: 100vh;
                overflow: hidden;
            }

            /* Layout Split */
            .split-container {
                display: flex;
                height: 100vh;
                width: 100vw;
            }

            /* Preview Pane (Kiri) */
            .preview-pane {
                flex: 1;
                background: #212529;
                overflow-y: auto;
                padding: 20px;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Action Pane (Kanan) */
            .action-pane {
                width: 400px;
                min-width: 400px;
                /* Cegah penyempitan */
                background: white;
                border-left: 1px solid #dee2e6;
                display: flex;
                flex-direction: column;
                height: 100vh;
                z-index: 20;
                /* Pastikan di atas layer lain */
            }

            /* Card Doc */
            .doc-card {
                background: #fff;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 15px;
                border: 1px solid #e9ecef;
                transition: all 0.2s;
                position: relative;
                cursor: pointer;
                /* Biar kerasa bisa diklik */
            }

            .doc-card:hover,
            .doc-card.active {
                border-color: var(--bbc-primary);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                transform: translateY(-2px);
            }

            /* Status Colors */
            .doc-card.invalid {
                background-color: #fff5f5;
                border-color: #dc3545;
            }

            .doc-card.valid {
                background-color: #f0fff4;
                border-color: #198754;
            }

            /* Preview Image */
            .preview-img {
                max-width: 100%;
                max-height: 90vh;
                object-fit: contain;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            }

            .preview-pdf {
                width: 100%;
                height: 90vh;
                border-radius: 8px;
                border: none;
            }

            /* Parts */
            .action-header {
                padding: 20px;
                border-bottom: 1px solid #f0f0f0;
                background: white;
                flex-shrink: 0;
            }

            .action-body {
                padding: 20px;
                flex: 1;
                overflow-y: auto;
            }

            .action-footer {
                padding: 20px;
                border-top: 1px solid #f0f0f0;
                background: #f8f9fa;
                flex-shrink: 0;
            }

            /* Klik Area Helper */
            .click-area {
                position: absolute;
                inset: 0;
                z-index: 1;
            }

            .btn-group,
            textarea {
                position: relative;
                z-index: 2;
                /* Biar tombol radio dan textarea tetap bisa diklik */
            }
        </style>
    </head>

    <body>

        <form id="verificationForm" class="split-container">

            <div class="preview-pane" id="previewContainer">
                <div class="text-center text-secondary">
                    <i class="bi bi-arrow-right-circle fs-1 mb-3 d-block opacity-50"></i>
                    <p>Pilih dokumen di sebelah kanan.</p>
                </div>
            </div>

            <div class="action-pane">

                <div class="action-header">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($trx->student->name) }}&background=random"
                            class="rounded-circle me-2" width="40" height="40">
                        <div style="line-height: 1.2; overflow: hidden;">
                            <h6 class="fw-bold mb-0 text-truncate">{{ $trx->student->name }}</h6>
                            <small class="text-muted">{{ $trx->student->nipd }}</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill small">
                            {{ $trx->bill->title }}
                        </span>
                    </div>
                </div>

                <div class="action-body">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Daftar Dokumen</h6>

                    @foreach ($trx->files as $file)
                        <div class="doc-card {{ $file->status }}" id="card-{{ $file->id }}">

                            <div class="click-area btn-preview" data-url="{{ asset('storage/' . $file->file_path) }}"
                                data-type="{{ pathinfo($file->file_path, PATHINFO_EXTENSION) }}"
                                data-id="{{ $file->id }}">
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text-fill text-primary me-2 fs-5"></i>
                                    <span class="fw-bold text-dark text-break"
                                        style="font-size: 0.9rem; line-height: 1.2;">
                                        {{ $file->requirement->document_name }}
                                    </span>
                                </div>
                                <i class="bi bi-eye text-muted small"></i>
                            </div>

                            <div class="btn-group w-100 mb-2" role="group">
                                <input type="radio" class="btn-check file-verdict"
                                    name="files[{{ $file->id }}][status]" id="valid-{{ $file->id }}"
                                    value="valid" data-id="{{ $file->id }}"
                                    {{ $file->status != 'invalid' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success btn-sm" for="valid-{{ $file->id }}">
                                    <i class="bi bi-check-lg"></i> Valid
                                </label>

                                <input type="radio" class="btn-check file-verdict"
                                    name="files[{{ $file->id }}][status]" id="invalid-{{ $file->id }}"
                                    value="invalid" data-id="{{ $file->id }}"
                                    {{ $file->status == 'invalid' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger btn-sm" for="invalid-{{ $file->id }}">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </label>
                            </div>

                            <div class="{{ $file->status == 'invalid' ? '' : 'd-none' }}"
                                id="reason-wrapper-{{ $file->id }}">
                                <textarea class="form-control form-control-sm bg-light border-0" name="files[{{ $file->id }}][reason]"
                                    placeholder="Alasan penolakan..." rows="2">{{ $file->reject_reason }}</textarea>
                            </div>
                        </div>
                    @endforeach

                    <div class="card bg-light border-0 p-3 mt-4 text-center">
                        <div id="verdict-display">
                            <h5 class="fw-bold text-success mb-1"><i class="bi bi-check-circle-fill"></i> DITERIMA
                            </h5>
                            <small class="text-muted">Semua dokumen aman.</small>
                        </div>
                        <input type="hidden" name="verdict" id="mainVerdict" value="valid">
                    </div>

                    <div class="mt-3 d-none" id="global-note-wrapper">
                        <label class="small fw-bold text-muted mb-1">Catatan untuk Siswa</label>
                        <textarea class="form-control bg-light border-0" name="admin_note" id="adminNote" rows="3"></textarea>
                    </div>
                </div>

                <div class="action-footer d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold" id="btnSave">
                        <i class="bi bi-save me-2"></i> SIMPAN KEPUTUSAN
                    </button>
                    <button type="button" onclick="window.close()" class="btn btn-light text-muted">Tutup</button>
                </div>
            </div>
        </form>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {

                // --- 1. LOGIC PREVIEW ---
                $(document).on('click', '.btn-preview', function(e) {
                    $('.doc-card').removeClass('active border-primary shadow-sm');
                    $(this).closest('.doc-card').addClass('active border-primary shadow-sm');

                    let url = $(this).data('url');
                    let type = $(this).data('type') ? $(this).data('type').toString().toLowerCase() : 'unknown';
                    let container = $('#previewContainer');

                    container.html('<div class="spinner-border text-light" role="status"></div>');

                    setTimeout(() => {
                        container.empty();
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(type)) {
                            container.html(
                                `<img src="${url}" class="preview-img animate__animated animate__zoomIn animate__faster" onerror="this.parentElement.innerHTML='<div class=\'text-center text-danger\'><i class=\'bi bi-exclamation-circle fs-1\'></i><p class=\'mt-2\'>Gagal memuat gambar.</p></div>';">`
                            );
                        } else if (type === 'pdf') {
                            container.html(
                                `<iframe src="${url}" class="preview-pdf animate__animated animate__fadeIn"></iframe>`
                            );
                        } else {
                            container.html(
                                `<div class="text-center text-white"><i class="bi bi-file-earmark-binary fs-1 mb-3"></i><p>Preview tidak tersedia.</p><a href="${url}" target="_blank" class="btn btn-primary btn-sm mt-2">Download File</a></div>`
                            );
                        }
                    }, 100);
                });

                if ($('.btn-preview').length > 0) {
                    setTimeout(() => {
                        $('.btn-preview').first().click();
                    }, 300);
                }

                // --- 2. LOGIC VERDICT (INTELLIGENT STATUS) ---
                function updateVerdict() {
                    let invalidDocs = $('.file-verdict[value="invalid"]:checked');
                    let invalidCount = invalidDocs.length;

                    // Cek apakah dokumen ini adalah REVISI?
                    // Tandanya: Status dokumen 'invalid', TAPI status transaksi 'pending_docs' (baru masuk antrian)
                    // Di sini kita ambil nilai dari reject_reason yang lama.

                    let revisedCount = 0;
                    let noteIssues = [];

                    invalidDocs.each(function() {
                        let card = $(this).closest('.doc-card');
                        let name = card.find('.fw-bold').text().trim();
                        let reasonBox = card.find('textarea');
                        let reason = reasonBox.val() || 'Tidak valid';

                        // Deteksi REVISI: Jika ada reason lama tapi user sedang melihat form ini
                        // Kita anggap sebagai "Masih Perlu Dicek"
                        // Tapi karena radio button 'invalid' checked, kita hitung sebagai masalah.

                        noteIssues.push(`- ${name}: ${reason}`);
                    });

                    let display = $('#verdict-display');
                    let mainVerdict = $('#mainVerdict');
                    let btn = $('#btnSave');
                    let noteWrapper = $('#global-note-wrapper');
                    let note = $('#adminNote');

                    // === LOGIKA BARU DI SINI ===
                    // Kita cek atribut data-is-revision yang kita suntikkan nanti di HTML

                    if (invalidCount > 0) {
                        mainVerdict.val('invalid');

                        // Jika status transaksi 'pending_docs' (artinya siswa baru kirim),
                        // tapi file masih ditandai invalid (karena history),
                        // Kita kasih label "SUDAH DIREVISI" biar Admin ngeh.

                        let trxStatus = "{{ $trx->status }}"; // Ambil dari Blade

                        if (trxStatus === 'pending_docs') {
                            display.html(`
                            <h5 class="fw-bold text-warning mb-1"><i class="bi bi-exclamation-circle-fill"></i> SUDAH DIREVISI</h5>
                            <small class="text-muted">${invalidCount} dokumen diperbaiki siswa (History Ditolak).</small>
                        `);
                            // Tombol tetap merah, karena kalau Admin tidak ubah jadi Valid, berarti ditolak lagi.
                            btn.removeClass('btn-primary').addClass('btn-danger').html('TOLAK LAGI (KEMBALIKAN)');
                        } else {
                            display.html(`
                            <h5 class="fw-bold text-danger mb-1"><i class="bi bi-x-circle-fill"></i> DITOLAK</h5>
                            <small class="text-muted">Ada ${invalidCount} dokumen bermasalah.</small>
                        `);
                            btn.removeClass('btn-primary').addClass('btn-danger').html('KEMBALIKAN KE SISWA');
                        }

                        noteWrapper.removeClass('d-none');
                        note.val("Mohon perbaiki dokumen berikut:\n" + noteIssues.join("\n"));

                    } else {
                        mainVerdict.val('valid');
                        display.html(`
                        <h5 class="fw-bold text-success mb-1"><i class="bi bi-check-circle-fill"></i> DITERIMA</h5>
                        <small class="text-muted">Semua dokumen valid.</small>
                    `);
                        btn.removeClass('btn-danger').addClass('btn-primary').html('LANJUT KE KEUANGAN');
                        noteWrapper.addClass('d-none');
                        note.val('Dokumen valid. Lanjut ke verifikasi pembayaran.');
                    }
                }

                $('.file-verdict').change(function() {
                    let id = $(this).data('id');
                    let val = $(this).val();
                    let card = $(`#card-${id}`);
                    let reasonBox = $(`#reason-wrapper-${id}`);

                    card.removeClass('valid invalid').addClass(val);

                    if (val === 'invalid') {
                        reasonBox.removeClass('d-none');
                        // Jika ini adalah revisi (pending_docs), kosongkan reason lama biar admin isi baru
                        if ("{{ $trx->status }}" === 'pending_docs') {
                            // Opsional: kosongkan textarea kalau mau admin ngetik ulang alasan tolaknya
                            // reasonBox.find('textarea').val('');
                        }
                        reasonBox.find('textarea').prop('required', true).focus();
                    } else {
                        reasonBox.addClass('d-none');
                        reasonBox.find('textarea').prop('required', false);
                    }
                    updateVerdict();
                });

                // Trigger update saat note per-file diedit
                $(document).on('input', 'textarea[name^="files"]', function() {
                    updateVerdict();
                });

                // Initial check saat load
                updateVerdict();

                // --- 3. SUBMIT AJAX ---
                $('#verificationForm').on('submit', function(e) {
                    e.preventDefault();
                    let btn = $('#btnSave');
                    btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...');

                    $.ajax({
                        url: "{{ route('admin.du.transactions.update', $trx->id) }}",
                        method: "PUT",
                        data: $(this).serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            Swal.fire('Berhasil!', res.message, 'success').then(() => {
                                if (window.opener && !window.opener.closed) {
                                    if (typeof window.opener.filterStatus === 'function') {
                                        window.opener.filterStatus('pending_docs');
                                    } else {
                                        window.opener.location.reload();
                                    }
                                }
                                window.close();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Gagal menyimpan data.', 'error');
                            btn.prop('disabled', false).html('SIMPAN KEPUTUSAN');
                        }
                    });
                });
            });
        </script>
    </body>

    </html>

@endif
