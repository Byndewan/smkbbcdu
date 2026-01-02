<div class="modal-header">
    <h5 class="modal-title">Verifikasi Transaksi <span class="badge bg-primary">{{ $trx->trx_code }}</span></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('du.transactions.update', $trx->id) }}" method="POST" class="form-ajax">
    @csrf
    @method('PUT')

    <div class="modal-body">

        <div class="alert alert-info d-flex align-items-center py-2 px-3 mb-3">
            <i class="bi bi-person-circle fs-4 me-2"></i>
            <div>
                <strong>{{ $trx->student_name }}</strong> &nbsp;|&nbsp; Tagihan: {{ $trx->bill_title }}
                <br>
                <small>Nominal: Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                    ({{ strtoupper($trx->payment_method) }})</small>
            </div>
        </div>

        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-file-earmark-text me-1"></i> Cek
            Kelengkapan Dokumen</h6>

        <div class="mb-4">
            @foreach ($files as $file)
                <div class="card mb-2 border-light bg-light">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                class="fw-bold text-decoration-none text-primary">
                                <i class="bi bi-link-45deg"></i> {{ $file->document_name }}
                            </a>

                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check btn-verify"
                                    name="files[{{ $file->id }}][status]" id="valid_{{ $file->id }}"
                                    value="valid" {{ $file->status == 'valid' ? 'checked' : '' }} checked>
                                <label class="btn btn-outline-success" for="valid_{{ $file->id }}">Terima</label>

                                <input type="radio" class="btn-check btn-verify"
                                    name="files[{{ $file->id }}][status]" id="invalid_{{ $file->id }}"
                                    value="invalid" {{ $file->status == 'invalid' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="invalid_{{ $file->id }}">Tolak</label>
                            </div>
                        </div>

                        <div class="reject-reason-box {{ $file->status == 'invalid' ? '' : 'd-none' }}"
                            id="reason_box_{{ $file->id }}">
                            <input type="text" name="files[{{ $file->id }}][reason]"
                                class="form-control form-control-sm border-danger text-danger"
                                placeholder="Tulis alasan penolakan dokumen ini..." value="{{ $file->reject_reason }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between">
        <div class="small text-muted fst-italic">
            <i class="bi bi-info-circle"></i> Jika ada 1 dokumen yang ditolak, status otomatis <strong>DITOLAK</strong>.
        </div>
        <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Keputusan</button>
    </div>
</form>

<script>
    $('.btn-verify').on('change', function() {
        let id = $(this).attr('id').split('_')[1];
        let val = $(this).val();

        if (val === 'invalid') {
            $('#reason_box_' + id).removeClass('d-none').find('input').focus();
        } else {
            $('#reason_box_' + id).addClass('d-none');
        }
    });

    $('.btn-verify-payment').on('change', function() {
        if ($(this).val() === 'invalid') {
            $('#payment_reason_box').removeClass('d-none').find('textarea').focus();
        } else {
            $('#payment_reason_box').addClass('d-none');
        }
    });
</script>
