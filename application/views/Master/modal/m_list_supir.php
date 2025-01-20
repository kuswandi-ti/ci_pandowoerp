<div class="modal fade" id="modal-list-driver" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 60%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">List Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbl-list-driver" class="table table-sm table-bordered table-striped table-valign-middle" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">NIK</th>
                                <th class="text-center text-white">Name</th>
                                <th class="text-center text-white">Initial</th>
                                <th class="text-center text-white">Position</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit--add--driver"><i class="fas fa-check"></i> Choose Address</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        Swal.close()

        var TableData = $("#tbl-list-driver").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            select: true,
            paging: true,
            orderCellsTop: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Master/DT_List_Driver",
                dataType: "json",
                type: "POST",
            },
            columns: [{
                    data: "sysid",
                    name: "sysid",
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
                    data: "jabatan",
                    name: "jabatan",
                }
            ],
            order: [
                [2, 'ASC']
            ],
            columnDefs: [{
                className: "align-middle text-center",
                targets: [0, 1, 2, 3, 4],
            }],
            // autoWidth: false,
            responsive: true,
            preDrawCallback: function() {
                $("#tbl-list-driver tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#tbl-list-driver tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#tbl-list-driver tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        })

        $(document).on('click', '#submit--add--driver', function() {
            var rowData = TableData.rows({
                selected: true
            }).data()[0];

            console.log(rowData)

            $('#init_driver').val(rowData.initial);
            $('#nama_driver').val(rowData.nama);

            $('#modal-list-driver').modal('hide');
        })
    })
</script>