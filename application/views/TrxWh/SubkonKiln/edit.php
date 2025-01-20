<style>
    .table-detail .header .left a {
        margin-right: 1rem;
        color: red;
    }

    .table-detail .header .left a:hover {
        text-decoration: revert;
    }

    .table-detail .header .left a>i {
        font-size: 11px;
    }

    .remove_item_dtl {
        color: red;
    }

    .select-currency {
        width: auto !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card bd-callout shadow add-data">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <input type="hidden" name="state" id="state" value="<?= $action ?>">
                    <input type="hidden" name="SysId" id="SysId" value="<?= $Hdr->SysId ?>">
                    <div class="card-header">
                        <h2 class="card-title mt-2"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('TrxWh/SubkonKiln/index') ?>" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">No. Document :</label>
                                <input type="text" class="form-control form-control-sm" name="DocNo" id="DocNo" placeholder="<?= $Hdr->DocNo ?>" readonly>
                            </div>
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Document :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input" value="<?= $Hdr->DocDate ?>" name="DocDate" id="DocDate" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Sub kontraktor :</label>
                                <select type="text" class="form-control form-control-sm select2" name="Account_Code" id="Account_Code" required>
                                    <option value="">-Pilih-</option>
                                    <?php foreach ($List_Vendor->result() as $li): ?>
                                        <option <?= ($Hdr->Account_Code == $li->Account_Code) ? 'selected' : '' ?> value="<?= $li->Account_Code ?>"><?= $li->AccountTitle_Code ?>. <?= $li->Account_Name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Referensi No.PO/RR untuk Subkon | No.Lpb untuk Pembelian Kayu Kering :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" value="<?= $Hdr->Ref_Number ?>" name="Ref_Number" id="Ref_Number" placeholder="Nomor Referensi RR/PO..." required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Keberangkatan :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input text-center" value="<?= $Hdr->Waktu_Keberangkatan ?>" name="Waktu_Keberangkatan" id="Waktu_Keberangkatan" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Estimasi Kembali :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input text-center" placeholder="Tanggal Estimasi" name="Waktu_Kepulangan" id="Waktu_Kepulangan" required value="<?= $Hdr->Waktu_Kepulangan ?>">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Mulai Proses Kiln :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-time text-center" placeholder="Waktu..." name="Estimasi_Mulai_Kiln" id="Estimasi_Mulai_Kiln" value="<?= $Hdr->Estimasi_Mulai_Kiln ?>" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-clock"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Selesai Proses Kiln :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-time text-center" placeholder="Waktu..." name="Estimasi_Selesai_Kln" id="Estimasi_Selesai_Kln" required value="<?= $Hdr->Estimasi_Selesai_Kln ?>">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-clock"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Jenis Kendaraan Pengangkut :</label>
                                <div class="input-group input-group-sm">
                                    <select class="form-control form-control-sm select2" name="Jenis_Kendaraan" id="Jenis_Kendaraan" required>
                                        <option value="">-Pilih-</option>
                                        <?php foreach ($Transport_with as $li): ?>
                                            <option <?= ($Hdr->Jenis_Kendaraan == $li->Transport_Name) ? 'selected' : '' ?> value="<?= $li->Transport_Name ?>"><?= $li->Transport_Name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">No Polisi :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" value="<?= $Hdr->No_Polisi ?>" placeholder="Plat Nomor..." name="No_Polisi" id="No_Polisi" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Alamat :</label>
                                <div class="input-group input-group-sm">
                                    <textarea class="form-control form-control-sm" required id="Account_Address" name="Account_Address" placeholder="Alamat Subkon..." rows="3" readonly="" data-sysid=""><?= $Hdr->Address ?></textarea>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" id="btn-list-address" type="button">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                    </div>
                                </div>
                                <input type="hidden" id="Account_Address_ID" name="Account_Address_ID" value="<?= $Hdr->Account_Addess_ID ?>">
                            </div>
                            <div class="col-lg-5 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Catatan :</label>
                                <div class="input-group input-group-sm">
                                    <textarea type="text" class="form-control form-control-sm" placeholder="catatan..." name="Note" id="Note" rows="3"><?= $Hdr->Note ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 px-4 table-detail">
                                <?php if ($action == 'update'): ?>
                                    <div class="d-flex justify-content-between header">
                                        <div class="d-flex">
                                            <a href="javascript:void(0);" class="search-data btn bg-gradient-danger mb-2">Pilih Bundle &nbsp;<i class="fab fa-searchengin"></i></a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="table-mini-container">
                                    <table class="table table-striped table-bordered" style="width: 100%;" id="table_data_selected">
                                        <thead style="background-color: #3B6D8C;">
                                            <tr class="text-white">
                                                <th>#</th>
                                                <th class="text-center text-white">BUNDLE</th>
                                                <th class="text-center text-white">KODE ITEM</th>
                                                <th class="text-center text-white">NAMA ITEM</th>
                                                <th class="text-center text-white">QTY</th>
                                                <th class="text-center text-white">KUBIKASI</th>
                                                <th class="text-center text-white"><i class="fas fa-clock"></i> MULAI KILN</th>
                                                <th class="text-center text-white"><i class="fas fa-clock"></i> SELESAI KILN</th>
                                                <?php if ($action == 'update'): ?>
                                                    <th class="text-center text-white"><i class="fas fa-trash"></i></th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <!-- SELECT SysId, SysId_Hdr, id_lot, lot, Item_Code, Item_Name, Start_time, End_Time, Kubikasi, Qty
                                        FROM bicarase_pandowo_db.qview_list_dtl_subkon_kiln; -->
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach ($Dtls as $index => $li): ?>
                                                <tr>
                                                    <td class="text-center align-middle"><input type="hidden" required="" name="ID[]" value="<?= $li->id_lot ?>">
                                                        <p class="mt-1"><?= $i ?></p>
                                                    </td>
                                                    <td class="text-center align-middle"><input type="hidden" required="" name="Lot[]" value="<?= $li->lot ?>"><?= $li->lot ?></td>
                                                    <td class="text-center align-middle"><?= $li->Item_Code ?></td>
                                                    <td class="text-center align-middle"><?= $li->Item_Name ?></td>
                                                    <td class="text-center align-middle"><?= $li->Qty ?></td>
                                                    <td class="text-center align-middle"><?= floatval($li->Kubikasi) ?></td>
                                                    <td class="text-center align-middle"><input name="Start_time[]" value="<?= substr($li->Start_time, 0, 16) ?>" type="text" placeholder="waktu...." style="height:40px;" class="form-control flatpickr-time form-control-sm text-center" id="Start_time_<?= $i ?>"></td>
                                                    <td class="text-center align-middle"><input name="End_Time[]" value="<?= substr($li->End_Time, 0, 16) ?>" type="text" placeholder="waktu...." style="height:40px;" class="form-control flatpickr-time form-control-sm text-center" id="End_Time_<?= $i ?>"></td>
                                                    <?php if ($action == 'update'): ?>
                                                        <td class="text-center align-middle"><button type="button" class="remove-row btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td>
                                                    <?php endif; ?>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="text-center mt-4" id="no_data_selected"><b>Tidak Ada Data</b></h5>
                            </div>
                        </div>
                        <!-- =============================== END FORM =========================== -->
                        <?php if ($action == 'update'): ?>
                            <div class="card-footer text-muted py-3 text-center mt-4">
                                <button type="button" href="javascript:void(0);" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="location-modal-lot">
    <div class="modal fade" id="modal_list_lot" aria-labelledby="Label" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 95%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Bundle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="#" method="post" id="filter-date">
                            <div class="row">
                                <div class="col-md-2">
                                    <p class="">Tgl Penerimaan LPB :</p>
                                </div>
                                <div class="col-lg-4 col-sm-8">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="from" id="from" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-01-01') ?>">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-info"><i class="fas fa-calendar"></i> S/D</button>
                                        </div>
                                        <input type="text" name="to" id="to" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-12-t') ?>">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr class="devider">
                        <div class="table-responsive">
                            <table id="Tbl_List_Lot" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr class="text-white">
                                        <th>#</th>
                                        <th class="text-center text-white">BUNDLE</th>
                                        <th class="text-center text-white">SUPPLIER</th>
                                        <th class="text-center text-white">ITEM</th>
                                        <th class="text-center text-white">PCS</th>
                                        <th class="text-center text-white">KUBIKASI</th>
                                        <th class="text-center text-white">GRADER</th>
                                        <th class="text-center text-white">TGL KIRIM</th>
                                        <th class="text-center text-white">LOKASI</th>
                                        <th class="text-center text-white">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- hi dude i dude some magic here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="select_data"><i class="fas fa-check"></i> &nbsp;&nbsp;&nbsp;Select</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="location-modal-address">
    <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="container">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addressModalLabel">Select Address</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table id="table-address"
                                    class="table table-sm table-bordered table-striped w-100">
                                    <thead>
                                        <tr class="bg-info">
                                            <th class="d-none">#</th>
                                            <th>#</th>
                                            <th>Address</th>
                                            <th>Area</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan dimasukkan di sini oleh JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btn-select-address" type="button" class="btn btn-primary">Select</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>