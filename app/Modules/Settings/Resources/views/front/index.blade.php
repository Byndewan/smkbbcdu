@extends('layouts.admin')

@section('title', 'Landing Page')

@push('styles')
    <style>
        #landingTab .nav-link {
            color: #64748b;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 1rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        #landingTab .nav-link:hover {
            color: var(--bbc-primary);
            background-color: rgba(0, 0, 0, 0.02);
        }

        #landingTab .nav-link.active {
            color: var(--bbc-primary) !important;
            border-bottom-color: var(--bbc-primary);
            background: transparent;
        }

        .btn-outline-primary {
            border-color: var(--bbc-primary);
            color: var(--bbc-primary);
        }

        .dataTables_wrapper .dataTables_scrollHeadInner {
            width: 100% !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Kelola Tampilan Depan</h4>
                <small class="text-muted">Atur konten landing page sekolah.</small>
            </div>
            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Website
            </a>
        </div>

        <form action="{{ route('admin.settings.front.update') }}" method="POST" enctype="multipart/form-data"
            id="form-global-settings">
            @csrf

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom border-light p-0">
                    <ul class="nav nav-tabs card-header-tabs mx-2" id="landingTab" role="tablist">
                        <li class="nav-item"><button type="button" class="nav-link active" data-type="global" data-bs-toggle="tab"
                                data-bs-target="#tab-general">Umum & Logo</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" data-type="global" data-bs-toggle="tab"
                                data-bs-target="#tab-hero">Hero Section</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" data-type="list" data-bs-toggle="tab"
                                data-bs-target="#tab-features">Fitur (3)</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" data-type="list" data-bs-toggle="tab"
                                data-bs-target="#tab-steps">Panduan (8)</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" data-type="list" data-bs-toggle="tab"
                                data-bs-target="#tab-faq">FAQ (5)</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" data-type="global" data-bs-toggle="tab"
                                data-bs-target="#tab-footer">Footer</button></li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="landingTabContent">

                        <div class="tab-pane fade show active" id="tab-general">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Logo Sekolah</label>
                                    <input type="file" name="logo" class="form-control bg-light border-0">
                                    @if (isset($setting->logo))
                                        <div class="mt-3 p-3 bg-light rounded text-center border border-dashed">
                                            <img src="{{ asset($setting->logo) }}" style="max-height: 60px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Favicon</label>
                                    <input type="file" name="favicon" class="form-control bg-light border-0">
                                    @if (isset($setting->favicon))
                                        <div class="mt-3">
                                            <img src="{{ asset($setting->favicon) }}" width="32"
                                                class="rounded shadow-sm">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-hero">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-muted">Label Kecil (Atas Judul)</label>
                                    <input type="text" name="hero_title" class="form-control bg-light border-0 py-2"
                                        value="{{ $setting->hero_title ?? '' }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-muted">Judul Besar (Heading)</label>
                                    <input type="text" name="hero_heading"
                                        class="form-control bg-light border-0 py-2 fs-4 fw-bold"
                                        value="{{ $setting->hero_heading ?? '' }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-muted">Deskripsi Singkat</label>
                                    <textarea name="hero_sort_desc" class="form-control bg-light border-0 py-2" rows="3">{{ $setting->hero_sort_desc ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Teks Tombol Utama</label>
                                    <input type="text" name="hero_button_name"
                                        class="form-control bg-light border-0 py-2"
                                        value="{{ $setting->hero_button_name ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Link Tombol Utama</label>
                                    <input type="text" name="hero_button_link"
                                        class="form-control bg-light border-0 py-2"
                                        value="{{ $setting->hero_button_link ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-footer">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Nomor WhatsApp Official</label>
                                    <input type="text" name="phone" class="form-control bg-light border-0 py-2"
                                        value="{{ $setting->phone ?? '' }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-muted">Deskripsi Footer</label>
                                    <textarea name="footer_description" class="form-control bg-light border-0 py-2" rows="2">{{ $setting->footer_description ?? '' }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-muted">Copyright Text</label>
                                    <input type="text" name="footer_copyright"
                                        class="form-control bg-light border-0 py-2"
                                        value="{{ $setting->footer_copyright ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-features">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <table id="table-features" class="table table-hover align-middle w-100 mb-0">
                                            <thead class="bg-light text-secondary">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Icon/Img</th>
                                                    <th>Judul Fitur</th>
                                                    <th>Deskripsi</th>
                                                    <th>Order</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div id="wrapper-form-feature" class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3" id="title-feature"><i
                                                    class="bi bi-plus-circle me-1"></i> Tambah Fitur</h6>
                                            <form action="{{ route('admin.settings.front.feature.store') }}" method="POST"
                                                enctype="multipart/form-data" id="form-feature">
                                                @csrf
                                                <input type="hidden" name="id" id="feature_id">
                                                <div class="mb-2">
                                                    <label class="small fw-bold text-muted">Judul</label>
                                                    <input type="text" name="heading" id="feature_heading"
                                                        class="form-control form-control-sm border-0">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="small fw-bold text-muted">Gambar/Icon</label>
                                                    <input type="file" name="image"
                                                        class="form-control form-control-sm border-0">
                                                    <small id="feature_img_help" class="text-muted d-none"
                                                        style="font-size:10px">* Kosongkan jika tidak ganti</small>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="small fw-bold text-muted">Deskripsi</label>
                                                    <textarea name="desc" id="feature_desc" class="form-control form-control-sm border-0" rows="3"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small fw-bold text-muted">Urutan</label>
                                                    <input type="number" name="sort_order" id="feature_sort"
                                                        class="form-control form-control-sm border-0" value="0">
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm fw-bold">Simpan</button>
                                                    <button type="button" class="btn btn-light btn-sm text-muted d-none"
                                                        id="btn-cancel-feature">Batal Edit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="msg-limit-feature"
                                        class="alert alert-warning d-none mt-3 border-0 bg-warning bg-opacity-10 text-warning">
                                        <i class="bi bi-info-circle me-1"></i> Maksimal 3 fitur tercapai.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-steps">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <table id="table-steps" class="table table-hover align-middle w-100 mb-0">
                                            <thead class="bg-light text-secondary">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Icon</th>
                                                    <th>Judul Langkah</th>
                                                    <th>Deskripsi</th>
                                                    <th>Order</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div id="wrapper-form-step" class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3" id="title-step"><i
                                                    class="bi bi-plus-circle me-1"></i> Tambah Langkah</h6>
                                            <form action="{{ route('admin.settings.front.step.store') }}" method="POST"
                                                id="form-step">
                                                @csrf
                                                <input type="hidden" name="id" id="step_id">
                                                <div class="mb-2">
                                                    <label class="small fw-bold text-muted">Icon Class
                                                        (FontAwesome)</label>
                                                    <input type="text" name="icon" id="step_icon"
                                                        class="form-control form-control-sm border-0 font-monospace"
                                                        placeholder="fa-solid fa-user">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="small fw-bold text-muted">Judul</label>
                                                    <input type="text" name="heading" id="step_heading"
                                                        class="form-control form-control-sm border-0">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="small fw-bold text-muted">Deskripsi</label>
                                                    <textarea name="desc" id="step_desc" class="form-control form-control-sm border-0" rows="2"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small fw-bold text-muted">Urutan</label>
                                                    <input type="number" name="sort_order" id="step_sort"
                                                        class="form-control form-control-sm border-0" value="0">
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm fw-bold">Simpan</button>
                                                    <button type="button" class="btn btn-light btn-sm text-muted d-none"
                                                        id="btn-cancel-step">Batal Edit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="msg-limit-step"
                                        class="alert alert-warning d-none mt-3 border-0 bg-warning bg-opacity-10 text-warning">
                                        <i class="bi bi-info-circle me-1"></i> Maksimal 8 langkah tercapai.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-faq">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <table id="table-faq" class="table table-hover align-middle w-100 mb-0">
                                            <thead class="bg-light text-secondary">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Pertanyaan</th>
                                                    <th>Jawaban</th>
                                                    <th>Kategori</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div id="wrapper-form-faq" class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3" id="title-faq"><i
                                                    class="bi bi-plus-circle me-1"></i> Tambah FAQ</h6>
                                            <form action="{{ route('admin.settings.front.faq.store') }}" method="POST"
                                                id="form-faq">
                                                @csrf
                                                <input type="hidden" name="id" id="faq_id">
                                                <div class="mb-2">
                                                    <label class="small fw-bold text-muted">Pertanyaan</label>
                                                    <textarea name="question" id="faq_question" class="form-control form-control-sm border-0" rows="2"></textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="small fw-bold text-muted">Jawaban</label>
                                                    <textarea name="answer" id="faq_answer" class="form-control form-control-sm border-0" rows="3"></textarea>
                                                </div>
                                                <div class="row g-2 mb-2">
                                                    <div class="col-6">
                                                        <label class="small fw-bold text-muted">Label Kategori</label>
                                                        <input type="text" name="title" id="faq_title"
                                                            class="form-control form-control-sm border-0"
                                                            placeholder="Umum">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="small fw-bold text-muted">Icon Class</label>
                                                        <input type="text" name="icon" id="faq_icon"
                                                            class="form-control form-control-sm border-0 font-monospace"
                                                            placeholder="fa-solid fa-question">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small fw-bold text-muted">Urutan</label>
                                                    <input type="number" name="sort_order" id="faq_sort"
                                                        class="form-control form-control-sm border-0" value="0">
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm fw-bold">Simpan</button>
                                                    <button type="button" class="btn btn-light btn-sm text-muted d-none"
                                                        id="btn-cancel-faq">Batal Edit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="msg-limit-faq"
                                        class="alert alert-warning d-none mt-3 border-0 bg-warning bg-opacity-10 text-warning">
                                        <i class="bi bi-info-circle me-1"></i> Maksimal 5 FAQ tercapai.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer bg-light border-top-0 py-3 text-end" id="global-save-btn">
                    <button type="submit" form="form-global-settings" class="btn btn-primary fw-bold px-4 shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Pengaturan Global
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    if ($(e.target).data('type') === 'list') {
                        $('#global-save-btn').addClass('d-none');
                    } else {
                        $('#global-save-btn').removeClass('d-none');
                    }
                });

                var tableFeatures = $('#table-features').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.settings.front.data', 'features') }}",
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

                var tableSteps = $('#table-steps').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.settings.front.data', 'steps') }}",
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

                var tableFaq = $('#table-faq').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.settings.front.data', 'faqs') }}",
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

                $('body').on('click', '.btn-edit', function() {
                    let type = $(this).data('type');
                    let row = $(this).data('row');

                    if (type === 'feature') {
                        $('#feature_id').val(row.id);
                        $('#feature_heading').val(row.features_card_heading);
                        $('#feature_desc').val(row.features_card_sort_desc);
                        $('#feature_sort').val(row.sort_order);
                        $('#feature_img_help, #btn-cancel-feature').removeClass('d-none');
                        $('#title-feature').text('Edit Fitur');
                        $('#form-feature').attr('action',
                        "{{ route('admin.settings.front.feature.store') }}");
                    } else if (type === 'step') {
                        $('#step_id').val(row.id);
                        $('#step_icon').val(row.how_icon);
                        $('#step_heading').val(row.how_item_heading);
                        $('#step_desc').val(row.how_item_sort_desc);
                        $('#step_sort').val(row.sort_order);
                        $('#btn-cancel-step').removeClass('d-none');
                        $('#title-step').text('Edit Langkah');
                    } else if (type === 'faq') {
                        $('#faq_id').val(row.id);
                        $('#faq_question').val(row.faq_card_question);
                        $('#faq_answer').val(row.faq_card_answer);
                        $('#faq_title').val(row.faq_card_title);
                        $('#faq_icon').val(row.faq_card_icon);
                        $('#faq_sort').val(row.sort_order);
                        $('#btn-cancel-faq').removeClass('d-none');
                        $('#title-faq').text('Edit FAQ');
                    }
                });

                $('#btn-cancel-feature').click(function() {
                    $('#form-feature')[0].reset();
                    $('#feature_id').val('');
                    $('#feature_img_help, #btn-cancel-feature').addClass('d-none');
                    $('#title-feature').html('<i class="bi bi-plus-circle me-1"></i> Tambah Fitur');
                });

                $('#btn-cancel-step').click(function() {
                    $('#form-step')[0].reset();
                    $('#step_id').val('');
                    $('#btn-cancel-step').addClass('d-none');
                    $('#title-step').html('<i class="bi bi-plus-circle me-1"></i> Tambah Langkah');
                });

                $('#btn-cancel-faq').click(function() {
                    $('#form-faq')[0].reset();
                    $('#faq_id').val('');
                    $('#btn-cancel-faq').addClass('d-none');
                    $('#title-faq').html('<i class="bi bi-plus-circle me-1"></i> Tambah FAQ');
                });

                const counts = @json($counts ?? ['features' => 0, 'steps' => 0, 'faqs' => 0]);
            });
        </script>
    @endpush
@endsection
