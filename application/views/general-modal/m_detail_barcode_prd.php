<div class="modal fade" id="modal-detail-barcode" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Detail Data Barcode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <?php if (empty($barcode)) : ?>
                        <table id="table_detail_barcode" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white" style="width: 25%;">LABEL</th>
                                    <th class="text-white">DATA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="bg-warning text-center" colspan="2">Barcode Tidak Terdaftar Dalam System !</td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <table id="table_detail_barcode" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white" style="width: 25%;">LABEL</th>
                                    <th class="text-white">DATA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>KODE PRODUCT : </td>
                                    <td><?= $barcode->Product_Code ?></td>
                                </tr>

                                <tr>
                                    <td>NAMA PRODUCT : </td>
                                    <td><?= $barcode->Product_Name ?></td>
                                </tr>

                                <tr>
                                    <td>KODE CUSTOMER : </td>
                                    <td><?= $barcode->Customer_Code ?></td>
                                </tr>

                                <tr>
                                    <td>NAMA CUSTOMER : </td>
                                    <td><?= $barcode->Customer_Name ?></td>
                                </tr>

                                <tr>
                                    <td>CHECKER : </td>
                                    <td><?= $barcode->Checker_Rakit ?></td>
                                </tr>

                                <tr>
                                    <td>LEADER RAKIT : </td>
                                    <td><?= $barcode->Leader_Rakit ?></td>
                                </tr>

                                <tr>
                                    <td>TANGGAL PRODUKSI : </td>
                                    <td><?= $barcode->Date_Prd ?></td>
                                </tr>

                                <tr>
                                    <td>BARCODE VALUE : </td>
                                    <td><?= $barcode->Barcode_Value ?></td>
                                </tr>

                                <tr>
                                    <td>STATUS</td>
                                    <td>
                                        <?php if ($barcode->IS_WASTING == '0') : ?>
                                            <button class="btn btn-sm bg-gradient-success">STOK</button>
                                        <?php else : ?>
                                            <button class="btn btn-sm bg-gradient-danger">WASTING</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    <?php endif; ?>
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