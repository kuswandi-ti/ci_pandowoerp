<div class="modal fade" id="modal-list-kendaraan" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 60%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">List Vehicle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbl-list-vehicle" class="table table-sm table-bordered table-striped table-valign-middle" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">Police Number</th>
                                <th class="text-center text-white">Status</th>
                                <th class="text-center text-white">Vehicle Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit--add--vehicle"><i class="fas fa-check"></i> Choose Address</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        Swal.close()

        var TableData = $("#tbl-list-vehicle").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            select: true,
            paging: true,
            orderCellsTop: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Master/DT_List_Vehicle",
                dataType: "json",
                type: "POST",
            },
            // <!-- SysId, No_Polisi, Status_Kepemilikan, Jenis -->
            columns: [{
                    data: "SysId",
                    name: "SysId",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "No_Polisi",
                    name: "No_Polisi",
                },
                {
                    data: "Status_Kepemilikan",
                    name: "Status_Kepemilikan",
                },
                {
                    data: "Jenis",
                    name: "Jenis",
                }
            ],
            order: [
                [1, 'ASC']
            ],
            columnDefs: [{
                className: "align-middle text-center",
                targets: [0, 1, 2, 3],
            }],
            // autoWidth: false,
            responsive: true,
            preDrawCallback: function() {
                $("#tbl-list-vehicle tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#tbl-list-vehicle tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#tbl-list-vehicle tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        })

        $(document).on('click', '#submit--add--vehicle', function() {
            var rowData = TableData.rows({
                selected: true
            }).data()[0];

            console.log(rowData)

            $('#id_kendaraan').val(rowData.SysId);
            $('#no_kendaraan').val(rowData.No_Polisi);

            $('#modal-list-kendaraan').modal('hide');
        })
    })
</script>