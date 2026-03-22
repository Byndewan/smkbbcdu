@extends('layouts.siswa')

@section('title', 'Pilih Metode Pembayaran')

@push('styles')
    <style>
        .payment-indicator.active {
            background: var(--bbc-hex);
            border-color: var(--bbc-hex);
        }

        .payment-indicator.active i {
            opacity: 1;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('student.bills.show', $bill->id) }}"
                class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-500 hover:text-[var(--bbc-hex)] transition btn-squishy">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Checkout</span>
                <h1 class="text-2xl font-[800] text-slate-800 dark:text-white leading-none mt-1">Metode Pembayaran</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1 space-y-6">
                <div class="clay-card !p-6 relative overflow-hidden group">
                    <div
                        class="absolute -right-6 -top-6 w-32 h-32 bg-[var(--bbc-hex)] opacity-10 rounded-full blur-2xl group-hover:opacity-20 transition-opacity">
                    </div>

                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1">Total Tagihan</p>
                    <h2 class="text-3xl font-[900] text-slate-800 dark:text-white">Rp
                        {{ number_format($bill->amount, 0, ',', '.') }}</h2>

                    <div
                        class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700/50 flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <i class="bi bi-tag-fill"></i>
                        {{ $bill->title }}
                    </div>
                </div>

                <div class="clay-card !p-6">
                    <h3 class="font-bold text-slate-700 dark:text-slate-200 mb-3">Petunjuk</h3>
                    <ul class="text-sm text-slate-500 space-y-2 list-disc ml-4">
                        <li>Pilih salah satu metode pembayaran di sebelah kanan.</li>
                        <li>Untuk <strong>Transfer Manual</strong>, wajib upload bukti transfer.</li>
                        <li>Untuk <strong>Virtual Account</strong>, cek status otomatis (tanpa upload).</li>
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-2">

                @php
                    $actionRoute =
                        $transaction->status == 'payment_rejected'
                            ? route('student.bills.resubmit', $transaction->id)
                            : route('student.bills.payment.process', $bill->id);
                @endphp

                <form action="{{ $actionRoute }}" method="POST" enctype="multipart/form-data" id="payment-form">
                    @csrf

                    @if ($transaction->status != 'payment_rejected')
                        <input type="hidden" name="du_transaction_id" value="{{ $transaction->id }}">
                    @endif

                    <div class="space-y-4 mb-8">
                        <label class="clay-card !p-5 cursor-pointer relative group transition-all hover:scale-[1.01] block">
                            <input type="radio" name="payment_method" value="manual" class="peer sr-only"
                                {{ $transaction->payment_method == 'manual' || !$transaction->payment_method ? 'checked' : '' }}>
                            <div
                                class="absolute inset-0 border-2 border-transparent peer-checked:border-[var(--bbc-hex)] rounded-[2rem] transition-colors">
                            </div>

                            <div class="flex items-center gap-4 relative z-10">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl shadow-sm">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-800 dark:text-white text-lg">Transfer Bank Lain</h4>
                                    <p class="text-xs text-slate-500">Transfer manual ke rekening sekolah & upload struk.
                                    </p>
                                </div>
                                <div
                                    class="payment-indicator w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center transition-colors">
                                    <i class="bi bi-check-lg text-white opacity-0 transition-opacity"></i>
                                </div>
                            </div>
                        </label>

                        <label class="clay-card !p-5 cursor-pointer relative group transition-all hover:scale-[1.01] block">
                            <input type="radio" name="payment_method" value="bni" class="peer sr-only"
                                {{ $transaction->payment_method == 'bni' ? 'checked' : '' }}>
                            <div
                                class="absolute inset-0 border-2 border-transparent peer-checked:border-[var(--bbc-hex)] rounded-[2rem] transition-colors">
                            </div>

                            <div class="flex items-center gap-4 relative z-10">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl shadow-sm">
                                    <i class="bi bi-credit-card-2-front"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-800 dark:text-white text-lg">BNI Virtual Account</h4>
                                    <p class="text-xs text-slate-500">Cek otomatis, konfirmasi instan.</p>
                                </div>
                                <div
                                    class="payment-indicator w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center transition-colors">
                                    <i class="bi bi-check-lg text-white opacity-0 transition-opacity"></i>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div id="section-manual" class="hidden animate-fade-in-up space-y-6">

                        <div class="clay-card !p-6 text-center bg-white dark:bg-slate-800">
                            <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-3">Rekening Tujuan</p>
                            <h2 class="text-3xl font-[900] text-slate-800 dark:text-white mb-1 tracking-wider font-mono"
                                id="copy-text">
                                {{ $bankAccount['account_number'] }}
                            </h2>
                            <p class="text-slate-500 font-bold mb-4">{{ $bankAccount['account_name'] }} -
                                {{ $bankAccount['bank_name'] }}</p>

                            <button type="button" onclick="copyToClipboard()" class="btn-clay-secondary py-2 px-4 text-xs">
                                <i class="bi bi-clipboard me-2"></i> Salin Rekening
                            </button>
                        </div>

                        <div class="clay-card !p-6 space-y-4">
                            <h3 class="font-bold text-lg mb-4 border-b border-slate-100 pb-2">Konfirmasi Transfer</h3>

                            <div>
                                <label class="text-xs font-bold text-slate-500 ml-2 uppercase">Bank Pengirim</label>
                                <select name="bank_sender"
                                    class="w-full mt-1 p-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-[var(--bbc-hex)] text-slate-700">
                                    <option value="" disabled selected>Pilih Bank Kamu</option>
                                    @foreach (['BRI', 'BCA', 'Mandiri', 'BNI', 'BSI', 'Dana/OVO/Gopay'] as $bank)
                                        <option value="{{ $bank }}"
                                            {{ $transaction->bank_sender == $bank ? 'selected' : '' }}>{{ $bank }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 ml-2 uppercase">Nama Pemilik Rek.</label>
                                    <input type="text" name="account_name"
                                        value="{{ $transaction->account_name ?? $student->name }}"
                                        class="w-full mt-1 p-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-[var(--bbc-hex)]"
                                        placeholder="a.n Siapa?">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 ml-2 uppercase">No. Rekening Asal</label>
                                    <input type="number" name="account_number" value="{{ $transaction->account_number }}"
                                        class="w-full mt-1 p-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-[var(--bbc-hex)]"
                                        placeholder="No. Rekeningmu">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-500 ml-2 uppercase">Tanggal Transfer</label>
                                <input type="date" name="payment_date"
                                    value="{{ $transaction->payment_date ? date('Y-m-d', strtotime($transaction->payment_date)) : date('Y-m-d') }}"
                                    class="w-full mt-1 p-3 rounded-xl bg-slate-100 dark:bg-slate-900 border-none focus:ring-2 focus:ring-[var(--bbc-hex)]">
                            </div>

                            <div class="mt-4">
                                <label class="text-xs font-bold text-slate-500 ml-2 mb-2 block uppercase">Bukti
                                    Transfer</label>

                                <div class="clay-card !p-4 flex flex-col sm:flex-row items-center gap-4 transition-all duration-300 border border-slate-200 dark:border-slate-700 hover:border-blue-300"
                                    id="proof-card">

                                    <div id="proof-icon"
                                        class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shrink-0 transition-colors duration-300
            {{ $transaction->proof_path && $transaction->status != 'payment_rejected' ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 dark:bg-slate-700 text-slate-400' }}">
                                        @if ($transaction->proof_path && $transaction->status != 'payment_rejected')
                                            <i class="bi bi-file-earmark-image"></i>
                                        @else
                                            <i class="bi bi-cloud-arrow-up-fill"></i>
                                        @endif
                                    </div>

                                    <div class="flex-1 text-center sm:text-left w-full overflow-hidden">
                                        <p class="font-bold text-slate-800 dark:text-white text-sm truncate"
                                            id="proof-filename">
                                            {{ $transaction->proof_path && $transaction->status != 'payment_rejected' ? 'Bukti Tersimpan' : 'Upload Bukti Baru' }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5" id="proof-subtitle">
                                            {{ $transaction->proof_path && $transaction->status != 'payment_rejected' ? 'File aman. Klik kirim jika tidak ada perubahan.' : 'Bukti sebelumnya ditolak. Wajib upload ulang.' }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2 justify-center w-full sm:w-auto">

                                        @if ($transaction->proof_path && $transaction->status != 'payment_rejected')
                                            <a href="{{ Storage::url($transaction->proof_path) }}" target="_blank"
                                                id="btn-view-proof"
                                                class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 shadow-sm flex items-center justify-center hover:text-blue-500 transition">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif

                                        <input type="file" name="proof_file" id="proof_file_input" class="hidden"
                                            accept="image/*,.pdf"
                                            {{ $transaction->status == 'payment_rejected' || !$transaction->proof_path ? 'required' : '' }}>

                                        <label for="proof_file_input"
                                            class="btn-clay-secondary !py-2 !px-5 cursor-pointer text-sm hover:!bg-[var(--bbc-hex)] hover:!text-white transition-colors">
                                            {{ $transaction->status == 'payment_rejected' ? 'Pilih File' : ($transaction->proof_path ? 'Ganti' : 'Pilih File') }}
                                        </label>
                                    </div>
                                </div>

                                @if ($transaction->status == 'payment_rejected')
                                    <p class="text-[10px] text-red-500 mt-2 ml-2 font-bold animate-pulse">
                                        * Mohon upload bukti transfer yang baru/jelas.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="section-va" class="hidden animate-fade-in-up">
                        @if ($transaction->va_number)
                            <div class="clay-card !p-8 text-center">
                                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-4">Nomor Virtual
                                    Account</p>
                                <h2 class="text-4xl font-[900] text-blue-600 mb-2 tracking-wider font-mono">
                                    {{ $transaction->va_number }}</h2>
                                <div class="p-3 bg-red-50 text-red-500 rounded-xl inline-block text-xs font-bold">
                                    <i class="bi bi-clock-history mr-1"></i> Expired:
                                    {{ date('d M Y, H:i', strtotime($transaction->payment_expiry_time)) }}
                                </div>
                            </div>
                        @else
                            <div class="clay-card !p-8 text-center">
                                <div
                                    class="w-20 h-20 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-4 text-3xl">
                                    <i class="bi bi-robot"></i>
                                </div>
                                <h3 class="text-lg font-bold">Siap Membuat VA?</h3>
                                <p class="text-slate-500 text-sm">Klik tombol konfirmasi di bawah untuk generate kode
                                    pembayaran.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        <button type="submit" id="btn-submit"
                            class="btn-clay w-full text-lg shadow-xl py-4 rounded-2xl">
                            {{ $transaction->status == 'payment_rejected' ? 'Kirim Perbaikan' : 'Konfirmasi Pembayaran' }}
                            <i class="bi bi-send-fill ml-2"></i>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const hasVA = "{{ $transaction->va_number ? 'true' : 'false' }}";
                const isRejected = "{{ $transaction->status == 'payment_rejected' ? 'true' : 'false' }}";
                const hasProof = "{{ $transaction->proof_path ? 'true' : 'false' }}";

                $('input[name="payment_method"]').on('change', function() {
                    $('.payment-indicator').removeClass('active');
                    $(this).closest('label').find('.payment-indicator').addClass('active');

                    let method = $(this).val();
                    let btn = $('#btn-submit');

                    $('#section-manual, #section-va').addClass('hidden');

                    if (method === 'manual') {
                        $('#section-manual').removeClass('hidden');
                        let btnText = isRejected === 'true' ? 'Kirim Perbaikan' : 'Kirim Bukti Transfer';
                        btn.html(`${btnText} <i class="bi bi-send-fill ml-2"></i>`).parent().removeClass(
                            'hidden');

                        if (hasProof === 'false') {
                            $('#section-manual input, #section-manual select').prop('required', true);
                        }
                    } else if (method === 'bni') {
                        $('#section-va').removeClass('hidden');
                        $('#section-manual input, #section-manual select').prop('required', false);

                        if (hasVA === 'true') {
                            btn.parent().addClass('hidden');
                        } else {
                            btn.html('Buat Virtual Account <i class="bi bi-lightning-fill ml-2"></i>').parent()
                                .removeClass('hidden');
                        }
                    }
                });

                $('#proof_file_input').on('change', function(e) {
                    let file = e.target.files[0];
                    if (file) {
                        $('#proof-icon').removeClass('bg-slate-200 dark:bg-slate-700 text-slate-400')
                            .addClass('bg-blue-100 text-blue-600')
                            .html('<i class="bi bi-check-lg"></i>');

                        $('#proof-card').addClass('!border-blue-400 bg-blue-50 dark:bg-blue-900/10');
                        $('#proof-filename').text(file.name);
                        $('#proof-subtitle').text('Siap dikirim. Klik konfirmasi di bawah.');
                        $('label[for="proof_file_input"]').text('Ganti File');

                        let objectUrl = URL.createObjectURL(file);
                        let viewBtn = $('#btn-view-proof');
                        if (viewBtn.length > 0) {
                            viewBtn.attr('href', objectUrl);
                        } else {
                            let newBtn =
                                `<a href="${objectUrl}" target="_blank" id="btn-view-proof" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 shadow-sm flex items-center justify-center hover:text-blue-500 transition"><i class="bi bi-eye"></i></a>`;
                            $('#proof_file_input').before(newBtn);
                        }
                    }
                });

                $('input[name="payment_method"]:checked').trigger('change');
            });

            function copyToClipboard() {
                let text = document.getElementById("copy-text").innerText;
                navigator.clipboard.writeText(text).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Nomor rekening tersalin!',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });

                });
            }
        </script>
    @endpush

@endsection
