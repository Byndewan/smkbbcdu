<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Status Pembayaran - {{ $bill->title }}</title>
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
        <div class="max-w-2xl mx-auto flex items-center gap-3">
            <a href="{{ route('student.bills.show', $bill->id) }}" class="text-gray-500 hover:text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="font-bold text-lg text-gray-800">Pembayaran</h1>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto mt-6 px-4">

        @if ($transaction->status == 'pending')
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
                        <h3 class="text-sm leading-5 font-medium text-yellow-800">Pembayaran Sedang Diperiksa</h3>
                        <div class="mt-2 text-sm leading-5 text-yellow-700">
                            <p>Admin sedang mengecek bukti transfer kamu. Mohon tunggu, notifikasi akan muncul jika
                                sudah diverifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($transaction->status == 'rejected')
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm leading-5 font-bold text-red-800">Verifikasi Gagal / Ditolak</h3>
                        <div class="mt-2 text-sm leading-5 text-red-700">
                            <p class="font-semibold mb-1">Catatan Admin:</p>
                            <p class="bg-white p-2 rounded border border-red-200 italic">
                                "{{ $transaction->admin_note ?? 'Mohon periksa kembali kelengkapan data.' }}"
                            </p>

                            @if (str_contains($transaction->admin_note, 'Dokumen'))
                                <p class="mt-2 font-bold"><a href="{{ route('student.bills.show', $bill->id) }}"
                                        class="underline hover:text-red-900">Klik disini untuk memperbaiki Dokumen (Step
                                        1)</a></p>
                            @else
                                <p class="mt-2">Silakan perbaiki data pembayaran dan upload bukti baru di bawah ini.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($transaction->status == 'paid')
            <div class="bg-green-100 border-l-4 border-green-500 p-8 mb-6 rounded-xl shadow text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-200 mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-green-800">Pembayaran Lunas!</h2>
                <p class="text-green-700 mt-2">Terima kasih, tagihan ini sudah selesai.</p>
                <div class="mt-6">
                    <a href="{{ route('student.dashboard') }}"
                        class="bg-green-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-green-700 transition">Kembali
                        ke Dashboard</a>
                </div>
            </div>
        @else
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

                <fieldset {{ $transaction->status == 'pending' ? 'disabled' : '' }}>

                    <h3 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wide">Pilih Metode Pembayaran
                    </h3>
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="payment_method" value="manual" class="payment-radio hidden"
                                {{ $transaction->payment_method == 'manual' || !$transaction->payment_method ? 'checked' : '' }}>
                            <div
                                class="payment-card bg-white border border-gray-200 p-4 rounded-xl transition-all duration-200 hover:border-blue-300 {{ $transaction->status == 'pending' ? 'bg-gray-50 opacity-75' : '' }}">
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
                            <input type="radio" name="payment_method" value="bni_va" class="payment-radio hidden"
                                {{ $transaction->payment_method == 'bni_va' ? 'checked' : '' }}>
                            <div
                                class="payment-card bg-white border border-gray-200 p-4 rounded-xl transition-all duration-200 hover:border-blue-300 {{ $transaction->status == 'pending' ? 'bg-gray-50 opacity-75' : '' }}">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                            <p class="text-gray-500 text-xs mb-2 uppercase tracking-wide">Transfer ke Rekening Sekolah
                            </p>
                            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png"
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
                            <h3 class="font-bold text-gray-800 mb-5 pb-2 border-b border-gray-100">Konfirmasi Transfer
                            </h3>
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
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening
                                        Anda</label>
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
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                        Transfer</label>
                                    <input type="date" name="payment_date"
                                        value="{{ $transaction->payment_date ? date('Y-m-d', strtotime($transaction->payment_date)) : date('Y-m-d') }}"
                                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti /
                                        Struk</label>
                                    <input type="file" name="proof_file"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-lg"
                                        {{ $transaction->status == 'rejected' ? 'required' : '' }}>
                                    @if ($transaction->proof_path || ($transaction->admin_note && str_contains($transaction->admin_note, 'Bukti:')))
                                        <p class="text-xs text-blue-500 mt-2">
                                            <a href="#" class="underline">Lihat bukti sebelumnya</a> (Upload
                                            lagi jika ingin mengganti)
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="section-va" class="method-section hidden">
                        <div class="bg-white p-10 rounded-xl shadow-sm border border-gray-100 text-center">
                            <div
                                class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-5">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-800 text-xl mb-2">Virtual Account</h3>
                            <p class="text-gray-500 text-sm max-w-xs mx-auto">Fitur pembayaran otomatis sedang
                                dikembangkan.</p>
                        </div>
                    </div>

                </fieldset>
                @if ($transaction->status != 'pending' || $transaction->status == 'rejected')
                    <div
                        class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 shadow-[0_-4px_10px_-1px_rgba(0,0,0,0.1)] z-50">
                        <div class="max-w-2xl mx-auto">
                            <button type="submit" id="btn-submit"
                                class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition shadow-lg flex justify-center items-center gap-2 transform active:scale-95 text-lg">
                                <span>{{ $transaction->status == 'rejected' ? 'Kirim Ulang Bukti' : 'Konfirmasi Pembayaran' }}</span>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

            </form>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Logic Tab Switch
            $('input[name="payment_method"]').on('change', function() {
                let method = $(this).val();
                $('.method-section').addClass('hidden');

                let btn = $('#btn-submit');

                if (method === 'manual') {
                    $('#section-manual').removeClass('hidden');
                    btn.prop('disabled', false).removeClass('bg-gray-400 cursor-not-allowed').addClass(
                        'bg-blue-600 hover:bg-blue-700 shadow-lg transform active:scale-95');
                    // Text tombol dinamis
                    let btnText =
                        "{{ $transaction->status == 'rejected' ? 'Kirim Ulang Bukti' : 'Konfirmasi Pembayaran' }}";
                    btn.html(
                        `<span>${btnText}</span> <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>`
                        );

                    // Set required
                    $('#section-manual input, #section-manual select').prop('required', true);
                    // Kecuali file upload kalau statusnya REJECTED (karena user mungkin cuma ganti data bank, tapi file tetep wajib sih biasanya)

                } else if (method === 'bni_va') {
                    $('#section-va').removeClass('hidden');
                    btn.prop('disabled', true).addClass('bg-gray-400 cursor-not-allowed').removeClass(
                        'bg-blue-600 hover:bg-blue-700 shadow-lg transform active:scale-95').text(
                        'Metode Belum Tersedia');

                    // Remove required
                    $('#section-manual input, #section-manual select').prop('required', false);
                }
            });

            // Trigger change di awal biar sesuai status database
            $('input[name="payment_method"]:checked').trigger('change');
        });

        function copyToClipboard() {
            let text = document.getElementById("copy-text").innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert("Nomor rekening berhasil disalin!");
            });
        }
    </script>
</body>

</html>
