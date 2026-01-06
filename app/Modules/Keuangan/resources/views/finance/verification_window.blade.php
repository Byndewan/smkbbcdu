<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Pembayaran - {{ $trx->student_name }}</title>
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
            background: #e9ecef;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            height: 100vh;
            overflow-y: auto;
            background: #fff;
            border-left: 1px solid #dee2e6;
            padding: 30px;
        }

        .img-preview {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    </style>
</head>

<body>

    <div class="row g-0">
        <div class="col-md-8 doc-container">

            @if ($trx->payment_method == 'bni')
                <div class="text-center">
                    <div class="card shadow-lg p-5 border-0 rounded-4" style="max-width: 500px;">
                        <img src="https://1.bp.blogspot.com/-qeSPPbNmjuY/X_leJy1NPVI/AAAAAAAABUA/SYGk6kSbG6gcunGZWmJMaFbgT7KlzgpMgCLcBGAsYHQ/s1800/download-logo-bank-bni-negara-indonesia-vector-jpg-logoawal1.jpg"
                            style="height: 60px;" class="mx-auto mb-4">
                        <h5 class="text-muted text-uppercase mb-1">Virtual Account Number</h5>
                        <h1 class="display-4 fw-bold text-primary mb-3">{{ $trx->va_number }}</h1>
                        <div class="alert alert-info">
                            <strong>Midtrans Order ID:</strong><br>
                            {{ $trx->trx_code }}
                        </div>
                        <p class="text-muted small">Cek di Dashboard Midtrans jika status belum terupdate otomatis.</p>
                    </div>
                </div>
            @else
                <div class="text-center w-100">
                    @if ($trx->proof_path)
                        <h5 class="mb-3 text-muted"><i class="bi bi-receipt me-2"></i>Bukti Transfer</h5>
                        <a href="{{ Storage::url($trx->proof_path) }}" target="_blank">
                            <img src="{{ Storage::url($trx->proof_path) }}" class="img-preview" alt="Bukti Transfer">
                        </a>
                        <p class="mt-2 text-muted small"><i class="bi bi-zoom-in"></i> Klik gambar untuk zoom</p>
                    @else
                        <div class="text-muted">
                            <i class="bi bi-image-alt display-1"></i>
                            <h3 class="mt-3">Tidak ada bukti transfer.</h3>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <div class="col-md-4 form-container">
            <div class="sticky-header">
                <h5 class="fw-bold text-primary mb-1">{{ $trx->student_name }}</h5>
                <p class="text-muted mb-2">{{ $trx->nipd }}</p>
                <h3 class="fw-bold text-success">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</h3>
            </div>

            <div class="mb-3">
                <table class="table table-sm table-borderless text-sm">
                    <tr>
                        <td class="text-muted">Metode</td>
                        <td class="fw-bold">: {{ strtoupper(str_replace('_', ' ', $trx->payment_method)) }}</td>
                    </tr>
                    @if ($trx->payment_method == 'manual')
                        <tr>
                            <td class="text-muted">Bank Pengirim</td>
                            <td class="fw-bold">: {{ $trx->bank_sender }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Atas Nama</td>
                            <td class="fw-bold">: {{ $trx->account_name }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <form id="financeForm">
                <div class="mb-4">
                    <label class="form-label fw-bold">Verifikasi Dana:</label>
                    <div class="card p-3 bg-light border-0">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_status" id="valid"
                                value="valid" {{ $trx->status === 'paid' ? 'checked disabled' : '' }} required>
                            <label class="form-check-label fw-bold text-success" for="valid">
                                <i class="bi bi-check-circle-fill me-1"></i> TERIMA (LUNAS)
                            </label>
                        </div>
                        <hr>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_status" id="invalid"
                                value="invalid" {{ $trx->status === 'paid' ? 'disabled' : '' }}>
                            <label class="form-check-label fw-bold text-danger" for="invalid">
                                <i class="bi bi-x-circle-fill me-1"></i> TOLAK (BELUM LUNAS)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-4 d-none" id="reasonBox">
                    <label class="form-label fw-bold text-danger">Alasan Penolakan:</label>
                    <textarea class="form-control" name="admin_note" rows="3"
                        placeholder="Contoh: Nominal tidak sesuai, Bukti palsu..." {{ $trx->status === 'paid' ? 'disabled' : '' }}></textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold" id="btnSave" {{ $trx->status === 'paid' ? 'disabled' : '' }}>
                        <i class="bi bi-save me-2"></i> SIMPAN STATUS
                    </button>
                    <button type="button" onclick="window.close()" class="btn btn-outline-secondary">
                        Tutup Halaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('input[name="payment_status"]').on('change', function() {
            if ($(this).val() === 'invalid') {
                $('#reasonBox').removeClass('d-none').find('textarea').prop('required', true).focus();
                $('#btnSave').removeClass('btn-primary').addClass('btn-danger').html('TOLAK PEMBAYARAN');
            } else {
                $('#reasonBox').addClass('d-none').find('textarea').prop('required', false);
                $('#btnSave').removeClass('btn-danger').addClass('btn-primary').html('TERIMA PEMBAYARAN');
            }
        });

        $('#financeForm').on('submit', function(e) {
            e.preventDefault();
            let btn = $('#btnSave');
            let originalText = btn.html();
            btn.prop('disabled', true).text('Menyimpan...');
            $.ajax({
                url: "{{ route('finance.update', $trx->id) }}",
                method: "PUT",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    if (window.opener && !window.opener.closed) {
                        window.opener.$('#table-transactions').DataTable().ajax
                    .reload();
                    }
                    window.close();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    </script>

</body>

</html>
