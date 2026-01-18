<form action="{{ isset($bill) ? route('admin.du.bills.update', $bill->id) : route('admin.du.bills.store') }}" method="POST"
    class="form-ajax">
    @csrf
    @if (isset($bill))
        @method('PUT')
    @endif

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">{{ isset($bill) ? 'Edit Tagihan' : 'Buat Tagihan Baru' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <h6 class="fw-bold text-primary mb-3 small text-uppercase">1. Informasi Tagihan</h6>

        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Judul Tagihan <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control bg-light border-0 py-2"
                value="{{ $bill->title ?? '' }}" placeholder="Contoh: Daftar Ulang Gelombang 1" required>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">Target Tahun Ajaran <span
                        class="text-danger">*</span></label>
                <select name="target_school_year_id" class="form-select bg-light border-0 py-2" required>
                    @foreach ($years as $y)
                        <option value="{{ $y->id }}"
                            {{ isset($bill) && $bill->target_school_year_id == $y->id ? 'selected' : '' }}>
                            {{ $y->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">Target Jurusan</label>
                <select name="target_major_id" class="form-select bg-light border-0 py-2">
                    <option value="">-- Semua Jurusan --</option>
                    @foreach ($majors as $m)
                        <option value="{{ $m->id }}"
                            {{ isset($bill) && $bill->target_major_id == $m->id ? 'selected' : '' }}>
                            {{ $m->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Nominal (Rp) <span class="text-danger">*</span></label>
            <div class="input-group border-0">
                <span class="input-group-text bg-light border-0 fw-bold text-muted">Rp</span>
                <input type="number" name="amount" class="form-control bg-light border-0 py-2 fw-bold text-dark"
                    value="{{ isset($bill) ? intval($bill->amount) : '' }}" placeholder="0" required>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control bg-light border-0 py-2"
                    value="{{ $bill->start_date ?? date('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">Jatuh Tempo</label>
                <input type="date" name="end_date" class="form-control bg-light border-0 py-2"
                    value="{{ $bill->end_date ?? '' }}" required>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2 border-top pt-3">
            <h6 class="fw-bold text-primary mb-0 small text-uppercase">2. Syarat Dokumen</h6>
            <button type="button" class="btn btn-sm btn-light text-success fw-bold" id="add-req">
                <i class="bi bi-plus-circle me-1"></i> Tambah
            </button>
        </div>

        <div id="req-wrapper" class="d-flex flex-column gap-2">
            @if (isset($requirements) && count($requirements) > 0)
                @foreach ($requirements as $req)
                    <div class="input-group req-item">
                        <span class="input-group-text bg-light border-0"><i
                                class="bi bi-file-earmark-text text-muted"></i></span>
                        <input type="text" name="req_names[]" class="form-control bg-light border-0 py-2"
                            value="{{ $req->document_name }}" placeholder="Nama Dokumen (misal: Kartu Keluarga)">
                        <button type="button" class="btn btn-light text-danger border-0 remove-req"><i
                                class="bi bi-trash"></i></button>
                    </div>
                @endforeach
            @else
                <div class="input-group req-item">
                    <span class="input-group-text bg-light border-0"><i
                            class="bi bi-file-earmark-text text-muted"></i></span>
                    <input type="text" name="req_names[]" class="form-control bg-light border-0 py-2"
                        placeholder="Nama Dokumen (misal: Kartu Keluarga)">
                    <button type="button" class="btn btn-light text-danger border-0 remove-req"><i
                            class="bi bi-trash"></i></button>
                </div>
            @endif
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan Tagihan</button>
    </div>
</form>

<script>
    $('#add-req').on('click', function() {
        let html = `
            <div class="input-group req-item animate__animated animate__fadeIn">
                <span class="input-group-text bg-light border-0"><i class="bi bi-file-earmark-text text-muted"></i></span>
                <input type="text" name="req_names[]" class="form-control bg-light border-0 py-2" placeholder="Nama Dokumen">
                <button type="button" class="btn btn-light text-danger border-0 remove-req"><i class="bi bi-trash"></i></button>
            </div>
        `;
        $('#req-wrapper').append(html);
    });

    $('#req-wrapper').on('click', '.remove-req', function() {
        if ($('#req-wrapper .req-item').length > 1) {
            $(this).closest('.req-item').remove();
        } else {
            // Toast or Alert
            // alert('Minimal 1 syarat');
        }
    });
</script>
