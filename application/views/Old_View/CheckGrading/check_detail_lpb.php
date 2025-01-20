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
                                            <option selected value="<?= $lpb_hdr->id_supplier ?>"><?= $lpb_hdr->nama ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Grader :</label>
                                        <input type="text" class="form-control form-control-sm readonly" name="grader" required value="<?php if ($lpb_hdr->grader == '') : ?><?= $this->session->userdata('impsys_initial') ?><?php else : ?><?= $lpb_hdr->grader ?><?php endif; ?>">
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
                                            <input type="text" class="form-control form-control-sm readonly datepicker" required name="tgl_kirim" id="tgl_kirim" placeholder="Tanggal kirim..." value="<?= $lpb_hdr->tgl_kirim ?>">
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
                                            <input type="text" class="form-control form-control-sm readonly datepicker" name="tgl_finish_sortir" id="tgl_finish_sortir" required placeholder="Tanggal kirim..." value="<?php if ($lpb_hdr->tgl_finish_sortir == '0000-00-00') : ?><?= '' ?><?php else : ?> <?= $lpb_hdr->tgl_finish_sortir ?><?php endif; ?>">
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
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Asal Daerah :</label>
                                        <select class="form-control form-control-sm" required name="daerah" id="daerah">
                                            <option selected value="<?= $lpb_hdr->asal_kiriman ?>"><?= $lpb_hdr->asal_kiriman ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 mb-2">
                                    <?php if ($checker == 0) : ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-primary" id="add-row">
                                                <i class="fas fa-plus"></i> ADD
                                            </button> &nbsp;&nbsp;
                                            <button type="button" class="btn btn-xs btn-danger" id="remove-row">
                                                <i class="fas fa-times"></i> REMOVE
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-lg-12 col-sm-12">
                                    <table class="table table-sm table-striped table-bordered table-hover" style="width: 100%;" id="tbl-lpb">
                                        <thead style="background-color: #3B6D8C;">
                                            <tr class="text-white">
                                                <th class="text-center" style="width: 5%;">#</th>
                                                <th class="text-center" style="width: 10%;">NO. LOT</th>
                                                <th class="text-center" style="width: 25%;">UKURAN KAYU</th>
                                                <th class="text-center" style="width: 20%;">QTY</th>
                                                <th class=" text-center" style="width: 5%;"><i class="fas fa-print"></i></th>
                                                <?php if ($checker > 0) : ?>
                                                    <th class="text-center" style="width: 15%;">Penempatan</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lpb_dtls as $li) : ?>
                                                <tr class="default-row" data-pk="<?= $li->sysid ?>">
                                                    <td align="center" class="nomor"><?= $li->flag ?></td>
                                                    <td align="center" class="lot"><?= $li->no_lot ?></td>
                                                    <td align="center" class="ukuran">
                                                        <span class="form-group">
                                                            <select class="form-control form-control-xs" required name="ukuran[]" data-pk="<?= $li->sysid ?>" style="width: 100%;">
                                                                <option value="<?= $li->sysid_material ?>" selected><?= $li->deskripsi ?> (<?= $li->inisial_kode ?>)</option>
                                                            </select>
                                                        </span>
                                                    </td>
                                                    <td align="center" class="qty" data-pk="<?= $li->sysid ?>"><?= $li->qty ?></td>
                                                    <td align="center">
                                                        <?php if ($li->lot_printed == 1) : ?>
                                                            <button type="button" data-pk="<?= $li->sysid ?>" title="sudah print" class="btn btn-sm bg-gradient-success print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>
                                                        <?php else : ?>
                                                            <button type="button" data-pk="<?= $li->sysid ?>" title="belum print" class="btn btn-sm bg-gradient-danger print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>
                                                        <?php endif; ?>
                                                    </td>
                                                    <?php if ($checker > 0) : ?>
                                                        <td align="center">
                                                            <span class="form-group">
                                                                <select class="form-control form-control-xs" required name="placement[]" data-pk="<?= $li->sysid ?>" style="width: 100%;">
                                                                    <option value="<?= $li->placement ?>" selected><?= $li->placement ?></option>
                                                                    <option value="GUDANG KAYU BASAH">GUDANG KAYU BASAH</option>
                                                                </select>
                                                            </span>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12 col-sm-12 mb-2">
                                    <?php if ($checker == 0) : ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-primary" id="add-row-btm">
                                                <i class="fas fa-plus"></i> ADD
                                            </button> &nbsp;&nbsp;
                                            <button type="button" class="btn btn-xs btn-danger" id="remove-row-btm">
                                                <i class="fas fa-times"></i> REMOVE
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 mb-2">
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
                                            <div class="form-group">
                                                <label>Jumlah Kiriman(m3):</label>
                                                <input type="number" style="max-width: 50%;" class="form-control form-control-sm" required placeholder="Jumlah kubikasi kiriman..." name="jumlah_kiriman" id="jumlah_kiriman" value="<?= (floatval($lpb_hdr->jumlah_kiriman) != 0) ? floatval($lpb_hdr->jumlah_kiriman) : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Keterangan :</label>
                                                <textarea class="form-control form-control-sm" placeholder="keterangan" rows="3" name="keterangan" id="keterangan"><?= $lpb_hdr->keterangan ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <!-- <php if ($lpb_hdr->lot_printed != '0') : ?> -->
                            <?php if ($checker > 0) : ?>
                                <button type="button" class="btn btn-primary float-right ml-2" id="finish--lpb"><i class="fas fa-check"></i> | NYATAKAN SEBAGI L.P.B SELESAI</button>
                            <?php endif; ?>
                            <!-- <button type="button" class="btn btn-success float-right ml-2">NO.LOT SUDAH DI CETAK <i class="fas fa-check-circle"></i></button> -->
                            <!-- <php else : ?> -->
                            <button type="button" id="print-lot-number" class="btn btn-danger"><i class="fas fa-print"></i> | Print Semua No. Lot</button>
                            <!-- <button type="button" class="btn btn-warning float-right ml-2">NO.LOT BELUM DI CETAK <i class="fas fa-exclamation-triangle"></i></button> -->
                            <button type="button" class="btn btn-info float-right" id="save--lpb"><i class="fas fa-save"></i> | SIMPAN LPB</button>
                            <!-- <php endif; ?> -->
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <php if ($lpb_hdr->lot_printed != '0') : ?>
    <script>
        $(document).ready(function() {
            $('input').prop('disabled', true);
            $('select').prop('disabled', true);
            $('textarea').prop('disabled', true);
        });
    </script>
<php endif; ?> -->