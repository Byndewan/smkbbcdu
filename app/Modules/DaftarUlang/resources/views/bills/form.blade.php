<div class="modal-header">
    <h5 class="modal-title">{{ isset($bill) ? 'Edit' : 'Buat' }} Tagihan Daftar Ulang</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ isset($bill) ? route('du.bills.update', $bill->id) : route('du.bills.store') }}" method="POST" class="form-ajax">
    @csrf
    @if(isset($bill)) @method('PUT') @endif

    <div class="modal-body">
        <h6 class="fw-bold text-primary mb-3">1. Informasi Tagihan</h6>
        <div class="mb-3">
            <label>Judul Tagihan <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ $bill->title ?? '' }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Target Tahun Ajaran <span class="text-danger">*</span></label>
                <select name="target_school_year_id" class="form-select" required>
                    @foreach($years as $y)
                        <option value="{{ $y->id }}" {{ (isset($bill) && $bill->target_school_year_id == $y->id) ? 'selected' : '' }}>
                            {{ $y->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Target Jurusan (Opsional)</label>
                <select name="target_major_id" class="form-select">
                    <option value="">-- Semua Jurusan --</option>
                    @foreach($majors as $m)
                        <option value="{{ $m->id }}" {{ (isset($bill) && $bill->target_major_id == $m->id) ? 'selected' : '' }}>
                            {{ $m->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Nominal (Rp) <span class="text-danger">*</span></label>
            <input type="number" name="amount" class="form-control" value="{{ isset($bill) ? intval($bill->amount) : '' }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $bill->start_date ?? date('Y-m-d') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Tanggal Berakhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $bill->end_date ?? '' }}" required>
            </div>
        </div>

        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-primary mb-0">2. Syarat Dokumen (Wajib Upload)</h6>
            <button type="button" class="btn btn-sm btn-outline-success" id="add-req">
                <i class="bi bi-plus-lg"></i> Tambah
            </button>
        </div>

        <div id="req-wrapper">
            @if(isset($requirements) && count($requirements) > 0)
                @foreach($requirements as $req)
                    <div class="input-group mb-2 req-item">
                        <span class="input-group-text"><i class="bi bi-file-earmark"></i></span>
                        <input type="text" name="req_names[]" class="form-control" value="{{ $req->document_name }}" >
                        <button type="button" class="btn btn-outline-danger remove-req"><i class="bi bi-trash"></i></button>
                    </div>
                @endforeach
            @else
                <div class="input-group mb-2 req-item">
                    <span class="input-group-text"><i class="bi bi-file-earmark"></i></span>
                    <input type="text" name="req_names[]" class="form-control">
                    <button type="button" class="btn btn-outline-danger remove-req"><i class="bi bi-trash"></i></button>
                </div>
            @endif
        </div>
        <small class="text-muted">* Kosongkan atau hapus baris jika tidak ada syarat dokumen tambahan.</small>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Tagihan</button>
    </div>
</form>

<script>
    $('#add-req').on('click', function(){
        let html = `
            <div class="input-group mb-2 req-item">
                <span class="input-group-text"><i class="bi bi-file-earmark"></i></span>
                <input type="text" name="req_names[]" class="form-control" >
                <button type="button" class="btn btn-outline-danger remove-req"><i class="bi bi-trash"></i></button>
            </div>
        `;
        $('#req-wrapper').append(html);
    });

    $('#req-wrapper').on('click', '.remove-req', function(){
        if($('#req-wrapper .req-item').length > 1) {
            $(this).closest('.req-item').remove();
        } else {
            alert('Minimal harus ada 1 baris (boleh dikosongkan isinya)');
        }
    });
</script>
