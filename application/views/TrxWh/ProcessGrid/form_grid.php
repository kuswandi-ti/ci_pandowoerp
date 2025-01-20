<style>
    .form-control-xs {
        height: calc(1.2em + .375rem + 2px) !important;
        padding: .125rem .25rem !important;
        font-size: .95rem !important;
        line-height: 1.5;
        border-radius: .2rem;
    }

    input.form-control.input-mini {
        width: 80%;
        height: calc(1.2em + .375rem + 2px) !important;
        padding: .125rem .25rem !important;
        font-size: .95rem !important;
        line-height: 1.5;
        border-radius: .2rem;
    }

    .rounded-circle {
        border-radius: 50%;
        width: 40px;
        /* Sesuaikan dengan ukuran yang diinginkan */
        height: 40px;
        /* Sesuaikan dengan ukuran yang diinginkan */
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">NO. LPB : <?= $lpb_hdr->lpb ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_lpb" method="post" action="<?= base_url('ReceiveMaterial/store_form_lpb') ?>">
                        <input type="hidden" value="<?= $lpb_hdr->lpb ?>" id="noLPB" name="lpb">
                        <input type="hidden" value="<?= $lpb_hdr->sysid ?>" id="sysid_hdr" name="sysid">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Supplier :</label>
                                        <select class="form-control form-control-sm" required name="supplier" id="supplier">
                                            <option selected value="<?= $lpb_hdr->id_supplier ?>"><?= $lpb_hdr->AccountTitle_Code . ' ' . $lpb_hdr->Account_Name . '(' . $lpb_hdr->Account_Code . ')' ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Grader :</label>
                                        <select class="form-control form-control-sm" name="grader" id="grader" required>
                                            <option value="">-pilih grader-</option>
                                            <?php foreach ($graders as $gr) : ?>
                                                <option value="<?= $gr->initial ?>" <?= ($lpb_hdr->grader == $gr->initial) ? 'selected' : '' ?>><?= $gr->nama_grader ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Legalitas :</label>
                                        <select class="form-control form-control-sm" required name="legalitas" id="legalitas">
                                            <?php foreach ($legalitas as $ul) : ?>
                                                <option value="<?= $ul->kode_legalitas ?>" <?php if ($ul->kode_legalitas == $lpb_hdr->legalitas) echo 'selected' ?>><?= $ul->kode_legalitas ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tanggal Kirim :</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control form-control-sm readonly flatpickr-input" required name="tgl_kirim" id="tgl_kirim" placeholder="Tanggal kirim..." value="<?= $lpb_hdr->tgl_kirim ?>">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Selesai sortir :</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control form-control-sm readonly flatpickr-input" name="tgl_finish_sortir" id="tgl_finish_sortir" required placeholder="Tanggal kirim..." value="<?php if ($lpb_hdr->tgl_finish_sortir == '0000-00-00') : ?><?= '' ?><?php else : ?> <?= $lpb_hdr->tgl_finish_sortir ?><?php endif; ?>">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>No. Legalitas :</label>
                                        <input class="form-control form-control-sm" required name="no_legalitas" id="no_legalitas" value="<?= $lpb_hdr->no_legalitas ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Currency :</label>
                                        <select class="form-control form-control-sm" required name="Currency" id="Currency">
                                            <?php foreach ($List_Currency->result() as $curr) : ?>
                                                <option <?= ($curr->Currency_ID ==  $lpb_hdr->Currency ? 'selected' : null) ?> value="<?= $curr->Currency_ID ?>"><?= $curr->Currency_ID ?> (<?= $curr->Currency_Description ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Rate to IDR :</label>
                                        <input type="number" value="1" class="form-control form-control-sm" required name="Rate" id="Rate">
                                    </div>
                                </div>
                                <?php if ($lpb_hdr->legalitas == 'SALES RETURN'): ?>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Sales Return :</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $lpb_hdr->SR_Numb ?>" required name="SR_Numb" id="SR_Numb">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-12 col-lg-12 mb-2">
                                    <?php if ($action == 'edit') : ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-primary add-row">
                                                <i class="fas fa-plus"></i> ADD
                                            </button> &nbsp;&nbsp;
                                            <button type="button" class="btn btn-xs btn-danger remove-row">
                                                <i class="fas fa-times"></i> REMOVE
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="table-responsive">
                                    <div class="col-lg-12 col-sm-12">
                                        <table class="table table-sm display compact dt-nowrap table-bordered" id="tbl-lpb">
                                            <thead style="background-color: #3B6D8C;">
                                                <tr>
                                                    <th class="text-center text-white">#</th>
                                                    <th class="text-center text-white">No. Bundle</th>
                                                    <th class="text-center text-white" style="max-width: 150px;">Jenis/Item</th>
                                                    <!-- <th class="text-center text-white" style="min-width: 150px;">QTY</th> -->
                                                    <?php if ($action != 'edit') : ?>
                                                        <th class="text-center text-white" style="min-width: 150px;">Kubikasi (M3)</th>
                                                    <?php endif; ?>
                                                    <th class=" text-center" style="min-width: 50px;"><i class="fas fa-print text-white"></i></th>
                                                    <th class="text-center text-white" style="width: 15%;">Penempatan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="main-tbody">
                                                <?php if ($action == 'edit') : ?>
                                                    <?php foreach ($lpb_dtls as $li) : ?>
                                                        <tr class="row-lot" data-pk="<?= $li->sysid ?>">
                                                            <td class="nomor text-center align-middle"><?= $li->flag ?></td>
                                                            <td class="lot text-center align-middle"><?= $li->no_lot ?></td>
                                                            <td class="ukuran text-center align-middle">
                                                                <span class="form-group">
                                                                    <select class="form-control form-control-xs" required name="ukuran[]" data-pk="<?= $li->sysid ?>" style="width: 100%;">
                                                                        <option value="<?= $li->sysid_material ?>" selected><?= $li->deskripsi ?> (<?= $li->kode ?>)</option>
                                                                    </select>
                                                                </span>
                                                            </td>
                                                            <!-- <td class="qty text-primary text-center align-middle" data-pk="<?= $li->sysid ?>"><?= $li->qty ?></td> -->
                                                            <td class="text-center align-middle">
                                                                <?php if ($li->lot_printed == 1) : ?>
                                                                    <button type="button" data-pk="<?= $li->sysid ?>" title="sudah print" class="btn btn-xs bg-gradient-success print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>
                                                                <?php else : ?>
                                                                    <button type="button" data-pk="<?= $li->sysid ?>" title="belum print" class="btn btn-xs bg-gradient-danger print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>
                                                                <?php endif; ?>
                                                            </td>
                                                            <!-- <php if ($checker > 0) : ?> -->
                                                            <td class="text-center align-middle">
                                                                <span class="form-group">
                                                                    <select class="form-control form-control-xs" required name="placement[]" data-pk="<?= $li->sysid ?>" style="width: 100%;">
                                                                        <option value="<?= $li->placement ?>" selected><?= $li->Warehouse_Name ?></option>
                                                                    </select>
                                                                </span>
                                                            </td>
                                                            <!-- <php endif; ?> -->
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6" class="bg-light">
                                                                <table cellpadding="5" cellspacing="0" border="0" class="ml-4 my-2 table-mini" style="width: 70vh;">
                                                                    <tbody class="bg-white">
                                                                        <?php
                                                                        $childs = $this->db->get_where('qview_dtl_size_item_lpb', ['Id_Lot' => $li->sysid])->result()
                                                                        ?>
                                                                        <?php foreach ($childs as $child) : ?>
                                                                            <tr data-pk="<?= $child->SysId ?>">
                                                                                <td class="text-center align-middle"><?= $child->flag ?></td>
                                                                                <td class="align-middle text-center">
                                                                                    <span class="form-group">
                                                                                        <select class="form-control form-control-xs w-50" required name="ukuran-child[]" data-pk="<?= $child->SysId ?>">
                                                                                            <option value="<?= $child->Id_Size_Item ?>" selected><?= $child->Size_Code ?></option>
                                                                                        </select>
                                                                                    </span>
                                                                                </td>
                                                                                <td class="qty-child text-primary align-middle text-center w-25" data-pk="<?= $child->SysId ?>"><?= floatval($child->Qty) ?></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                                <div class="ml-4 my-2">
                                                                    <button type="button" class="btn btn-xs btn-success add-child" data-pk="<?= $li->sysid ?>">
                                                                        &nbsp;&nbsp;<i class="fas fa-plus"></i>&nbsp;&nbsp;
                                                                    </button> &nbsp;
                                                                    <button type="button" class="btn btn-xs btn-danger remove-child" data-pk="<?= $li->sysid ?>">
                                                                        &nbsp;&nbsp;<i class="fas fa-trash"></i>&nbsp;&nbsp;
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <?php foreach ($lpb_dtls as $li) : ?>
                                                        <tr class="default-row border border-primary" data-pk="<?= $li->sysid ?>">
                                                            <td class="nomor text-center align-middle"><?= $li->flag ?></td>
                                                            <td class="lot text-center align-middle"><?= $li->no_lot ?></td>
                                                            <td class="ukuran text-center align-middle">
                                                                <span class="form-group">
                                                                    <select class="form-control form-control-xs" required data-pk="<?= $li->sysid ?>" style="width: 100%;">
                                                                        <option value="<?= $li->sysid_material ?>" selected><?= $li->deskripsi ?> (<?= $li->kode ?>)</option>
                                                                    </select>
                                                                </span>
                                                            </td>
                                                            <td class="text-center align-middle" data-pk="<?= $li->sysid ?>"><?= $li->kubikasi ?></td>
                                                            <td class="text-center align-middle">
                                                                <?php if ($li->lot_printed == 1) : ?>
                                                                    <button type="button" data-pk="<?= $li->sysid ?>" title="sudah print" class="btn btn-xs bg-gradient-success print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>
                                                                <?php else : ?>
                                                                    <button type="button" data-pk="<?= $li->sysid ?>" title="belum print" class="btn btn-xs bg-gradient-danger print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>
                                                                <?php endif; ?>
                                                            </td>
                                                            <!-- <php if ($checker > 0) : ?> -->
                                                            <td class="text-center align-middle">
                                                                <span class="form-group">
                                                                    <select class="form-control form-control-xs" required data-pk="<?= $li->sysid ?>" style="width: 100%;">
                                                                        <option value="<?= $li->placement ?>" selected><?= $li->Warehouse_Name ?></option>
                                                                    </select>
                                                                </span>
                                                            </td>
                                                            <!-- <php endif; ?> -->
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6" class="bg-light">
                                                                <table cellpadding="5" cellspacing="0" border="0" class="ml-4 my-2 table-mini" style="width: 70vh;">
                                                                    <tbody class="bg-white">
                                                                        <?php
                                                                        $childs = $this->db->get_where('qview_dtl_size_item_lpb', ['Id_Lot' => $li->sysid])->result()
                                                                        ?>
                                                                        <?php foreach ($childs as $child) : ?>
                                                                            <tr data-pk="<?= $child->SysId ?>">
                                                                                <td class="text-center align-middle"><?= $child->flag ?></td>
                                                                                <td class="align-middle text-center">
                                                                                    <span class="form-group">
                                                                                        <select class="form-control form-control-xs" required data-pk="<?= $child->SysId ?>">
                                                                                            <option value="<?= $child->Id_Size_Item ?>" selected><?= $child->Size_Code ?></option>
                                                                                        </select>
                                                                                    </span>
                                                                                </td>
                                                                                <td class="align-middle text-center w-25" data-pk="<?= $child->SysId ?>" sty><?= floatval($child->Qty) ?></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                                <?php if ($action == 'edit') : ?>
                                                                    <div class="ml-4 my-2">
                                                                        <button type="button" class="btn btn-xs btn-success add-child" data-pk="<?= $li->sysid ?>">
                                                                            &nbsp;&nbsp;<i class="fas fa-plus"></i>&nbsp;&nbsp;
                                                                        </button> &nbsp;
                                                                        <button type="button" class="btn btn-xs btn-danger remove-child" data-pk="<?= $li->sysid ?>">
                                                                            &nbsp;&nbsp;<i class="fas fa-trash"></i>&nbsp;&nbsp;
                                                                        </button>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 mb-2 mt-2">
                                    <?php if ($action == 'edit') : ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-primary add-row">
                                                <i class="fas fa-plus"></i> ADD
                                            </button> &nbsp;&nbsp;
                                            <button type="button" class="btn btn-xs btn-danger remove-row">
                                                <i class="fas fa-times"></i> REMOVE
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Penilaian :</label>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" name="penilaian" required value="JELEK" <?php if ($lpb_hdr->penilaian == "JELEK") echo 'checked' ?> type="radio">
                                                <label class="form-check-label">5 JELEK</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="penilaian" value="CUKUP" <?php if ($lpb_hdr->penilaian == "CUKUP") echo 'checked' ?> type="radio">
                                                <label class="form-check-label">7 CUKUP</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="penilaian" value="BAGUS" <?php if ($lpb_hdr->penilaian == "BAGUS") echo 'checked' ?> type="radio">
                                                <label class="form-check-label">9 BAGUS</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group w-75">
                                        <label>Asal Daerah :</label>
                                        <select class="form-control form-control-sm" required name="daerah" id="daerah">
                                            <option selected value="<?= $lpb_hdr->asal_kiriman ?>"><?= $lpb_hdr->asal_kiriman ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Keterangan :</label>
                                        <textarea class="form-control form-control-sm" placeholder="keterangan" rows="3" name="keterangan" id="keterangan"><?= $lpb_hdr->keterangan ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Kubikasi Jumlah Kiriman:</label>
                                        <input type="number" style="max-width: 75%;" class="form-control form-control-sm" required placeholder="Jumlah kubikasi kiriman..." name="jumlah_kiriman" id="jumlah_kiriman" value="<?= (floatval($lpb_hdr->jumlah_kiriman) != 0) ? floatval($lpb_hdr->jumlah_kiriman) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pcs Jumlah Kiriman:</label>
                                        <input type="number" style="max-width: 75%;" class="form-control form-control-sm" required placeholder="Jumlah pcs kiriman..." name="jumlah_pcs_kiriman" id="jumlah_pcs_kiriman" value="<?= (floatval($lpb_hdr->jumlah_pcs_kiriman) != 0) ? floatval($lpb_hdr->jumlah_pcs_kiriman) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Upah Bongkar :</label>
                                        <input type="number" style="max-width: 75%;" class="form-control form-control-sm" required placeholder="Nominal Uang Bongkar..." name="tanggungan_uang_bongkar" id="tanggungan_uang_bongkar" value="<?= (floatval($lpb_hdr->tanggungan_uang_bongkar) != 0) ? floatval($lpb_hdr->tanggungan_uang_bongkar) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Total Kubikasi LPB :</label>
                                        <input type="number" style="max-width: 75%;" class="form-control form-control-sm" required readonly placeholder="Kubikasi LPB..." name="TotKubikasi" id="TotKubikasi" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 mt-2">
                                    <a href="<?= base_url('TrxWh/ProcessGrid/approval') ?>" class="btn bg-gradient-danger"><i class="fas fa-arrow-left"></i> Back</a>
                                    <button type="button" id="print-lot-number" class="btn bg-gradient-danger"><i class="fas fa-print"></i> | Print Semua No. Bunlde</button>
                                </div>
                                <?php if ($action == 'edit') : ?>
                                    <div class="col-lg-6 col-sm-12 mt-2">
                                        <button type="button" class="btn btn-info float-md-right" id="save--lpb"><i class="fas fa-save"></i> | SIMPAN LPB</button>
                                        <button type="button" id="btn-send-approval" class="btn bg-gradient-warning btn-send-approval float-md-right mr-2" data-pk="<?= $lpb_hdr->lpb ?>" data-toggle="tooltip" title="Ajukan Approval"><i class="fas fa-share"></i> | Ajukan Approval</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/global-assets/x-editable/dist/bootstrap4-editable/js/tooltip.js"></script>
<script src="<?= base_url() ?>assets/global-assets/x-editable/dist/bootstrap4-editable/js/povoper.js"></script>
<script src="<?= base_url() ?>assets/global-assets/x-editable/dist/bootstrap4-editable/js/bootstrap-editable.js"></script>