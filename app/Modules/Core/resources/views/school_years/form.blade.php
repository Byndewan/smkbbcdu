<div class="modal-header">
    <h5 class="modal-title">{{ isset($data) ? 'Edit' : 'Tambah' }} Tahun Ajaran</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ isset($data) ? route('core.school-years.update', $data->id) : route('core.school-years.store') }}"
    method="POST" class="form-ajax">
    @csrf
    @if (isset($data))
        @method('PUT')
    @endif

    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Kode (ID Unik) <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control" placeholder="Contoh: TA-2025/2026"
                value="{{ $data->code ?? '' }}">
            <small class="text-muted">Digunakan untuk relasi data lama.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
            <input type="text" name="name" id="school_year_input" class="form-control"
                placeholder="Contoh: 2025/2026" list="school_year_list" value="{{ $data->name ?? '' }}">
            <div id="dropdown" class="dropdown-list"></div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
