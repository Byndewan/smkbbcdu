import $ from "jquery";
import Swal from "sweetalert2";

window.$ = window.jQuery = $;
window.Swal = Swal;

// HANDLER OPEN MODAL
$(document).on("click", ".btn-modal", function (e) {
    e.preventDefault();
    let url = $(this).data("url");
    let modalTarget = "#modal-master";
    $(modalTarget + " .modal-content").html(`
        <div class="p-5 text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Sedang memuat form...</p>
        </div>
    `);

    const myModal = new bootstrap.Modal(
        document.getElementById("modal-master")
    );
    myModal.show();
    $.get(url, function (response) {
        $(modalTarget + " .modal-content").html(response);
    }).fail(function () {
        $(modalTarget + " .modal-content").html(`
            <div class="p-5 text-center text-danger">
                <i class="bi bi-exclamation-triangle fs-1"></i>
                <p>Gagal memuat data. Coba lagi.</p>
            </div>
        `);
    });
});

// HANDLER SUBMIT FORM CREATE/UPDATE
$(document).on("submit", ".form-ajax", function (e) {
    e.preventDefault();
    let form = $(this);
    let url = form.attr("action");
    let method = form.attr("method");
    let data = new FormData(this);
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();
    let btnSubmit = form.find('button[type="submit"]');
    let btnOriginalText = btnSubmit.html();
    btnSubmit
        .prop("disabled", true)
        .html(
            '<span class="spinner-border spinner-border-sm"></span> Menyimpan...'
        );

    $.ajax({
        url: url,
        method: method,
        data: data,
        processData: false,
        contentType: false,
        success: function (response) {
            const currentModal = form.closest(".modal");
            if (currentModal.length > 0) {
                const modalDom = currentModal.get(0);
                const modalInstance = bootstrap.Modal.getInstance(modalDom);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    currentModal
                        .find('[data-bs-dismiss="modal"]')
                        .trigger("click");
                    currentModal.modal("hide");
                    $(".modal-backdrop").remove();
                    $("body")
                        .removeClass("modal-open")
                        .css("padding-right", "");
                }
            }

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: "success",
                title: "Berhasil!",
                text: response.message || "Data berhasil disimpan.",
                timer: 800,
                timerProgressBar: true,
                showConfirmButton: false,
            });

            if ($.fn.DataTable.isDataTable("#datatable")) {
                $("#datatable").DataTable().ajax.reload(null, false);
            } else {
                setTimeout(() => window.location.reload(), 1000);
            }
            btnSubmit.prop("disabled", false).html(btnOriginalText);
            // refreshPendingBadge();
        },
        error: function (xhr) {
            btnSubmit.prop("disabled", false).html(btnOriginalText);
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    let input = form.find('[name="' + key + '"]');
                    input.addClass("is-invalid");
                    input.after(
                        `<div class="invalid-feedback">${value[0]}</div>`
                    );
                });
            } else {
                Swal.fire(
                    "Error!",
                    xhr.responseJSON.message ||
                        "Terjadi kesalahan pada server.",
                    "error"
                );
            }
        },
    });
});

// HANDLER (DELETE)
$(document).on("click", ".btn-delete", function (e) {
    e.preventDefault();
    let url = $(this).data("url");
    let name = $(this).data("name") || "data ini";

    Swal.fire({
        title: "Yakin hapus?",
        text: `Anda akan menghapus ${name}. Data tidak bisa kembali!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                method: "DELETE",
                data: {
                    _token: document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                success: function (response) {
                    Swal.fire("Terhapus!", response.message, "success");
                    if ($.fn.DataTable.isDataTable("#datatable")) {
                        $("#datatable").DataTable().ajax.reload();
                    } else {
                        location.reload();
                    }
                    // refreshPendingBadge();
                },
                error: function () {
                    Swal.fire(
                        "Gagal!",
                        "Terjadi kesalahan saat menghapus.",
                        "error"
                    );
                },
            });
        }
    });
});

// function refreshPendingBadge() {
//     let url = "/admin/daftar-ulang/transactions/count-pending";
//     $.get(url, function (response) {
//         console.log(response);
//         let badge = $("#badge-pending-transactions");
//         let count = response.count;
//         if (count > 0) {
//             badge.text(count);
//             badge.removeClass("d-none");
//         } else {
//             badge.addClass("d-none");
//         }
//     });
// }

$(function () {
    // refreshPendingBadge();
});

document.addEventListener("change", function (e) {
    if (e.target.name === "status" && e.target.value === "paid") {
        document.getElementById("admin_note").value = "";
    }
});
