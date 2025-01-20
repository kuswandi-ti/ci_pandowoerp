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
                        <input type="hidden" name="IdentityPattern" id="IdentityPattern" value="<?= $IdentityPattern ?>">
                        <input type="hidden" name="SysId" id="SysId" value="<?= $RowAccount->SysId ?>">
                        <div class="row mt-3">
                            <div class="col-lg-3 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Bentuk Badan Usaha :</label>
                                <select class="form-control form-control-sm select2" name="AccountTitle_Code" id="AccountTitle_Code" required>
                                    <?php foreach ($Titles as $title) : ?>
                                        <option <?= ($RowAccount->AccountTitle_Code == $title->Title_Code ? 'selected' : null) ?> value="<?= $title->Title_Code ?>"><?= $title->Title_Code ?> (<?= $title->Title_Name ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-9 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">&nbsp;</label>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#M_Contact">
                                    List Contact <?= $RowAccount->Account_Name ?> <i class="fas fa-address-book"></i>
                                </button>
                                &nbsp;
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#M_Address">
                                    List Alamat <?= $RowAccount->Account_Name ?> <i class="fas fa-map-pin"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;"><?= $account ?> Code:</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="patern_item_code" id="otomatis_ac" value="otomatis_ac" checked>
                                        </div>
                                    </div>
                                    <input type="text" id="Code" name="Code" class="form-control form-control-sm disable" value="<?= $RowAccount->Account_Code ?>" placeholder="<?= $account ?> Code Akan Otomatis di isikan Oleh system." readonly>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;"><?= $account ?> Name :</label>
                                <input type="text" class="form-control form-control-sm" readonly value="<?= $RowAccount->Account_Name ?>" name="Account_Name" id="Account_Name" required placeholder="Nama <?= $account ?> ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">NPWP (Tax File Number) :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->TaxFileNumber ?>" name="TaxFileNumber" id="TaxFileNumber" placeholder="NPWP ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">NPPKP :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->nppkp ?>" name="nppkp" id="nppkp" placeholder="NPPKP ....">
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
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->Account_EmailAddress ?>" name="Account_EmailAddress" id="Account_EmailAddress" placeholder="Email ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Website :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->Website ?>" name="Website" id="Website" placeholder="Website ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Alamat Lengkap :</label>
                                <textarea type="text" class="form-control form-control-sm" name="Account_Address" id="Account_Address" required placeholder="Alamat ...."><?= $RowAccount->Account_Address ?></textarea>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Asal Negara :</label>
                                <select class="form-control form-control-sm select2" name="Account_Country_Id" id="Account_Country_Id" required>
                                    <?php foreach ($Countries as $flag) : ?>
                                        <option <?= ($RowAccount->Account_Country_Id == $flag->Country_ID ? 'selected' : null) ?> value="<?= $flag->Country_ID ?>" <?= ($flag->Is_Default == 1 ? 'selected' : '') ?>><?= $flag->Country_ID ?> (<?= $flag->Country_Name ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Provinsi :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->Account_State_Id ?>" name="Account_State_Id" id="Account_State_Id" required placeholder="Provinsi ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kabupaten/Kota :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->Account_City_Id ?>" name="Account_City_Id" id="Account_City_Id" required placeholder="Kota ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Zip Code/Kode Pos :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->Account_ZipCode ?>" name="Account_ZipCode" id="Account_ZipCode" required placeholder="Kode Pos ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Local/Non-Local :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="islocal" id="islocal1" value="1" <?= ($RowAccount->islocal == 1 ? 'checked' : null) ?>>
                                    <label class="form-check-label" for="islocal1">
                                        Local
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="islocal" id="islocal2" value="0" <?= ($RowAccount->islocal == 0 ? 'checked' : null) ?>>
                                    <label class="form-check-label" for="islocal2">
                                        Luar Negri
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Cotact Person 1 :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->Account_Phone1 ?>" name="Account_Phone1" id="Account_Phone1" placeholder="Nomor Telp ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Cotact Person 2 :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->Account_Phone2 ?>" name="Account_Phone2" id="Account_Phone2" placeholder="Nomor Telp ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Fax :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->Account_Fax ?>" name="Account_Fax" id="Account_Fax" placeholder="Nomor Fax ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Default Currency/Mata Uang <?= $account ?> :</label>
                                <select class="form-control form-control-sm select2" name="Account_CurrencyId" id="Account_CurrencyId" required>
                                    <?php foreach ($Currencys->result() as $curr) : ?>
                                        <option <?= ($RowAccount->Account_CurrencyId == $curr->Currency_ID ? 'selected' : null) ?> value="<?= $curr->Currency_ID ?>" <?= ($curr->Is_Default == 1 ? 'selected' : '') ?>><?= $curr->Currency_ID ?> (<?= $curr->Currency_Description ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Payment Terms :</label>
                                <input type="number" class="form-control form-control-sm" value="<?= $RowAccount->PaymentTerms ?>" name="PaymentTerms" id="PaymentTerms" required placeholder="Payment Term ....">
                            </div>
                            <div class="col-lg-2 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Satuan Waktu Payment Term</label>
                                <select class="form-control form-control-sm select2" name="PaymentTerm" id="PaymentTerm" required>
                                    <option <?= ($RowAccount->PaymentTerm == 'Day' ? 'selected' : null) ?> value="Day">Hari</option>
                                    <option <?= ($RowAccount->PaymentTerm == 'Month' ? 'selected' : null) ?> value="Month">Bulan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-4 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Kondisi Penerimaan Dokumen :</label>
                                <select class="form-control form-control-sm" required name="PaymentTermTypeDoc" id="PaymentTermTypeDoc">
                                    <option <?= ($RowAccount->PaymentTermTypeDoc == 'AFTER INVOICE RECEIVED' ? 'selected' : null) ?> value="AFTER INVOICE RECEIVED">AFTER INVOICE RECEIVED</option>
                                    <option <?= ($RowAccount->PaymentTermTypeDoc == 'AFTER PO CLOSE' ? 'selected' : null) ?> value="AFTER PO CLOSE">AFTER PO CLOSE</option>
                                    <option <?= ($RowAccount->PaymentTermTypeDoc == 'AFTER GOODS RECEIVED NOTE' ? 'selected' : null) ?> value="AFTER GOODS RECEIVED NOTE">AFTER GOODS RECEIVED NOTE</option>
                                    <option <?= ($RowAccount->PaymentTermTypeDoc == 'AFTER PO DOCUMENT RECEIVED' ? 'selected' : null) ?> value="AFTER PO DOCUMENT RECEIVED">AFTER DOCUMENT PO RECEIVED</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-sm-6 px-4 form-group">
                                <label style="font-weight: 500;">Ketentuan Pembayaran :</label>
                                <textarea rows="4" class="form-control form-control-sm" name="PaymentCondition" id="PaymentCondition" placeholder="Payment Condition..."><?= $RowAccount->PaymentCondition ?></textarea>
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
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->BankName ?>" name="BankName" id="BankName" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?> placeholder="Nama Bank ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kode Bank :</label>
                                <input type="number" class="form-control form-control-sm" value="<?= $RowAccount->BankCode ?>" name="BankCode" id="BankCode" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?> placeholder="Kode Bank ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nomor Rekening :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->BankAccount ?>" name="BankAccount" id="BankAccount" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?> placeholder="Atas Nama ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Pemilik Rekening :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->BankAccName ?>" name="BankAccName" id="BankAccName" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?> placeholder="Atas Nama ....">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Bank Cabang :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowAccount->BankBranch ?>" name="BankBranch" id="BankBranch" placeholder="Cabang ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Mata Uang Bank :</label>
                                <select class="form-control form-control-sm select2" name="BankCurrencyID" id="BankCurrencyID" <?= ($IdentityPattern == 'VP' ? 'required' : null) ?>>
                                    <option value="" selected disabled>- Choose -</option>
                                    <?php foreach ($Currencys->result() as $curr) : ?>
                                        <option <?= ($RowAccount->BankCurrencyID == $curr->Currency_ID ? 'selected' : null) ?> value="<?= $curr->Currency_ID ?>"><?= $curr->Currency_ID ?> (<?= $curr->Currency_Description ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- =============================== END FORM =========================== -->
                    <div class="card-footer text-muted py-3 text-center mt-4">
                        <button type="button" href="#" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="location">
        <!-- Modal -->
        <div class="modal fade" id="M_Address" tabindex="-1" aria-labelledby="M_AddressLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="M_AddressLabel">List Alamat Pengiriman <?= $RowAccount->Account_Name ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" action="#" id="detail-form" style="display: none;">
                            <div class="row">
                                <div class="col-lg-8 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Alamat Lengkap :</label>
                                    <textarea class="form-control form-control-sm" name="Address" id="Address" required placeholder="...."></textarea>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Keterangan/Catatan/Deskripsi :</label>
                                    <textarea class="form-control form-control-sm" name="Description" id="Description" placeholder="...."></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Area :</label>
                                    <select type="text" class="form-control form-control-sm select2" name="Area" id="Area" required>
                                        <option value="" selected disabled>-Pilih-</option>
                                        <option value="Domestic">Dalam Negri (Domestic)</option>
                                        <option value="OverSeas">Luar Negri (Oversea)</option>
                                    </select>
                                </div>
                                <input type="hidden" name="Account_Code_Addr" id="Account_Code_Addr" value="<?= $RowAccount->Account_Code ?>">
                                <div class="col-lg-3 col-sm-12 px-4 form-group">
                                    <button type="button" class="btn btn-primary mt-4" id="submit-detail-form"><i class="fas fa-download"></i>&nbsp;&nbsp; Save</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <table id="DataTable" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr class="text-center text-white">
                                        <th>#</th>
                                        <th>Alamat Lengkap</th>
                                        <th>Area</th>
                                        <th>Deskripsi</th>
                                        <th>Aktivasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- hi dude i dude some magic here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="M_Contact" tabindex="-1" aria-labelledby="M_ContactLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="M_ContactLabel">List Contact : <?= $RowAccount->Account_Name ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" action="#" id="detail-form-contact" style="display: none;">
                            <input type="hidden" name="Account_Code_Contact" id="Account_Code_Contact" value="<?= $RowAccount->Account_Code ?>">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Nama Contact :</label>
                                    <input class="form-control form-control-sm" name="Contact_Name" id="Contact_Name" required placeholder="....">
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Nama Panggilan :</label>
                                    <input class="form-control form-control-sm" name="Contact_Initial_Name" id="Contact_Initial_Name" placeholder="....">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Gender :</label>
                                    <select class="form-control form-control-sm select2" name="Gender" id="Gender">
                                        <option value="">-Pilih-</option>
                                        <option value="laki-laki">Laki-laki</option>
                                        <option value="perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class=" row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Jabatan :</label>
                                    <input class="form-control form-control-sm" name="Job_title" id="Job_title" placeholder="....">
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Email :</label>
                                    <input type="email" class="form-control form-control-sm" name="Email_Address" id="Email_Address" placeholder="....">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Telpon :</label>
                                    <input class="form-control form-control-sm" name="Telephone" id="Telephone" required placeholder="....">
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">No. Handphone :</label>
                                    <input class="form-control form-control-sm" name="Mobile_Phone" id="Mobile_Phone" placeholder="....">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Fax :</label>
                                    <input class="form-control form-control-sm" name="Fax" id="Fax" placeholder="....">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Negara :</label>
                                    <select class="form-control form-control-sm select2" required name="Country_contact" id="Country_contact" placeholder="....">
                                        <?php foreach ($Countries as $flag) : ?>
                                            <option <?= ($RowAccount->Account_Country_Id == $flag->Country_ID ? 'selected' : null) ?> value="<?= $flag->Country_ID ?>" <?= ($flag->Is_Default == 1 ? 'selected' : '') ?>><?= $flag->Country_ID ?> (<?= $flag->Country_Name ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Provinsi/Negara Bagian :</label>
                                    <input class="form-control form-control-sm" name="State" id="State" placeholder="....">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Kota/Kabupaten :</label>
                                    <input class="form-control form-control-sm" name="City" id="City" placeholder="....">
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Alamat Lengkap :</label>
                                    <textarea class="form-control form-control-sm" name="Home_Address" id="Home_Address" placeholder="Alamat Lengkap...."></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: bold;">Catatan :</label>
                                    <textarea class="form-control form-control-sm" name="Note" id="Note" placeholder="...."></textarea>
                                </div>
                                <div class="col-lg-3 col-sm-12 px-4 form-group">
                                    <button type="button" class="btn btn-primary mt-4" id="submit-contact"><i class="fas fa-download"></i>&nbsp;&nbsp; Save</button>
                                    <button type="button" class="btn btn-danger mt-4" id="cancel-contact"><i class="fas fa-times"></i>&nbsp;&nbsp; Cancel</button>
                                </div>
                            </div>
                            <hr>
                        </form>
                        <div class="table-responsive" id="table-contact">
                            <table id="DataTable-Contact" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr class="text-center text-white">
                                        <th>Nama</th>
                                        <th>Panggilan</th>
                                        <th>Gender</th>
                                        <th>Jabatan</th>
                                        <th>Email</th>
                                        <th>Telpon</th>
                                        <th>Hp</th>
                                        <th>Negara</th>
                                        <th>Provinsi</th>
                                        <th>Kab/Kot</th>
                                        <th>Alamat</th>
                                        <th>Fax</th>
                                        <th>Note</th>
                                        <th>Aktivasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- hi dude i dude some magic here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>