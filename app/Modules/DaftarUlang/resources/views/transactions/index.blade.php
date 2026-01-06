@extends('layouts.admin')

@section('title', 'Data Transaksi Masuk')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-cash-stack me-2 text-primary"></i>Transaksi Pembayaran</h5>
            </div>
            <div class="card-body">

                <ul class="nav nav-pills mb-4" id="pills-tab">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold position-relative" onclick="filterStatus('pending_docs', this)">
                            <i class="bi bi-inbox me-1"></i> Perlu Dicek
                            @if(isset($counts['pending_docs']) && $counts['pending_docs'] > 0)
                                <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger">
                                    {{ $counts['pending_docs'] }}
                                </span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="nav-link fw-bold" onclick="filterStatus('doc_rejected', this)">
                            <i class="bi bi-x-circle me-1"></i> Ditolak
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="nav-link fw-bold" onclick="filterStatus('payment_review', this)">
                            <i class="bi bi-arrow-right-circle me-1"></i> Lanjut Keuangan
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="nav-link fw-bold" onclick="filterStatus('paid', this)">
                            <i class="bi bi-check-all me-1"></i> Selesai
                        </button>
                    </li>
                </ul>
                <div class="table-responsive">
                    <table id="datatable" class="table table-hover align-middle w-100">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Siswa</th>
                                <th>Tagihan</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
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
            window.filterStatus = function(status, element) {
                $('.nav-link').removeClass('active');
                $(element).addClass('active');
                $('#datatable').DataTable().ajax.url("{{ route('du.transactions.index') }}?status=" + status).load();
            }

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
                            data: 'status',
                            name: 'status',
                            className: 'text-center',
                            render: function(data) {
                                let badges = {
                                    'pending_docs': '<span class="badge bg-warning text-dark">Perlu Cek Dokumen</span>',
                                    'doc_rejected': '<span class="badge bg-danger">Dokumen Ditolak</span>',
                                    'payment_review': '<span class="badge bg-info text-dark">Di Review Keuangan</span>',
                                    'paid': '<span class="badge bg-success">Lunas (Arsip)</span>',
                                    'payment_rejected': '<span class="badge bg-danger">Bukti Ditolak</span>'
                                };
                                return badges[data] || '<span class="badge bg-secondary">'+data+'</span>';
                            }
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
