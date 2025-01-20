<div class="col-lg-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header">
            <div class="card-tools">
                <a href="<?= base_url('MasterData/Item') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="main-form-tab" data-toggle="pill" href="#main-form1" role="tab" aria-controls="main-form" aria-selected="false"><i class="fab fa-wpforms"></i> &nbsp;&nbsp; View/Edit Data Item</a>
                </li>
                <li class="nav-item" <?php if ($item->PackingList_Type == 'NONE') : ?> style="display: none;" <?php endif; ?>>
                    <a class="nav-link" id="secondary-tab" data-toggle="pill" href="#secondary" role="tab" aria-controls="secondary" aria-selected="true"><i class="fas fa-barcode"></i> &nbsp;&nbsp; Barcode Pattern Item</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade active show" id="main-form1" role="tabpanel" aria-labelledby="main-form-tab">
                    <!-- <div class="container-fluid"> -->
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <!-- <div class="card bd-callout shadow"> -->
                            <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                                <div class="card-body">
                                    <div class="row mt-3">
                                        <div class="col-lg-6 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Item Code :</label>
                                            <input type="text" class="form-control form-control-sm" name="item_code" id="item_code" required readonly value="<?= $item->Item_Code ?>">
                                            <input type="hidden" name="sysid" id="sysid" value="<?= $item->SysId ?>">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Item Name :</label>
                                            <input type="text" class="form-control form-control-sm" name="item_name" id="item_name" required placeholder="Nama Item ...." value="<?= $item->Item_Name ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Item Category : </label>
                                            <select class="form-control form-control-sm" name="item_category" id="Item_Category" required disabled>
                                                <?php foreach ($Categorie_Groups->result() as $cat) : ?>
                                                    <option value="<?= $cat->Category_Parent ?>" <?= ($cat->SysId == $item->Item_Category_Group ? 'selected' : '') ?>><?= $cat->Item_Category ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Item Group : </label>
                                            <select class="form-control form-control-sm" name="item_category_group" id="Item_Category_Group" required disabled>
                                                <option selected disabled>- Choose -</option>
                                                <?php foreach ($Categorie_Groups->result() as $cat) : ?>
                                                    <option value="<?= $cat->SysId ?>" <?= ($cat->SysId == $item->Item_Category_Group ? 'selected' : '') ?>><?= $cat->Group_Name ?> (<?= $cat->Item_Category ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Item Unit : </label>
                                            <select class="form-control form-control-sm" name="uom_id" id="uom_id" required disabled>
                                                <option selected disabled>- Choose -</option>
                                                <?php foreach ($Uoms->result() as $li) : ?>
                                                    <option value="<?= $li->Unit_Type_ID ?>" <?= ($li->Unit_Type_ID == $item->Uom_Id ? 'selected' : '') ?>><?= $li->Uom ?> (<?= $li->Unit_Name ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Packing List Type : </label>
                                            <select class="form-control form-control-sm" name="PackingList_Type" id="PackingList_Type" required>
                                                <option selected disabled>- Choose -</option>
                                                <option value="NONE" <?= ($item->PackingList_Type == 'NONE' ? 'selected' : '') ?>>NONE</option>
                                                <option value="SINGLE" <?= ($item->PackingList_Type == 'SINGLE' ? 'selected' : '') ?>>SINGLE</option>
                                                <option value="GROUP" <?= ($item->PackingList_Type == 'GROUP' ? 'selected' : '') ?>>GROUP</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Source Item : </label>
                                            <select class="form-control form-control-sm" name="source" id="source" required>
                                                <option value="1" <?= ($item->Source == '1' ? 'selected' : '') ?>>Purchase</option>
                                                <option value="2" <?= ($item->Source == '2' ? 'selected' : '') ?>>Production</option>
                                                <option value="3" <?= ($item->Source == '3' ? 'selected' : '') ?>>Purchase & Production</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Default Currency : </label>
                                            <select class="form-control form-control-sm" name="Currency" id="Currency" disabled required>
                                                <?php foreach ($Currencys as $curr) : ?>
                                                    <option value="<?= $curr->Currency_ID ?>" <?= ($curr->Currency_ID == $item->Default_Currency_Id ? 'selected' : '') ?>><?= $curr->Currency_ID ?> (<?= $curr->Currency_Description ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Item/Jasa : </label>
                                            <select class="form-control form-control-sm" name="is_expenses" id="is_expenses" required disabled>
                                                <option value="0" <?= ($item->Is_Expenses == 0 ? 'selected' : '') ?>>Inventory/Disimpan</option>
                                                <option value="1" <?= ($item->Is_Expenses == 1 ? 'selected' : '') ?>>Expenses (Biaya/Jasa) Tidak Disimpan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-2 col-sm-6 px-4 form-group mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="0" disabled id="non_grid" name="Is_Grid_Item" <?= $item->Is_Grid_Item == 0 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="non_grid">
                                                    Non Grade Item
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="1" disabled id="grid" name="Is_Grid_Item" <?= $item->Is_Grid_Item == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="grid">
                                                    Grade Item
                                                </label>
                                            </div>
                                        </div>



                                        <div class="col-lg-3 col-sm-6 px-4 form-group">
                                            <label style="font-weight: 500;">Tinggi/Tebal Item :</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" class="form-control form-control-sm" name="item_height" id="item_height" placeholder="Tinggi CM...." value="<?= floatval($item->Item_Height) ?>" <?= ($item->Is_Grid_Item == 1 ? 'readonly' : '') ?>>
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
                                                <input type="number" class="form-control form-control-sm" name="item_width" id="item_width" placeholder="lebar CM...." value="<?= floatval($item->Item_Width) ?>" <?= ($item->Is_Grid_Item == 1 ? 'readonly' : '') ?>>
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
                                                <input type="number" class="form-control form-control-sm" name="item_length" id="item_length" placeholder="Panjang CM...." value="<?= floatval($item->Item_Length) ?>" <?= ($item->Is_Grid_Item == 1 ? 'readonly' : '') ?>>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        CM
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="row mt-3">
                                        <!-- <div class="col-lg-3 col-sm-6 px-4 form-group">
                                            <label style="font-weight: 500;">Jenis Pohon Industri :</label>
                                            <select class="form-control form-control-sm" name="Id_Pki" id="Id_Pki" required disabled>
                                                <php foreach ($Woods->result() as $li) : ?>
                                                    <option value="<= $li->SysId ?>" <= ($li->SysId == $item->Id_Pki ? 'selected' : '') ?>><= $li->Nama_Pohon_Kayu ?> (<= $li->Grouping_Code ?>)</option>
                                                <php endforeach; ?>
                                            </select>
                                        </div> -->
                                        <!-- <div class="row mt-3" id="location-grid-pattern-code" style="display: none;"> -->
                                        <div class="col-lg-3 col-sm-6 px-4 form-group">
                                            <label style="font-weight: 500;">Kode Pattern Ukuran :</label>
                                            <input class="form-control form-control-sm" maxlength="4" name="Grid_Pattern_Code" id="Grid_Pattern_Code" readonly value="<?= $item->Grid_Pattern_Code ?>">
                                        </div>
                                        <!-- </div> -->
                                        <div class="col-lg-3 col-sm-6 px-4 form-group">
                                            <label style="font-weight: 500;">Volume Item :</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" class="form-control form-control-sm" name="Volume_M3" id="Volume_M3" placeholder="Volume" value="<?= floatval($item->Volume_M3) ?>" <?= ($item->Is_Grid_Item == 1 ? 'readonly' : '') ?>>
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
                                                <input type="number" class="form-control form-control-sm" name="MeterSquare_M2" id="MeterSquare_M2" placeholder="keliling" value="<?= floatval($item->MeterSquare_M2) ?>">
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
                                                <input type="number" class="form-control form-control-sm" name="item_weight" id="item_weight" placeholder="Berat Kg...." value="<?= floatval($item->Item_Weight) ?>">
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
                                                    <option value="<?= $wh->Warehouse_ID ?>" <?= $item->Default_Warehouse_Id == $wh->Warehouse_ID ? 'selected' : '' ?>><?= $wh->Warehouse_Name ?>(<?= $wh->Item_Category ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-6 col-sm-4 px-4 form-group">
                                            <label style="font-weight: 500;">Brand :</label>
                                            <input type="text" class="form-control form-control-sm" name="brand" id="brand" placeholder="Brand Item ...." value="<?= $item->Brand ?>">
                                        </div>
                                        <div class="col-lg-6 col-sm-4 px-4 form-group">
                                            <label style="font-weight: 500;">Model :</label>
                                            <input type="text" class="form-control form-control-sm" name="model" id="model" placeholder="Model Item ...." value="<?= $item->Model ?>">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-6 col-sm-4 px-4 form-group">
                                            <label style="font-weight: 500;">Item Color :</label>
                                            <input type="text" class="form-control form-control-sm" name="item_color" id="item_color" placeholder="Warna Item ...." value="<?= $item->Item_Color ?>">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 px-4 form-group">
                                            <label style="font-weight: 500;">Description :</label>
                                            <textarea rows="4" class="form-control form-control-sm" name="item_description" id="item_description" placeholder="Deskripsi..."><?= $item->Item_Description ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-4 col-sm-6 px-4 form-group">
                                            <label style="font-weight: 500;">Custom Field 1 :</label>
                                            <textarea rows="4" class="form-control form-control-sm" name="Custom_Field_1" id="Custom_Field_1" placeholder="Custom field..."><?= $item->Custom_Field_1 ?></textarea>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 px-4 form-group">
                                            <label style="font-weight: 500;">Custom Field 2 :</label>
                                            <textarea rows="4" class="form-control form-control-sm" name="Custom_Field_2" id="Custom_Field_2" placeholder="Custom field..."><?= $item->Custom_Field_2 ?></textarea>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 px-4 form-group">
                                            <label style="font-weight: 500;">Custom Field 3 :</label>
                                            <textarea rows="4" class="form-control form-control-sm" name="Custom_Field_3" id="Custom_Field_3" placeholder="Custom field..."><?= $item->Custom_Field_3 ?></textarea>
                                        </div>
                                    </div>
                                    <!-- =============================== END FORM =========================== -->
                                    <div class="card-footer text-muted py-3 text-center mt-4">
                                        <button type="button" href="#" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Update & Submit</button>
                                    </div>
                                </div>
                            </form>
                            <!-- </div> -->
                        </div>
                    </div>
                    <!-- </div> -->
                </div>
                <div class="tab-pane fade" id="secondary" role="tabpanel" aria-labelledby="secondary-tab">
                    <form method="post" enctype="multipart/form-data" action="#" id="detail-form">
                        <input type="hidden" name="item_code_param" id="item_code_param" value="<?= $item->Item_Code ?>">
                        <div class="card-body">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <h3 style="font-weight: bold;" class="text-center">Setting Packing Item</h3>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold; ">Qty Per-Packing :</label>
                                <input type="number" class="form-control form-control-sm" min="1" name="qty_item_perpack" id="qty_item_perpack" required <?php
                                                                                                                                                            if ($item->PackingList_Type == 'SINGLE') {
                                                                                                                                                                echo 'value="1" readonly';
                                                                                                                                                            } else {
                                                                                                                                                                echo 'value="' . $RowPattern['Qty_Packing'] . '"';
                                                                                                                                                            } ?>>
                            </div>
                            <hr>
                            <div class="col-lg-6 col-sm-12 px-4 mt-5 form-group">
                                <h3 style="font-weight: bold;" class="text-center">Setting Barcode</h3>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold; ">Identitas <?= $this->config->item('company_name_init') ?> :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="CompanyIdentity" <?= ($RowPattern['Company_Identity'] == '' ? 'checked' : null) ?> id="CompanyIdentity0" value="">
                                    <label class="form-check-label" for="CompanyIdentity0">
                                        Tidak Digunakan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="CompanyIdentity" <?= ($RowPattern['Company_Identity'] == $this->config->item('company_initial_1')) ?> id="CompanyIdentity1" value="<?= $this->config->item('company_initial_1') ? 'checked' : null ?>">
                                    <label class="form-check-label" for="CompanyIdentity1">
                                        <?= $this->config->item('company_initial_1') ?>-
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="CompanyIdentity" <?= ($RowPattern['Company_Identity'] == $this->config->item('company_initial_2')) ?> id="CompanyIdentity2" value="<?= $this->config->item('company_initial_2') ? 'checked' : null ?>">
                                    <label class="form-check-label" for="CompanyIdentity2">
                                        <?= $this->config->item('company_initial_2') ?>-
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold; ">Pattern Character Item :</label>
                                <input type="text" class="form-control form-control-sm" maxlength="4" name="header" id="header" required placeholder="Awalan Kode Barcode..." value="<?= $RowPattern['Pattern_Char'] ?>" <?= (empty($RowPattern['Pattern_Char']) ? '' : 'readonly') ?>>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold; ">Pemisah :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="FirstConcate" id="FirstConcate0" value="" <?= ($RowPattern['First_Concate'] == '' ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="FirstConcate0">
                                        Tidak Ada
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="FirstConcate" id="FirstConcate1" value="-" <?= ($RowPattern['First_Concate'] == '-' ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="FirstConcate1">
                                        - (Strip)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="FirstConcate" id="FirstConcate2" value="_" <?= ($RowPattern['First_Concate'] == '_' ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="FirstConcate2">
                                        _ (Underscore)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="FirstConcate" id="FirstConcate3" value="~" <?= ($RowPattern['First_Concate'] == '~' ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="FirstConcate3">
                                        ~ (Tanda Gelombang)
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold; ">Periode Reset :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="PeriodeReset" id="PeriodeReset1" <?= ($RowPattern['Reset_Period'] == 'Y' ? 'checked' : null) ?> value="Y">
                                    <label class="form-check-label" for="PeriodeReset1">
                                        Tahunan (<?= date('Y') ?>)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="PeriodeReset" id="PeriodeReset2" <?= ($RowPattern['Reset_Period'] == 'Ym' ? 'checked' : null) ?> value="Ym">
                                    <label class="form-check-label" for="PeriodeReset2">
                                        Bulanan (<?= date('Ym') ?>)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="PeriodeReset" id="PeriodeReset3" <?= ($RowPattern['Reset_Period'] == 'Ymd' ? 'checked' : null) ?> value="Ymd">
                                    <label class="form-check-label" for="PeriodeReset3">
                                        Harian (<?= date('Ymd') ?>)
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold; ">Pemisah :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="SecondConcate" id="SecondConcate0" value="" <?= ($RowPattern['Second_Concate'] == '' ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="SecondConcate0">
                                        Tidak Ada
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="SecondConcate" id="SecondConcate1" value="-" <?= ($RowPattern['Second_Concate'] == '-' ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="SecondConcate1">
                                        - (Strip)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="SecondConcate" id="SecondConcate2" value="_" <?= ($RowPattern['Second_Concate'] == '_' ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="SecondConcate2">
                                        _ (Underscore)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="SecondConcate" id="SecondConcate3" value="~" <?= ($RowPattern['Second_Concate'] == '~' ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="SecondConcate3">
                                        ~ (Tanda Gelombang)
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold; ">Panjang Counter :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="LengthCounter" id="LengthCounter1" value="3" <?= ($RowPattern['Counter_Length'] == 3 ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="LengthCounter1">
                                        3 (001)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="LengthCounter" id="LengthCounter2" value="4" <?= ($RowPattern['Counter_Length'] == 4 ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="LengthCounter2">
                                        4 (0001)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="LengthCounter" id="LengthCounter3" value="5" <?= ($RowPattern['Counter_Length'] == 5 ? 'checked' : '') ?>>
                                    <label class="form-check-label" for="LengthCounter3">
                                        5 (00001)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer text-muted py-3 text-center mt-4">
                        <button type="button" href="#" class="btn btn-primary px-5 btn-lg" id="submit-detail"><i class="fas fa-save"></i> | Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div id="location">

    </div>
</div>