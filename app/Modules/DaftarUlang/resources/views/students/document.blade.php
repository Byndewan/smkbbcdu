@extends('layouts.siswa')

@section('title', $bill->title)

@section('content')

<div class="max-w-5xl mx-auto font-sans">

    <div class="flex items-center gap-5 mb-8 px-1">
        <a href="{{ route('student.dashboard') }}" class="w-12 h-12 rounded-xl bg-white border border-black/10 flex items-center justify-center text-slate-500 hover:bg-black hover:text-white hover:border-black transition-all shadow-sm">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest font-mono">Detail Tagihan</span>
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white leading-none mt-1">{{ $bill->title }}</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1 space-y-6">

            <div class="bg-white border border-black/10 rounded-3xl p-8 relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-gray-50 rounded-full -z-0"></div>

                <div class="relative z-10">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Total Tagihan</p>
                    <h2 class="text-4xl font-display font-bold text-slate-900">Rp {{ number_format($bill->amount, 0, ',', '.') }}</h2>

                    <div class="mt-6 pt-6 border-t border-dashed border-slate-200 flex items-center gap-3 text-sm font-medium text-slate-500">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center border border-gray-200">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase text-slate-400 font-bold">Jatuh Tempo</span>
                            <span class="font-mono text-slate-700">{{ \Carbon\Carbon::parse($bill->end_date)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($transaction)
                <div class="bg-white border border-black/10 rounded-3xl p-6">
                    <h3 class="font-display font-bold text-slate-900 mb-4 text-lg">Status Pembayaran</h3>

                    @if(in_array($transaction->status, ['doc_rejected', 'payment_rejected']))
                         <div class="p-5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
                            <div class="font-bold flex items-center gap-2 mb-2 text-lg">
                                <i class="bi bi-exclamation-triangle-fill"></i> Perlu Revisi
                            </div>
                            <p class="opacity-90 leading-relaxed border-t border-red-200 pt-2 mt-1">
                                "{{ $transaction->admin_note }}"
                            </p>
                        </div>
                    @elseif(in_array($transaction->status, ['pending_docs', 'payment_review']))
                        <div class="p-5 rounded-2xl bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm font-bold flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-400 text-white flex items-center justify-center shrink-0 animate-pulse">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <span>Sedang Diverifikasi Admin</span>
                        </div>
                    @elseif($transaction->status == 'paid')
                        <div class="p-5 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-sm font-bold flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <span>Pembayaran Lunas</span>
                        </div>
                    @else
                         <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 text-slate-500 text-sm font-bold flex items-center gap-3">
                            <i class="bi bi-pencil-square text-xl"></i> Draft (Belum Bayar)
                        </div>
                    @endif
                </div>
            @endif

            <div class="hidden lg:block">
                 @if (!$transaction || $transaction->status == 'draft')
                    <a href="{{ route('student.bills.payment', $bill->id) }}" id="btn-next-desktop"
                       class="btn-brutal w-full py-4 rounded-xl flex items-center justify-center gap-2 bg-black text-white font-bold opacity-50 cursor-not-allowed pointer-events-none transition-all">
                        Lanjut Pembayaran <i class="bi bi-arrow-right"></i>
                    </a>
                @elseif($transaction->status == 'doc_rejected')
                    <form action="{{ route('student.bills.resubmit', $transaction->id) }}" method="POST">
                        @csrf
                        <button type="submit" id="btn-resubmit-desktop"
                                class="btn-brutal w-full py-4 rounded-xl bg-red-600 text-white font-bold opacity-50 cursor-not-allowed pointer-events-none hover:bg-red-700">
                            Kirim Perbaikan Dokumen
                        </button>
                    </form>
                @elseif($transaction->status == 'payment_rejected')
                    <a href="{{ route('student.bills.payment', $bill->id) }}" class="btn-brutal w-full py-4 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 text-center block">
                        Perbaiki Bukti Bayar &rarr;
                    </a>
                @elseif(in_array($transaction->status, ['pending_docs', 'payment_review']))
                    <button disabled class="w-full py-4 rounded-xl bg-gray-100 text-gray-400 font-bold border border-gray-200 cursor-not-allowed">
                        Menunggu Verifikasi...
                    </button>
                @endif
            </div>

        </div>

        <div class="lg:col-span-2">
            <div class="bg-white border border-black/10 rounded-3xl p-8 min-h-full">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-display font-bold text-xl text-slate-900">Dokumen Persyaratan</h3>
                    <span class="text-xs font-mono font-bold bg-black text-white px-3 py-1 rounded-lg">
                        {{ count($requirements) }} ITEM
                    </span>
                </div>

                <div class="space-y-4" id="document-list">
                    @foreach ($requirements as $req)
                        <div class="p-5 border border-black/10 rounded-2xl flex flex-col sm:flex-row items-center gap-5 transition-all duration-300 bg-white
                            {{ $req->file_status == 'invalid' ? '!border-red-500 !bg-red-50' : 'hover:border-black/30' }}">

                            <div class="w-14 h-14 rounded-xl border-2 flex items-center justify-center text-xl shrink-0 status-icon transition-all duration-300
                                {{ $req->file_status == 'valid' ? 'border-green-500 text-green-600 bg-green-50' :
                                   ($req->file_status == 'invalid' ? 'border-red-500 text-red-500 bg-white' :
                                   ($req->file_path ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-dashed border-gray-300 text-gray-300')) }}"
                                 data-req-id="{{ $req->req_id }}">

                                @if($req->file_status == 'valid') <i class="bi bi-check-lg"></i>
                                @elseif($req->file_status == 'invalid') <i class="bi bi-x-lg"></i>
                                @elseif($req->file_path) <i class="bi bi-file-earmark-check-fill"></i>
                                @else <i class="bi bi-upload"></i>
                                @endif
                            </div>

                            <div class="flex-1 text-center sm:text-left w-full">
                                <h4 class="font-bold text-slate-900 text-base">{{ $req->document_name }}</h4>
                                <p class="text-xs mt-1 status-text font-medium
                                    {{ $req->file_status == 'invalid' ? 'text-red-600' : 'text-slate-500' }}">
                                    @if($req->file_status == 'invalid')
                                        <i class="bi bi-info-circle mr-1"></i> Ditolak: {{ $req->reject_reason }}
                                    @elseif($req->file_status == 'valid')
                                        Terverifikasi
                                    @elseif($req->file_path)
                                        File berhasil diupload
                                    @else
                                        {{ $req->is_mandatory ? 'Wajib diupload (JPG/PDF)' : 'Opsional' }}
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center gap-3 w-full sm:w-auto justify-center">
                                <a href="{{ $req->file_path ? Storage::url($req->file_path) : '#' }}" target="_blank"
                                   data-req-id="{{ $req->req_id }}"
                                   class="btn-view w-10 h-10 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-black hover:text-white hover:border-black transition-all {{ $req->file_path ? '' : 'hidden' }}"
                                   title="Lihat File">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if ($req->file_status != 'valid')
                                    <input type="file" id="file-{{ $req->req_id }}" class="hidden file-input"
                                           data-req-id="{{ $req->req_id }}" data-url="{{ route('student.bills.upload') }}"
                                           accept="image/*,.pdf">
                                    <label for="file-{{ $req->req_id }}"
                                           class="btn-icon-brutal px-6 py-2.5 bg-white text-black border border-black/10 rounded-lg text-sm font-bold cursor-pointer hover:bg-black hover:text-white transition-all min-w-[100px] text-center">
                                        {{ $req->file_path ? 'Ganti' : 'Upload' }}
                                    </label>
                                @else
                                    <div class="px-4 py-2 rounded-lg bg-green-100 text-green-700 border border-green-200 text-xs font-bold uppercase tracking-wide">
                                        Approved
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-md border-t border-black/10 lg:hidden z-40">
        @if (!$transaction || $transaction->status == 'draft')
            <a href="{{ route('student.bills.payment', $bill->id) }}" id="btn-next-mobile"
               class="btn-brutal w-full py-3.5 rounded-xl bg-black text-white font-bold text-center block opacity-50 cursor-not-allowed pointer-events-none shadow-lg">
                Lanjut Pembayaran
            </a>
        @elseif($transaction->status == 'doc_rejected')
            <form action="{{ route('student.bills.resubmit', $transaction->id) }}" method="POST">
                @csrf
                <button type="submit" id="btn-resubmit-mobile"
                        class="btn-brutal w-full py-3.5 rounded-xl bg-red-600 text-white font-bold shadow-lg opacity-50 cursor-not-allowed pointer-events-none">
                    Kirim Perbaikan Dokumen
                </button>
            </form>
        @endif
    </div>

</div>

@push('styles')
<style>
    /* Custom Brutalist Button Class */
    .btn-brutal {
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .btn-brutal:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .animate-bounce-slight {
        animation: bounce-slight 1s infinite;
    }
    @keyframes bounce-slight {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
</style>
@endpush

<script>
    $(document).ready(function() {
        // --- LOGIC VALIDASI UPLOAD (TIDAK BERUBAH) ---
        function checkAllUploaded() {
            let totalMandatory = {{ $requirements->where('is_mandatory', 1)->count() }};
            // Kita hitung jumlah elemen yang memiliki class penanda sukses
            let totalUploaded = $('.status-icon').filter(function() {
                // Cek class style baru (border-blue-500 atau border-green-500)
                return $(this).hasClass('border-blue-500') || $(this).hasClass('border-green-500');
            }).length;

            let btns = $('#btn-next-desktop, #btn-next-mobile, #btn-resubmit-desktop, #btn-resubmit-mobile');

            if (totalUploaded >= totalMandatory) {
                btns.removeClass('opacity-50 cursor-not-allowed pointer-events-none')
                    .addClass('animate-bounce-slight');
            } else {
                btns.addClass('opacity-50 cursor-not-allowed pointer-events-none')
                    .removeClass('animate-bounce-slight');
            }
        }

        checkAllUploaded();

        // --- AJAX UPLOAD ---
        $('.file-input').on('change', function() {
            let file = this.files[0];
            let reqId = $(this).data('req-id');
            let url = $(this).data('url');
            let billId = "{{ $bill->id }}";
            let labelBtn = $(`label[for="file-${reqId}"]`);
            let originalText = labelBtn.text();

            if (file) {
                let formData = new FormData();
                formData.append('file', file);
                formData.append('req_id', reqId);
                formData.append('bill_id', billId);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                // Loading State
                labelBtn.html('<i class="bi bi-arrow-clockwise animate-spin"></i>').addClass('opacity-50 pointer-events-none');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Update Icon Box Style (Brutalist Blue)
                        let iconBox = $(`.status-icon[data-req-id="${reqId}"]`);
                        iconBox.attr('class', 'w-14 h-14 rounded-xl border-2 flex items-center justify-center text-xl shrink-0 status-icon transition-all duration-300 border-blue-500 text-blue-600 bg-blue-50');
                        iconBox.html('<i class="bi bi-file-earmark-check-fill"></i>');

                        // Update Text
                        let card = iconBox.closest('.border'); // Cari parent container
                        card.find('.status-text').text('File berhasil diupload').removeClass('text-red-600').addClass('text-slate-500');
                        card.removeClass('!border-red-500 !bg-red-50').addClass('hover:border-black/30');

                        // Update View Button
                        let freshUrl = response.file_url + '?t=' + new Date().getTime();
                        $(`.btn-view[data-req-id="${reqId}"]`).attr('href', freshUrl).removeClass('hidden');

                        // Reset Label
                        labelBtn.text('Ganti').removeClass('opacity-50 pointer-events-none');

                        // Re-check validation
                        checkAllUploaded();

                        // SweetAlert match Dark Mode
                        const isDark = document.documentElement.classList.contains('dark');
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Upload Berhasil',
                            showConfirmButton: false,
                            timer: 3000,
                            background: isDark ? '#1e293b' : '#ffffff',
                            color: isDark ? '#ffffff' : '#0f172a'
                        });
                    },
                    error: function(xhr) {
                        labelBtn.text(originalText).removeClass('opacity-50 pointer-events-none');
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan upload.',
                            confirmButtonColor: '#000'
                        });
                    }
                });
            }
        });
    });
</script>

@endsection
