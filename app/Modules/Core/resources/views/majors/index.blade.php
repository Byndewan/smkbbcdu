@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Jurusan</h5>
        <button data-url="{{ route('core.majors.create') }}" class="btn btn-primary btn-modal">
            <i class="bi bi-plus-lg"></i> Tambah Jurusan
        </button>
    </div>
    <div class="card-body">
        <table id="datatable" class="table table-hover w-100">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Jurusan</th>
                    <th>Singkatan</th>
                    <th>Aksi</th>
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
            ajax: "{{ route('core.majors.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
                {data: 'abbreviation', name: 'abbreviation'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endpush
@endsection
