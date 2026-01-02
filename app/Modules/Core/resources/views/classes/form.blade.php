<div class="modal-header">
    <h5 class="modal-title">{{ isset($data) ? 'Edit' : 'Tambah' }} Kelas</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ isset($data) ? route('core.classes.update', $data->id) : route('core.classes.store') }}"
    method="POST" class="form-ajax">
    @csrf
    @if (isset($data))
        @method('PUT')
    @endif

    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Kode Kelas <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control" placeholder="Contoh: KL-XII-RPL-1"
                value="{{ $data->code ?? '' }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: XII RPL 1"
                value="{{ $data->name ?? '' }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
            <select name="major_id" class="form-select">
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

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
