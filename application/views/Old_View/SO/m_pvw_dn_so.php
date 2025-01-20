<div class="modal fade" id="m_preview_so_dn" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">NO.Sales Order : <?= $Hdr->No_Po_Customer ?> (<?= $Hdr->Doc_No_Internal ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="table-responsive">
                                <input type="hidden" value="<?= $Hdr->Doc_No_Internal ?>" id="SO">
                                <table id="TableData_DN" class="tbl-xs table-bordered table-hover display compact table-valign-middle" style="width: 100%;">
                                    <thead style="background-color: #3B6D8C;">
                                        <tr>
                                            <th class="text-center text-white">#</th>
                                            <th class="text-center text-white">#</th>
                                            <th class="text-center text-white">DN Number</th>
                                            <th class="text-center text-white">Customer Code</th>
                                            <th class="text-center text-white">Customer Name</th>
                                            <th class="text-center text-white">No.PO</th>
                                            <th class="text-center text-white">No.SO</th>
                                            <th class="text-center text-white">Product Code</th>
                                            <th class="text-center text-white">Product Name</th>
                                            <th class="text-center text-white">Qty</th>
                                            <th class="text-center text-white">Uom</th>
                                            <th class="text-center text-white">No. Loading</th>
                                            <th class="text-center text-white">Send_Date</th>
                                            <th class="text-center text-white">Address</th>
                                            <th class="text-center text-white">Att To</th>
                                            <th class="text-center text-white">Vehicle Police Number</th>
                                            <th class="text-center text-white">Driver Name</th>
                                            <th class="text-center text-white">Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- hi dude i dude some magic here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(function() {
        var TableDataDn = $("#TableData_DN").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            paging: true,
            select: true,
            "responsive": true,
            dom: 'lBfrtip',
            "oLanguage": {
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
            },
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "SalesOrder/DT_Dn_So",
                dataType: "json",
                type: "post",
                data: {
                    SO: $('#SO').val()
                }
            },
            columns: [{
                    data: "SysId_Hdr",
                    name: "SysId_Hdr",
                    orderable: false,
                    visible: false,
                },
                {
                    data: "SysId_Dtl",
                    name: "SysId_Dtl",
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<div class="btn btn-group">
						<a href="${$('meta[name="base_url"]').attr('content')}CompleteDN/Print_DN/${row.DN_Number}" target="_blank" data-toggle="tooltip" title="Print Dn" class="btn bg-gradient-danger btn-sm btn-print" data-pk="${data}"><i class="fas fa-print"></i></a>
						</div>`
                    }
                },
                {
                    data: "DN_Number",
                    name: "DN_Number",
                    render: function(data, type, row, meta) {
                        return `<pre>${data}</pre>`
                    }
                },
                {
                    data: "Customer_Code",
                    name: "Customer_Code",
                },
                {
                    data: "Customer_Name",
                    name: "Customer_Name",
                    visible: false,
                },
                {
                    data: "No_PO_Customer",
                    name: "No_PO_Customer",
                    render: function(data, type, row, meta) {
                        return `<pre>${data}</pre>`
                    }
                },
                {
                    data: "No_PO_Internal",
                    name: "No_PO_Internal",
                    render: function(data, type, row, meta) {
                        return `<pre>${data}</pre>`
                    }
                },
                {
                    data: "Product_Code",
                    name: "Product_Code",
                    visible: false,
                },
                {
                    data: "Product_Name",
                    name: "Product_Name",
                },
                {
                    data: "Qty",
                    name: "Qty",
                },
                {
                    data: "Uom",
                    name: "Uom",
                },
                {
                    data: "No_Loading",
                    name: "No_Loading",
                    render: function(data, type, row, meta) {
                        if (data == null || data == '') {
                            return `<span class="badge badge-danger">Not Yet Loading</span>`
                        } else {
                            return `<a href="javascript:void(0)" class="text-primary font-weight-bold detail-loading">${data}</a>`;
                        }
                    }
                },
                {
                    data: "Send_Date",
                    name: "Send_Date",
                },
                {
                    data: "Complete_Address",
                    name: "Complete_Address",
                    render: function(data, type, row, meta) {
                        return `<pre>${data}</pre>`
                    }
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
            "order": [
                [2, "desc"]
            ],
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1, 2, 3, 4, , 5, 7, 8, 9, 10, 11, 13, 14, 15, 16],
                },
                {
                    className: "text-left",
                    targets: [13]
                }
            ],
            autoWidth: false,
            preDrawCallback: function() {
                $("#TableData_DN tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#TableData_DN tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#TableData_DN tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
            "buttons": ["copy",
                {
                    extend: 'csvHtml5',
                    title: `List Delivery Note, SO : ${$('#modal-title').text()}` + '~' + moment().format('LL'),
                    className: "btn btn-info",
                }, {
                    extend: 'excelHtml5',
                    title: `List Delivery Note, SO : ${$('#modal-title').text()}` + '~' + moment().format('LL'),
                    className: "btn btn-success",
                }, {
                    extend: 'print',
                    title: `List Delivery Note, SO : ${$('#modal-title').text()}` + '~' + moment().format('LL'),
                    className: "btn btn-danger",
                }
            ],
        }).buttons().container().appendTo('#TableData_DN .col-md-6:eq(0)');
    })
</script>