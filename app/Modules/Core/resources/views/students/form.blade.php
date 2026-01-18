@extends('layouts.admin')

@section('title', 'Data Siswa')
@section('no-sidebar')
@endsection

@section('no-navbar')
@endsection

@section('content')
    <form id="studentForm"
        action="{{ isset($student) ? route('admin.core.students.update', $student->id) : route('admin.core.students.store') }}"
        method="POST">
        @csrf
        @if (isset($student))
            @method('PUT')
        @endif

        <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">
                {{ isset($student) ? 'Edit Data Siswa' : 'Tambah Siswa Baru' }}
            </h5>
            <button type="button" class="btn-close" onclick="window.close()"></button>
        </div>

        <div class="modal-body">

            <h6 class="text-primary fw-bold small text-uppercase mb-3 border-bottom pb-2">Identitas Utama
            </h6>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">NIPD <span class="text-danger">*</span></label>
                    <input type="text" name="nipd" class="form-control bg-light" value="{{ $student->nipd ?? '' }}"
                        required placeholder="Nomor Induk Sekolah">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Email (Akun Login) <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control bg-light" value="{{ $student->email ?? '' }}"
                        required placeholder="siswa@sekolah.sch.id">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-light" value="{{ $student->name ?? '' }}"
                        required placeholder="Nama sesuai ijazah">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="g_L" value="L"
                                {{ isset($student) && $student->gender == 'L' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="g_L">Laki-laki</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="g_P" value="P"
                                {{ isset($student) && $student->gender == 'P' ? 'checked' : '' }}>
                            <label class="form-check-label" for="g_P">Perempuan</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">No. WhatsApp</label>
                    <input type="text" name="phone" class="form-control bg-light" value="{{ $student->phone ?? '' }}"
                        placeholder="08xxx">
                </div>
            </div>

            <h6 class="text-primary fw-bold small text-uppercase mb-3 border-bottom pb-2">Kontak & Bio</h6>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Tempat Lahir</label>
                    <input type="text" name="pob" class="form-control bg-light" value="{{ $student->pob ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Tanggal Lahir</label>
                    <input type="date" name="dob" class="form-control bg-light"
                        value="{{ isset($student) && $student->dob ? \Carbon\Carbon::parse($student->dob)->format('Y-m-d') : '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Tahun Ajaran</label>
                    <select name="school_year_id" class="form-select bg-light" required>
                        @foreach ($years as $year)
                            <option value="{{ $year->id }}"
                                {{ isset($student) && $student->school_year_id == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Status Siswa</label>
                    <select name="is_active" class="form-select bg-light">
                        <option value="0" {{ isset($student) && !$student->is_active ? 'selected' : '' }}>Non-Aktif
                            / Alumni</option>
                            <option value="1" {{ !isset($student) || $student->is_active ? 'selected' : '' }}>Aktif
                            </option>
                    </select>
                </div>
            </div>

            <h6 class="text-primary fw-bold small text-uppercase mb-3 border-bottom pb-2">Informasi
                Akademik
            </h6>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label small fw-bold">Jurusan <span class="text-danger">*</span></label>
                    <select name="major_id" class="form-select bg-light" id="majorSelect" required>
                        <option value="" disabled selected>Pilih Jurusan</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" data-code="{{ $major->code }}"
                                {{ isset($student) && $student->major_id == $major->id ? 'selected' : '' }}>
                                {{ $major->name }} ({{ $major->abbreviation }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Kelas Baru/Saat Ini</label>
                    <select name="class_id" class="form-select bg-light" id="classSelect">
                        <option value="">Belum Masuk Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" data-code="{{ $class->code }}"
                                {{ isset($student) && $student->current_class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Kelas Sebelumnya</label>
                    <select name="prev_class_id" class="form-select bg-light" id="classSelect">
                        <option value="">Belum Masuk Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" data-code="{{ $class->code }}"
                                {{ isset($student) && $student->prev_class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>

        </div>

        <div class="modal-footer border-0 pt-0 mt-3 gap-2">
            <button type="button" class="btn btn-light" onclick="window.close()">Tutup</button>
            <button type="submit" class="btn btn-primary fw-bold" id="btnSave">
                <i class="bi bi-save me-1"></i> {{ isset($student) ? 'Simpan Perubahan' : 'Simpan Data' }}
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.getElementById('majorSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const majorCode = selectedOption.dataset.code;
            if (!majorCode) return;
            const majorKey = majorCode.replace('JR-', '');
            filterClassByCode('classSelect', majorKey);
            filterClassByCode('prevClassSelect', majorKey);
        });

        function filterClassByCode(selectId, majorKey) {
            const select = document.getElementById(selectId);
            const options = select.querySelectorAll('option');

            options.forEach(option => {
                const classCode = option.dataset.code;
                if (!classCode) {
                    option.hidden = false;
                    return;
                }

                if (classCode.includes(`-${majorKey}-`)) {
                    option.hidden = false;
                } else {
                    option.hidden = true;
                    option.selected = false;
                }
            });
        }

        $(document).ready(function() {
            $('#studentForm').on('submit', function(e) {
                e.preventDefault();
                let btn = $('#btnSave');
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...');
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: res.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            if (window.opener && !window.opener.closed) {
                                try {
                                    window.opener.$('#datatable').DataTable().ajax
                                        .reload(null, false);
                                } catch (e) {
                                    console.log('Table reload failed:', e);
                                }
                            }
                            window.close();
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(
                            '<i class="bi bi-save me-2"></i> Simpan Data');
                        let msg = xhr.responseJSON ? xhr.responseJSON.message :
                            'Terjadi kesalahan sistem.';
                        Swal.fire('Gagal!', msg, 'error');
                    }
                });
            });
        });
    </script>
@endpush
