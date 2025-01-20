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
        // select: true,
        ajax: {
            url: $('meta[name="base_url"]').attr('content') + "CheckGrading/DataTable_monitoring_grading",
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
            data: "lpb",
            name: "lpb",
        },
        {
            data: "supplier",
            name: "supplier",
        },
        {
            data: "tgl_kirim",
            name: "tgl_kirim",
        },
        {
            data: "tgl_finish_sortir",
            name: "tgl_finish_sortir",
            render: function (data, type, row, meta) {
                if (row.tgl_finish_sortir == '0000-00-00') {
                    return null;
                } else {
                    return row.tgl_finish_sortir;
                }
            }
        },
        {
            data: "grader",
            name: "grader",
        },
        {
            data: "lot",
            name: "lot",
            searchable: false,
            orderable: false,
        },
        {
            data: "legalitas",
            name: "legalitas",
            searchable: false,
            orderable: false,
        },
        {
            data: "status_lpb",
            name: "status_lpb",
            render: function (data, type, row, meta) {
                return `<button class="btn btn-xs btn-danger"><i>${row.status_lpb}</i></button`;
            }
        },
        {
            data: null,
            name: "handle",
            searchable: false,
            orderable: false,
            render: function (data, type, row, meta) {
                if (row.lot_printed == '0') {
                    return `<button class="btn btn-warning btn-flat btn-xs blink_me">${row.lot_printed} lot print</button> &nbsp; 
                    <a href="${$('meta[name="base_url"]').attr('content')}CheckGrading/check_detail_lpb/${row.lpb}" class="btn btn-xs bg-gradient-primary" data-toggle="tooltip" title="Isi Detail LPB">&nbsp;<i class="far fa-edit"></i>&nbsp;</a>&nbsp;
                    <button class="btn btn-xs bg-gradient-danger btn-delete" data-pk="${row.lpb}" data-toggle="tooltip" title="Delete">&nbsp;<i class="far fa-trash-alt"></i>&nbsp;</button>`;
                } else {
                    return `<button class="btn btn-success btn-flat btn-xs blink_me">${row.lot_printed} lot print</button> &nbsp; 
                    <a href="${$('meta[name="base_url"]').attr('content')}CheckGrading/check_detail_lpb/${row.lpb}" class="btn btn-xs bg-gradient-primary" data-toggle="tooltip" title="Detail LPB">&nbsp;<i class="far fa-edit"></i>&nbsp;</a>`;
                }
            }
        },
        ],
        order: [
            [0, "desc"]
        ],
        columnDefs: [{
            className: "text-center",
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        },
        {
            className: "text-left",
            targets: []
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
        },
    });

    $(document).on('click', '.btn-delete', function () {
        var lpb = $(this).attr('data-pk');
        Swal.fire({
            title: 'Hapus data ?',
            text: "data yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: $('meta[name="base_url"]').attr('content') + "CheckGrading/delete_lpb",
                    data: {
                        lpb: lpb
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
                            TableData.ajax.reload(null, false)
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.msg
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
});
