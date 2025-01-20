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
                    <!-- <form id="form_lpb" method="post" action="<= base_url('ReceiveMaterial/store_form_lpb') ?>"> -->
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
                                    <input type="text" class="form-control form-control-sm readonly" name="grader" required value="<?= $lpb_hdr->grader ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Daerah Asal :</label>
                                    <select class="form-control form-control-sm" required name="daerah" id="daerah">
                                        <option selected value="<?= $lpb_hdr->asal_kiriman ?>"><?= $lpb_hdr->asal_kiriman ?></option>
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
                                        <input type="text" class="form-control form-control-sm readonly datepicker" name="tgl_finish_sortir" id="tgl_finish_sortir" required placeholder="Tanggal kirim..." value="<?= $lpb_hdr->tgl_finish_sortir ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Legalitas:</label>
                                    <select class="form-control form-control-sm" name="legalitas" id="legalitas">
                                        <option value="<?= $lpb_hdr->legalitas ?>" selected><?= $lpb_hdr->legalitas ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <table class="table-sm display compact nowrap table-striped table-bordered table-hover" style="width: 100%; color:black;" id="tbl-dtl-lpb">
                                    <thead style="background-color: #3B6D8C;">
                                        <tr class="text-white">
                                            <th class="text-center">#</th>
                                            <th class="text-center">NO. LOT</th>
                                            <th class="text-center">UKURAN KAYU</th>
                                            <th class="text-center">Harga/Pcs</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">M3</th>
                                            <th class="text-center">Sub-Total(Rp)</th>
                                            <th class="text-center">STATUS</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $tot_kubikasi = 0; ?>
                                        <?php foreach ($lpb_dtls as $li) : ?>
                                            <tr class="default-row" data-pk="<?= $li->sysid ?>" id="row--<?= $li->sysid ?>">
                                                <td align="center" class="nomor"><?= $li->flag ?></td>
                                                <td align="center" class="lot"><?= $li->no_lot ?></td>
                                                <td align="center" class="ukuran">
                                                    <?= $li->deskripsi ?> (<?= $li->inisial_kode ?>)
                                                </td>
                                                <td align="center">Rp. &nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" data-pk="<?= $li->sysid ?>" class="editable_price"><?= number_format(floatval($li->harga_per_pcs), 0, '.', ',') ?></a></td>
                                                <td align="center" class="qty" data-pk="<?= $li->sysid ?>"><?= $li->qty ?></td>
                                                <td align="center"><?= floatval($li->kubikasi * $li->qty) ?> <?php $tot_kubikasi +=  floatval($li->kubikasi * $li->qty); ?></td>
                                                <td align="right">Rp. <?= number_format(floatval($li->harga_per_pcs * $li->qty), 0, ',', '.') ?></td>
                                                <td align="center"><span class="badge badge-info"><i class="fas fa-map-marker-alt blink_me"></i> <?= $li->status_kayu ?></span></td>
                                                <td align="center">
                                                    <?php if ($li->lot_printed == 1) : ?>
                                                        <a href="<?= base_url('DatabaseLpb/tempelan_single_lot/' . $li->sysid) ?>" target="_blank" title="sudah print" class="badge bg-gradient-success">&nbsp;<i class="fas fa-print"></i>&nbsp;</a>
                                                    <?php else : ?>
                                                        <a href="<?= base_url('DatabaseLpb/tempelan_single_lot/' . $li->sysid) ?>" target="_blank" title="belum print" class="badge bg-gradient-danger">&nbsp;<i class="fas fa-print"></i>&nbsp;</a>
                                                    <?php endif; ?>
                                                    <?php if ($li->into_oven == '0') : ?>
                                                        &nbsp;<a href="javascript:void(0)" data-toggle="tooltip" data-pk="<?= $li->sysid ?>" data-title="Hapus <?= $li->no_lot ?>" title="Hapus <?= $li->no_lot ?>" class="badge bg-gradient-danger btn-delete">&nbsp;<i class="fas fa-trash"></i>&nbsp;</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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
                                            <label>Jumlah Kiriman(m3):</label><br>
                                            <a type="number" href="javascript:void(0)" data-pk="<?= $lpb_hdr->sysid ?>" class="btn btn-sm btn-dark text-info editable_m3_kirim"><?= floatval($lpb_hdr->jumlah_kiriman) ?></a> (m3)
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Keterangan :</label>
                                            <textarea class="form-control form-control-sm" placeholder="keterangan" rows="3" name="keterangan" id="keterangan"><?= $lpb_hdr->keterangan ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Persentase Kubikasi Diterima :</label>
                                            <?php
                                            $persent = (floatval($tot_kubikasi) / max($lpb_hdr->jumlah_kiriman, 1)) * 100;
                                            $persentase = round($persent, 2);
                                            if ($persentase < 80) {
                                                $text_kalkulasi = 'JELEK';
                                            } else if ($persentase < 90) {
                                                $text_kalkulasi = 'CUKUP';
                                            } else {
                                                $text_kalkulasi = 'BAGUS';
                                            }
                                            ?>
                                            <p><?= $persentase ?>% (<?= $text_kalkulasi ?>)</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Total Diterima:</label>
                                            <p><?= floatval($tot_kubikasi) ?> (m3)</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Upah Bongkar :</label>
                                            <p>Rp. <a href="javascript:void(0)" data-pk="<?= $lpb_hdr->sysid ?>" class="editable_uang_bongkar btn btn-sm btn-dark text-info"><?= number_format($lpb_hdr->tanggungan_uang_bongkar, 0, ',', '.') ?></a> <b>X</b> <?= floatval($lpb_hdr->jumlah_kiriman) ?> (m3) = Rp. <?= number_format(floatval($lpb_hdr->tanggungan_uang_bongkar) *  floatval($lpb_hdr->jumlah_kiriman), 0, ',', '.') ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Checker :</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm readonly" required name="checker" id="checker" placeholder="Checker..." value="<?= $lpb_hdr->selesai_by ?>">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fas fa-search"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>NO. LEGALITAS :</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm readonly" required name="no_lpb" id="no_lpb" placeholder="No. Legalitas..." value="<?= $lpb_hdr->no_legalitas ?>">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fas fa-file"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- </form> -->
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('DatabaseLpb/tempelan_lot_material/' . $lpb_hdr->lpb) ?>" target="_blank" class="btn btn-danger"><i class="fas fa-print"></i> | Print NO. LOT</a>
                    <a href="<?= base_url() ?>DatabaseLpb/report_commercial_lpb/<?= $lpb_hdr->lpb ?>" target="_blank" class="btn bg-gradient-danger float-right" data-toggle="tooltip" title="Report Commercial LPB"><i class="fas fa-print"></i> | Commercial Report</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="location">
    <div class="modal fade" id="modal-add-lot" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form-edit" method="POST" action="<?= base_url('DatabaseLpb/store_add_lot_susulan') ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Lot Susulan, LPB : <?= $lpb_hdr->lpb ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="<?= $lpb_hdr->lpb ?>" id="lpb" name="lpb">
                        <input type="hidden" value="<?= $lpb_hdr->sysid ?>" id="sysid_hdr_" name="sysid_hdr">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <select class="form-control" required name="ukuran" id="ukuran" style="width: 100%;"></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Deskripsi :</span>
                                    </div>
                                    <input type="number" class="form-control" required placeholder="Quantity..." name="Qty" id="Qty">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>