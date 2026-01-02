<div class="modal-header">
    <h5 class="modal-title fw-bold">
        <i class="bi bi-wallet2 text-success me-2"></i>Verifikasi Dana Masuk
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('finance.update', $trx->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="modal-body">

        <div class="row mb-4">
            <div class="col-6">
                <small class="text-muted text-uppercase">Siswa</small>
                <div class="fw-bold fs-5">{{ $trx->student_name }}</div>
            </div>
            <div class="col-6 text-end">
                <small class="text-muted text-uppercase">Total Transfer</small>
                <div class="fw-bold fs-4 text-primary">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="card bg-dark text-white border-0 text-center mb-3">
                    @php
                        $proofPath = $trx->proof_path;
                        if (!$proofPath && $trx->admin_note && str_contains($trx->admin_note, 'Bukti:')) {
                            $proofPath = str_replace('Bukti: ', '', $trx->admin_note);
                            $proofPath = explode(' ', $proofPath)[0];
                        }
                    @endphp

                    @if ($proofPath)
                        @if (str_ends_with($proofPath, '.pdf'))
                            <div class="card-body py-5">
                                <i class="bi bi-file-pdf display-1 text-danger"></i>
                                <br>
                                <a href="{{ Storage::url($proofPath) }}" target="_blank"
                                    class="btn btn-light btn-sm mt-3">Buka PDF</a>
                            </div>
                        @else
                            <a href="{{ Storage::url($proofPath) }}" target="_blank">
                                <img src="{{ Storage::url($proofPath) }}" class="card-img-top"
                                    style="height: 300px; object-fit: contain; background: #333;">
                            </a>
                        @endif
                        <div class="card-footer py-1 small">
                            <i class="bi bi-zoom-in"></i> Klik gambar untuk memperbesar
                        </div>
                    @else
                        <div class="card-body py-5">
                            <i class="bi bi-image-alt display-4 text-secondary"></i>
                            <p class="mt-2 text-muted">Tidak ada bukti upload</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-7">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Detail Rekening Pengirim</h6>

                <table class="table table-sm table-borderless text-sm mb-4">
                    <tr>
                        <td class="text-muted" width="100">Bank</td>
                        <td class="fw-bold">: {{ $trx->bank_sender ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. Rekening</td>
                        <td class="fw-bold">: {{ $trx->account_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Atas Nama</td>
                        <td class="fw-bold">: {{ $trx->account_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tgl Transfer</td>
                        <td>: {{ $trx->payment_date ? date('d F Y', strtotime($trx->payment_date)) : '-' }}</td>
                    </tr>
                </table>

                <div class="alert alert-warning d-flex align-items-center small p-2 mb-3">
                    <i class="bi bi-exclamation-triangle me-2 fs-5"></i>
                    <div>
                        Pastikan dana sudah benar-benar masuk di Mutasi Rekening Sekolah sebelum klik "Terima".
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Keputusan Keuangan:</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check btn-finance-action" name="payment_status"
                            id="finance_valid" value="valid" checked>
                        <label class="btn btn-outline-success py-2" for="finance_valid">
                            <i class="bi bi-check-circle-fill me-1"></i> DANA MASUK (LUNAS)
                        </label>

                        <input type="radio" class="btn-check btn-finance-action" name="payment_status"
                            id="finance_invalid" value="invalid">
                        <label class="btn btn-outline-danger py-2" for="finance_invalid">
                            <i class="bi bi-x-circle-fill me-1"></i> TOLAK
                        </label>
                    </div>
                </div>

                <div class="mb-2 d-none" id="finance_reason_box">
                    <label class="small text-danger fw-bold">Alasan Penolakan:</label>
                    <textarea name="admin_note" class="form-control border-danger" rows="3"
                        placeholder="Contoh: Nominal transfer kurang, Bukti buram/palsu, Tidak ada di mutasi..."></textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="submit" class="btn btn-success fw-bold w-100 py-2">
            <i class="bi bi-save me-2"></i> Simpan & Update Status
        </button>
    </div>
</form>

<script>
    $('.btn-finance-action').on('change', function() {
        if ($(this).val() === 'invalid') {
            $('#finance_reason_box').removeClass('d-none').find('textarea').focus().prop('required', true);
        } else {
            $('#finance_reason_box').addClass('d-none').find('textarea').prop('required', false);
        }
    });
</script>
