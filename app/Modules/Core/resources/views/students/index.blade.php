@extends('layouts.admin')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Data Siswa</h5>
            <div>
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-excel"></i> Import Excel
                </button>
                <button class="btn btn-primary btn-modal" data-url="#">
                    <i class="bi bi-plus-lg"></i> Tambah Manual
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIPD</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <formus action="{{ route('core.students.import') }}" method="POST" enctype="multipart/form-data"
                    class="form-ajax">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i> Pastikan format Excel sesuai template.
                            Kolom wajib: <b>nipd, nama_siswa, kode_kelas_sebelumnya, kode_kelas_baru, kode_jurusan, kode_tahun</b>.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih File Excel</label>
                            <input type="file" name="file" class="form-control" required accept=".xlsx, .xls">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Upload & Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('core.students.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nipd',
                            name: 'nipd'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'class_name',
                            name: 'core_classes.name'
                        },
                        {
                            data: 'major_name',
                            name: 'core_majors.abbreviation'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            });

            
        </script>
    @endpush
@endsection
