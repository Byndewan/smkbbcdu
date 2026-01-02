@extends('layouts.admin')

@section('title', 'Verifikasi Keuangan')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Verifikasi Pembayaran</h4>
                <p class="text-muted small mb-0">List transaksi yang sudah lolos verifikasi dokumen & menunggu cek mutasi.
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table id="datatable" class="table table-hover w-100">
                    <thead class="bg-light text-uppercase small text-secondary">
                        <tr>
                            <th width="5%">No</th>
                            <th>No. Ref</th>
                            <th>Siswa</th>
                            <th>Tagihan</th>
                            <th>Metode</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('finance.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'trx_code',
                            name: 'du_transactions.trx_code'
                        },
                        {
                            data: 'student_name',
                            name: 'core_students.name'
                        },
                        {
                            data: 'bill_title',
                            name: 'du_bills.title'
                        },
                        {
                            data: 'payment_method',
                            name: 'du_transactions.payment_method'
                        },
                        {
                            data: 'total_amount',
                            name: 'du_transactions.total_amount'
                        },
                        {
                            data: 'updated_at',
                            name: 'du_transactions.updated_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    order: [
                        [5, "asc"]
                    ]
                });
            });
        </script>
    @endpush
@endsection
