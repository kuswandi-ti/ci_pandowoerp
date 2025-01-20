<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/Warehouse') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kode Gudang :</label>
                                <input type="text" class="form-control form-control-sm" name="Warehouse_Code" id="Warehouse_Code" required placeholder="Kode Gudang ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Gudang :</label>
                                <input type="text" class="form-control form-control-sm" name="Warehouse_Name" id="Warehouse_Name" required placeholder="Nama Gudang ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Category :</label>
                                <select class="form-control form-control-sm select2" name="Item_Category_ID" id="Item_Category_ID" required>
                                    <option value="" selected disabled>- Pilih -</option>
                                    <?php foreach ($Categories as $cat) : ?>
                                        <option value="<?= $cat->SysId ?>"><?= $cat->Item_Category ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Deskripsi :</label>
                                <textarea type="text" class="form-control form-control-sm" name="Description" id="Description" placeholder="Deskripsi Gudang ...."></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Berfungsi Sebagai Gudang Penerimaan Barang Inventory Logistik & Produksi :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Entry_Wh" id="Is_Entry_Wh1" value="1">
                                    <label class="form-check-label" for="Is_Entry_Wh1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Entry_Wh" id="Is_Entry_Wh2" value="0">
                                    <label class="form-check-label" for="Is_Entry_Wh2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Berfungsi Sebagai Sumber Alokasi Logistik & Produksi:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Source_Shp" id="Is_Source_Shp1" value="1" checked>
                                    <label class="form-check-label" for="Is_Source_Shp1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Source_Shp" id="Is_Source_Shp2" value="0">
                                    <label class="form-check-label" for="Is_Source_Shp2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Berfungsi Sebagai Sumber Alokasi Logistik & Produksi:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Source_Allocation" id="Is_Source_Allocation1" value="1">
                                    <label class="form-check-label" for="Is_Source_Allocation1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Source_Allocation" id="Is_Source_Allocation2" value="0">
                                    <label class="form-check-label" for="Is_Source_Allocation2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Berfungsi Sebagai Gudang Scrapt/Afkir :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Is_Afkir" id="Is_Afkir1" value="1" required>
                                    <label class="form-check-label" for="Is_Afkir1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Afkir" id="Is_Afkir2" value="0" checked>
                                    <label class="form-check-label" for="Is_Afkir2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Berfungsi Sebagai Gudang Barang Trading :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Is_Trading_Wh" id="Is_Trading_Wh1" value="1" required>
                                    <label class="form-check-label" for="Is_Trading_Wh1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Trading_Wh" id="Is_Trading_Wh2" value="0" checked>
                                    <label class="form-check-label" for="Is_Trading_Wh2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Berfungsi Sebagai Gudang Penerimaan Item Grading (Before KD):</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Receive_Grid" id="Is_Receive_Grid1" value="1">
                                    <label class="form-check-label" for="Is_Receive_Grid1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Receive_Grid" id="Is_Receive_Grid2" value="0" checked>
                                    <label class="form-check-label" for="Is_Kiln2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Berfungsi Sebagai Kiln Dry :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Kiln" id="Is_Kiln1" value="1">
                                    <label class="form-check-label" for="Is_Kiln1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Kiln" id="Is_Kiln2" value="0" checked>
                                    <label class="form-check-label" for="Is_Kiln2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Berfungsi Sebagai Gudang Alokasi Setelah Proses Kiln (After KD):</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Wh_After_Kiln" id="Is_Wh_After_Kiln1" value="1">
                                    <label class="form-check-label" for="Is_Wh_After_Kiln1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Wh_After_Kiln" id="Is_Wh_After_Kiln2" value="0" checked>
                                    <label class="form-check-label" for="Is_Wh_After_Kiln2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- =============================== END FORM =========================== -->
                    <div class="card-footer text-muted py-3 text-center mt-4">
                        <button type="button" href="#" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>