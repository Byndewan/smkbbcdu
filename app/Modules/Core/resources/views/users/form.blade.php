<form action="{{ route('core.users.store') }}" method="POST" class="form-ajax">
    @csrf
    <input type="hidden" name="id" value="{{ $user->id ?? '' }}">

    <div class="modal-header">
        <h5 class="modal-title">{{ $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" value="{{ $user->name ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email" value="{{ $user->email ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Password
                @if (!$user->exists) <span class="text-danger">*</span> @else <small>(Kosongkan jika tidak ubah)</small> @endif
            </label>
            <input type="password" class="form-control" name="password" @if(!$user->exists) required @endif>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                <select class="form-select" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold d-block">Tipe Akses Data</label>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="is_operator_switch" name="is_operator"
                        value="1" {{ $user->is_operator ?? false ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_operator_switch">Operator?</label>
                </div>

                <div id="major_container" class="d-none">
                    <select class="form-select" name="major_id" id="major_id">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" {{ $user->major_id == $major->id ? 'selected' : '' }}>
                                {{ $major->name }} ({{ $major->abbreviation }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text text-danger small">* Wajib dipilih untuk Operator.</div>
                </div>

                <div id="global_access_text" class="form-text text-success small d-none">
                    <i class="fas fa-check-circle me-1"></i> User ini bisa mengakses <strong>SEMUA DATA</strong>
                    (Global).
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        function toggleMajor() {
            if ($('#is_operator_switch').is(':checked')) {
                $('#major_container').removeClass('d-none').addClass('animate__animated animate__fadeIn');
                $('#global_access_text').addClass('d-none');
                $('#major_id').prop('required', true);
            } else {
                $('#major_container').addClass('d-none');
                $('#global_access_text').removeClass('d-none');
                $('#major_id').prop('required', false);
                $('#major_id').val('');
            }
        }

        toggleMajor();
        $('#is_operator_switch').on('change', toggleMajor);
    });
</script>
