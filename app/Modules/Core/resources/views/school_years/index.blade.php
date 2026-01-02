@extends('layouts.admin')

@section('title', 'Tahun Ajaran')

@section('content')
    <style>
        .dropdown-list {
            position: absolute;
            background: #fff;
            border: 1px solid #ddd;
            width: 96%;
            max-height: 150px;
            overflow-y: auto;
            display: none;
        }

        .dropdown-list div {
            padding: 5px 10px;
            cursor: pointer;
        }

        .dropdown-list div:hover {
            background: #f0f0f0;
        }
    </style>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Data Tahun Ajaran</h5>
            <button data-url="{{ route('core.school-years.create') }}" class="btn btn-primary btn-modal">
                <i class="bi bi-plus-lg"></i> Tambah Data
            </button>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Tahun Ajaran</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
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
                    ajax: "{{ route('core.school-years.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'status',
                            name: 'is_active'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            });

            $(document).on("input", "#school_year_input", function() {
                let val = this.value.trim();
                let match = val.match(/^(\d{4})$/);
                let dropdown = $("#dropdown");
                dropdown.empty();

                if (!match) {
                    dropdown.hide();
                    return;
                }

                let year = parseInt(match[1]);
                let up = [];
                let down = [];

                for (let i = 0; i < 3; i++) {
                    up.push(`${year + i}/${year + i}`);
                    up.push(`${year + i}/${year + i + 1}`);
                    down.push(`${year - i}/${year - i}`);
                    down.push(`${year - i}/${year - i + 1}`);
                }

                let suggestions = [...up, ...down];

                suggestions.sort((a, b) => parseInt(b.split("/")[0]) - parseInt(a.split("/")[0]));

                suggestions.forEach(item => {
                    dropdown.append(`<div class="option">${item}</div>`);
                });

                dropdown.show();
            });

            $(document).on("click", "#dropdown .option", function() {
                $("#school_year_input").val($(this).text());
                $("#dropdown").hide();
            });
        </script>
    @endpush
@endsection
