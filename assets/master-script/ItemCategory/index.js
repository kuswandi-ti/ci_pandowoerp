$(document).ready(function () {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    var TableData = $("#DataTable").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
        lengthMenu: [
            [10, 25, 50, 10000],
            [10, 25, 50, 'All']
        ],
        // select: true,
        ajax: {
            url: $('meta[name="base_url"]').attr('content') + "MasterData/ItemCategory/DT_item_category",
            dataType: "json",
            type: "POST",
        },
        columns: [{
            data: "Item_Category",
            name: "Item_Category",
        }, {
            data: "Item_Category_Init",
            name: "Item_Category_Init",
        }, {
            data: "Is_Prod",
            name: "Is_Prod",
            render: function (data, type, row, meta) {
                if (data == 1) {
                    return `<i class="fas fa-check text-success"></i>`
                } else {
                    return `<i class="fas fa-times text-danger"></i>`
                }
            }
        }, {
            data: "Is_Allocation",
            name: "Is_Allocation",
            render: function (data, type, row, meta) {
                if (data == 1) {
                    return `<i class="fas fa-check text-success"></i>`
                } else {
                    return `<i class="fas fa-times text-danger"></i>`
                }
            }
        }, {
            data: "Is_Asset",
            name: "Is_Asset",
            render: function (data, type, row, meta) {
                if (data == 1) {
                    return `<i class="fas fa-check text-success"></i>`
                } else {
                    return `<i class="fas fa-times text-danger"></i>`
                }
            }
        }, {
            data: "Is_So_Item",
            name: "Is_So_Item",
            render: function (data, type, row, meta) {
                if (data == 1) {
                    return `<i class="fas fa-check text-success"></i>`
                } else {
                    return `<i class="fas fa-times text-danger"></i>`
                }
            }
        }, {
            data: "Is_Po_Item",
            name: "Is_Po_Item",
            render: function (data, type, row, meta) {
                if (data == 1) {
                    return `<i class="fas fa-check text-success"></i>`
                } else {
                    return `<i class="fas fa-times text-danger"></i>`
                }
            }
        }, {
            data: "SysId",
            name: "SysId",
            render: function (data, type, row, meta) {
                return `<button type="button" class="btn btn-xs btn-warning btn-list" title="List Category Group" data-toggle="tooltip" data-pk="${data}"><i class="fas fa-list"></i> List Group</button>`

            }
        },
        ],
        order: [
            [1, "asc"]
        ],
        columnDefs: [{
            className: "text-center",
            targets: [0, 1, 2, 3, 4, 5, 6, 7],
        },
        {
            className: "text-left",
            targets: []
        }
        ],
        autoWidth: false,
        // responsive: true,
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
        initComplete: function (settings, json) {
            // ---------------
        },
        "buttons": [{
            text: `<i class="fas fa-plus fs-3"></i> Add Category`,
            className: "bg-primary",
            action: function (e, dt, node, config) {
                $('#ModalMainForm').modal('show');
            }
        }, {
            text: `<i class="fas fa-edit fs-3"></i>`,
            className: "btn disabled text-dark bg-white",
            action: function (e, dt, node, config) {
                var RowData = dt.rows({
                    selected: true
                }).data();
                // if (RowData.length == 0) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Ooppss...',
                //         text: 'Silahkan pilih data untuk melihat detail !',
                //         footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                //     });
                // } else {
                //     Init_Show_Detail(RowData[0].SysId)
                // }
            }
        }, {
            text: `<i class="fas fa-toggle-on"></i>`,
            className: "btn disabled text-dark bg-white",
            action: function (e, dt, node, config) {
                var RowData = dt.rows({
                    selected: true
                }).data();
                // if (RowData.length == 0) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Ooppss...',
                //         text: 'Silahkan pilih data untuk merubah status !',
                //         footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                //     });
                // } else {
                //     Fn_Toggle_Status(RowData[0].SysId)
                // }
            }
        }, {
            text: `Export to :`,
            className: "btn disabled text-dark bg-white",
        }, {
            text: `<i class="far fa-file-excel"></i>`,
            extend: 'excelHtml5',
            title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
            className: "btn btn-success",
        }, {
            text: `<i class="far fa-file-pdf"></i>`,
            extend: 'pdfHtml5',
            title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
            className: "btn btn-danger",
            orientation: "landscape"
        }],
    }).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');


    // ---------------------- END OF DATATABLE

    // ------------------------------------ START FORM VALIDATION
    const MainForm = $('#main-form');
    const BtnSubmit = $('#btn-submit-main');
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
            url: $('meta[name="base_url"]').attr('content') + "MasterData/ItemCategory/post",
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
                        $("#DataTable").DataTable().ajax.reload(null, false);
                        $('#ModalMainForm').modal('hide');
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
                BtnSubmit.prop("disabled", false);
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

    $(document).on('click', '.btn-list', function () {
        let SysId = $(this).attr('data-pk');
        $.ajax({
            type: "GET",
            url: $('meta[name="base_url"]').attr('content') + "MasterData/ItemCategory/append_modal_category_group",
            data: {
                SysId: SysId
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
                $('#location').html(response);
                $('#ModalList').modal('show');
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
    })
})