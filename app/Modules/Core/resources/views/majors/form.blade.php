<form action="{{ isset($data) ? route('admin.core.majors.update', $data->id) : route('admin.core.majors.store') }}" method="POST"
    class="form-ajax">
    @csrf
    @if (isset($data))
        @method('PUT')
    @endif

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">{{ isset($data) ? 'Edit Jurusan' : 'Tambah Jurusan' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Kode Jurusan <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control bg-light border-0 py-2"
                value="{{ $data->code ?? '' }}" placeholder="JR-RPL" required>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <label class="form-label fw-bold small text-muted">Nama Lengkap <span
                        class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control bg-light border-0 py-2"
                    value="{{ $data->name ?? '' }}" placeholder="Rekayasa Perangkat Lunak" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Singkatan</label>
                <input type="text" name="abbreviation" class="form-control bg-light border-0 py-2"
                    value="{{ $data->abbreviation ?? '' }}" placeholder="RPL">
            </div>
        </div>

        <div class="mb-0">
            <label class="form-label fw-bold small text-muted">Deskripsi</label>
            <textarea name="description" class="form-control bg-light border-0 py-2" rows="3">{{ $data->description ?? '' }}</textarea>
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
    </div>
</form>
