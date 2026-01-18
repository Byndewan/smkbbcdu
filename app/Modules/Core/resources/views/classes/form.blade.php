<form action="{{ isset($data) ? route('admin.core.classes.update', $data->id) : route('admin.core.classes.store') }}" method="POST"
    class="form-ajax">
    @csrf
    @if (isset($data))
        @method('PUT')
    @endif

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">{{ isset($data) ? 'Edit Kelas' : 'Tambah Kelas' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Kode Kelas <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control bg-light border-0 py-2"
                placeholder="Contoh: KL-XII-RPL-1" value="{{ $data->code ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Nama Kelas <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control bg-light border-0 py-2"
                placeholder="Contoh: XII RPL 1" value="{{ $data->name ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Jurusan <span class="text-danger">*</span></label>
            <select name="major_id" class="form-select bg-light border-0 py-2" required>
                <option value="">-- Pilih Jurusan --</option>
                @foreach ($majors as $major)
                    <option value="{{ $major->id }}"
                        {{ isset($data) && $data->major_id == $major->id ? 'selected' : '' }}>
                        {{ $major->name }} ({{ $major->abbreviation }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
    </div>
</form>
