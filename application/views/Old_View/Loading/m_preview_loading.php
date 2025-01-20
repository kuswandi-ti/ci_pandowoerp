<div class="modal fade" id="m_preview_data_loading" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">No Loading : <?= $loading->No_loading ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h6 class="text-center" style="font-weight: bold;"><?= 'Loading Shipping' ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-6">
                                        <h5 class="display-5 text-center">Table Data Loading</h5>
                                        <table id="table_detail_barcode" class="table-striped table-bordered display compact nowrap mt-3" style="width: 100%;">
                                            <thead style="background-color: #3B6D8C;">
                                                <tr>
                                                    <th class="text-center text-white" style="width: 25%;">LABEL</th>
                                                    <th class="text-white">DATA</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>No. Loading </td>
                                                    <td id="no_loading"><?= $loading->No_loading ?></td>
                                                </tr>
                                                <tr>
                                                    <td>CUSTOMER </td>
                                                    <td><span id="customer_name"><?= $loading->Customer_Name ?></span> (<?= $loading->Customer_Code ?>)</td>
                                                </tr>
                                                <tr>
                                                    <td>PRODUCT </td>
                                                    <td id="product"><?= $loading->Nama ?> (<?= $loading->Kode ?>)</td>
                                                </tr>
                                                <tr>
                                                    <td>QTY LOADING </td>
                                                    <td><?= $loading->Qty_Loading ?></td>
                                                </tr>
                                                <tr>
                                                    <td>STATUS</td>
                                                    <td>
                                                        <?php if ($loading->STATUS == 'LOADING') : ?>
                                                            <button class="badge badge-success">LOADING</button>
                                                        <?php else : ?>
                                                            <button class="badge badge-danger">FINISH (CLOSE)</button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <input type="hidden" name="Deskripsi_Product" id="Deskripsi_Product" value="<?= $loading->Deskripsi ?>">
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="table-responsive">
                                            <table id="Tbl_list_Loading" class="table-sm table-striped table-bordered" style="width: 100%;">
                                                <thead style="background-color: #3B6D8C;">
                                                    <tr>
                                                        <th class="text-center text-white">#</th>
                                                        <th class="text-center text-white">NO BARCODE</th>
                                                        <th class="text-center text-white">WAKTU SCAN</th>
                                                        <th class="text-center text-white">USER SCAN</th>
                                                        <!-- <th class="text-center text-white">HANDLE</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
<script src=" <?= base_url() ?>assets/Loading/loading_finish.js"></script>