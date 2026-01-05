<form action="{{ route('core.permissions.store') }}" method="POST" class="form-ajax">
    @csrf
    <input type="hidden" name="id" value="{{ $perm->id ?? '' }}">

    <div class="modal-header">
        <h5 class="modal-title">{{ $perm->exists ? 'Edit Permission' : 'Tambah Permission Baru' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Nama Permission <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name"
                placeholder="Contoh: create-student, verify-payment" value="{{ $perm->name ?? '' }}" required>
            <div class="form-text text-muted">Gunakan huruf kecil dan tanda hubung (-) untuk standar penamaan.</div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Simpan
        </button>
    </div>
</form>
