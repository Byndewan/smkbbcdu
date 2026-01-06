<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - {{ $bill->title }}</title>
    @vite(['resources/css/student.css', 'resources/js/app.js'])
    <style>
        .payment-radio:checked+.payment-card {
            border-color: #2563EB;
            background-color: #EFF6FF;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1);
        }

        .check-icon {
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s ease-in-out;
        }

        .payment-radio:checked+.payment-card .check-icon {
            opacity: 1;
            transform: scale(1);
        }
    </style>
</head>

<body class="bg-gray-50 font-sans pb-32">
    <nav class="bg-white shadow-sm px-4 py-3 sticky top-0 z-50">
        <div class="max-w-full mx-auto flex items-center gap-3">
            @if (($transaction->status ?? '') === 'draft' || ($transaction->status ?? '') === null)
            <a href="{{ route('student.bills.show', $bill->id) }}" class="text-gray-500 hover:text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            @endif
            <h1 class="font-bold text-lg text-gray-800">Pembayaran</h1>
        </div>
    </nav>

    <div class="max-w-full mx-auto mt-6 px-4">
        @if (in_array($transaction->status, ['pending_docs', 'payment_review']))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm leading-5 font-medium text-yellow-800">Sedang Diverifikasi</h3>
                        <div class="mt-2 text-sm leading-5 text-yellow-700">
                            <p>Data pembayaran kamu sedang diperiksa oleh
                                <strong>{{ $transaction->status == 'pending_docs' ? 'Admin Daftar Ulang' : 'Bagian Keuangan' }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($transaction->status == 'payment_rejected')
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded shadow-sm animate-pulse">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm leading-5 font-bold text-red-800">Pembayaran Ditolak</h3>
                        <div class="mt-2 text-sm leading-5 text-red-700">
                            <p class="font-semibold">Alasan Penolakan:</p>
                            <p class="bg-white p-2 rounded border border-red-200 italic mt-1 font-medium">
                                "{{ $transaction->admin_note }}"
                            </p>
                            <p class="mt-2">Silakan perbaiki data pembayaran dan upload ulang bukti di bawah ini.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div
            class="bg-blue-600 text-white rounded-xl p-5 shadow-lg mb-6 flex justify-between items-center relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-blue-100 text-xs">Total yang harus dibayar</p>
                <h2 class="text-2xl font-bold">Rp {{ number_format($bill->amount, 0, ',', '.') }}</h2>
            </div>
            <div class="relative z-10 text-right">
                <span class="bg-blue-500 text-xs px-2 py-1 rounded">Invoice #{{ $transaction->trx_code }}</span>
            </div>
            <div class="absolute -right-4 -bottom-8 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
        </div>

        <form action="{{ route('student.bills.payment.process', $bill->id) }}" method="POST"
            enctype="multipart/form-data" id="payment-form">
            @csrf
            <input type="hidden" name="du_transaction_id" value="{{ $transaction->id }}">

            <fieldset {{ in_array($transaction->status, ['pending_docs', 'payment_review']) ? 'disabled' : '' }}>

                <h3 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wide">Pilih Metode Pembayaran</h3>

                <div class="grid grid-cols-1 gap-4 mb-6">
                    <label class="cursor-pointer relative">
                        <input type="radio" name="payment_method" value="manual" class="payment-radio hidden"
                            {{ $transaction->payment_method == 'manual' || !$transaction->payment_method ? 'checked' : '' }}>
                        <div
                            class="payment-card bg-white border border-gray-200 p-4 rounded-xl transition-all duration-200 hover:border-blue-300">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800">Transfer Manual</h4>
                                    <p class="text-xs text-gray-500">Upload bukti transfer</p>
                                </div>
                                <div class="check-icon text-blue-600">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="cursor-pointer relative">
                        <input type="radio" name="payment_method" value="bni" class="payment-radio hidden"
                            {{ $transaction->payment_method == 'bni' ? 'checked' : '' }}>
                        <div
                            class="payment-card bg-white border border-gray-200 p-4 rounded-xl transition-all duration-200 hover:border-blue-300">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800">BNI Virtual Account</h4>
                                    <p class="text-xs text-gray-500">Cek Otomatis (Instan)</p>
                                </div>
                                <div class="check-icon text-blue-600">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <div id="section-manual" class="method-section">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6 text-center">
                        <p class="text-gray-500 text-xs mb-2 uppercase tracking-wide">Transfer ke Rekening Sekolah</p>
                        <img src="https://iconlogovector.com/uploads/images/2023/04/md-44b66d3e868ed529c1b691fd2bbb822861.jpg"
                            class="h-8 mx-auto mb-3" alt="BNI">
                        <h2 class="text-3xl font-bold text-gray-800 tracking-wider mb-1" id="copy-text">
                            {{ $bankAccount['account_number'] }}</h2>
                        <p class="font-medium text-gray-600 mb-4">{{ $bankAccount['account_name'] }}</p>
                        <button type="button" onclick="copyToClipboard()"
                            class="text-xs bg-gray-100 text-gray-600 px-4 py-2 rounded-full hover:bg-gray-200 transition font-medium">
                            <i class="bi bi-clipboard"></i> Salin Nomor Rekening
                        </button>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-5 pb-2 border-b border-gray-100">Konfirmasi Transfer</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Pengirim</label>
                                <select name="bank_sender"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-blue-500"
                                    required>
                                    <option value="" disabled
                                        {{ !$transaction->bank_sender ? 'selected' : '' }}>-- Pilih Bank Anda --
                                    </option>
                                    @foreach (['BNI', 'BRI', 'BCA', 'Mandiri', 'BSI', 'Lainnya'] as $bank)
                                        <option value="{{ $bank }}"
                                            {{ $transaction->bank_sender == $bank ? 'selected' : '' }}>
                                            {{ $bank }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening Anda</label>
                                <input type="number" name="account_number"
                                    value="{{ $transaction->account_number }}"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-blue-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik
                                    Rekening</label>
                                <input type="text" name="account_name"
                                    value="{{ $transaction->account_name ?? $student->name }}"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-blue-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transfer</label>
                                <input type="date" name="payment_date"
                                    value="{{ $transaction->payment_date ? date('Y-m-d', strtotime($transaction->payment_date)) : date('Y-m-d') }}"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-blue-500"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti /
                                    Struk</label>
                                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col gap-2 transition hover:border-blue-300"
                                    id="proof-container">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div id="proof-icon"
                                                class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->proof_path ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-400' }}">
                                                @if ($transaction->proof_path)
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">Bukti Transfer</p>
                                                <p class="text-xs text-gray-400" id="proof-status-text">
                                                    {{ $transaction->proof_path ? 'File tersimpan' : 'Wajib diupload (JPG/PNG/PDF)' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ $transaction->proof_path ? Storage::url($transaction->proof_path) : '#' }}"
                                                target="_blank" id="btn-view-proof"
                                                class="px-3 py-2 rounded-lg text-gray-500 border border-gray-200 hover:bg-gray-50 {{ $transaction->proof_path ? '' : 'hidden' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <input type="file" name="proof_file" id="proof_file_input"
                                                class="hidden" accept="image/*,.pdf"
                                                {{ $transaction->status == 'payment_rejected' || !$transaction->proof_path ? 'required' : '' }}>
                                            <label for="proof_file_input" id="btn-upload-proof"
                                                class="cursor-pointer bg-white text-blue-600 border border-blue-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-50 transition">
                                                {{ $transaction->proof_path ? 'Ganti File' : 'Upload' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="section-va" class="method-section hidden">
                    @if ($transaction->va_number)
                        <div class="bg-blue-50 p-8 rounded-xl border border-blue-200 text-center mb-6">
                            <h3 class="text-gray-500 text-sm uppercase tracking-wide mb-2">Nomor Virtual Account BNI
                            </h3>
                            <h1 class="text-4xl font-bold text-blue-800" id="va-number">{{ $transaction->va_number }}
                            </h1>
                            <p class="text-sm text-gray-600 mt-2">Kadaluarsa: <span
                                    class="font-bold text-red-500">{{ date('d M Y, H:i', strtotime($transaction->payment_expiry_time)) }}</span>
                            </p>
                        </div>
                    @else
                        <div class="bg-white p-10 rounded-xl shadow-sm border border-gray-100 text-center">
                            <div
                                class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-5">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-800 text-xl mb-2">Bayar via BNI Virtual Account</h3>
                            <p class="text-xs text-blue-500 animate-pulse">Klik tombol di bawah untuk mendapatkan Kode
                                Pembayaran</p>
                        </div>
                    @endif
                </div>

            </fieldset>

            @if (!in_array($transaction->status, ['pending_docs', 'payment_review']))
                <div
                    class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 shadow-[0_-4px_10px_-1px_rgba(0,0,0,0.1)] z-50">
                    <div class="max-w-full mx-auto">
                        <button type="submit" id="btn-submit"
                            class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition shadow-lg flex justify-center items-center gap-2 transform active:scale-95 text-lg">
                            <span>{{ $transaction->status == 'payment_rejected' ? 'Kirim Perbaikan Data' : 'Konfirmasi Pembayaran' }}</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const hasVA = "{{ $transaction->va_number ? 'true' : 'false' }}";
            $('input[name="payment_method"]').on('change', function() {
                let method = $(this).val();
                $('.method-section').addClass('hidden');
                let btn = $('#btn-submit');

                if (method === 'manual') {
                    $('#section-manual').removeClass('hidden');
                    btn.removeClass('bg-green-600').addClass('bg-blue-600').find('span').text(
                        "{{ $transaction->status == 'payment_rejected' ? 'Kirim Perbaikan Data' : 'Konfirmasi Pembayaran' }}"
                        );
                    $('#section-manual input, #section-manual select').prop('required', true);
                } else if (method === 'bni') {
                    $('#section-va').removeClass('hidden');
                    $('#section-manual input, #section-manual select').prop('required', false);

                    if (hasVA === 'true') {
                        btn.parent().parent().addClass('hidden');
                    } else {
                        btn.removeClass('bg-blue-600').addClass('bg-green-600').find('span').text(
                            'Dapatkan Kode Pembayaran');
                    }
                }
            });
            $('input[name="payment_method"]:checked').trigger('change');

            $('#proof_file_input').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const localPreviewUrl = URL.createObjectURL(file);
                    const btnView = $('#btn-view-proof');
                    btnView.attr('href', localPreviewUrl).removeClass('hidden');

                    $('#proof-status-text').text('File terpilih: ' + file.name).addClass(
                        'text-blue-600 font-medium');
                    $('#btn-upload-proof').text('Ganti File');

                    const iconBox = $('#proof-icon');
                    iconBox.removeClass('bg-gray-100 text-gray-400').addClass('bg-blue-100 text-blue-600');
                    iconBox.html(
                        `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`
                        );
                    $('#proof-container').addClass('border-blue-300 bg-blue-50');
                }
            });
        });

        function copyToClipboard() {
            let text = document.getElementById("copy-text").innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert("Nomor rekening berhasil disalin!");
            });
        }

        function copyVA() {
            let text = document.getElementById("va-number").innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert("Nomor VA berhasil disalin!");
            });
        }
    </script>
</body>

</html>
