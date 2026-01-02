@extends('layouts.admin')

@section('title', 'Data Transaksi Masuk')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-cash-stack me-2 text-primary"></i>Transaksi Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-hover align-middle w-100">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Siswa</th>
                                <th>Tagihan</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
                    ajax: "{{ route('du.transactions.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'student_info',
                            name: 'student_name'
                        },
                        {
                            data: 'bill_title',
                            name: 'du_bills.title'
                        },
                        {
                            data: 'total_amount',
                            name: 'total_amount',
                            className: 'fw-bold'
                        },
                        {
                            data: 'payment_method',
                            name: 'payment_method',
                            render: function(data) {
                                return data ? data.toUpperCase() : '-';
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data) {
                                return data ? new Date(data).toLocaleDateString('id-ID') : '-';
                            }
                        },
                        {
                            data: 'status',
                            name: 'status',
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
                    order: [
                        [5, 'desc']
                    ]
                });
            });
        </script>
    @endpush
@endsection
