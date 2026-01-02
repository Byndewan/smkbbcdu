<div class="modal-header">
    <h5 class="modal-title">{{ isset($data) ? 'Edit' : 'Tambah' }} Jurusan</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ isset($data) ? route('core.majors.update', $data->id) : route('core.majors.store') }}" method="POST" class="form-ajax">

    @csrf
    @if (isset($data))
        @method('PUT')
    @endif

    <div class="modal-body text-start">
        <div class="mb-3">
            <label>Kode Jurusan <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control" value="{{ $data->code ?? '' }}"
                placeholder="Contoh: JR-RPL">
        </div>

        <div class="row">
            <div class="col-md-8 mb-3">
                <label>Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $data->name ?? '' }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Singkatan <span class="text-danger">*</span></label>
                <input type="text" name="abbreviation" class="form-control" value="{{ $data->abbreviation ?? '' }}">
            </div>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ $data->description ?? '' }}</textarea>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            {{ isset($data) ? 'Simpan Perubahan' : 'Simpan' }}
        </button>
    </div>
</form>
