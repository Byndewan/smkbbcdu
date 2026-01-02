@extends('layouts.admin')

@section('title', 'Kelas')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Data Kelas</h5>
            <button data-url="{{ route('core.classes.create') }}" class="btn btn-primary btn-modal">
                <i class="bi bi-plus-lg"></i> Tambah Data
            </button>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    autoWidth: false,
                    searchDelay: 100,
                    stateSave: true,
                    ajax: "{{ route('core.classes.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'major_abb',
                            name: 'major_id'
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
