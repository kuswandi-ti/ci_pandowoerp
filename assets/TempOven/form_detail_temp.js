$(document).ready(function () {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        width: 300,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    $('.onlyfloat').keypress(function (event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        var input = $(this).val();
        if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
            event.preventDefault();
        }
    });

    $(".readonly").keydown(function (event) {
        return false;
    });

    $('#Tgl').datetimepicker({
        format: 'YYYY-MM-DD',
        autoclose: true,
        allowClear: true,
        todayHighlight: true,
        orientation: 'bottom',
        showClose: true,
        buttons: {
            showClose: true,
        }
    });

    $('#JamMenit').datetimepicker({
        format: 'HH:mm',
        autoclose: true,
        allowClear: true,
        todayHighlight: true,
        orientation: 'bottom',
        buttons: {
            showClose: true,
        }
    })

    // ----------------------- DATATABLE

    function initialize_TableData_Oven(sysid_hdr) {
        $("#DataTable-Temperature").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            // select: true,
            "searching": false,
            dom: 'lfrtip',
            "oLanguage": {
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
            },
            "aLengthMenu": [[25, 500, 999], [25, 500, 999]],
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/DataTable_Temperature_Oven",
                dataType: "json",
                type: "GET",
                data: {
                    sysid_hdr: sysid_hdr
                }
            },
            columns: [{
                data: 'SysId',
                name: "SysId",
                orderable: false,
                render: function (data, type, row, meta) {
                    return `<button class="btn btn-xs bg-gradient-danger delete"><i class="fas fa-trash"></i></button>`;
                }
            }, {
                data: "Date",
                name: "Date",
            }, {
                data: "Time",
                name: "Time",
            }, {
                data: "KADAR_AIR_MC1",
                name: "KADAR_AIR_MC1",
            }, {
                data: "KADAR_AIR_MC2",
                name: "KADAR_AIR_MC2",
            }, {
                data: "KADAR_AIR_MC3",
                name: "KADAR_AIR_MC3",
            }, {
                data: "SIK_T1",
                name: "SIK_T1",
            }, {
                data: "SIK_T2",
                name: "SIK_T2",
            }, {
                data: "SIK_T3",
                name: "SIK_T3",
            }, {
                data: "BOILER_SET",
                name: "BOILER_SET",
            }, {
                data: "BOILER_ACT",
                name: "BOILER_ACT",
            }, {
                data: "DRY_BULB_SET",
                name: "DRY_BULB_SET",
            }, {
                data: "DRY_BULB_ACT",
                name: "DRY_BULB_ACT",
            }, {
                data: "WET_BULD_SET",
                name: "WET_BULD_SET",
            }, {
                data: "WET_BULD_ACT",
                name: "WET_BULD_ACT",
            }, {
                data: "Keterangan",
                name: "Keterangan",
            }, {
                data: "nama",
                name: "nama",
            }],
            order: [
                [1, "desc"]
            ],
            columnDefs: [{
                className: "text-center",
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
            }],
            autoWidth: false,
            preDrawCallback: function () {
                $("#DataTable tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function () {
                $("#DataTable tbody td").addClass("blurry");
                setTimeout(function () {
                    $("#DataTable tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
            // "buttons": ["copy",
            //     {
            //         extend: 'csvHtml5',
            //         title: $('title').text() + '_' + moment().format('LL'),
            //         className: "btn btn-info",
            //     }, {
            //         extend: 'excelHtml5',
            //         title: $('title').text() + '_' + moment().format('LL'),
            //         className: "btn btn-success",
            //     }, {
            //         extend: 'pdfHtml5',
            //         title: $('title').text() + '_' + moment().format('LL'),
            //         className: "btn btn-danger",
            //     }, "print"],
        }).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
    }

    initialize_TableData_Oven($('#sysid_hdr').val());

    // ---------------SCRIPT NEED TRIGGER
    $('#form_add').validate({
        rules: {
            temperature: {
                required: true,
            }
        },
        messages: {
            temperature: {
                required: "temperature tidak boleh kosong!",
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
        if ($("#form_add").valid()) {
            Fn_store_add_form($('#form_add').serialize());
        } else {
            $('html, body').animate({
                scrollTop: ($('.error:visible').offset().top - 200)
            }, 400);
        }
    });

    function Fn_store_add_form(DataForm) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/store_temperature",
            data: DataForm,
            beforeSend: function () {
                $("#submit-form").prop("disabled", true);
                $("#submit-form").html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ...`
                )
            },
            success: function (response) {
                if (response.code == 200) {
                    Toast.fire({
                        icon: 'success',
                        title: response.msg
                    });
                    // $('.select2').val(null).trigger('change');
                    $('#form_add')[0].reset();
                    $("#submit-form").prop("disabled", false);
                    $("#submit-form").html(
                        `<i class="fas fa-download"></i>`
                    )
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.msg,
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                }
                $("#DataTable-Temperature").DataTable().ajax.reload(null, false)
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
            }
        });
    }

    $('#DataTable-Temperature tbody').on('click', 'button.delete', function () {
        let data = $('#DataTable-Temperature').DataTable().row($(this).parents('tr')).data();

        Swal.fire({
            title: 'System Message!',
            text: `Apakah anda yakin untuk menghapus data temperatur ini ?`,
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
                    url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/delete_temperature",
                    data: {
                        SysId: data['SysId'],
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
                            Toast.fire({
                                icon: 'success',
                                title: response.msg
                            });
                            $("#DataTable-Temperature").DataTable().ajax.reload(null, false)
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Ooops...',
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

    $('#btn-off').on('click', function () {
        Swal.fire({
            title: 'System Message!',
            text: `Apakah anda yakin untuk mengakhiri pencatatn temperatur oven ?`,
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
                    url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/Set_Oven_To_Off",
                    data: {
                        SysId: $('#sysid_hdr').val(),
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
                                title: 'Success...',
                                text: response.msg,
                                footer: '<a href="javascript:void(0)" class="text-info">Notifikasi System IMP-SYS</a>'
                            }).then((result) => {
                                if (result.isConfirmed === true) {
                                    window.location.href = $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/Monitoring_history_temp_oven";
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Ooops...',
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