<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pembayaran - {{ $bill->title }}</title>
    @vite(['resources/css/student.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans pb-20">

    <nav class="bg-white shadow-sm px-4 py-3 sticky top-0 z-50">
        <div class="max-w-2xl mx-auto flex items-center gap-3">
            <a href="{{ route('student.dashboard') }}" class="text-gray-500 hover:text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="font-bold text-lg text-gray-800 truncate">{{ $bill->title }}</h1>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto mt-6 px-4">

        <div class="bg-blue-600 text-white rounded-xl p-6 shadow-lg mb-6 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-blue-100 text-sm mb-1">Total Tagihan</p>
                <h2 class="text-3xl font-bold">Rp {{ number_format($bill->amount, 0, ',', '.') }}</h2>
                <div class="mt-4 flex items-center gap-2 text-sm text-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Jatuh Tempo: {{ \Carbon\Carbon::parse($bill->end_date)->format('d M Y') }}
                </div>
            </div>
            <div class="absolute -right-6 -bottom-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
        </div>

        <div class="flex items-center justify-between mb-8 px-2">
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                    1</div>
                <span class="text-sm font-medium text-gray-800">Dokumen</span>
            </div>
            <div class="h-1 flex-1 bg-gray-200 mx-4 rounded"></div>
            <div class="flex items-center gap-2 opacity-50">
                <div
                    class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm">
                    2</div>
                <span class="text-sm font-medium text-gray-500">Transfer</span>
            </div>
        </div>

        <h3 class="font-bold text-gray-800 mb-4">Lengkapi Persyaratan</h3>
        <div class="space-y-4" id="document-list">

            @foreach ($requirements as $req)
                <div
                    class="bg-white p-4 rounded-xl border {{ $req->file_status == 'invalid' ? 'border-red-300 bg-red-50' : 'border-gray-100' }} shadow-sm flex flex-col gap-2 transition hover:border-blue-300">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center status-icon
    {{ $req->file_status == 'valid'
        ? 'bg-green-100 text-green-600'
        : ($req->file_status == 'invalid'
            ? 'bg-red-100 text-red-600'
            : ($req->file_path
                ? 'bg-blue-100 text-blue-600'
                : 'bg-gray-100 text-gray-400')) }}"
                                data-req-id="{{ $req->req_id }}">
                                @if ($req->file_status == 'valid')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @elseif($req->file_status == 'invalid')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                @elseif($req->file_path)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                @endif
                            </div>

                            <div>
                                <p class="font-semibold text-gray-800">{{ $req->document_name }}</p>

                                @if ($req->file_status == 'valid')
                                    <p class="text-xs text-green-600 font-bold">Dokumen Valid</p>
                                @elseif($req->file_status == 'invalid')
                                    <p class="text-xs text-red-600 font-bold">Ditolak:
                                        {{ $req->reject_reason ?? 'Perbaiki dokumen ini' }}</p>
                                @else
                                    <p class="text-xs text-gray-400">
                                        {{ $req->file_path ? 'Menunggu Verifikasi' : 'Wajib diupload' }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ $req->file_path ? Storage::url($req->file_path) : '#' }}" target="_blank"
                                class="btn-view px-3 py-2 rounded-lg text-gray-500 border border-gray-200 hover:bg-gray-50 {{ $req->file_path ? '' : 'hidden' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </a>

                            @if ($req->file_status != 'valid')
                                <input type="file" id="file-{{ $req->req_id }}" class="hidden file-input"
                                    data-req-id="{{ $req->req_id }}" data-url="{{ route('student.upload.temp') }}"
                                    accept="image/*,.pdf">
                                <label for="file-{{ $req->req_id }}"
                                    class="cursor-pointer bg-white text-blue-600 border border-blue-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-50 transition btn-upload">
                                    {{ $req->file_path ? 'Ganti File' : 'Upload' }}
                                </label>
                            @else
                                <span
                                    class="text-green-600 text-xs px-3 py-2 border border-green-200 bg-green-50 rounded-lg">
                                    <i class="bi bi-lock-fill"></i> Diterima
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <div class="fixed bottom-0 w-full bg-white border-t border-gray-200 p-4 shadow-lg">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">Total Pembayaran</p>
                <p class="font-bold text-lg text-blue-600">Rp {{ number_format($bill->amount, 0, ',', '.') }}</p>
            </div>

            <a href="{{ route('student.bills.payment', $bill->id) }}" id="btn-next"
                class="bg-gray-300 text-white px-6 py-3 rounded-xl font-bold transition text-center pointer-events-none cursor-not-allowed">
                Lanjut Bayar
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            function checkAllUploaded() {
                let totalMandatory = {{ $requirements->where('is_mandatory', 1)->count() }};
                let totalUploaded = $('.status-icon').filter(function() {
                    return $(this).hasClass('bg-green-100') || $(this).hasClass('bg-blue-100');
                }).length;

                let btnNext = $('#btn-next');

                if (totalUploaded >= totalMandatory) {
                    btnNext.removeClass('bg-gray-300 pointer-events-none cursor-not-allowed')
                        .addClass('bg-blue-600 hover:bg-blue-700 shadow-lg transform hover:-translate-y-1');
                } else {
                    btnNext.addClass('bg-gray-300 pointer-events-none cursor-not-allowed')
                        .removeClass('bg-blue-600 hover:bg-blue-700 shadow-lg transform hover:-translate-y-1');
                }
            }
            checkAllUploaded();
            $('.file-input').on('change', function() {
                let file = this.files[0];
                let reqId = $(this).data('req-id');
                let url = $(this).data('url');
                let billId = "{{ $bill->id }}";

                if (file) {
                    let formData = new FormData();
                    formData.append('file', file);
                    formData.append('req_id', reqId);
                    formData.append('bill_id', billId);
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    let btnLabel = $(`label[for="file-${reqId}"]`);
                    let originalText = btnLabel.text();
                    btnLabel.text('Uploading...').addClass('opacity-50 cursor-not-allowed');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            let iconContainer = $(`.status-icon[data-req-id="${reqId}"]`);
                            iconContainer.removeClass(
                                    'bg-gray-100 text-gray-400 bg-red-100 text-red-600 bg-green-100 text-green-600'
                                )
                                .addClass('bg-blue-100 text-blue-600');
                            iconContainer.html(`
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            `);
                            let textContainer = btnLabel.parent().parent().find(
                                '.status-text-container');
                            let btnView = $(`.btn-view[data-req-id="${reqId}"]`);
                            btnView.attr('href', response.file_url).removeClass('hidden');
                            btnLabel.text('Ganti').removeClass(
                                'opacity-50 cursor-not-allowed');
                            checkAllUploaded();

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Berhasil diupload',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!', xhr.responseJSON.message ||
                                'Terjadi kesalahan upload.', 'error');
                            btnLabel.text(originalText).removeClass(
                                'opacity-50 cursor-not-allowed');
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>
