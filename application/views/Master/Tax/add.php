<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/TipeAkun/') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kode Pajak :</label>
                                <input type="text" class="form-control form-control-sm" name="Tax_Code" id="Tax_Code" placeholder="Kode Pajak ...." required>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Pajak :</label>
                                <input type="text" class="form-control form-control-sm" name="Tax_Name" id="Tax_Name" placeholder="Nama Pajak ...." required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Persentase Pajak :</label>
                                <input type="number" max="99" maxlength="2" class="form-control form-control-sm" name="Tax_Rate" id="Tax_Rate" placeholder="Persentase Penarikan pajak ...." required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-8 px-4 form-group mt-2">
                            <label style="font-weight: 500;">Pajak bisa diterapkan pada penjualan/Sales order ? :</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="0" id="ForSalesTrue" name="ForSales" checked>
                                <label class="form-check-label" for="ForSalesTrue">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1" id="ForSalesFalse" name="ForSales">
                                <label class="form-check-label" for="ForSalesFalse">
                                    Tidak
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-8 px-4 form-group mt-2">
                            <label style="font-weight: 500;">Pajak bisa diterapkan pada Pembelian/Purchase order ? :</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="0" id="ForPurchaseTrue" name="ForPurchase" checked>
                                <label class="form-check-label" for="ForPurchaseTrue">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1" id="ForPurchaseFalse" name="ForPurchase">
                                <label class="form-check-label" for="ForPurchaseFalse">
                                    Tidak
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-8 px-4 form-group mt-2">
                            <label style="font-weight: 500;">Pajak dapat include pada harga pembelian? :</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="0" id="isIncludeTrue" name="isInclude">
                                <label class="form-check-label" for="isIncludeTrue">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1" id="isIncludeFalse" name="isInclude" checked>
                                <label class="form-check-label" for="isIncludeFalse">
                                    Tidak
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-8 px-4 form-group mt-2">
                            <label style="font-weight: 500;">Pembayaran pajak dapat di lakukan secara parsial ? :</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="0" id="isKreditableTrue" name="isKreditable">
                                <label class="form-check-label" for="isKreditableTrue">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1" id="isKreditableFalse" name="isKreditable" checked>
                                <label class="form-check-label" for="isKreditableFalse">
                                    Tidak
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-8 px-4 form-group mt-2">
                            <label style="font-weight: 500;">Pajak PPNBM ? :</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="0" id="isPPNBMTrue" name="isPPNBM">
                                <label class="form-check-label" for="isPPNBMTrue">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1" id="isPPNBMFalse" name="isPPNBM" checked>
                                <label class="form-check-label" for="isPPNBMFalse">
                                    Tidak
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted py-3 text-center mt-4">
                        <button type="button" href="#" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>