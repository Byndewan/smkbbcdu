@extends('layouts.admin')

@section('title', 'Manajemen Role')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Data Peran Pengguna</h5>
            <button data-url="{{ route('core.roles.create') }}" class="btn btn-primary btn-modal">
                <i class="bi bi-plus-lg"></i> Tambah Data
            </button>
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama Peran</th>
                        <th>Hak Akses (Permissions)</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('core.roles.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions',
                        orderable: false,
                        searchable: false
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
