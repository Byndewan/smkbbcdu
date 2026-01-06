@extends('layouts.admin')

@section('title', 'Kelola Tagihan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">Daftar Tagihan (Bills)</h5>
                            <small class="text-muted">Atur nominal dan syarat pembayaran daftar ulang disini.</small>
                        </div>

                        <button data-url="{{ route('du.bills.create') }}" class="btn btn-primary btn-modal">
                            <i class="bi bi-plus-lg me-1"></i> Buat Tagihan Baru
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover align-middle w-100">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Judul Tagihan</th>
                                        <th>Target (Jurusan & Tahun)</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('du.bills.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'title',
                            name: 'title',
                            className: 'fw-bold'
                        },
                        {
                            data: 'target',
                            name: 'target'
                        },
                        {
                            data: 'amount',
                            name: 'amount',
                            className: 'text-end fw-bold text-success'
                        },
                        {
                            data: 'status',
                            name: 'is_active',
                            className: 'text-center'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                    ],
                });
            });
        </script>
    @endpush
@endsection
