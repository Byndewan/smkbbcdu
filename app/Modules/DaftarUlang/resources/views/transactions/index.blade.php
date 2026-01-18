@extends('layouts.admin')

@section('title', 'Data Transaksi')

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-inbox-fill me-2 text-primary"></i>Transaksi Masuk</h6>
        </div>

        <div class="card-body">
            <ul class="nav nav-pills mb-4 gap-2" id="pills-tab">
                <li class="nav-item">
                    <button class="nav-link active fw-bold small" onclick="filterStatus('pending_docs', this)">
                        <i class="bi bi-file-earmark-text me-1"></i> Cek Dokumen
                        @if (isset($counts['pending_docs']) && $counts['pending_docs'] > 0)
                            <span class="badge bg-danger ms-1 rounded-pill">{{ $counts['pending_docs'] }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold small text-muted" onclick="filterStatus('doc_rejected', this)">
                        <i class="bi bi-x-circle me-1"></i> Dokumen Ditolak
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold small text-muted" onclick="filterStatus('payment_review', this)">
                        <i class="bi bi-arrow-right-circle me-1"></i> Lanjut Keuangan
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold small text-muted" onclick="filterStatus('paid', this)">
                        <i class="bi bi-check-all me-1"></i> Selesai
                    </button>
                </li>
            </ul>

            <div class="table-responsive">
                <table id="datatable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th width="5%" class="ps-4">No</th>
                            <th>Siswa</th>
                            <th>Tagihan</th>
                            <th>Nominal</th>
                            <th class="text-center">Status</th>
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
        window.filterStatus = function(status, element) {
            $('.nav-link').removeClass('active text-primary').addClass('text-muted');
            $(element).addClass('active text-white').removeClass('text-muted');
            $('#datatable').DataTable().ajax.url("{{ route('admin.du.transactions.index') }}?status=" + status).load();
        }

        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.du.transactions.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'ps-4 text-muted'
                    },
                    {
                        data: 'student_info',
                        name: 'student_name'
                    },
                    {
                        data: 'bill_title',
                        name: 'du_bills.title',
                        className: 'fw-bold text-dark'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        className: 'font-monospace text-end pe-4'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        render: function(data) {
                            let map = {
                                'pending_docs': '<span class="badge bg-warning text-dark bg-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-search me-1"></i> Cek Dokumen</span>',
                                'doc_rejected': '<span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Ditolak</span>',
                                'payment_review': '<span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Di Keuangan</span>',
                                'paid': '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Lunas</span>',
                                'payment_rejected': '<span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Bukti Ditolak</span>'
                            };
                            return map[data] || data;
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
                dom: '<"d-flex justify-content-between align-items-center p-3"lf>rt<"d-flex justify-content-between align-items-center p-3"ip>'
            });
        });
    </script>
@endpush
