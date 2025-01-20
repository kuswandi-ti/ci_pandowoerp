<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/Account/' . $account) ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- <h5 class="card-header py-3"><= $page_title ?></h5> -->
                        <input type="hidden" name="IdentityPattern" id="IdentityPattern" value="<?= $IdentityPattern ?>">
                        <div class="row mt-3">
                            <div class="col-lg-3 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Bentuk Badan Usaha :</label>
                                <select class="form-control form-control-sm select2" name="AccountTitle_Code" id="AccountTitle_Code" required>
                                    <?php foreach ($Titles as $title) : ?>
                                        <option value="<?= $title->Title_Code ?>"><?= $title->Title_Code ?> (<?= $title->Title_Name ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;"><?= $account ?> Code Otomatis dari System:</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="patern_item_code" id="otomatis_ac" value="otomatis_ac" checked>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control form-control-sm disable" placeholder="<?= $account ?> Code Akan Otomatis di isikan Oleh system." disabled>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;"><?= $account ?> Name :</label>
                                <input type="text" class="form-control form-control-sm" name="Account_Name" id="Account_Name" required placeholder="Nama <?= $account ?> ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">NPWP (Tax File Number) :</label>
                                <input type="text" class="form-control form-control-sm" name="TaxFileNumber" id="TaxFileNumber" placeholder="NPWP ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">NPPKP :</label>
                                <input type="text" class="form-control form-control-sm" name="nppkp" id="nppkp" placeholder="NPPKP ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <hr class="devider">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Alamat Email :</label>
                                <input type="text" class="form-control form-control-sm" name="Account_EmailAddress" id="Account_EmailAddress" placeholder="Email ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Website :</label>
                                <input type="text" class="form-control form-control-sm" name="Website" id="Website" placeholder="Website ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Alamat Lengkap :</label>
                                <textarea type="text" class="form-control form-control-sm" name="Account_Address" id="Account_Address" required placeholder="Alamat ...."></textarea>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Asal Negara :</label>
                                <select class="form-control form-control-sm select2" name="Account_Country_Id" id="Account_Country_Id" required>
                                    <?php foreach ($Countries as $flag) : ?>
                                        <option value="<?= $flag->Country_ID ?>" <?= ($flag->Is_Default == 1 ? 'selected' : '') ?>><?= $flag->Country_ID ?> (<?= $flag->Country_Name ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Provinsi :</label>
                                <input type="text" class="form-control form-control-sm" name="Account_State_Id" id="Account_State_Id" required placeholder="Provinsi ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kabupaten/Kota :</label>
                                <input type="text" class="form-control form-control-sm" name="Account_City_Id" id="Account_City_Id" required placeholder="Kota ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Zip Code/Kode Pos :</label>
                                <input type="text" class="form-control form-control-sm" name="Account_ZipCode" id="Account_ZipCode" required placeholder="Kode Pos ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Local/Non-Local :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="islocal" id="islocal1" value="1" checked>
                                    <label class="form-check-label" for="islocal1">
                                        Local
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="islocal" id="islocal2" value="0">
                                    <label class="form-check-label" for="islocal2">
                                        Luar Negri
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Cotact Person 1 :</label>
                                <input type="text" class="form-control form-control-sm" name="Account_Phone1" id="Account_Phone1" placeholder="Nomor Telp ...." required>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Cotact Person 2 :</label>
                                <input type="text" class="form-control form-control-sm" name="Account_Phone2" id="Account_Phone2" placeholder="Nomor Telp ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Fax :</label>
                                <input type="text" class="form-control form-control-sm" name="Account_Fax" id="Account_Fax" placeholder="Nomor Fax ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Default Currency/Mata Uang <?= $account ?> :</label>
                                <select class="form-control form-control-sm select2" name="Account_CurrencyId" id="Account_CurrencyId" required>
                                    <?php foreach ($Currencys->result() as $curr) : ?>
                                        <option value="<?= $curr->Currency_ID ?>" <?= ($curr->Is_Default == 1 ? 'selected' : '') ?>><?= $curr->Currency_ID ?> (<?= $curr->Currency_Description ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Payment Terms :</label>
                                <input type="number" class="form-control form-control-sm" name="PaymentTerms" id="PaymentTerms" required placeholder="Payment Term ....">
                            </div>
                            <div class="col-lg-2 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Satuan Waktu Payment Term</label>
                                <select class="form-control form-control-sm select2" name="PaymentTerm" id="PaymentTerm" required>
                                    <option value="Day">Hari</option>
                                    <option value="Month">Bulan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-4 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Kondisi Penerimaan Dokumen :</label>
                                <select class="form-control form-control-sm" required name="PaymentTermTypeDoc" id="PaymentTermTypeDoc">
                                    <option value="" selected disabled>-Choose-</option>
                                    <option value="AFTER INVOICE RECEIVED">AFTER INVOICE RECEIVED</option>
                                    <option value="AFTER PO CLOSE">AFTER PO CLOSE</option>
                                    <option value="AFTER GOODS RECEIVED NOTE">AFTER GOODS RECEIVED NOTE</option>
                                    <option value="AFTER PO DOCUMENT RECEIVED">AFTER DOCUMENT PO RECEIVED</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Ketentuan Pembayaran :</label>
                                <textarea rows="4" class="form-control form-control-sm" name="PaymentCondition" id="PaymentCondition" placeholder="Payment Condition..."></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <hr class="devider">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Bank :</label>
                                <input type="text" class="form-control form-control-sm" name="BankName" id="BankName" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?> placeholder="Nama Bank ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kode Bank :</label>
                                <input type="number" class="form-control form-control-sm" name="BankCode" id="BankCode" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?> placeholder="Kode Bank ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nomor Rekening :</label>
                                <input type="text" class="form-control form-control-sm" name="BankAccount" id="BankAccount" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?> placeholder="Nomor Rekening ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Pemilik Rekening :</label>
                                <input type="text" class="form-control form-control-sm" name="BankAccName" id="BankAccName" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?> placeholder="Atas Nama ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Bank Cabang :</label>
                                <input type="text" class="form-control form-control-sm" name="BankBranch" id="BankBranch" placeholder="Cabang ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Mata Uang Bank :</label>
                                <select class="form-control form-control-sm select2" name="BankCurrencyID" id="BankCurrencyID" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?>>
                                    <option value="" selected disabled>- Choose -</option>
                                    <?php foreach ($Currencys->result() as $curr) : ?>
                                        <option value="<?= $curr->Currency_ID ?>"><?= $curr->Currency_ID ?> (<?= $curr->Currency_Description ?>)</option>
                                    <?php endforeach; ?>
                                </select>
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