@extends('layouts.admin')

@section('title', 'Tahun Ajaran')

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-event-fill me-2 text-primary"></i>Daftar Tahun Ajaran</h6>
            <button data-url="{{ route('admin.core.school-years.create') }}" class="btn btn-primary btn-modal shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="datatable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th width="5%" class="ps-4">No</th>
                            <th width="20%">Kode</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.core.school-years.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'ps-4 text-muted'
                    },
                    {
                        data: 'code',
                        name: 'code',
                        className: 'font-monospace text-muted small'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'fw-bold text-dark fs-6'
                    },
                    {
                        data: 'status',
                        name: 'is_active'
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
