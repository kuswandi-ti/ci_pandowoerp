<style>
    div.dt-buttons {
        clear: both;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h6 class="text-center" style="font-weight: bold;"><?= $page_title ?></h6>
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
<div id="location"></div>