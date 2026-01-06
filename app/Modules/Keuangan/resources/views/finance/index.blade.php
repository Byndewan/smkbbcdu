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

                <ul class="nav nav-pills mb-4">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold position-relative"
                            onclick="filterStatus('payment_review', this)">
                            <i class="bi bi-hourglass-split me-1"></i> Perlu Di Cek (Pembayaran)
                            @if (isset($counts['payment_review']) && $counts['payment_review'] > 0)
                                <span
                                    class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger">
                                    {{ $counts['payment_review'] }}
                                </span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="nav-link fw-bold" onclick="filterStatus('payment_rejected', this)">
                            <i class="bi bi-x-circle me-1"></i> Ditolak
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="nav-link fw-bold" onclick="filterStatus('paid', this)">
                            <i class="bi bi-check-circle-fill me-1"></i> Lunas (Arsip)
                        </button>
                    </li>
                </ul>
                <table id="datatable" class="table table-hover w-100">
                    <thead class="bg-light text-uppercase small text-secondary">
                        <tr>
                            <th width="5%">No</th>
                            <th>No. Ref</th>
                            <th>Siswa</th>
                            <th>Tagihan</th>
                            <th>Metode</th>
                            <th>Nominal</th>
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
            window.filterStatus = function(status, element) {
                $('.nav-link').removeClass('active');
                $(element).addClass('active');
                $('#datatable').DataTable().ajax.url("{{ route('finance.index') }}?status=" + status).load();
            }

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
