<div class="modal fade" id="modal-detail-ttrx" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">History Transaksi Product : <?= $row_product->Nama ?> (<?= $row_product->Kode ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="product_code" value="<?= $row_product->Kode ?>">
                <div class="table-responsive">
                    <table id="DataTable-Ttrx" class="table-striped table-bordered display compact nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white pt-2 pb-2">#</th>
                                <th class="text-center text-white pt-2 pb-2">PRODUCT CODE</th>
                                <th class="text-center text-white pt-2 pb-2">QTY BEFORE</th>
                                <th class="text-center text-white pt-2 pb-2">ARITMATICS</th>
                                <th class="text-center text-white pt-2 pb-2">QTY TRANSACTION</th>
                                <th class="text-center text-white pt-2 pb-2">QTY AFTER</th>
                                <th class="text-center text-white pt-2 pb-2">REMARK</th>
                                <th class="text-center text-white pt-2 pb-2">TRANSACTION TIME</th>
                                <th class="text-center text-white pt-2 pb-2">TRANSACTION USER</th>
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
        function Init_Datatable_Trrx() {
            $("#DataTable-Ttrx").DataTable({
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
                    url: $('meta[name="base_url"]').attr('content') + "FinishGood/DataTable_Ttrx_FG",
                    dataType: "json",
                    type: "POST",
                    data: {
                        product_code: $('#product_code').val()
                    }
                },
                columns: [{
                        data: "sysid",
                        name: "sysid",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "ProductCode",
                        name: "ProductCode",
                    },
                    {
                        data: "old_stok",
                        name: "old_stok",
                    },
                    {
                        data: "aritmatics",
                        name: "aritmatics",
                    },
                    {
                        data: "qty_trans",
                        name: "qty_trans",
                    },
                    {
                        data: "new_stok",
                        name: "new_stok",
                    },
                    {
                        data: "remark",
                        name: "remark",
                    },
                    {
                        data: "do_at",
                        name: "do_at",
                    },
                    {
                        data: "do_by",
                        name: "do_by",
                    }
                ],
                order: [
                    [7, 'DESC']
                ],
                columnDefs: [{
                    className: "align-middle text-center",
                    targets: [0, 1, 3, 6, 7, 8],
                }],
                // autoWidth: false,
                responsive: true,
                preDrawCallback: function() {
                    $("#DataTable-Ttrx tbody td").addClass("blurry");
                },
                language: {
                    processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
                },
                drawCallback: function() {
                    $("#DataTable-Ttrx tbody td").addClass("blurry");
                    setTimeout(function() {
                        $("#DataTable-Ttrx tbody td").removeClass("blurry");
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                },
                "buttons": ["copy",
                    {
                        extend: 'csvHtml5',
                        title: $('#modal-title').text() + '~' + moment().format("YYYY-MM-DD"),
                        className: "btn btn-info",
                    }, {
                        extend: 'excelHtml5',
                        title: $('#modal-title').text() + '~' + moment().format("YYYY-MM-DD"),
                        className: "btn btn-success",
                    }, {
                        extend: 'pdfHtml5',
                        title: $('#modal-title').text() + '~' + moment().format("YYYY-MM-DD"),
                        className: "btn btn-danger",
                        orientation: "landscape"
                    }, "print"
                ],
            }).buttons().container().appendTo('#DataTable-Ttrx .col-md-6:eq(0)');
        }

        Init_Datatable_Trrx();
    })
</script>