<div class="modal fade" id="modal_list_shipping" data-backdrop="static" data-keyboard="false">
    <style>
        #table_lot_by_deskripsi_filter {
            float: left;
        }

        #table_lot_by_deskripsi_filter label input {
            width: 50vh;
        }
    </style>
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">LIST SHIPPING SO : <?= $so_number ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="table_lot_by_deskripsi" class="table table-bordered table-striped display compact table-sm" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <!-- unit price
                                qty so
                                qty shipped
                                ost so
                                Value SO
                                Value shipped
                                value ost so -->
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">No Shipping</th>
                                <th class="text-center text-white">Tanggal Shiiping</th>
                                <th class="text-center text-white">Item Code</th>
                                <th class="text-center text-white">Item Name</th>
                                <th class="text-center text-white">QTY Shipping</th>
                                <th class="text-center text-white">Alamat</th>
                                <!-- <th class="text-center text-white"><i class="fas fa-calendar"></i> KIRIM</th> -->
                                <!-- <th class="text-center text-white"><i class="fas fa-calendar"></i> FINISH</th> -->
                                <!-- <th class="text-center text-white">Penempatan</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- $this->data['datas'] = $this->db->query("SELECT hdr.ShipToAddress_ID, taa.Address, hdr.ShipInst_Number,
                            dtl.SysId, dtl.SysId_Hdr, dtl.Item_Code, dtl.Item_Name, dtl.Dimension, dtl.Qty, dtl.Warehouse_Qty, dtl.Uom, dtl.isFreeItem, dtl.SO_Number, dtl.Notes -->
                            <?php $i = 1; ?>
                            <?php foreach ($datas as $li) : ?>
                                <tr>
                                    <td class="text-center"><?= $i; ?></td>
                                    <td class="text-center"><?= $li->ShipInst_Number ?></td>
                                    <td class="text-center"><?= $li->ShipInst_Date ?></td>
                                    <td class="text-center"><?= $li->Item_Code ?></td>
                                    <td class="text-center"><?= $li->Item_Name ?></td>
                                    <td class="text-center"><?= floatval($li->Qty) ?></td>
                                    <td class="text-center"><?= $li->Address ?></td>
                                </tr>
                                <!-- hdr.ShipToAddress_ID, taa.Address, hdr.ShipInst_Number,
                                dtl.SysId, dtl.SysId_Hdr, dtl.Item_Code, dtl.Item_Name, dtl.Dimension, dtl.Qty, dtl.Warehouse_Qty, dtl.Uom, dtl.isFreeItem, dtl.SO_Number, dtl.Notes -->
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer float-right">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function() {
        $('#table_lot_by_deskripsi').DataTable({
                destroy: true,
                // processing: true,
                // serverSide: true,
                dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
                "aLengthMenu": [
                    [15, 25, 50, 10000],
                    [15, 25, 50, "All"],
                ],
                order: [
                    [2, "desc"]
                ],
                columnDefs: [{
                        className: "text-center",
                        targets: "_all",
                    },
                    {
                        className: "text-left",
                        targets: [],
                    },
                ],
                autoWidth: false,
                // responsive: true,
                preDrawCallback: function() {
                    $("#table_lot_by_deskripsi tbody td").addClass("blurry");
                },
                language: {
                    processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
                },
                drawCallback: function() {
                    $("#table_lot_by_deskripsi tbody td").addClass("blurry");
                    setTimeout(function() {
                        $("#table_lot_by_deskripsi tbody td").removeClass("blurry");
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                },
                "buttons": [{
                    extend: 'csvHtml5',
                    title: $('.modal-title').text() + '~' + moment().format("YYYY-MM-DD"),
                    className: "btn btn-info",
                }, {
                    extend: 'excelHtml5',
                    title: $('.modal-title').text() + '~' + moment().format("YYYY-MM-DD"),
                    className: "btn btn-success",
                }, {
                    extend: 'pdfHtml5',
                    title: $('.modal-title').text() + '~' + moment().format("YYYY-MM-DD"),
                    className: "btn btn-danger",
                    orientation: "landscape"
                }],
            })
            .buttons()
            .container()
            .appendTo("#table_lot_by_deskripsi_wrapper .col-md-6:eq(0)");
    })
</script>