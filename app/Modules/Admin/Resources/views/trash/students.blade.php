@extends('layouts.admin')

@section('title', 'Sampah Siswa')

@push('styles')
    <style>
        .table-trash thead th {
            background-color: #fff1f2 !important;
            /* Merah muda */
            color: #991b1b !important;
            border-bottom: 2px solid #fecaca !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.trash.dashboard') }}" class="text-decoration-none text-muted small mb-1 d-block">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard Sampah
                </a>
                <h4 class="fw-bold text-danger mb-0">Arsip Siswa Terhapus</h4>
            </div>
            <div class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                <i class="bi bi-shield-lock me-1"></i> Area Sensitif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="table-trash-students" class="table table-hover align-middle w-100 mb-0 table-trash">
                        <thead>
                            <tr>
                                <th width="5%" class="ps-4">No</th>
                                <th>Identitas Siswa</th>
                                <th>Kelas Terakhir</th>
                                <th>Info Penghapusan</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var table = $('#table-trash-students').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.trash.students') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'ps-4 text-muted'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `<div>
                                        <div class="fw-bold text-dark">${data}</div>
                                        <div class="small text-muted font-monospace">${row.nipd}</div>
                                    </div>`;
                        }
                    },
                    {
                        data: 'class_name',
                        name: 'core_classes.name',
                        render: function(data, type, row) {
                            return `<span class="badge bg-light text-dark border">${data || '-'}</span>
                                    <span class="badge bg-light text-muted border">${row.major_name || '-'}</span>`;
                        }
                    },
                    {
                        data: 'deleter_info',
                        name: 'deleter_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: '<"d-flex justify-content-between align-items-center p-3"lf>rt<"d-flex justify-content-between align-items-center p-3"ip>'
            });

            window.restoreData = function(url) {
                Swal.fire({
                    title: 'Pulihkan Data?',
                    text: "Data akan kembali ke menu Siswa Aktif (Status: Non-Aktif)",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Pulihkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            beforeSend: function() {
                                Swal.showLoading();
                            },
                            success: function(res) {
                                Swal.fire('Berhasil!', res.message, 'success');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON.message ||
                                    'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            };

            window.forceDelete = function(url) {
                Swal.fire({
                    title: 'Hapus Permanen?',
                    html: "Data akan <b>HILANG SELAMANYA</b> dan tidak bisa dikembalikan!<br>Ketik <b>HAPUS</b> untuk konfirmasi.",
                    icon: 'warning',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'HAPUS',
                    cancelButtonText: 'Batal',
                    preConfirm: (confirmText) => {
                        if (confirmText !== 'HAPUS') {
                            Swal.showValidationMessage(
                                'Konfirmasi salah! Ketik HAPUS dengan huruf besar semua.')
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            beforeSend: function() {
                                Swal.showLoading();
                            },
                            success: function(res) {
                                Swal.fire('Terhapus!', res.message, 'success');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON.message ||
                                    'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            };
        });
    </script>
@endpush
