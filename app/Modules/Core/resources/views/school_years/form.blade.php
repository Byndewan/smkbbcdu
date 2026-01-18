<form action="{{ isset($data) ? route('admin.core.school-years.update', $data->id) : route('admin.core.school-years.store') }}"
    method="POST" class="form-ajax">
    @csrf
    @if (isset($data))
        @method('PUT')
    @endif

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">{{ isset($data) ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body position-relative">
        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">Kode (ID Unik) <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control bg-light border-0 py-2" placeholder="TA-2025/2026"
                value="{{ $data->code ?? '' }}" required>
        </div>
        <div class="mb-3 position-relative">
            <label class="form-label fw-bold small text-muted">Tahun Ajaran <span class="text-danger">*</span></label>
            <input type="text" name="name" id="school_year_input" class="form-control bg-light border-0 py-2"
                placeholder="Contoh: 2025/2026" value="{{ $data->name ?? '' }}" autocomplete="off" required>

            <div id="dropdown" class="list-group position-absolute w-100 shadow-sm mt-1 d-none"
                style="z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
    </div>
</form>

<script>
    $(document).on("input", "#school_year_input", function() {
        let val = this.value.trim();
        let match = val.match(/^(\d{4})$/);
        let dropdown = $("#dropdown");
        dropdown.empty().addClass('d-none');

        if (!match) return;

        let year = parseInt(match[1]);
        let suggestions = [];
        for (let i = 0; i < 3; i++) {
            suggestions.push(`${year + i}/${year + i}`);
            suggestions.push(`${year + i}/${year + i + 1}`);
            suggestions.push(`${year - i}/${year - i}`);
            suggestions.push(`${year - i}/${year - i + 1}`);
        }

        suggestions = [...new Set(suggestions)].sort((a, b) => parseInt(b.split("/")[0]) - parseInt(a.split(
            "/")[0]));

        suggestions.forEach(item => {
            dropdown.append(
                `<button type="button" class="list-group-item list-group-item-action option">${item}</button>`
                );
        });
        dropdown.removeClass('d-none');
    });

    $(document).on("click", ".option", function() {
        $("#school_year_input").val($(this).text());
        $("#dropdown").addClass('d-none');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#school_year_input, #dropdown').length) {
            $("#dropdown").addClass('d-none');
        }
    });
</script>
