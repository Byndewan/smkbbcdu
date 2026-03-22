@extends('layouts.admin')

@section('title', 'Verifikasi Keuangan')

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-wallet2 me-2 text-success"></i>Verifikasi Pembayaran</h6>
        </div>

        <div class="card-body">
            <ul class="nav nav-pills mb-4 gap-2">
                <li class="nav-item">
                    <button class="nav-link active fw-bold small" onclick="filterStatus('payment_review', this)">
                        <i class="bi bi-hourglass-split me-1"></i> Cek Mutasi
                        @if (isset($counts['payment_review']) && $counts['payment_review'] > 0)
                            <span class="badge bg-danger ms-1 rounded-pill">{{ $counts['payment_review'] }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold small text-muted" onclick="filterStatus('payment_rejected', this)">
                        <i class="bi bi-x-circle me-1"></i> Ditolak
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold small text-muted" onclick="filterStatus('paid', this)">
                        <i class="bi bi-archive me-1"></i> Arsip Lunas
                    </button>
                </li>
            </ul>

            <div class="table-responsive">
                <table id="datatable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th width="5%" class="ps-4">No</th>
                            <th>Kode TRX</th>
                            <th>Siswa</th>
                            <th>Tagihan</th>
                            <th>Metode</th>
                            <th class="text-end">Nominal</th>
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
            $('.nav-link').removeClass('active text-success').addClass('text-muted');
            $(element).addClass('active text-white').removeClass('text-muted');
            $('#datatable').DataTable().ajax.url("{{ route('admin.finance.index') }}?status=" + status).load();
        }

        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.finance.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'ps-4 text-muted'
                    },
                    {
                        data: 'trx_code',
                        name: 'trx_code',
                        className: 'font-monospace small'
                    },
                    {
                        data: 'student_name',
                        name: 'student_name'
                    },
                    {
                        data: 'bill_title',
                        name: 'du_bills.title',
                        className: 'fw-bold text-dark'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        className: 'font-monospace fw-bold text-success text-end'
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
                    [5, "desc"]
                ],
                dom: '<"d-flex justify-content-between align-items-center p-3"lf>rt<"d-flex justify-content-between align-items-center p-3"ip>'
            });
        });
    </script>
    <script>
        setTimeout(() => {
            if (typeof window.Echo === 'undefined') return;
            console.log("📡 Keuangan Listening (Mode Hemat Resource)...");
            window.Echo.channel('admin-channel')
                .listen('.payment.received', (e) => {
                    let status = e.transaction.status;
                    const statusKeuangan = ['payment_review', 'paid', 'payment_rejected'];
                    if (statusKeuangan.includes(status)) {
                        if ($.fn.DataTable.isDataTable('#datatable')) {
                            $('#datatable').DataTable().ajax.reload(null, false);

                            // Refresh badge counter di sini
                        }
                    }
                });
        }, 1000);
    </script>
@endpush
