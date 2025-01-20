$(document).ready(function () {
    $(document).on('click', '.is-active', function () {
        var this_is = $(this);
        Swal.fire({
            title: 'System Message!',
            text: `Apakah anda yakin untuk merubah status legalitas ini ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: $('meta[name="base_url"]').attr('content') + "Master/toggle_status_legalitas",
                    data: {
                        sysid: $(this).attr('data-pk')
                    },
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
                                footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                            });
                            setTimeout(function () {
                                location.reload()
                            }, 2500);
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Warning!',
                                text: response.msg,
                                footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
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
    })
})