<div class="modal fade" id="modal-dn-outstanding" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 89%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">List Complete Delivery Note, Outstanding Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <input type="hidden" name="customer_code" id="customer_code" value="<?= $customer_code ?>">
                    <table id="tbl-list-outstanding-invoice" class="table table-sm table-bordered table-striped table-valign-middle" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">DN Number</th>
                                <th class="text-center text-white">SO Number</th>
                                <th class="text-center text-white">No.PO Customer</th>
                                <th class="text-center text-white">Cust Code</th>
                                <th class="text-center text-white">Customer Name</th>
                                <th class="text-center text-white">Send Date</th>
                                <th class="text-center text-white">Customer Address</th>
                                <th class="text-center text-white">ATT to</th>
                                <th class="text-center text-white">Vehicle Police Number</th>
                                <th class="text-center text-white">Driver</th>
                                <th class="text-center text-white">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="choose-dn"><i class="fas fa-check"></i> Choose Delivery Note</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        Swal.close()

        var TableData = $("#tbl-list-outstanding-invoice").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            select: true,
            paging: true,
            orderCellsTop: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "CompleteDN/DT_List_Outstanding_Dn_vs_Invoice",
                dataType: "json",
                type: "POST",
                data: {
                    customer_code: $('#customer_code').val()
                }
            },
            columns: [{
                    data: "SysId_Hdr_DN",
                    name: "SysId_Hdr_DN",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "DN_Number",
                    name: "DN_Number",
                },
                {
                    data: "SO_Number",
                    name: "SO_Number",
                },
                {
                    data: "No_PO_Customer",
                    name: "No_PO_Customer",
                },
                {
                    data: "Customer_Code",
                    name: "Customer_Code",
                },
                {
                    data: "Customer_Name",
                    name: "Customer_Name",
                },
                {
                    data: "Send_Date",
                    name: "Send_Date",
                },
                {
                    data: "Complete_Address",
                    name: "Complete_Address",
                },
                {
                    data: "Att_To",
                    name: "Att_To",
                },
                {
                    data: "Vehicle_Police_Number",
                    name: "Vehicle_Police_Number",
                },
                {
                    data: "Driver_Name",
                    name: "Driver_Name",
                },
                {
                    data: "Remark",
                    name: "Remark",
                },
            ],
            order: [
                [1, 'ASC']
            ],
            columnDefs: [{
                className: "align-middle text-center",
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            }],
            // autoWidth: false,
            responsive: true,
            preDrawCallback: function() {
                $("#tbl-list-outstanding-invoice tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#tbl-list-outstanding-invoice tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#tbl-list-outstanding-invoice tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        })

        $(document).on('click', '#choose-dn', function() {
            var rowData = TableData.rows({
                selected: true
            }).data()[0];

            if (rowData == undefined || rowData.length == 0) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'You need select Delivery Note !',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
            }

            $('#dn_id').val(rowData.SysId_Hdr_DN);
            $('#DN').val(rowData.DN_Number);
            $('#id_po').val(rowData.SysId_Hdr_SO)
            $('#no_po_customer').val(rowData.No_PO_Customer);
            $('#no_po_internal').val(rowData.SO_Number);
            $('#NPWP').val(rowData.NPWP);
            $('#id_address').val(rowData.SysId_Address);
            $('#customer_address').val(rowData.Complete_Address);


            $('#modal-dn-outstanding').modal('hide');
        })
    })
</script>