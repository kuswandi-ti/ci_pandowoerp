$(function () {
    $.validator.setDefaults({
        submitHandler: function () {
            Fn_store_login($('#username').val(), $('#password').val());
        }
    });

    $('#form-login').validate({
        rules: {
            username: {
                required: true,
            },
            password: {
                required: true,
                minlength: 5
            }
        },
        messages: {
            username: {
                required: "Username tidak boleh kosong!",
            },
            password: {
                required: "Password tidak boleh kosong!",
                minlength: "password anda minimal 5 karakter!"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.input-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    function Fn_store_login(u, p) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: "Auth/post_login",
            data: {
                u: u,
                p: p
            },
            beforeSend: function () {
                $("#btn--login").prop("disabled", true);
                $("#btn--login").html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                )
            },
            success: function (response) {
                if (response.code == 200) {
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: 'Success...',
                        timer: 1500,
                        timerProgressBar: true,
                        text: response.msg,
                        didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                            timerInterval = setInterval(() => {
                                b.textContent = Swal.getTimerLeft()
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        },
                        footer: '<a href="javascript:void(0)" class="text-info">Notifikasi System IMP-SYS</a>'
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = "Dashboard";
                        }
                    })
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: response.msg,
                        footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                    });
                    $("#btn--login").prop("disabled", false);
                    $('#btn--login').html(`<i class="fas fa-sign-in-alt"></i> Sign-In`);
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
                $("#btn--login").prop("disabled", false);
                $('#btn--login').html(`<i class="fas fa-sign-in-alt"></i> Sign-In`);
            }
        });
    }
    $('#username').focus();
});