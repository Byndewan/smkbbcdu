<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Dokumen - {{ $trx->student_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f4f6f9;
            overflow-x: hidden;
        }

        .doc-container {
            height: 100vh;
            overflow-y: auto;
            background: #343a40;
            padding: 20px;
        }

        .form-container {
            height: 100vh;
            overflow-y: auto;
            background: #fff;
            border-left: 1px solid #dee2e6;
            padding: 30px;
        }

        .img-preview {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 2px solid transparent;
        }

        .pdf-preview {
            width: 100%;
            height: 500px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 10;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .doc-card {
            background: #495057;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .doc-card.invalid {
            border: 2px solid #dc3545;
            background: #2c0b0e;
        }

        .doc-card.valid {
            border: 2px solid #198754;
        }
    </style>
</head>

<body>

    <form id="verificationForm">
        <div class="row g-0">
            <div class="col-md-8 doc-container">
                @forelse($files as $file)
                    <div class="doc-card {{ $file->status == 'invalid' ? 'invalid' : 'valid' }}"
                        id="card-{{ $file->id }}">

                        <div class="d-flex justify-content-between align-items-center mb-2 text-white">
                            <h5 class="fw-bold m-0"><i
                                    class="bi bi-file-earmark-text me-2"></i>{{ $file->document_name }}</h5>

                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check file-verdict"
                                    name="files[{{ $file->id }}][status]" id="valid-{{ $file->id }}"
                                    value="valid" data-id="{{ $file->id }}" data-name="{{ $file->document_name }}"
                                    {{ $file->status != 'invalid' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success btn-sm fw-bold"
                                    for="valid-{{ $file->id }}">Valid</label>

                                <input type="radio" class="btn-check file-verdict"
                                    name="files[{{ $file->id }}][status]" id="invalid-{{ $file->id }}"
                                    value="invalid" data-id="{{ $file->id }}"
                                    data-name="{{ $file->document_name }}"
                                    data-reject-reason="{{ $file->reject_reason }}"
                                    {{ $file->status == 'invalid' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger btn-sm fw-bold"
                                    for="invalid-{{ $file->id }}">Invalid</label>
                            </div>
                        </div>

                        @if (str_ends_with($file->file_path, '.pdf'))
                            <iframe src="{{ Storage::url($file->file_path) }}" class="pdf-preview"></iframe>
                        @else
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank">
                                <img src="{{ Storage::url($file->file_path) }}" class="img-preview">
                            </a>
                        @endif

                        <div class="mt-2 {{ $file->status == 'invalid' ? '' : 'd-none' }}"
                            id="reason-wrapper-{{ $file->id }}">
                            <textarea class="form-control form-control-sm bg-dark text-white border-secondary file-reason"
                                name="files[{{ $file->id }}][reason]" data-name="{{ $file->document_name }}"
                                placeholder="Alasan penolakan (misal: Buram, Terpotong)..." rows="3">{{ $file->reject_reason }}</textarea>
                            {{-- <input type="text"> --}}
                        </div>
                    </div>
                @empty
                    <div class="text-white text-center mt-5">
                        <h3>Tidak ada dokumen.</h3>
                    </div>
                @endforelse
            </div>

            <div class="col-md-4 form-container">
                <div class="sticky-header">
                    <h5 class="fw-bold text-primary mb-1">{{ $trx->student_name }}</h5>
                    <p class="text-muted mb-2">{{ $trx->nipd }} - {{ $trx->class_name }}</p>
                    <span class="badge bg-warning text-dark">{{ $trx->bill_title }}</span>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Kesimpulan Otomatis:</label>
                    <div class="card p-3 bg-light border-0">
                        <h4 class="fw-bold text-center" id="displayVerdict">
                            <i class="bi bi-check-circle-fill text-success"></i> DOKUMEN DITERIMA
                        </h4>
                        <p class="text-center text-muted small m-0" id="displayVerdictText">Semua dokumen aman.</p>
                    </div>
                    <input type="hidden" name="verdict" id="mainVerdict" value="valid">
                </div>

                <div class="mb-4" id="reasonBox">
                    <label class="form-label fw-bold text-danger">Catatan Untuk Petugas:</label>
                    <p class="text-muted fw-bold">Apabila semua dokumen telah sesuai, silakan klik “Valid” pada pojok
                        kanan atas gambar.
                        Status akan otomatis berubah menjadi “DITERIMA”</p>
                </div>

                <div class="mb-4" id="reasonBox">
                    <label class="form-label fw-bold text-danger">Catatan Untuk Siswa:</label>
                    <textarea class="form-control" name="admin_note" id="adminNote" rows="6"
                        placeholder="Catatan otomatis akan muncul di sini...">{{ $trx->admin_note }}</textarea>
                    <div class="form-text">Admin bisa mengedit catatan ini sebelum disimpan.</div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold" id="btnSave">
                        <i class="bi bi-save me-2"></i> SIMPAN & PROSES
                    </button>
                    <button type="button" onclick="window.close()" class="btn btn-outline-secondary">Tutup</button>
                </div>
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function calculateMainVerdict(updateNote = true) {
                let invalidDocs = $('.file-verdict[value="invalid"]:checked');
                let invalidCount = invalidDocs.length;
                let revisedCount = 0;
                let note = "";
                invalidDocs.each(function() {
                    const rejectReason = $(this).data('reject-reason') || '';
                    if (
                        rejectReason !== '' &&
                        rejectReason.includes('Untuk Dokumen Ini Sudah Diperbaiki Sesuai')
                    ) {
                        revisedCount++;
                    }
                });

                if (invalidCount > 0) {
                    $('#mainVerdict').val('invalid');

                    let title = 'PERLU REVISI';
                    let text = 'Ditemukan ' + invalidCount + ' dokumen bermasalah.';
                    let notePrefix = 'Mohon perbaiki dokumen berikut:\n';

                    if (revisedCount === invalidCount) {
                        title = 'SUDAH DIREVISI';
                        text = invalidCount + ' dokumen sudah direvisi oleh siswa.';
                        notePrefix = 'Dokumen telah direvisi oleh siswa:\n';
                    }

                    $('#displayVerdict').html(
                        '<i class="bi bi-x-circle-fill text-danger"></i> ' + title
                    );
                    $('#displayVerdictText').text(text);

                    $('#btnSave')
                        .removeClass('btn-primary')
                        .addClass('btn-danger')
                        .html('KEMBALIKAN KE SISWA');

                    note = notePrefix;

                    invalidDocs.each(function() {
                        let docName = $(this).data('name');
                        let id = $(this).data('id');
                        let reasonWrapper = $('#reason-wrapper-' + id);
                        let reason = reasonWrapper.find('textarea').val() || 'Tidak valid';

                        note += `- ${docName}: ${reason}\n`;
                    });

                } else {
                    $('#mainVerdict').val('valid');
                    $('#displayVerdict').html(
                        '<i class="bi bi-check-circle-fill text-success"></i> DOKUMEN VALID'
                    );
                    $('#displayVerdictText').text('Semua dokumen lengkap dan sesuai.');

                    $('#btnSave')
                        .removeClass('btn-danger')
                        .addClass('btn-primary')
                        .html('LANJUT KE KEUANGAN');

                    note = 'Dokumen valid. Data diteruskan ke Bagian Keuangan.';
                }

                if (updateNote) {
                    $('#adminNote').val(note);
                }
            }

            calculateMainVerdict(false);

            $('.file-verdict').on('change', function() {
                let id = $(this).data('id');
                let status = $(this).val();
                let card = $('#card-' + id);
                let reasonWrapper = $('#reason-wrapper-' + id);
                let reasonInput = reasonWrapper.find('textarea');
                card.removeClass('valid invalid');
                if (status === 'invalid') {
                    card.addClass('invalid');
                    reasonWrapper.removeClass('d-none');
                    reasonInput.prop('required', true).focus();
                } else {
                    card.addClass('valid');
                    reasonWrapper.addClass('d-none');
                    reasonInput.prop('required', false);
                }

                calculateMainVerdict(true);
            });

            $('.file-reason').on('input', function() {
                calculateMainVerdict(true);
            });

            $('#verificationForm').on('submit', function(e) {
                e.preventDefault();
                let btn = $('#btnSave');
                let originalText = btn.html();
                btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('du.transactions.update', $trx->id) }}",
                    method: "PUT",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        if (window.opener && !window.opener.closed) {
                            window.opener.$('#datatable').DataTable().ajax.reload();
                        }
                        window.close();
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON.message || 'Terjadi kesalahan'));
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });

        });
    </script>

</body>

</html>
