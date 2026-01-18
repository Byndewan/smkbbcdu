<form action="{{ route('admin.core.permissions.store') }}" method="POST" class="form-ajax">
    @csrf
    <input type="hidden" name="id" value="{{ $perm->id ?? '' }}">

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">{{ $perm->exists ? 'Edit Permission' : 'Tambah Permission' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Nama Permission <span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control bg-light border-0 py-2" name="name"
                placeholder="ex: create-student" value="{{ $perm->name ?? '' }}" required>
            <div class="form-text text-muted small mt-2"><i class="bi bi-info-circle me-1"></i> Gunakan huruf kecil dan
                tanda hubung (-).</div>
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
    </div>
</form>
