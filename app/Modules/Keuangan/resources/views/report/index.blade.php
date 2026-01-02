@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="container-fluid">
        <h4 class="fw-bold text-dark mb-4">Laporan & Analisa</h4>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold m-0"><i class="bi bi-funnel text-primary me-2"></i>Filter Data</h6>
            </div>
            <div class="card-body">
                <form id="filter-form">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Rentang Tanggal</label>
                            <div class="input-group">
                                <input type="date" id="start_date" name="start_date" class="form-control" required>
                                <span class="input-group-text bg-light">s/d</span>
                                <input type="date" id="end_date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold">Jurusan</label>
                            <select id="major_id" name="major_id" class="form-select">
                                <option value="">-- Semua Jurusan --</option>
                                @foreach ($majors as $major)
                                    <option value="{{ $major->id }}">{{ $major->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="button" id="btn-filter" class="btn btn-primary w-100 fw-bold">
                                <i class="bi bi-search me-1"></i> Tampilkan
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="button" id="btn-reset" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0">
                    <i class="bi bi-table text-success me-2"></i>Preview Data
                </h6>

                <form action="{{ route('finance.export') }}" method="POST" id="form-export" target="_blank">
                    @csrf
                    <input type="hidden" name="start_date" id="export_start">
                    <input type="hidden" name="end_date" id="export_end">
                    <input type="hidden" name="major_id" id="export_major">

                    <button type="submit" class="btn btn-success fw-bold btn-sm">
                        <i class="bi bi-file-earmark-excel me-1"></i> Download Excel
                    </button>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-hover w-100">
                        <thead class="bg-light text-uppercase small">
                            <tr>
                                <th width="5%">No</th>
                                <th>No. Ref</th>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Jurusan</th>
                                <th>Tagihan</th>
                                <th class="text-end">Nominal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="6" class="text-end text-uppercase small">Total Pemasukan (Filtered):</td>
                                <td class="text-end pe-4 text-success fs-6" id="grand-total">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    searching: false,
                    ajax: {
                        url: "{{ route('finance.report') }}",
                        data: function(d) {
                            d.start_date = $('#start_date').val();
                            d.end_date = $('#end_date').val();
                            d.major_id = $('#major_id').val();
                        }
                    },
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
                            data: 'updated_at',
                            name: 'du_transactions.updated_at'
                        },
                        {
                            data: 'student_name',
                            name: 'core_students.name'
                        },
                        {
                            data: 'major_name',
                            name: 'core_majors.name'
                        },
                        {
                            data: 'bill_title',
                            name: 'du_bills.title'
                        },
                        {
                            data: 'total_amount',
                            name: 'du_transactions.total_amount'
                        }
                    ],
                    order: [
                        [2, "desc"]
                    ],
                    drawCallback: function(settings) {
                        var json = settings.json;
                        if (json && json.grandTotal) {
                            $('#grand-total').text('Rp ' + json.grandTotal);
                        }
                    }
                });

                $('#btn-filter').click(function() {
                    let start = $('#start_date').val();
                    let end = $('#end_date').val();

                    if (!start || !end) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops!',
                            text: 'Harap pilih rentang tanggal terlebih dahulu!',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    table.draw();
                });

                $('#btn-reset').click(function() {
                    $('#filter-form')[0].reset();
                    table.draw();
                    $('#grand-total').text('Rp 0');
                });

                $('#form-export').on('submit', function() {
                    $('#export_start').val($('#start_date').val());
                    $('#export_end').val($('#end_date').val());
                    $('#export_major').val($('#major_id').val());
                });

            });
        </script>
    @endpush
@endsection
