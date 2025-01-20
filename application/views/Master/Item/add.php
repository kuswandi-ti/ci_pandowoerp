<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/Item') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- <h5 class="card-header py-3"><= $page_title ?></h5> -->
                        <div class="row mt-3">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Code Otomatis from System:</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="patern_item_code" id="otomatis_ic" value="otomatis_ic" checked>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control form-control-sm disable" placeholder="Item Code Akan Otomatis di isikan Oleh system." disabled>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Code Manual:</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="patern_item_code" id="manual_ic" value="manual_ic">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" readonly name="item_code" id="item_code" placeholder="Item Code....">
                                </div>
                                <span class="text-danger">*Karakter dilarang : [ "/" "( )" "&@~`/!$%^&*+={}[]|:;"\'<,>?." ]</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Name :</label>
                                <input type="text" class="form-control form-control-sm" name="item_name" id="item_name" required placeholder="Nama Item ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Category : </label>
                                <select class="form-control form-control-sm select2" name="item_category" id="Item_Category" required>
                                    <option selected disabled>- Choose -</option>
                                    <?php foreach ($Categories->result() as $cat) : ?>
                                        <option value="<?= $cat->SysId ?>"><?= $cat->Item_Category ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Group : </label>
                                <select class="form-control form-control-sm" name="item_category_group" id="Item_Category_Group" required>
                                    <option selected disabled>- Choose -</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Unit : </label>
                                <select class="form-control form-control-sm" name="uom_id" id="uom_id" required>
                                    <option selected disabled>- Choose -</option>
                                    <?php foreach ($Uoms->result() as $li) : ?>
                                        <option value="<?= $li->Unit_Type_ID ?>"><?= $li->Uom ?> (<?= $li->Unit_Name ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Packing List Type : </label>
                                <select class="form-control form-control-sm" name="PackingList_Type" id="PackingList_Type" required>
                                    <option selected disabled>- Choose -</option>
                                    <option value="NONE">NONE</option>
                                    <option value="SINGLE">SINGLE</option>
                                    <option value="GROUP">GROUP</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Source Item : </label>
                                <select class="form-control form-control-sm" name="source" id="source" required>
                                    <option value="1">Purchase</option>
                                    <option value="2">Production</option>
                                    <option value="3" selected>Purchase & Production</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Default Currency : </label>
                                <select class="form-control form-control-sm" name="Currency" id="Currency" required>
                                    <?php foreach ($Currencys as $curr) : ?>
                                        <option value="<?= $curr->Currency_ID ?>" <?= ($curr->Is_Default == 1 ? 'selected' : '') ?>><?= $curr->Currency_ID ?> (<?= $curr->Currency_Description ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item/Jasa : </label>
                                <select class="form-control form-control-sm" name="is_expenses" id="is_expenses" required>
                                    <option value="0" selected>Item Inventory/Disimpan</option>
                                    <option value="1">Expenses (Biaya/Jasa) Tidak Disimpan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-2 col-sm-6 px-4 form-group mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="0" id="non_grid" name="Is_Grid_Item" checked>
                                    <label class="form-check-label" for="non_grid">
                                        Non Grade Item
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="1" id="grid" name="Is_Grid_Item">
                                    <label class="form-check-label" for="grid">
                                        Grade Item
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Tinggi/Tebal Item :</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control form-control-sm" name="item_height" id="item_height" value="0" placeholder="Tinggi CM....">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            CM
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Lebar Item :</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control form-control-sm" name="item_width" id="item_width" value="0" placeholder="lebar CM....">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            CM
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Panjang Item :</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control form-control-sm" name="item_length" id="item_length" value="0" placeholder="Panjang cm....">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            CM
                                        </div>
                                    </div>
                                    &nbsp;
                                    &nbsp;
                                    <div class="input-group-prepend">
                                        <button class="btn btn-danger" type="button" id="calculate-volume" data-toggle="tooltip" data-placement="top" title="Hitung Volume"><i class="fas fa-calculator"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3" id="location-grid-pattern-code" style="display: none;">
                            <div class="col-lg-3 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Kode Pattern Ukuran :</label>
                                <input class="form-control form-control-sm" maxlength="5" name="Grid_Pattern_Code" id="Grid_Pattern_Code" placeholder="contoh SONOKELING : SK">
                            </div>
                        </div>
                        <div class="row mt-3" id="location-jpi" style="display: none;">
                            <div class="col-lg-3 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Jenis Pohon Industri :</label>
                                <select class="form-control form-control-sm" name="Id_Pki" id="Id_Pki">
                                    <option selected value="">-Jenis Pohon Industri-</option>
                                    <?php foreach ($Woods->result() as $li) : ?>
                                        <option value="<?= $li->SysId ?>"><?= $li->Nama_Pohon_Kayu ?> (<?= $li->Grouping_Code ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-3 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Volume Item :</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control form-control-sm" name="Volume_M3" id="Volume_M3" placeholder="volume...." value="0">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            M3
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Meter Square :</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control form-control-sm" name="MeterSquare_M2" id="MeterSquare_M2" placeholder="keliling...." value="0">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            M2
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-3 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Item weight :</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control form-control-sm" name="item_weight" id="item_weight" placeholder="Berat Kg....">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            KG
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Default Warehouse : </label>
                                <select class="form-control form-control-sm" name="Default_Warehouse_Id" id="Default_Warehouse_Id" required>
                                    <option selected disabled>- Choose -</option>
                                    <?php foreach ($Warehouses->result() as $wh) : ?>
                                        <option value="<?= $wh->Warehouse_ID ?>"><?= $wh->Warehouse_Name ?>(<?= $wh->Item_Category ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-6 col-sm-4 px-4 form-group">
                                <label style="font-weight: 500;">Brand :</label>
                                <input type="text" class="form-control form-control-sm" name="brand" id="brand" placeholder="Brand Item ...." value="-">
                            </div>
                            <div class="col-lg-6 col-sm-4 px-4 form-group">
                                <label style="font-weight: 500;">Model :</label>
                                <input type="text" class="form-control form-control-sm" name="model" id="model" placeholder="Model Item ...." value="-">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-6 col-sm-4 px-4 form-group">
                                <label style="font-weight: 500;">Item Color :</label>
                                <input type="text" class="form-control form-control-sm" name="item_color" id="item_color" placeholder="Warna Item ...." value="-">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Description :</label>
                                <textarea rows="4" class="form-control form-control-sm" name="item_description" id="item_description" placeholder="Deskripsi..."></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-4 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Custom Field 1 :</label>
                                <textarea rows="4" class="form-control form-control-sm" name="Custom_Field_1" id="Custom_Field_1" placeholder="Custom field..."></textarea>
                            </div>
                            <div class="col-lg-4 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Custom Field 2 :</label>
                                <textarea rows="4" class="form-control form-control-sm" name="Custom_Field_2" id="Custom_Field_2" placeholder="Custom field..."></textarea>
                            </div>
                            <div class="col-lg-4 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Custom Field 3 :</label>
                                <textarea rows="4" class="form-control form-control-sm" name="Custom_Field_3" id="Custom_Field_3" placeholder="Custom field..."></textarea>
                            </div>
                        </div>
                        <!-- <div class="col-lg-10 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Note :</label>
                                <textarea rows="3" class="form-control form-control-sm" name="note" id="note" placeholder="Note..."></textarea>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Job Type : </label>
                                <select class="form-control form-control-sm" name="tipe_pekerjaan" id="tipe_pekerjaan" required>
                                    <option selected disabled>- Pilih -</option>
                                    <option value=""></option>
                                </select>
                            </div> -->
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