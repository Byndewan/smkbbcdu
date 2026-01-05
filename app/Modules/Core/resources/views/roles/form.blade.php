<form action="{{ route('core.roles.store') }}" method="POST" class="form-ajax">
    @csrf
    <input type="hidden" name="id" value="{{ $role->id ?? '' }}">

    <div class="modal-header">
        <h5 class="modal-title">{{ $role->exists ? 'Edit Role' : 'Tambah Role Baru' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Nama Role <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name"
                placeholder="Contoh: Kepala Sekolah" value="{{ $role->name ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold d-block">Pilih Hak Akses:</label>
            <div class="card bg-light p-3">
                <div class="row" id="permission-container">
                    @foreach ($permissions as $perm)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
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
            <div class="form-text mt-1 text-muted">Centang akses yang diizinkan untuk role ini.</div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Simpan
        </button>
    </div>
</form>
