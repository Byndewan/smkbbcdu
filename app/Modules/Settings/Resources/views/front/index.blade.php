@extends('layouts.admin')

@section('title', 'Kelola Landing Page')

@push('styles')
    <style>
        /* Styling Tab biar Hitam Pekat saat Active */
        #landingTab .nav-link {
            color: #6c757d;
        }

        #landingTab .nav-link:hover {
            color: #000;
        }

        #landingTab .nav-link.active {
            color: #000000 !important;
            font-weight: 700 !important;
            border-bottom-color: #fff;
        }

        /* Fix Table Header Background */
        .dataTables_wrapper .dataTables_scrollHeadInner {
            width: 100% !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark">Kelola Tampilan Depan</h4>
            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-eye"></i> Lihat Website
            </a>
        </div>

        <form action="{{ route('settings.front.update') }}" method="POST" enctype="multipart/form-data"
            id="form-global-settings">
            @csrf

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="landingTab" role="tablist">
                        <li class="nav-item"><button class="nav-link active fw-bold" data-type="global" data-bs-toggle="tab"
                                data-bs-target="#tab-general" type="button">Umum & Logo</button></li>
                        <li class="nav-item"><button class="nav-link fw-bold" data-type="global" data-bs-toggle="tab"
                                data-bs-target="#tab-hero" type="button">Hero Section</button></li>

                        <li class="nav-item"><button class="nav-link fw-bold" data-type="list" data-bs-toggle="tab"
                                data-bs-target="#tab-features" type="button">Fitur</button></li>
                        <li class="nav-item"><button class="nav-link fw-bold" data-type="list" data-bs-toggle="tab"
                                data-bs-target="#tab-steps" type="button">Panduan</button></li>
                        <li class="nav-item"><button class="nav-link fw-bold" data-type="list" data-bs-toggle="tab"
                                data-bs-target="#tab-faq" type="button">FAQ</button></li>

                        <li class="nav-item"><button class="nav-link fw-bold" data-type="global" data-bs-toggle="tab"
                                data-bs-target="#tab-footer" type="button">Footer</button></li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="landingTabContent">
                        <div class="tab-pane fade show active" id="tab-general">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Logo Sekolah</label>
                                    <input type="file" name="logo" class="form-control">
                                    @if (isset($setting->logo))
                                        <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                            <img src="{{ asset($setting->logo) }}" height="50" alt="Logo Saat Ini">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Favicon</label>
                                    <input type="file" name="favicon" class="form-control">
                                    @if (isset($setting->favicon))
                                        <img src="{{ asset($setting->favicon) }}" width="32" class="mt-2"
                                            alt="Favicon">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-hero">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Label Kecil</label>
                                <input type="text" name="hero_title" class="form-control"
                                    value="{{ $setting->hero_title ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Judul Besar</label>
                                <input type="text" name="hero_heading" class="form-control form-control-lg"
                                    value="{{ $setting->hero_heading ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="hero_sort_desc" class="form-control" rows="3">{{ $setting->hero_sort_desc ?? '' }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Teks Tombol</label>
                                    <input type="text" name="hero_button_name" class="form-control"
                                        value="{{ $setting->hero_button_name ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Link Tombol</label>
                                    <input type="text" name="hero_button_link" class="form-control"
                                        value="{{ $setting->hero_button_link ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-footer">
                            <div class="mb-3">
                                <label class="form-label fw-bold">WhatsApp Official</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ $setting->phone ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsi Footer</label>
                                <textarea name="footer_description" class="form-control" rows="2">{{ $setting->footer_description ?? '' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Copyright</label>
                                <input type="text" name="footer_copyright" class="form-control"
                                    value="{{ $setting->footer_copyright ?? '' }}">
                            </div>
                        </div>

        </form>
        <div class="tab-pane fade" id="tab-features">
            <div class="row">
                <div class="col-md-8">
                    <table id="table-features" class="table table-bordered table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Img</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Order</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-md-4">
                    <div id="wrapper-form-feature">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3" id="title-feature">Tambah Fitur</h6>
                                <form action="{{ route('settings.front.feature.store') }}" method="POST"
                                    enctype="multipart/form-data" id="form-feature">
                                    @csrf
                                    <input type="hidden" name="id" id="feature_id">
                                    <div class="mb-2"><label class="small">Judul</label><input type="text"
                                            name="heading" id="feature_heading" class="form-control form-control-sm"
                                            required></div>
                                    <div class="mb-2"><label class="small">Gambar</label><input type="file"
                                            name="image" class="form-control form-control-sm"> <small
                                            id="feature_img_help" class="text-muted d-none"
                                            style="font-size:10px">Kosongkan jika tidak
                                            ganti</small></div>
                                    <div class="mb-2"><label class="small">Deskripsi</label>
                                        <textarea name="desc" id="feature_desc" class="form-control form-control-sm" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-2"><label class="small">Urutan</label><input type="number"
                                            name="sort_order" id="feature_sort" class="form-control form-control-sm"
                                            value="0"></div>
                                    <div class="d-flex gap-2 mt-2">
                                        <button class="btn btn-success btn-sm w-100">Simpan</button>
                                        <button type="button" class="btn btn-secondary btn-sm d-none"
                                            id="btn-cancel-feature">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="msg-limit-feature" class="alert alert-warning text-center d-none">
                        <i class="bi bi-exclamation-circle me-1"></i> Maksimal 3 Fitur tercapai.
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-steps">
            <div class="row">
                <div class="col-md-8">
                    <table id="table-steps" class="table table-bordered table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Icon</th>
                                <th>Langkah</th>
                                <th>Deskripsi</th>
                                <th>Order</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-md-4">
                    <div id="wrapper-form-step">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3" id="title-step">Tambah Langkah</h6>
                                <form action="{{ route('settings.front.step.store') }}" method="POST" id="form-step">
                                    @csrf
                                    <input type="hidden" name="id" id="step_id">
                                    <div class="mb-2"><label class="small">Icon (FontAwesome)</label><input
                                            type="text" name="icon" id="step_icon"
                                            class="form-control form-control-sm" placeholder="fas fa-user" required></div>
                                    <div class="mb-2"><label class="small">Judul</label><input type="text"
                                            name="heading" id="step_heading" class="form-control form-control-sm"
                                            required>
                                    </div>
                                    <div class="mb-2"><label class="small">Deskripsi</label>
                                        <textarea name="desc" id="step_desc" class="form-control form-control-sm" rows="2" required></textarea>
                                    </div>
                                    <div class="mb-2"><label class="small">Urutan</label><input type="number"
                                            name="sort_order" id="step_sort" class="form-control form-control-sm"
                                            value="0"></div>
                                    <div class="d-flex gap-2 mt-2">
                                        <button class="btn btn-success btn-sm w-100">Simpan</button>
                                        <button type="button" class="btn btn-secondary btn-sm d-none"
                                            id="btn-cancel-step">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="msg-limit-step" class="alert alert-warning text-center d-none">
                        <i class="bi bi-exclamation-circle me-1"></i> Maksimal 8 Langkah tercapai.
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-faq">
            <div class="row">
                <div class="col-md-8">
                    <table id="table-faq" class="table table-bordered table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanya</th>
                                <th>Jawab</th>
                                <th>Tag</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-md-4">
                    <div id="wrapper-form-faq">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3" id="title-faq">Tambah FAQ</h6>
                                <form action="{{ route('settings.front.faq.store') }}" method="POST" id="form-faq">
                                    @csrf
                                    <input type="hidden" name="id" id="faq_id">
                                    <div class="mb-2"><label class="small">Pertanyaan</label>
                                        <textarea name="question" id="faq_question" class="form-control form-control-sm" rows="2" required></textarea>
                                    </div>
                                    <div class="mb-2"><label class="small">Jawaban</label>
                                        <textarea name="answer" id="faq_answer" class="form-control form-control-sm" rows="3" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-2"><label class="small">Label</label><input type="text"
                                                name="title" id="faq_title" class="form-control form-control-sm"
                                                placeholder="Help"></div>
                                        <div class="col-6 mb-2"><label class="small">Icon</label><input type="text"
                                                name="icon" id="faq_icon" class="form-control form-control-sm"
                                                placeholder="fas fa-info"></div>
                                    </div>
                                    <div class="mb-2"><label class="small">Urutan</label><input type="number"
                                            name="sort_order" id="faq_sort" class="form-control form-control-sm"
                                            value="0"></div>
                                    <div class="d-flex gap-2 mt-2">
                                        <button class="btn btn-success btn-sm w-100">Simpan</button>
                                        <button type="button" class="btn btn-secondary btn-sm d-none"
                                            id="btn-cancel-faq">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="msg-limit-faq" class="alert alert-warning text-center d-none">
                        <i class="bi bi-exclamation-circle me-1"></i> Maksimal 5 FAQ tercapai.
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    <div class="card-footer bg-light text-end" id="global-save-btn">
        <button type="submit" form="form-global-settings" class="btn btn-primary fw-bold px-4">
            <i class="bi bi-save me-1"></i> Simpan Perubahan Global
        </button>
    </div>
    </div>

    </div>

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                const counts = @json($counts ?? ['features' => 0, 'steps' => 0, 'faqs' => 0]);
                const limits = {
                    features: 3,
                    steps: 8,
                    faqs: 3
                };

                function checkLimit(type) {
                    let count = counts[type] || 0;
                    let limit = limits[type];
                    let wrapper, msg;

                    if (type === 'features') {
                        wrapper = '#wrapper-form-feature';
                        msg = '#msg-limit-feature';
                    }
                    if (type === 'steps') {
                        wrapper = '#wrapper-form-step';
                        msg = '#msg-limit-step';
                    }
                    if (type === 'faqs') {
                        wrapper = '#wrapper-form-faq';
                        msg = '#msg-limit-faq';
                    }

                    if (count >= limit) {
                        $(wrapper).addClass('d-none');
                        $(msg).removeClass('d-none');
                    } else {
                        $(wrapper).removeClass('d-none');
                        $(msg).addClass('d-none');
                    }
                }

                checkLimit('features');
                checkLimit('steps');
                checkLimit('faqs');

                $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    let type = $(e.target).data('type');
                    if (type === 'list') {
                        $('#global-save-btn').addClass('d-none');
                    } else {
                        $('#global-save-btn').removeClass('d-none');
                    }
                });
                var tableFeatures, tableSteps, tableFaq;
                if (!tableFeatures) {
                    tableFeatures = $('#table-features').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('settings.front.data', 'features') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'features_image',
                                name: 'features_image',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'features_card_heading',
                                name: 'features_card_heading'
                            },
                            {
                                data: 'features_card_sort_desc',
                                name: 'features_card_sort_desc'
                            },
                            {
                                data: 'sort_order',
                                name: 'sort_order'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ]
                    });
                }
                if (!tableSteps) {
                    tableSteps = $('#table-steps').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('settings.front.data', 'steps') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'how_icon',
                                name: 'how_icon',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'how_item_heading',
                                name: 'how_item_heading'
                            },
                            {
                                data: 'how_item_sort_desc',
                                name: 'how_item_sort_desc'
                            },
                            {
                                data: 'sort_order',
                                name: 'sort_order'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ]
                    });
                }
                if (!tableFaq) {
                    tableFaq = $('#table-faq').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('settings.front.data', 'faqs') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'faq_card_question',
                                name: 'faq_card_question'
                            },
                            {
                                data: 'faq_card_answer',
                                name: 'faq_card_answer'
                            },
                            {
                                data: 'faq_card_title',
                                name: 'faq_card_title'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ]
                    });
                }
                $('body').on('click', '.btn-edit', function() {
                    let type = $(this).data('type');
                    let row = $(this).data('row');
                    if (type === 'feature') {
                        $('#wrapper-form-feature').removeClass('d-none');
                        $('#msg-limit-feature').addClass('d-none');
                        $('#feature_id').val(row.id);
                        $('#feature_heading').val(row.features_card_heading);
                        $('#feature_desc').val(row.features_card_sort_desc);
                        $('#feature_sort').val(row.sort_order);
                        $('#feature_img_help, #btn-cancel-feature').removeClass('d-none');
                        $('#title-feature').text('Edit Fitur');
                        $('#form-feature').attr('action', "{{ route('settings.front.feature.store') }}");
                        $('html, body').animate({
                            scrollTop: $("#form-feature").offset().top - 100
                        }, 300);
                    } else if (type === 'step') {
                        $('#wrapper-form-step').removeClass('d-none');
                        $('#msg-limit-step').addClass('d-none');
                        $('#step_id').val(row.id);
                        $('#step_icon').val(row.how_icon);
                        $('#step_heading').val(row.how_item_heading);
                        $('#step_desc').val(row.how_item_sort_desc);
                        $('#step_sort').val(row.sort_order);
                        $('#btn-cancel-step').removeClass('d-none');
                        $('#title-step').text('Edit Langkah');
                        $('html, body').animate({
                            scrollTop: $("#form-step").offset().top - 100
                        }, 300);
                    } else if (type === 'faq') {
                        $('#wrapper-form-faq').removeClass('d-none');
                        $('#msg-limit-faq').addClass('d-none');
                        $('#faq_id').val(row.id);
                        $('#faq_question').val(row.faq_card_question);
                        $('#faq_answer').val(row.faq_card_answer);
                        $('#faq_title').val(row.faq_card_title);
                        $('#faq_icon').val(row.faq_card_icon);
                        $('#btn-cancel-faq').removeClass('d-none');
                        $('#title-faq').text('Edit FAQ');
                        $('html, body').animate({
                            scrollTop: $("#form-faq").offset().top - 100
                        }, 300);
                    }
                });
                $('#btn-cancel-feature').click(function() {
                    $('#form-feature')[0].reset();
                    $('#feature_id').val('');
                    $('#feature_img_help, #btn-cancel-feature').addClass('d-none');
                    $('#title-feature').text('Tambah Fitur');
                    heckLimit('features');
                });
                $('#btn-cancel-step').click(function() {
                    $('#form-step')[0].reset();
                    $('#step_id').val('');
                    $('#btn-cancel-step').addClass('d-none');
                    $('#title-step').text('Tambah Langkah');
                    heckLimit('steps');
                });
                $('#btn-cancel-faq').click(function() {
                    $('#form-faq')[0].reset();
                    $('#faq_id').val('');
                    $('#btn-cancel-faq').addClass('d-none');
                    $('#title-faq').text('Tambah FAQ');
                    heckLimit('faqs');
                });
                $('body').on('click', '.btn-delete', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    let url = $(this).data('url');
                    let token = "{{ csrf_token() }}";
                    let table = $(this).closest('table').attr('id');
                    Swal.fire({
                        title: "Yakin hapus?",
                        text: "Data tidak bisa kembali!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                data: {
                                    _token: token
                                },
                                success: function(response) {
                                    Swal.fire("Terhapus!", response.message, "success");
                                    if (table) {
                                        $('#' + table).DataTable().ajax.reload(null, false);

                                        if (table === 'table-features') {
                                            counts.features--;
                                            checkLimit('features');
                                        }
                                        if (table === 'table-steps') {
                                            counts.steps--;
                                            checkLimit('steps');
                                        }
                                        if (table === 'table-faq') {
                                            counts.faqs--;
                                            checkLimit('faqs');
                                        }
                                    } else {
                                        location.reload();
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire("Gagal!", "Terjadi kesalahan sistem.",
                                        "error");
                                }
                            });
                        }
                    });
                });

                $('body').on('click', '.btn-preview', function() {
                    let type = $(this).data('type');
                    let row = $(this).data('row');
                    let htmlContent = '';

                    if (type === 'feature') {
                        htmlContent = `
                    <div class="flex flex-col shrink w-full bg-white rounded-xl shadow-lg p-6 items-center justify-center text-center max-w-sm mx-auto">
                        <img src="/${row.features_image}" class="w-full h-40 object-contain mb-4">
                        <h6 class="text-xl font-semibold text-gray-800">${row.features_card_heading}</h6>
                        <p class="text-gray-600 py-2 text-md">${row.features_card_sort_desc}</p>
                    </div>`;
                    } else if (type === 'step') {
                        htmlContent = `
                    <div class="flex items-start gap-4 p-4 bg-white rounded-xl shadow-md max-w-md mx-auto">
                        <div class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center text-white font-bold text-lg shrink-0">
                            <i class="${row.how_icon}"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">${row.how_item_heading}</h4>
                            <p class="text-gray-600 text-sm">${row.how_item_sort_desc}</p>
                        </div>
                    </div>`;
                    } else if (type === 'faq') {
                        htmlContent = `
                    <div class="border-y border-gray-200 bg-white p-4 max-w-md mx-auto">
                        <div class="font-semibold text-gray-900 mb-2">${row.faq_card_question}</div>
                        <div class="text-gray-600 text-sm">${row.faq_card_answer}</div>
                    </div>`;
                    }
                    let modalBody = `
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Live Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light p-4">
                    <iframe id="preview-frame" style="width:100%; height:100%; border:none;"></iframe>
                </div>
            `;

                    $('#modal-master .modal-content').html(modalBody);
                    var myModal = new bootstrap.Modal(document.getElementById('modal-master'));
                    myModal.show();

                    setTimeout(() => {
                        let doc = document.getElementById('preview-frame').contentWindow.document;
                        doc.open();
                        doc.write(`
                    <html class="h-full">
                    <head>
                        <script src="https://cdn.tailwindcss.com"><\/script>
                        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
                    </head><body class="bg-gray-100 flex items-center justify-center h-full p-4">${htmlContent}</body></html>
                `);
                        doc.close();
                    }, 500);
                });

            });
        </script>
    @endpush
@endsection
