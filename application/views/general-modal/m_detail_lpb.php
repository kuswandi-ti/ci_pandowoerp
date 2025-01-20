<div class="modal fade" id="modal-detail-lpb" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">LPB : <?= $lpb_hdr->lpb ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <form id="form_lpb" method="post" action="<= base_url('ReceiveMaterial/store_form_lpb') ?>"> -->
                <input type="hidden" value="<?= $lpb_hdr->lpb ?>" id="noLPB" name="lpb">
                <input type="hidden" value="<?= $lpb_hdr->sysid ?>" id="sysid_hdr" name="sysid">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Supplier :</label>
                            <select class="form-control form-control-sm" required name="supplier" id="supplier">
                                <option selected value="<?= $lpb_hdr->id_supplier ?>"><?= $lpb_hdr->nama ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Grader :</label>
                            <input type="text" class="form-control form-control-sm readonly" name="grader" required value="<?= $lpb_hdr->grader ?>">
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
                        <table class="table table-sm table-striped table-bordered table-hover" style="width: 100%;" id="tbl_preview_lpb">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-white">
                                    <th class="text-center">#</th>
                                    <th class="text-center">NO. LOT</th>
                                    <th class="text-center">UKURAN KAYU</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-center">LOKASI</th>
                                    <th class="text-center">PRINT</th>
                                    <th class="text-center">STATUS</th>
                                    <th>Harga\Pcs</th>
                                    <th>Kubikasi</th>
                                    <th>Sub-Total</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lpb_dtls as $li) : ?>
                                    <tr class="default-row" data-pk="<?= $li->sysid ?>">
                                        <td align="center" class="nomor"><?= $li->flag ?></td>
                                        <td align="center" class="lot"><?= $li->no_lot ?></td>
                                        <td align="center" class="ukuran">
                                            <?= $li->deskripsi ?> (<?= $li->inisial_kode ?>)
                                        </td>
                                        <td align="center" class="qty" data-pk="<?= $li->sysid ?>"><?= $li->qty ?></td>
                                        <td align="center" data-pk="<?= $li->sysid ?>"><?= $li->placement ?></td>
                                        <td align="center">
                                            <?php if ($li->lot_printed == 1) : ?>
                                                <a href="<?= base_url('DatabaseLpb/tempelan_single_lot/' . $li->sysid) ?>" target="_blank" title="sudah print" class="btn btn-sm bg-gradient-success">&nbsp;<i class="fas fa-print"></i>&nbsp;</a>
                                            <?php else : ?>
                                                <a href="<?= base_url('DatabaseLpb/tempelan_single_lot/' . $li->sysid) ?>" target="_blank" title="belum print" class="btn btn-sm bg-gradient-danger">&nbsp;<i class="fas fa-print"></i>&nbsp;</a>
                                            <?php endif; ?>
                                        </td>
                                        <td align="center"><button class="btn btn-xs btn-info btn-flat"><i class="fas fa-map-marker-alt blink_me"></i> <?= $li->status_kayu ?></button></td>
                                        <td><?= $li->harga_per_pcs ?></td>
                                        <td><?= $li->kubikasi * $li->qty ?></td>
                                        <td><?= $li->harga_per_pcs * $li->qty ?></td>
                                        <td><?= $lpb_hdr->nama ?></td>
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
                        <div class="row">
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
                                        <input type="text" class="form-control form-control-sm readonly" required name="no_legalitas" id="no_legalitas" placeholder="No. Legalitas..." value="<?= $lpb_hdr->no_legalitas ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fas fa-file"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </form> -->
            </div>
            <div class="modal-footer">
                <a href="<?= base_url('DatabaseLpb/tempelan_lot_material/' . $lpb_hdr->lpb) ?>" target="_blank" class="btn btn-secondary mr-auto"><i class="fas fa-print"></i> | Print NO. LOT</a>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(function() {
        $('input').prop('disabled', true)
        $('textarea').prop('readonly', true)
        $('select').prop('disabled', true)

        var table = $("#tbl_preview_lpb").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "columnDefs": [{
                "targets": [7, 8, 9, 10],
                "visible": false,
                "searchable": false
            }, ]
        })
    })
</script>