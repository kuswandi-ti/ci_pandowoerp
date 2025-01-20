<div class="modal fade" id="modal-list-po" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 80%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">List SO Outstanding</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbl-list-po" class="table table-sm table-bordered table-striped table-valign-middle" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">No. So</th>
                                <th class="text-center text-white">No. PO Customer</th>
                                <th class="text-center text-white">Status</th>
                                <th class="text-center text-white">Cust. Kode</th>
                                <th class="text-center text-white">Cust. Name</th>
                                <th class="text-center text-white">Tanggal Terbit</th>
                                <th class="text-center text-white">Term of Payment</th>
                                <th class="text-center text-white">Remark TOP</th>
                                <th class="text-center text-white">Term of Delivery</th>
                                <th class="text-center text-white">Customer Address</th>
                                <th class="text-center text-white">Koresponden</th>
                                <th class="text-center text-white">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="Customer_Code" id="Customer_Code" value="<?= $Customer_Code ?>">
                <button type="button" class="btn btn-primary" id="submit--add--po"><i class="fas fa-check"></i> Choose PO. Number</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        Swal.close()

        var TableData = $("#tbl-list-po").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            select: true,
            paging: true,
            orderCellsTop: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "SalesOrder/DT_SO_Outstanding_Customer_ID",
                dataType: "json",
                type: "POST",
                data: {
                    id_customer: $('#id_customer').val()
                }
            },
            columns: [{
                    data: "SO_SysId_Hdr",
                    name: "SO_SysId_Hdr",
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: "SO_Number",
                    name: "SO_Number"
                }, {
                    data: "No_Po_Customer",
                    name: "No_Po_Customer"
                },
                {
                    data: "Status_SO",
                    name: "Status_SO",
                    orderable: false,
                    render: function(data, type, row, meta) {
                        if (data == 'OPEN') {
                            return `<a href="javascript:void(0)" class="btn btn-success blink_me">OPEN</a>`;
                        } else {
                            return `<a href="javascript:void(0)" class="btn btn-dark">CLOSE</a>`;
                        }
                    }
                },
                {
                    data: "Customer_Code",
                    name: "Customer_Code"
                },
                {
                    data: "Customer_Name",
                    name: "Customer_Name"
                },
                {
                    data: "Tgl_Terbit",
                    name: "Tgl_Terbit"
                },
                {
                    data: "Term_Of_Payment",
                    name: "Term_Of_Payment",
                    visible: false,
                },
                {
                    data: "Remark_TOP",
                    name: "Remark_TOP",
                    visible: false,
                },
                {
                    data: "Term_Of_Delivery",
                    name: "Term_Of_Delivery"
                },
                {
                    data: "Customer_Address",
                    name: "Customer_Address"
                },
                {
                    data: "Koresponden",
                    name: "Koresponden",
                },
                {
                    data: "Note",
                    name: "Note"
                }
            ],
            "order": [
                [9, "ASC"]
            ],
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1, 2, 3, 4, , 5, 7, 8, 9, 10, 11, 12],
                },
                {
                    className: "text-left",
                    targets: []
                }, {
                    targets: 4,
                    "createdCell": function(td, cellData, rowData, row, col) {
                        if (rowData.Status_PO == 'CLOSE') {
                            $(td).css('background-color', 'black')
                        }
                    }
                },
            ],
            // autoWidth: false,
            responsive: true,
            preDrawCallback: function() {
                $("#tbl-list-po tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#tbl-list-po tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#tbl-list-po tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        })
        $(document).on('click', '#submit--add--po', function() {
            var rowData = TableData.rows({
                selected: true
            }).data()[0];
            console.log(rowData);

            $('#id_po').val(rowData.SO_SysId_Hdr);
            $('#no_po_customer').val(rowData.No_Po_Customer);
            $('#no_po_internal').val(rowData.SO_Number);
            $('#id_address').val(rowData.ID_Address);
            $('#customer_address').val(rowData.Customer_Address);

            $('#modal-list-po').modal('hide');
        })
    })
</script>