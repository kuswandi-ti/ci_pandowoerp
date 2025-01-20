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
                            <table id="table_detail_barcode" class="table-striped table-bordered display compact nowrap" style="width: 100%;">
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
                                        <td><?= $loading->Customer_Name ?> (<?= $loading->Customer_Code ?>)</td>
                                    </tr>
                                    <tr>
                                        <td>PRODUCT </td>
                                        <td><?= $loading->Nama ?> (<?= $loading->Kode ?>)</td>
                                    </tr>
                                    <tr>
                                        <td>QTY LOADING </td>
                                        <td><a href="#" class="editable_qty_loading" data-pk="<?= $loading->SysId ?>"><?= $loading->Qty_Loading ?></a></td>
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
                        <div class="col-sm-1 col-lg-1">
                        </div>
                        <div class="col-sm-4 col-lg-4" style="margin: auto; display: block;">
                            <div class="row">
                                <div class="input-group">
                                    <input type="text" class="form-control" autofocus="autofocus" id="no_barcode" name="no_barcode">
                                    <div class="input-group-prepend">
                                        <button type="button" data-toggle="tooltip" title="Melihat Detail Data Barcode" id="preview--data" class="btn bg-gradient-danger"> &nbsp;&nbsp;&nbsp;<b><i class="fas fa-barcode"></i></b>&nbsp;&nbsp;&nbsp; </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="input-group text-center">
                                    <input type="hidden" name="silang_product" id="silang_product" value="<?= $loading->Silang_Product ?>">
                                    <button type="button" class="btn btn-success btn-sm float-center" id="submit--barcode"><i class="fas fa-download"></i> | MASUKAN SEBAGAI LOADING</button>
                                    <!-- data-toggle="tooltip" title="Menyatakan lot keluar oven" -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12" id="container-list-barcode">
            <div class="card card-danger card-outline">
                <div class="card-header text-center">
                    <div class="row">
                        <div class="col-lg-6">
                            <button id="finish--loading" class="btn bg-gradient-primary"><b>NYATAKAN SELESAI</b></button>
                        </div>
                        <div class="col-lg-6">
                            <h5 class="text-center"><b>Preparation Loading Product <span><?php if ($loading->Silang_Product == 'TRUE') echo 'Silang Customer' ?></span></b></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="Tbl_Tmp_Loading" class="table-sm table-striped table-bordered" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">NO BARCODE</th>
                                    <th class="text-center text-white">WAKTU SCAN</th>
                                    <th class="text-center text-white">USER SCAN</th>
                                    <th class="text-center text-white">HANDLE</th>
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
<div id="location"></div>