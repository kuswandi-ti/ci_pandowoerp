<div class="modal fade" id="modal-list-customer" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 60%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">List Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbl-list" class="table table-sm table-bordered table-striped table-valign-middle" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">Customer Code</th>
                                <th class="text-center text-white">Customer Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit--add"><i class="fas fa-check"></i> Choose Address</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        Swal.close()

        var TableData = $("#tbl-list").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            select: true,
            paging: true,
            orderCellsTop: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Master/DT_List_Customer",
                dataType: "json",
                type: "POST",
            },
            columns: [{
                    data: "SysId",
                    name: "SysId",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "Customer_Code",
                    name: "Customer_Code",
                },
                {
                    data: "Customer_Name",
                    name: "Customer_Name",
                }
            ],
            order: [
                [1, 'ASC']
            ],
            columnDefs: [{
                className: "align-middle text-center",
                targets: [0, 1, 2],
            }],
            // autoWidth: false,
            responsive: true,
            preDrawCallback: function() {
                $("#tbl-list tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#tbl-list tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#tbl-list tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        })

        $(document).on('click', '#submit--add', function() {
            var rowData = TableData.rows({
                selected: true
            }).data()[0];

            if (rowData == undefined || rowData.length == 0) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'You need select customer first!',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
            }

            $('#id_customer').val(rowData.SysId);
            $('#customer_code').val(rowData.Customer_Code);
            $('#customer_name').val(rowData.Customer_Name);

            $('#dn_id').val();
            $('#DN').val();
            $('#id_po').val()
            $('#no_po_customer').val();
            $('#no_po_internal').val();
            $('#NPWP').val();
            $('#id_address').val();
            $('#customer_address').val();


            $('#modal-list-customer').modal('hide');
        })
    })
</script>