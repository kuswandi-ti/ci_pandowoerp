<div class="modal fade" id="modal-list-karyawan" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $title ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-modal-list-karyawan" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">NIK</th>
                                    <th class="text-center text-white">NAMA</th>
                                    <th class="text-center text-white">INIT</th>
                                    <th class="text-center text-white">DEPARTMENT</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submit--add"><i class="fas fa-user-plus"></i> Pilih</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    $(document).ready(function() {
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
        });

        var TableData = $("#tbl-modal-list-karyawan").DataTable({
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
                    render: function(data, type, row, meta) {
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
            preDrawCallback: function() {
                $("#tbl-modal-list-karyawan tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#tbl-modal-list-karyawan tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#tbl-modal-list-karyawan tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
        });

        $(document).on('click', '#submit--add', function() {
            var rowData = TableData.rows({
                selected: true
            }).data()[0];

            if (rowData == undefined) {
                Toast.fire({
                    icon: 'error',
                    title: 'pilih data karyawan yang akan di jadikan checker!'
                });
            } else {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: $('meta[name="base_url"]').attr('content') + "Master/add_authority_checker",
                    data: {
                        nik: rowData.nik,
                        initial: rowData.initial
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Loading....',
                            html: '<div class="spinner-border text-primary"></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        })
                    },
                    success: function(response) {
                        Swal.close()
                        if (response.code == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success...',
                                text: response.msg,
                                footer: '<a href="javascript:void(0)" class="text-info">Notifikasi System</a>'
                            });
                            $("#tbl-master-karyawan").DataTable().row.add([
                                '#',
                                rowData.nik,
                                rowData.nama,
                                rowData.initial,
                                `<button class="btn btn-xs bg-gradient-danger btn-delete" data-pk="${rowData.nik}" data-toggle="tooltip" title="Delete authority checker"><i class="fas fa-trash"></i></button>`
                            ]).draw(false);
                            $('#modal-list-karyawan').modal('hide');
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Warning!',
                                text: response.msg,
                                footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                            });
                        }
                    },
                    error: function() {
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
</script>