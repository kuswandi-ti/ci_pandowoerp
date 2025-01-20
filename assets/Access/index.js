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

    $(".readonly").keydown(function (event) {
        return false;
    });

    // ======================== SCRIPT NEEED TRIGGER =============================//
    $(document).on('click', '#btn-select-user', function () {
        var rowData = tbl_mst_karyawan.rows({
            selected: true
        }).data()[0];

        $('#nik').val(rowData.nik);
        $('#nama').val(rowData.nama);
        $('#init').val(rowData.initial);
        $('#dept').val(rowData.department);
        Initialize_DataTable_Access_Menu(rowData.nik);
        $('#modal-list-karyawan').modal('hide')
    });

    $(document).on('click', 'tbody .change-access', function () {
        var data_row = $("#DataTable-Access").DataTable().row($(this).closest('tr')).data();
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "Access/toggle_access",
            data: {
                nik_input: $('#nik').val(),
                row_nik: data_row.nik,
                row_sysid_group: data_row.sysid_group,
                row_sysid_parent: data_row.sysid_parent,
                row_sysid_child: data_row.sysid_child,
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
                    $("#DataTable-Access").DataTable().ajax.reload(null, false)
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
    })

    // ------------------------- DATATABLE ---------------------------//
    var tbl_mst_karyawan = $("#tbl-modal-list-karyawan").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: {
            style: 'single'
        },
        ajax: {
            url: $('meta[name="base_url"]').attr('content') + "Master/DataTable_modal_list_karyawan",
            dataType: "json",
            type: "POST",
        },
        columns: [{
            data: "sysid",
            name: "sysid",
            visible: true,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: "nik",
            name: "nik",
        },
        {
            data: "nama",
            name: "nama",
        },
        {
            data: "initial",
            name: "initial",
        },
        {
            data: "department",
            name: "department",
        },

        ],
        order: [
            [2, "asc"]
        ],
        columnDefs: [{
            className: "text-center",
            targets: [0, 1, 2, 3, 4],
        }],
        autoWidth: false,
        responsive: true,
        preDrawCallback: function () {
            $("#tbl-modal-list-karyawan tbody td").addClass("blurry");
        },
        language: {
            processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
        },
        drawCallback: function () {
            $("#tbl-modal-list-karyawan tbody td").addClass("blurry");
            setTimeout(function () {
                $("#tbl-modal-list-karyawan tbody td").removeClass("blurry");
            });
            $('[data-toggle="tooltip"]').tooltip();
        },
    });

    function Initialize_DataTable_Access_Menu(nik) {
        $("#DataTable-Access").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            "pageLength": 15,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Access/Datatable_Access_User",
                dataType: "json",
                type: "POST",
                data: { "nik": nik }
            },
            columns: [{
                data: "sysid_group",
                name: "sysid_group",
                visible: true,
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                data: "label_group",
                name: "label_group",
            }, {
                data: "sysid_parent",
                name: "sysid_parent",
                visible: false,
                orderable: false,
            }, {
                data: "label_parent",
                name: "label_parent",
            }, {
                data: "sysid_child",
                name: "sysid_child",
                visible: false,
                orderable: false,
            }, {
                data: "label_child",
                name: "label_child",
            }, {
                data: "sysid",
                name: "sysid",
                visible: false,
                orderable: false,
            }, {
                data: "nik",
                name: "nik",
                orderable: false,
                render: function (data, type, row, meta) {
                    if (row.nik == null) {
                        return `<button class="change-access btn btn-xs bg-gradient-danger" data-nik="${nik}"><i class="fas fa-times-circle"></i></button>`;
                    } else {
                        return `<button class="change-access btn btn-xs bg-gradient-success" data-nik="${nik}"><i class="fas fa-check-circle"></i></button>`;
                    }
                }
            }
            ],
            order: [
                [1, "asc"]
            ],
            columnDefs: [{
                className: "text-center",
                targets: [0, 1, 2, 3, 4, 5, 6, 7],
            }],
            autoWidth: false,
            responsive: true,
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
            }
        })
    }
});