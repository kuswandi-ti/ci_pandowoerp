$(document).ready(function () {
    $('#non_grid').click(function () {
        $('#item_length').removeAttr('required');
        $('#item_width').removeAttr('required');
        $('#item_height').removeAttr('required');
        $('#item_length').val('');
        $('#item_width').val('');
        $('#item_height').val('');
    });

    $('#grid').click(function () {
        $('#item_length').attr('required', 'required');
        $('#item_width').attr('required', 'required');
        $('#item_height').attr('required', 'required');
    })


    $('#uom_id').select2()
    $('#Item_Category_Group').select2()
    $('#Default_Warehouse_Id').select2()


    // ------------------------------------ START FORM VALIDATION
    const MainForm = $('#main-form');
    const BtnSubmit = $('#btn-submit');
    MainForm.validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
    $.validator.setDefaults({
        debug: true,
        success: 'valid'
    });

    $(BtnSubmit).click(function (e) {
        e.preventDefault();
        if (MainForm.valid()) {
            Swal.fire({
                title: 'Loading....',
                html: '<div class="spinner-border text-primary"></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            Fn_Submit_Form(MainForm)
        } else {
            $('html, body').animate({
                scrollTop: ($('.error:visible').offset().top - 200)
            }, 400);
        }
    });

    function Fn_Submit_Form() {
        BtnSubmit.prop("disabled", true);
        var formDataa = new FormData(MainForm[0]);
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/update",
            data: formDataa,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.close()
                if (response.code == 200) {
                    $(MainForm)[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.msg,
                        showCancelButton: false,
                    }).then((result) => {
                        return window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/Item";
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.msg,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Confirm!',
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                }
            },
            error: function (xhr, status, error) {
                var statusCode = xhr.status;
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
                });
            }
        });
    }
    // ------------------------------------ END FORM VALIDATION

    const DetailForm = $('#detail-form');
    const BtnSubmitDtl = $('#submit-detail');
    DetailForm.validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
    $.validator.setDefaults({
        debug: true,
        success: 'valid'
    });

    $(BtnSubmitDtl).click(function (e) {
        e.preventDefault();
        if (DetailForm.valid()) {
            Swal.fire({
                title: 'Loading....',
                html: '<div class="spinner-border text-primary"></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            Fn_Submit_Form_Detail(DetailForm)
        } else {
            $('html, body').animate({
                scrollTop: ($('.error:visible').offset().top - 200)
            }, 400);
        }
    });

    function Fn_Submit_Form_Detail() {
        BtnSubmitDtl.prop("disabled", true);
        var formDataa = new FormData(DetailForm[0]);
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/post_barcode_setting",
            data: formDataa,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.close()
                if (response.code == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.msg,
                        showCancelButton: false,
                    }).then((result) => {
                        return window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/Item";
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.msg,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Confirm!',
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                }
                BtnSubmitDtl.prop("disabled", false);
            },
            error: function (xhr, status, error) {
                var statusCode = xhr.status;
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
                });
            }
        });
    }
})