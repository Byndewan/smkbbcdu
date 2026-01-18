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
        <title>Verifikasi Pembayaran - {{ $trx->student->name }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

            .split-container {
                display: flex;
                height: 100vh;
            }

            .preview-pane {
                flex: 1;
                background: #343a40;
                overflow-y: auto;
                padding: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .action-pane {
                width: 400px;
                background: white;
                border-left: 1px solid #dee2e6;
                overflow-y: auto;
                display: flex;
                flex-direction: column;
            }

            .preview-img {
                max-width: 100%;
                max-height: 90vh;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            }

            .action-header {
                padding: 20px;
                border-bottom: 1px solid #f0f0f0;
                background: white;
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .action-footer {
                padding: 20px;
                border-top: 1px solid #f0f0f0;
                background: #f8f9fa;
                position: sticky;
                bottom: 0;
            }
        </style>
    </head>

    <body>
        <form id="financeForm" class="split-container">
            <div class="preview-pane">
                @if ($trx->payment_method == 'manual')
                    @if ($trx->proof_path)
                        @if (str_ends_with($trx->proof_path, '.pdf'))
                            <iframe src="{{ Storage::url($trx->proof_path) }}"
                                style="width:100%; height:90vh; border:none; border-radius:8px;"></iframe>
                        @else
                            <img src="{{ Storage::url($trx->proof_path) }}" class="preview-img">
                        @endif
                    @else
                        <div class="text-white text-center opacity-50">
                            <i class="bi bi-image-alt fs-1"></i>
                            <p class="mt-2">Tidak ada bukti transfer.</p>
                        </div>
                    @endif
                @else
                    <div class="card p-5 text-center shadow-lg border-0">
                        <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png"
                            width="100" class="mb-4 mx-auto">
                        <h2 class="fw-bold">{{ $trx->va_number }}</h2>
                        <p class="text-muted">Pembayaran via Virtual Account (Otomatis)</p>
                        <div class="alert alert-info small mb-0">Status Midtrans: {{ $trx->status }}</div>
                    </div>
                @endif
            </div>

            <div class="action-pane">
                <div class="action-header">
                    <h5 class="fw-bold text-primary mb-1">{{ $trx->student->name }}</h5>
                    <small class="text-muted d-block mb-3">{{ $trx->student->nipd }}</small>

                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3">
                        <span class="small text-muted">Total Bayar</span>
                        <span class="fw-bold text-dark fs-5">Rp
                            {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="p-4 flex-grow-1">

                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Detail Transfer</h6>
                    <table class="table table-sm table-borderless text-sm mb-4">
                        <tr>
                            <td class="text-muted">Metode</td>
                            <td class="fw-bold text-end text-uppercase">{{ $trx->payment_method }}</td>
                        </tr>
                        @if ($trx->payment_method == 'manual')
                            <tr>
                                <td class="text-muted">Bank</td>
                                <td class="fw-bold text-end">{{ $trx->bank_sender }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Atas Nama</td>
                                <td class="fw-bold text-end">{{ $trx->account_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Rekening</td>
                                <td class="fw-bold text-end">{{ $trx->account_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tgl Transfer</td>
                                <td class="fw-bold text-end">{{ date('d M Y', strtotime($trx->payment_date)) }}</td>
                            </tr>
                        @endif
                    </table>

                    <hr class="opacity-10">

                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Keputusan</h6>

                    <div class="card bg-light border-0 p-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_status" id="valid"
                                value="valid" required>
                            <label class="form-check-label fw-bold text-success" for="valid">
                                <i class="bi bi-check-circle-fill me-1"></i> DANA DITERIMA (LUNAS)
                            </label>
                        </div>
                        <hr>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_status" id="invalid"
                                value="invalid">
                            <label class="form-check-label fw-bold text-danger" for="invalid">
                                <i class="bi bi-x-circle-fill me-1"></i> TOLAK (SALAH NOMINAL/PALSU)
                            </label>
                        </div>
                    </div>

                    <div class="mt-3 d-none" id="reasonBox">
                        <label class="small fw-bold text-danger mb-1">Alasan Penolakan</label>
                        <textarea class="form-control bg-light border-0" name="admin_note" rows="3"
                            placeholder="Contoh: Nominal tidak sesuai mutasi..."></textarea>
                    </div>

                </div>

                <div class="action-footer d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold" id="btnSave">PROSES VERIFIKASI</button>
                    <button type="button" onclick="window.close()" class="btn btn-light text-muted">Batal</button>
                </div>
            </div>
        </form>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $('input[name="payment_status"]').change(function() {
                if ($(this).val() === 'invalid') {
                    $('#reasonBox').removeClass('d-none').find('textarea').prop('required', true);
                    $('#btnSave').removeClass('btn-primary').addClass('btn-danger').text('TOLAK PEMBAYARAN');
                } else {
                    $('#reasonBox').addClass('d-none').find('textarea').prop('required', false);
                    $('#btnSave').removeClass('btn-danger').addClass('btn-primary').text('TERIMA & LUNASKAN');
                }
            });

            $('#financeForm').on('submit', function(e) {
                e.preventDefault();
                let btn = $('#btnSave');
                btn.prop('disabled', true).text('Menyimpan...');
                $.ajax({
                    url: "{{ route('admin.finance.update', $trx->id) }}",
                    method: "PUT",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        Swal.fire('Berhasil!', res.message, 'success').then(() => {
                            if (window.opener) window.opener.filterStatus('payment_review');
                            window.close();
                        });
                    },
                    error: function(err) {
                        Swal.fire('Error', 'Gagal memproses.', 'error');
                        btn.prop('disabled', false);
                    }
                });
            });
        </script>
    </body>

    </html>
@endif
