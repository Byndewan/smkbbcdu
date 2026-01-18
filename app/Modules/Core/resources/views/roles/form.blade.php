<form action="{{ route('admin.core.roles.store') }}" method="POST" class="form-ajax">
    @csrf
    <input type="hidden" name="id" value="{{ $role->id ?? '' }}">

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">{{ $role->exists ? 'Edit Role' : 'Tambah Role Baru' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="mb-4">
            <label class="form-label fw-bold small text-muted">Nama Role <span class="text-danger">*</span></label>
            <input type="text" class="form-control bg-light border-0 py-2" name="name"
                placeholder="Contoh: Kepala Sekolah" value="{{ $role->name ?? '' }}" required>
        </div>

        <div class="mb-2">
            <label class="form-label fw-bold small text-muted d-block mb-2">Pilih Hak Akses:</label>
            <div class="card bg-light border-0 p-3" style="max-height: 250px; overflow-y: auto;">
                <div class="row g-2">
                    @foreach ($permissions as $perm)
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="{{ $perm->name }}" id="perm_{{ $perm->id }}"
                                    {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="perm_{{ $perm->id }}">
                                    {{ $perm->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-text text-muted small mt-2"><i class="bi bi-info-circle me-1"></i> Centang akses yang
                diizinkan untuk role ini.</div>
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
    </div>
</form>
