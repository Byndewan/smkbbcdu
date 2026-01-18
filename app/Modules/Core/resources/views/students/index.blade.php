@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3 d-flex justify-content-end align-items-center">
            <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm text-white fw-bold shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import
                </button>
                <button
                    onclick="const w=1500;const h=900;const left=(screen.width-w)/2;const top=(screen.height-h)/2;window.open('{{ route('admin.core.students.create') }}', '_blank', `width=${w},height=${h},left=${left},top=${top},resizable=yes,scrollbars=yes`)"
                    class="btn btn-primary btn-sm fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="datatable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th width="5%" class="ps-4">No</th>
                            <th>Identitas Siswa</th>
                            <th class="text-center">L/P</th>
                            <th>Info Kelas</th>
                            <th>Jurusan</th>
                            <th>Status</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Import Data Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.core.students.import') }}" method="POST" enctype="multipart/form-data"
                    class="form-ajax">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info small mb-3">
                            <i class="bi bi-info-circle-fill me-1"></i> Gunakan template Excel terbaru.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">File Excel (.xlsx)</label>
                            <input type="file" name="file" class="form-control bg-light border-0" required
                                accept=".xlsx, .xls">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success text-white fw-bold">Upload & Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.core.students.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'ps-4 text-muted'
                    },
                    {
                        data: 'identitas',
                        name: 'core_students.name'
                    },
                    {
                        data: 'jk',
                        name: 'core_students.gender',
                        className: 'text-center'
                    },
                    {
                        data: 'kelas_info',
                        name: 'current.name'
                    },
                    {
                        data: 'jurusan',
                        name: 'core_majors.name'
                    },
                    {
                        data: 'status',
                        name: 'core_students.is_active'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                dom: '<"d-flex justify-content-between align-items-center p-3"lf>rt<"d-flex justify-content-between align-items-center p-3"ip>'
            });
        });
    </script>
@endpush
