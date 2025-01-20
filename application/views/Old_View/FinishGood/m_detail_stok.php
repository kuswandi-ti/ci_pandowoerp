<div class="modal fade" id="modal-detail-stok" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">List Stok Product : <?= $row_product->Nama ?> (<?= $row_product->Kode ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="product_code" value="<?= $row_product->Kode ?>">
                <div class="table-responsive">
                    <table id="DataTable-stok" class="table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white pt-2 pb-2">#</th>
                                <th class="text-center text-white pt-2 pb-2">CUSTOMER</th>
                                <th class="text-center text-white pt-2 pb-2">PRODUCT CODE</th>
                                <th class="text-center text-white pt-2 pb-2">PRODUCT NAME</th>
                                <th class="text-center text-white pt-2 pb-2">CHECKER</th>
                                <th class="text-center text-white pt-2 pb-2">LEADER</th>
                                <th class="text-center text-white pt-2 pb-2">DATE PRD</th>
                                <th class="text-center text-white pt-2 pb-2">BARCODE NUMBER</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- hi dude i dude some magic here -->
                        </tbody>
                    </table>
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
        function Init_Datatable_Stok() {
            $("#DataTable-stok").DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                paging: true,
                dom: 'lBfrtip',
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
                },
                orderCellsTop: true,
                fixedHeader: {
                    header: true,
                    headerOffset: 48
                },
                "lengthMenu": [
                    [15, 100, 300, 500, 1000],
                    [15, 100, 300, 500, 1000]
                ],
                ajax: {
                    url: $('meta[name="base_url"]').attr('content') + "FinishGood/DataTable_Stok_Product",
                    dataType: "json",
                    type: "POST",
                    data: {
                        product_code: $('#product_code').val()
                    }
                },
                columns: [{
                        data: "SysId",
                        name: "SysId",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "Customer_Name",
                        name: "Customer_Name",
                    },
                    {
                        data: "Product_Code",
                        name: "Product_Code",
                    },
                    {
                        data: "Product_Name",
                        name: "Product_Name",
                    },
                    {
                        data: "Checker_Rakit",
                        name: "Checker_Rakit",
                    },
                    {
                        data: "Leader_Rakit",
                        name: "Leader_Rakit",
                    },
                    {
                        data: "Date_Prd",
                        name: "Date_Prd",
                    },
                    {
                        data: "Barcode_Value",
                        name: "Barcode_Value",
                    }
                ],
                order: [
                    [7, 'DESC']
                ],
                columnDefs: [{
                    className: "align-middle text-center",
                    targets: [0, 1, 2, 3, 4, 5, 6, 7],
                }],
                // autoWidth: false,
                responsive: true,
                preDrawCallback: function() {
                    $("#DataTable-stok tbody td").addClass("blurry");
                },
                language: {
                    processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
                },
                drawCallback: function() {
                    $("#DataTable-stok tbody td").addClass("blurry");
                    setTimeout(function() {
                        $("#DataTable-stok tbody td").removeClass("blurry");
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                },
                "buttons": ["copy",
                    {
                        extend: 'csvHtml5',
                        title: $('#modal-title').text() + ` / ` + moment().format("YYYY-MM-DD"),
                        className: "btn btn-info",
                    }, {
                        extend: 'excelHtml5',
                        title: $('#modal-title').text() + ` / ` + moment().format("YYYY-MM-DD"),
                        className: "btn btn-success",
                    }, {
                        extend: 'pdfHtml5',
                        title: $('#modal-title').text() + ` / ` + moment().format("YYYY-MM-DD"),
                        className: "btn btn-danger",
                        orientation: "landscape",
                        customize: function(doc) {
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            doc.styles['td:nth-child(2)'] = {
                                width: '30px',
                                'max-width': '30px'
                            }
                            doc.content.splice(0, 1);
                            doc.pageMargins = [20, 40, 20, 30];
                            doc.defaultStyle.fontSize = 7;
                            doc.styles.tableHeader.fontSize = 7;
                            doc['header'] = (function() {
                                return {
                                    columns: [{
                                        alignment: 'center',
                                        // italics: true,
                                        text: $('#modal-title').text() + ` / ` + moment().format("YYYY-MM-DD"),
                                        fontSize: 14,
                                        margin: [10, 0]
                                    }],
                                    margin: 20
                                }
                            });
                            doc['footer'] = (function(page, pages) {
                                return {
                                    columns: [
                                        '',
                                        {
                                            alignment: 'right',
                                            text: [{
                                                    text: page.toString(),
                                                    italics: true
                                                },
                                                ' of ',
                                                {
                                                    text: pages.toString(),
                                                    italics: true
                                                }
                                            ]
                                        }
                                    ],
                                    margin: [10, 0]
                                }
                            });
                            var objLayout = {};
                            objLayout['hLineWidth'] = function(i) {
                                return .5;
                            };
                            objLayout['vLineWidth'] = function(i) {
                                return .5;
                            };
                            objLayout['hLineColor'] = function(i) {
                                return '#aaa';
                            };
                            objLayout['vLineColor'] = function(i) {
                                return '#aaa';
                            };
                            objLayout['paddingLeft'] = function(i) {
                                return 4;
                            };
                            objLayout['paddingRight'] = function(i) {
                                return 4;
                            };
                            doc.content[0].layout = objLayout;
                        }
                    }, "print"
                ],
            }).buttons().container().appendTo('#DataTable-stok .col-md-6:eq(0)');
        }

        Init_Datatable_Stok();
    })
</script>