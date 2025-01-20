$(document).ready(function () {
    $('#form_change_password').validate({
        rules: {
            password: {
                required: true,
            },
            password1: {
                equalTo: password2
            }
        },
        messages: {
            password: {
                required: "this field is required!",
            }
        },
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

    $('#submit-form').click(function (e) {
        e.preventDefault();
        if ($("#form_change_password").valid()) {
            Fn_change_password($('#form_change_password').serialize());
        } else {
            $('html, body').animate({
                scrollTop: ($('.error:visible').offset().top - 200)
            }, 400);
        }
    });

    function Fn_change_password(DataForm) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "User/store_change_password",
            data: DataForm,
            beforeSend: function () {
                Swal.fire({
                    title: 'Loading....',
                    html: '<div class="spinner-border text-primary"></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                })
            },
            success: function (response) {
                Swal.close()
                if (response.code == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.msg,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                    $('#form_change_password')[0].reset();
                    setTimeout(
                        function () {
                            window.location.href = $('meta[name="base_url"]').attr('content') + "Auth/logout";
                        }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.msg,
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                }
            },
            error: function () {
                Swal.close()
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
            }
        });
    }
})