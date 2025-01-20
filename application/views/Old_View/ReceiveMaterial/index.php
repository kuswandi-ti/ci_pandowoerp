<style>
    .form-control-xs {
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
                    <h3 class="card-title">Generate Nomor L.P.B</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" id="BeLpb" value="<?= $lpb ?>">
                    <!-- <input type="hidden" id="BeLot" value="<= $lot ?>"> -->
                    <form id="form_lpb" method="post" action="<?= base_url('ReceiveMaterial/store_form_lpb') ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Supplier :</label>
                                        <select class="form-control form-control-sm" required name="supplier" id="supplier"></select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal Kirim :</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control form-control-sm readonly datepicker" required name="tgl_kirim" id="tgl_kirim" placeholder="Tanggal kirim...">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Legalitas :</label>
                                        <select class="form-control form-control-sm" required name="legalitas" id="legalitas" required>
                                            <option value="" selected>-PILIH LEGALITAS-</option>
                                            <?php foreach ($legalitas as $li) : ?>
                                                <option value="<?= $li->kode_legalitas ?>"><?= $li->kode_legalitas ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>No. Legalitas :</label>
                                        <input type="text" class="form-control form-control-sm" name="no_legalitas" id="no_legalitas" placeholder="No Legalitas..." maxlength="20" minlength="4" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Asal Daerah :</label>
                                        <select class="form-control form-control-sm" required name="daerah" id="daerah"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6" style="display: none;">
                                    <div class="form-group">
                                        <label>Grader :</label>
                                        <input type="text" class="form-control form-control-sm readonly" readonly name="grader">
                                    </div>
                                </div>
                                <div class="col-sm-6" style="display: none;">
                                    <div class="form-group">
                                        <label>Selesai sortir :</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control form-control-sm readonly" readonly name="tgl_finish_sortir" id="tgl_finish_sortir" placeholder="Tanggal kirim...">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <hr /> -->
                            <div class="row" style="display: none;">
                                <div class="col-lg-12 col-sm-12 mb-2" style="display: none;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-primary" id="add-row">
                                            <i class="fas fa-plus"></i> ADD
                                        </button>&nbsp;&nbsp;
                                        <button type="button" class="btn btn-xs btn-danger" id="remove-row">
                                            <i class="fas fa-times"></i> REMOVE
                                        </button>
                                    </div>
                                </div>
                                <table class="table table-sm table-striped table-bordered table-hover" style="width: 100%;" id="tbl-lpb">
                                    <thead style="background-color: #3B6D8C;">
                                        <tr class="text-white">
                                            <th class="text-center" style="width: 10%;">#</th>
                                            <th class="text-center" style="width: 25%;">NO. LOT</th>
                                            <th class="text-center">UKURAN KAYU</th>
                                            <th class="text-center" style="width: 25%;">QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td align="center" class="nomor">1</td>
                                            <td align="center" class="lot">
                                                <span class="form-group">
                                                    <?= $lpb ?>-<?= 1 ?>
                                                </span>
                                            </td>
                                            <td align="center" class="ukuran">
                                                <span class="form-group">
                                                    <select class="form-control form-control-xs" name="ukuran[]" style="width: 100%;"></select>
                                                </span>
                                            </td>
                                            <td align="center" class="qty">
                                                <span class="form-group">
                                                    <input type="number" class="form-control form-control-xs text-center onlyfloat" value="0" placeholder="Qty..." name="qty[]">
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- <hr /> -->
                            <div class="row" style="display: none;">
                                <div class="col-lg-12 col-sm-12 mb-2">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Penilaian :</label>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="penilaian" value="JELEK" type="radio">
                                                        <label class="form-check-label">5 JELEK</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="penilaian" value="CUKUP" type="radio">
                                                        <label class="form-check-label">7 CUKUP</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="penilaian" value="BAGUS" type="radio">
                                                        <label class="form-check-label">9 BAGUS</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Keterangan :</label>
                                                <textarea class="form-control form-control-sm" placeholder="keterangan" rows="3" name="keterangan" id="keterangan"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right" id="submit-form"><i class="fas fa-save"></i> | BUAT NO. LPB</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>