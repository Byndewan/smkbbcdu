@extends('layouts.siswa')

@section('title', 'Pilih Metode Pembayaran')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Custom Styles for this page */
        .radio-card {
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
        }
        /* Saat Radio Checked (Dikontrol via Peer di Tailwind) */
        input:checked + .radio-card-border {
            border-color: #000;
            border-width: 2px;
            background-color: #fafafa;
        }
        input:checked ~ .radio-content .indicator {
            background-color: #000;
            border-color: #000;
            color: #fff;
        }

        /* Input Field Style */
        .input-brutal {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        .input-brutal:focus {
            background-color: #fff;
            border-color: #000;
            box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.1);
            outline: none;
        }

        /* Button Style */
        .btn-brutal {
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .btn-brutal:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="max-w-5xl mx-auto font-sans">

        <div class="flex items-center gap-5 mb-8 px-1">
            <a href="{{ route('student.bills.show', $bill->id) }}"
                class="w-12 h-12 rounded-xl bg-white border border-black/10 flex items-center justify-center text-slate-500 hover:bg-black hover:text-white hover:border-black transition-all shadow-sm">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest font-mono">Checkout</span>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white leading-none mt-1">Metode Pembayaran</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white border border-black/10 rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full blur-2xl -z-0 translate-x-10 -translate-y-10"></div>

                    <div class="relative z-10">
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Total Tagihan</p>
                        <h2 class="text-4xl font-display font-bold text-slate-900">Rp {{ number_format($bill->amount, 0, ',', '.') }}</h2>

                        <div class="mt-6 pt-6 border-t border-dashed border-slate-200 flex items-center gap-3 text-sm font-medium text-slate-500">
                            <i class="bi bi-tag-fill text-slate-300 text-lg"></i>
                            <span class="font-bold text-slate-700">{{ $bill->title }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-black/10 rounded-3xl p-6">
                    <h3 class="font-display font-bold text-slate-900 mb-4 text-lg">Petunjuk</h3>
                    <ul class="text-sm text-slate-500 space-y-3 pl-4 list-disc marker:text-slate-300">
                        <li>Pilih salah satu metode di sebelah kanan.</li>
                        <li><strong>Transfer Manual:</strong> Wajib upload bukti transfer (foto/screenshot).</li>
                        <li><strong>Virtual Account:</strong> Cek otomatis tanpa perlu upload bukti.</li>
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-2">

                @php
                    $actionRoute = $transaction->status == 'payment_rejected'
                            ? route('student.bills.resubmit', $transaction->id)
                            : route('student.bills.payment.process', $bill->id);
                @endphp

                <form action="{{ $actionRoute }}" method="POST" enctype="multipart/form-data" id="payment-form">
                    @csrf

                    @if ($transaction->status != 'payment_rejected')
                        <input type="hidden" name="du_transaction_id" value="{{ $transaction->id }}">
                    @endif

                    <div class="grid grid-cols-1 gap-4 mb-8">

                        <label class="relative cursor-pointer group">
                            <input type="radio" name="payment_method" value="manual" class="peer sr-only"
                                {{ $transaction->payment_method == 'manual' || !$transaction->payment_method ? 'checked' : '' }}>

                            <div class="radio-card-border absolute inset-0 rounded-2xl border border-slate-200 bg-white transition-all peer-checked:border-black peer-checked:border-2 peer-checked:bg-gray-50"></div>

                            <div class="relative z-10 p-5 flex items-center gap-4 radio-content">
                                <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 border border-orange-100 flex items-center justify-center text-xl">
                                    <i class="bi bi-bank2"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-900 text-lg">Transfer Bank Lain</h4>
                                    <p class="text-xs text-slate-500">Manual ke rekening sekolah & upload struk.</p>
                                </div>
                                <div class="indicator w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center text-white transition-all">
                                    <i class="bi bi-check text-sm"></i>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group">
                            <input type="radio" name="payment_method" value="bni" class="peer sr-only"
                                {{ $transaction->payment_method == 'bni' ? 'checked' : '' }}>

                            <div class="radio-card-border absolute inset-0 rounded-2xl border border-slate-200 bg-white transition-all peer-checked:border-black peer-checked:border-2 peer-checked:bg-gray-50"></div>

                            <div class="relative z-10 p-5 flex items-center gap-4 radio-content">
                                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center text-xl">
                                    <i class="bi bi-credit-card-2-front"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-900 text-lg">BNI Virtual Account</h4>
                                    <p class="text-xs text-slate-500">Verifikasi otomatis & instan.</p>
                                </div>
                                <div class="indicator w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center text-white transition-all">
                                    <i class="bi bi-check text-sm"></i>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div id="section-manual" class="hidden space-y-6">

                        <div class="bg-gray-50 border border-dashed border-slate-300 rounded-2xl p-6 text-center">
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">Rekening Tujuan</p>
                            <h2 class="text-3xl font-bold text-slate-900 mb-1 font-mono tracking-wide" id="copy-text">
                                {{ $bankAccount['account_number'] }}
                            </h2>
                            <p class="text-sm text-slate-600 font-medium mb-4">
                                {{ $bankAccount['account_name'] }} • <span class="font-bold">{{ $bankAccount['bank_name'] }}</span>
                            </p>

                            <button type="button" onclick="copyToClipboard()" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold hover:bg-black hover:text-white transition-colors shadow-sm">
                                <i class="bi bi-clipboard me-2"></i> Salin Nomor
                            </button>
                        </div>

                        <div class="bg-white border border-black/10 rounded-3xl p-6 md:p-8 space-y-5 shadow-sm">
                            <h3 class="font-display font-bold text-lg border-b border-gray-100 pb-3 mb-2">Konfirmasi Transfer</h3>

                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase ml-1 mb-1 block">Bank Pengirim</label>
                                <div class="relative">
                                    <select name="bank_sender" class="input-brutal w-full py-2 px-3 rounded-xl appearance-none text-sm font-bold text-slate-700">
                                        <option value="" disabled selected>Pilih Bank Kamu</option>
                                        @foreach (['BRI', 'BCA', 'Mandiri', 'BNI', 'BSI', 'Dana/OVO/Gopay'] as $bank)
                                            <option value="{{ $bank }}" {{ $transaction->bank_sender == $bank ? 'selected' : '' }}>
                                                {{ $bank }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-4 top-4 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase ml-1 mb-1 block">Nama Pemilik Rek.</label>
                                    <input type="text" name="account_name" value="{{ $transaction->account_name ?? $student->name }}"
                                        class="input-brutal w-full py-2 px-3 rounded-xl text-sm font-bold text-slate-700 placeholder-slate-300"
                                        placeholder="a.n Siapa?">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase ml-1 mb-1 block">No. Rekening Asal</label>
                                    <input type="number" name="account_number" value="{{ $transaction->account_number }}"
                                        class="input-brutal w-full py-2 px-3 rounded-xl text-sm font-bold text-slate-700 placeholder-slate-300"
                                        placeholder="Nomor Rekeningmu">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase ml-1 mb-1 block">Tanggal Transfer</label>
                                <input type="date" name="payment_date"
                                    value="{{ $transaction->payment_date ? date('Y-m-d', strtotime($transaction->payment_date)) : date('Y-m-d') }}"
                                    class="input-brutal w-full py-2 px-3 rounded-xl text-sm font-bold text-slate-700">
                            </div>

                            <div class="pt-2">
                                <label class="text-xs font-bold text-slate-500 uppercase ml-1 mb-2 block">Bukti Transfer</label>

                                <div class="border border-black/10 rounded-2xl p-4 flex flex-col sm:flex-row items-center gap-4 transition-all hover:border-black/30 bg-white" id="proof-card">

                                    <div id="proof-icon" class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 transition-colors duration-300
                                        {{ $transaction->proof_path && $transaction->status != 'payment_rejected' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-gray-100 text-gray-400' }}">
                                        @if ($transaction->proof_path && $transaction->status != 'payment_rejected')
                                            <i class="bi bi-file-earmark-check"></i>
                                        @else
                                            <i class="bi bi-cloud-upload"></i>
                                        @endif
                                    </div>

                                    <div class="flex-1 text-center sm:text-left w-full overflow-hidden px-2">
                                        <p class="font-bold text-slate-800 text-sm truncate" id="proof-filename">
                                            {{ $transaction->proof_path && $transaction->status != 'payment_rejected' ? 'Bukti Tersimpan' : 'Upload Foto / Screenshot' }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-0.5" id="proof-subtitle">
                                            {{ $transaction->proof_path && $transaction->status != 'payment_rejected' ? 'Klik kirim jika tidak ada perubahan.' : 'Format JPG/PDF max 2MB' }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if ($transaction->proof_path && $transaction->status != 'payment_rejected')
                                            <a href="{{ Storage::url($transaction->proof_path) }}" target="_blank"
                                                class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-black hover:text-white transition-colors"
                                                title="Lihat File">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif

                                        <input type="file" name="proof_file" id="proof_file_input" class="hidden" accept="image/*,.pdf"
                                            {{ $transaction->status == 'payment_rejected' || !$transaction->proof_path ? 'required' : '' }}>

                                        <label for="proof_file_input"
                                            class="px-4 py-2 bg-black text-white rounded-lg text-xs font-bold cursor-pointer hover:bg-gray-800 transition shadow-md">
                                            {{ $transaction->status == 'payment_rejected' ? 'Pilih File' : ($transaction->proof_path ? 'Ganti' : 'Pilih File') }}
                                        </label>
                                    </div>
                                </div>

                                @if ($transaction->status == 'payment_rejected')
                                    <p class="text-xs text-red-500 mt-2 font-bold flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> Mohon upload bukti yang baru & jelas.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="section-va" class="hidden space-y-6">
                        @if ($transaction->va_number)
                            <div class="bg-white border border-black/10 rounded-3xl p-8 text-center shadow-sm">
                                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-4">Nomor Virtual Account</p>
                                <h2 class="text-4xl font-display font-bold text-blue-600 mb-4 tracking-wider font-mono select-all">
                                    {{ $transaction->va_number }}
                                </h2>
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100">
                                    <i class="bi bi-clock"></i> Expired: {{ date('d M Y, H:i', strtotime($transaction->payment_expiry_time)) }}
                                </div>
                            </div>
                        @else
                            <div class="bg-white border border-black/10 rounded-3xl p-10 text-center shadow-sm">
                                <div class="w-20 h-20 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-6 text-3xl border border-blue-100">
                                    <i class="bi bi-magic"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Siap Membuat VA?</h3>
                                <p class="text-slate-500 text-sm max-w-xs mx-auto">Klik tombol konfirmasi di bawah untuk mendapatkan kode pembayaran otomatis.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        <button type="submit" id="btn-submit"
                            class="btn-brutal w-full py-4 bg-black text-white rounded-xl text-lg font-bold flex items-center justify-center gap-3">
                            {{ $transaction->status == 'payment_rejected' ? 'Kirim Perbaikan' : 'Konfirmasi Pembayaran' }}
                            <i class="bi bi-arrow-right"></i>
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

                // Logic Change Method
                $('input[name="payment_method"]').on('change', function() {
                    let method = $(this).val();
                    let btn = $('#btn-submit');

                    // Reset View
                    $('#section-manual, #section-va').addClass('hidden');

                    if (method === 'manual') {
                        // MANUAL LOGIC
                        $('#section-manual').removeClass('hidden');
                        let btnText = isRejected === 'true' ? 'Kirim Perbaikan' : 'Kirim Bukti Transfer';
                        btn.html(`${btnText} <i class="bi bi-send-fill ml-2"></i>`).parent().removeClass('hidden');

                        // Set required if proof missing
                        if (hasProof === 'false') {
                            $('#section-manual input, #section-manual select').prop('required', true);
                        }
                    } else if (method === 'bni') {
                        // VA LOGIC
                        $('#section-va').removeClass('hidden');
                        $('#section-manual input, #section-manual select').prop('required', false);

                        if (hasVA === 'true') {
                            // Kalau sudah punya VA, tombol submit hilang (cuma display)
                            btn.parent().addClass('hidden');
                        } else {
                            btn.html('Buat Virtual Account <i class="bi bi-lightning-fill ml-2"></i>').parent().removeClass('hidden');
                        }
                    }
                });

                // Logic File Upload Preview
                $('#proof_file_input').on('change', function(e) {
                    let file = e.target.files[0];
                    if (file) {
                        // Update Icon & Text
                        $('#proof-icon').removeClass('bg-gray-100 text-gray-400')
                            .addClass('bg-blue-50 text-blue-600 border-blue-100')
                            .html('<i class="bi bi-check-lg"></i>');

                        $('#proof-card').addClass('border-blue-300 bg-blue-50/30');
                        $('#proof-filename').text(file.name);
                        $('#proof-subtitle').text('Siap dikirim.');
                        $('label[for="proof_file_input"]').text('Ganti File');

                        // Optional: Create local preview link if supported
                        // (Simplified for visual feedback only)
                    }
                });

                // Trigger change on load
                $('input[name="payment_method"]:checked').trigger('change');
            });

            // Clipboard Copy
            function copyToClipboard() {
                let text = document.getElementById("copy-text").innerText.trim();
                navigator.clipboard.writeText(text).then(() => {
                    const isDark = document.documentElement.classList.contains('dark');
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Nomor tersalin!',
                        showConfirmButton: false,
                        timer: 2000,
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#ffffff' : '#0f172a'
                    });
                });
            }
        </script>
    @endpush

@endsection
