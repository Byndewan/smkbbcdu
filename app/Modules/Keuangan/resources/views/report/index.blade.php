@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-funnel-fill me-2 text-primary"></i>Filter Laporan</h6>
                </div>
                <div class="card-body">
                    <form id="filter-form">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">Rentang Tanggal</label>
                                <div class="input-group">
                                    <input type="date" id="start_date" class="form-control bg-light border-0"
                                        value="{{ date('Y-m-01') }}">
                                    <span class="input-group-text bg-light border-0 text-muted">s/d</span>
                                    <input type="date" id="end_date" class="form-control bg-light border-0"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">Jurusan</label>
                                <select id="major_id" class="form-select bg-light border-0">
                                    <option value="">-- Semua Jurusan --</option>
                                    @foreach ($majors as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 d-flex gap-2">
                                <button type="button" id="btn-filter" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-search me-1"></i> Tampilkan
                                </button>
                                <button type="button" id="btn-reset" class="btn btn-light text-muted" title="Reset Filter">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                                <div class="vr"></div>
                                <button type="submit" form="form-export"
                                    class="btn btn-success fw-bold text-white shadow-sm">
                                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('admin.finance.export') }}" method="POST" id="form-export" target="_blank"
                        class="d-none">
                        @csrf
                        <input type="hidden" name="start_date" id="export_start">
                        <input type="hidden" name="end_date" id="export_end">
                        <input type="hidden" name="major_id" id="export_major">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th width="5%" class="ps-4">No</th>
                                    <th>Kode TRX</th>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Jurusan</th>
                                    <th>Tagihan</th>
                                    <th class="text-end pe-4">Nominal</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="6" class="text-end fw-bold text-muted text-uppercase small pt-3">Total
                                        Periode Ini:</td>
                                    <td class="text-end fw-bold text-primary fs-5 pe-4 pt-3" id="grand-total">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function syncExport() {
                $('#export_start').val($('#start_date').val());
                $('#export_end').val($('#end_date').val());
                $('#export_major').val($('#major_id').val());
            }

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('admin.finance.report') }}",
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.major_id = $('#major_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'ps-4 text-muted'
                    },
                    {
                        data: 'trx_code',
                        className: 'font-monospace small'
                    },
                    {
                        data: 'updated_at',
                        className: 'small'
                    },
                    {
                        data: 'student_name'
                    },
                    {
                        data: 'major_name'
                    },
                    {
                        data: 'bill_title'
                    },
                    {
                        data: 'total_amount',
                        className: 'text-end fw-bold text-dark pe-4'
                    }
                ],
                drawCallback: function(settings) {
                    var json = settings.json;
                    if (json && json.grandTotal) {
                        $('#grand-total').text('Rp ' + json.grandTotal);
                    }
                },
                dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>'
            });

            $('#btn-filter').click(function() {
                syncExport();
                table.draw();
            });

            $('#btn-reset').click(function() {
                $('#filter-form')[0].reset();
                syncExport();
                table.draw();
            });

            syncExport();
        });
    </script>
@endpush
