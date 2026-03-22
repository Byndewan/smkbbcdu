@extends('layouts.siswa')

@section('title', $bill->title)

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('student.dashboard') }}" class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-500 hover:text-[var(--bbc-hex)] transition btn-squishy">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Detail Tagihan</span>
            <h1 class="text-2xl font-[800] text-slate-800 dark:text-white leading-none mt-1">{{ $bill->title }}</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1 space-y-6">

            <div class="clay-card !p-6 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-[var(--bbc-hex)] opacity-10 rounded-full blur-2xl group-hover:opacity-20 transition-opacity"></div>

                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1">Total Tagihan</p>
                <h2 class="text-3xl font-[900] text-slate-800 dark:text-white">Rp {{ number_format($bill->amount, 0, ',', '.') }}</h2>

                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700/50 flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                    <i class="fa-regular fa-calendar"></i>
                    Jatuh Tempo: {{ \Carbon\Carbon::parse($bill->end_date)->format('d M Y') }}
                </div>
            </div>

            @if($transaction)
                <div class="clay-card !p-6">
                    <h3 class="font-bold text-slate-700 dark:text-slate-200 mb-4">Status Pembayaran</h3>

                    @if(in_array($transaction->status, ['doc_rejected', 'payment_rejected']))
                         <div class="p-4 rounded-2xl bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-sm">
                            <div class="font-bold flex items-center gap-2 mb-1">
                                <i class="bi bi-exclamation-circle"></i> Perlu Revisi
                            </div>
                            <p class="opacity-80">"{{ $transaction->admin_note }}"</p>
                        </div>
                    @elseif(in_array($transaction->status, ['pending_docs', 'payment_review']))
                        <div class="p-4 rounded-2xl bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 text-sm font-bold flex items-center gap-2">
                            <i class="bi bi-hourglass-split"></i> Sedang Diverifikasi Admin
                        </div>
                    @elseif($transaction->status == 'paid')
                        <div class="p-4 rounded-2xl bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 text-sm font-bold flex items-center gap-2">
                            <i class="bi bi-check-circle"></i> Pembayaran Lunas
                        </div>
                    @else
                         <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-700/50 text-slate-500 text-sm font-bold flex items-center gap-2">
                            <i class="bi bi-file-earmark-richtext-fill"></i> Draft (Belum Bayar)
                        </div>
                    @endif
                </div>
            @endif

            <div class="hidden lg:block">
                 @if (!$transaction || $transaction->status == 'draft')
                    <a href="{{ route('student.bills.payment', $bill->id) }}" id="btn-next-desktop"
                       class="btn-clay w-full opacity-50 cursor-not-allowed pointer-events-none transition-all">
                        Lanjut Pembayaran &rarr;
                    </a>
                @elseif($transaction->status == 'doc_rejected')
                    <form action="{{ route('student.bills.resubmit', $transaction->id) }}" method="POST">
                        @csrf
                        <button type="submit" id="btn-resubmit-desktop"
                                class="btn-clay w-full opacity-50 cursor-not-allowed pointer-events-none bg-red-500 shadow-red-200 hover:shadow-red-300">
                            Kirim Perbaikan Dokumen
                        </button>
                    </form>
                @elseif($transaction->status == 'payment_rejected')
                    <a href="{{ route('student.bills.payment', $bill->id) }}" class="btn-clay w-full bg-red-500">
                        Perbaiki Bukti Bayar &rarr;
                    </a>
                @elseif(in_array($transaction->status, ['pending_docs', 'payment_review']))
                    <button disabled class="w-full py-4 rounded-2xl bg-slate-200 text-slate-400 font-bold cursor-not-allowed">
                        Menunggu Verifikasi...
                    </button>
                @endif
            </div>

        </div>

        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-slate-700 dark:text-slate-200">Persyaratan Dokumen</h3>
                <span class="text-xs bg-white dark:bg-slate-800 px-3 py-1 rounded-full shadow-sm text-slate-500">
                    {{ count($requirements) }} Dokumen
                </span>
            </div>

            <div class="space-y-4" id="document-list">
                @foreach ($requirements as $req)
                    <div class="clay-card !p-5 flex flex-col sm:flex-row items-center gap-5 transition-all duration-300
                        {{ $req->file_status == 'invalid' ? '!border-red-400 !bg-red-50 dark:!bg-red-900/10' : '' }}">

                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl shrink-0 status-icon transition-colors duration-300
                            {{ $req->file_status == 'valid' ? 'bg-green-100 text-green-600' :
                               ($req->file_status == 'invalid' ? 'bg-red-100 text-red-500' :
                               ($req->file_path ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 dark:bg-slate-700 text-slate-400')) }}"
                             data-req-id="{{ $req->req_id }}">

                            @if($req->file_status == 'valid') <i class="bi bi-check"></i>
                            @elseif($req->file_status == 'invalid') <i class="bi bi-x"></i>
                            @elseif($req->file_path) <i class="bi bi-cloud-arrow-up"></i>
                            @else <i class="bi bi-file-earmark-richtext-fill"></i>
                            @endif
                        </div>

                        <div class="flex-1 text-center sm:text-left w-full">
                            <h4 class="font-bold text-slate-800 dark:text-white">{{ $req->document_name }}</h4>
                            <p class="text-xs mt-1 status-text
                                {{ $req->file_status == 'invalid' ? 'text-red-500 font-bold' : 'text-slate-500' }}">
                                @if($req->file_status == 'invalid')
                                    Ditolak: {{ $req->reject_reason }}
                                @elseif($req->file_status == 'valid')
                                    Terverifikasi
                                @elseif($req->file_path)
                                    File terupload
                                @else
                                    {{ $req->is_mandatory ? 'Wajib diupload (JPG/PDF)' : 'Opsional' }}
                                @endif
                            </p>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto justify-center">
                            <a href="{{ $req->file_path ? Storage::url($req->file_path) : '#' }}" target="_blank"
                               data-req-id="{{ $req->req_id }}"
                               class="btn-view w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 shadow-sm flex items-center justify-center hover:text-blue-500 transition {{ $req->file_path ? '' : 'hidden' }}">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if ($req->file_status != 'valid')
                                <input type="file" id="file-{{ $req->req_id }}" class="hidden file-input"
                                       data-req-id="{{ $req->req_id }}" data-url="{{ route('student.bills.upload') }}"
                                       accept="image/*,.pdf">
                                <label for="file-{{ $req->req_id }}"
                                       class="btn-clay-secondary !py-2 !px-5 cursor-pointer text-sm min-w-[100px] hover:!bg-[var(--bbc-hex)] hover:!text-white">
                                    {{ $req->file_path ? 'Ganti' : 'Upload' }}
                                </label>
                            @else
                                <div class="px-4 py-2 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 text-xs font-bold uppercase tracking-wide">
                                    Approved
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="fixed bottom-6 left-0 right-0 px-6 lg:hidden z-40">
        @if (!$transaction || $transaction->status == 'draft')
            <a href="{{ route('student.bills.payment', $bill->id) }}" id="btn-next-mobile"
               class="btn-clay w-full shadow-2xl opacity-50 cursor-not-allowed pointer-events-none transition-all">
                Lanjut Pembayaran
            </a>
        @elseif($transaction->status == 'doc_rejected')
            <form action="{{ route('student.bills.resubmit', $transaction->id) }}" method="POST">
                @csrf
                <button type="submit" id="btn-resubmit-mobile"
                        class="btn-clay w-full shadow-2xl opacity-50 cursor-not-allowed pointer-events-none bg-red-500">
                    Kirim Perbaikan Dokumen
                </button>
            </form>
        @endif
    </div>

</div>

<script>
    $(document).ready(function() {
        function checkAllUploaded() {
            let totalMandatory = {{ $requirements->where('is_mandatory', 1)->count() }};
            let totalUploaded = $('.status-icon').filter(function() {
                return $(this).hasClass('bg-blue-100') || $(this).hasClass('bg-green-100');
            }).length;

            let btns = $('#btn-next-desktop, #btn-next-mobile, #btn-resubmit-desktop, #btn-resubmit-mobile');

            if (totalUploaded >= totalMandatory) {
                btns.removeClass('opacity-50 cursor-not-allowed pointer-events-none')
                    .addClass('animate-bounce-slight');
            } else {
                btns.addClass('opacity-50 cursor-not-allowed pointer-events-none');
            }
        }

        checkAllUploaded();

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

                labelBtn.text('...').addClass('opacity-50');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let iconBox = $(`.status-icon[data-req-id="${reqId}"]`);
                        iconBox.attr('class', 'w-14 h-14 rounded-2xl flex items-center justify-center text-xl shrink-0 status-icon transition-colors duration-300 bg-blue-100 text-blue-600');
                        iconBox.html('<i class="bi bi-cloud-arrow-up"></i>');
                        iconBox.closest('.clay-card').find('.status-text').text('File terupload').removeClass('text-red-500 font-bold').addClass('text-slate-500');
                        iconBox.closest('.clay-card').removeClass('!border-red-400 !bg-red-50 dark:!bg-red-900/10');
                        let freshUrl = response.file_url + '?t=' + new Date().getTime();
                        $(`.btn-view[data-req-id="${reqId}"]`).attr('href', freshUrl).removeClass('hidden');

                        labelBtn.text('Ganti').removeClass('opacity-50');

                        checkAllUploaded();

                        const Toast = Swal.mixin({
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                            didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                        });
                        Toast.fire({ icon: 'success', title: 'Upload Berhasil' });
                    },
                    error: function(xhr) {
                        labelBtn.text(originalText).removeClass('opacity-50');
                        Swal.fire('Gagal', 'Terjadi kesalahan upload.', 'error');
                    }
                });
            }
        });
    });
</script>

@endsection
