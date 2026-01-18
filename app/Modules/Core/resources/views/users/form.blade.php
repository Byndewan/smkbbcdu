<form action="{{ route('admin.core.users.store') }}" method="POST" class="form-ajax">
    @csrf
    <input type="hidden" name="id" value="{{ $user->id ?? '' }}">

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">{{ $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-bold small text-muted">Nama Lengkap <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control bg-light border-0 py-2" name="name"
                    value="{{ $user->name ?? '' }}" placeholder="Nama User" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold small text-muted">Email Login <span
                        class="text-danger">*</span></label>
                <input type="email" class="form-control bg-light border-0 py-2" name="email"
                    value="{{ $user->email ?? '' }}" placeholder="email@sekolah.id" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold small text-muted">Password
                    @if ($user->exists)
                        <span class="badge bg-light text-dark fw-normal ms-1">Isi jika ingin mengubah</span>
                    @else
                        <span class="text-danger">*</span>
                    @endif
                </label>
                <input type="password" class="form-control bg-light border-0 py-2" name="password" placeholder="******"
                    @if (!$user->exists) required @endif>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">Role <span class="text-danger">*</span></label>
                <select class="form-select bg-light border-0 py-2" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">Tipe Operator</label>
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input" type="checkbox" id="is_operator_switch" name="is_operator"
                        value="1" {{ $user->is_operator ?? false ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_operator_switch">Batasi Akses Jurusan?</label>
                </div>
            </div>

            <div class="col-12 d-none" id="major_container">
                <label class="form-label fw-bold small text-muted">Jurusan Yang Dikelola <span
                        class="text-danger">*</span></label>
                <select class="form-select bg-light border-0 py-2" name="major_id" id="major_id">
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major->id }}" {{ $user->major_id == $major->id ? 'selected' : '' }}>
                            {{ $major->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        function toggleMajor() {
            if ($('#is_operator_switch').is(':checked')) {
                $('#major_container').removeClass('d-none').addClass('animate__animated animate__fadeIn');
                $('#major_id').prop('required', true);
            } else {
                $('#major_container').addClass('d-none');
                $('#major_id').prop('required', false).val('');
            }
        }
        toggleMajor();
        $('#is_operator_switch').on('change', toggleMajor);
    });
</script>
