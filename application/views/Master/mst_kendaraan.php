<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <button type="button" class="btn bg-gradient-primary btn-xs" data-toggle="modal" data-target="#modal-add">
                        <span class="btn-label"><i class="fas fa-plus"></i></span> | Form Kendaraan Baru
                    </button>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-master" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">No Kendaraan</th>
                                    <th class="text-center text-white">Status Kepemilikan</th>
                                    <th class="text-center text-white">Jenis Kendaraan</th>
                                    <th class="text-center text-white">Note</th>
                                    <th class="text-center text-white">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($Kendaraans->result() as $li) : ?>
                                    <tr id="<?= $li->SysId ?>">
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center"><?= $li->No_Polisi ?></td>
                                        <td class="text-center"><?= $li->Status_Kepemilikan ?></td>
                                        <td class="text-center"><?= $li->Jenis ?></td>
                                        <td class="text-center"><?= $li->Remark ?></td>
                                        <td class="text-center">
                                            <?php if ($li->is_active == 1) : ?>
                                                <button class="btn btn-xs bg-gradient-success is-active" data-pk="<?= $li->SysId ?>" title="Change status"><i class="fas fa-check-circle"></i></button>
                                            <?php else : ?>
                                                <button class="btn btn-xs bg-gradient-danger is-active" data-pk="<?= $li->SysId ?>" title="Change status"><i class="fas fa-times-circle"></i></button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php $i++;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location">
        <div class="modal fade" id="modal-add" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="form-edit" method="POST" action="<?= base_url('Master/store_kendaraan') ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Form Kendaraan Baru</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="width: 130px;">Nomor Polisi :</span>
                                        </div>
                                        <input type="text" minlength="9" maxlength="11" class="form-control" required placeholder="No Polisi..." name="no_polisi" id="no_polisi">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="width: 130px;">Kepemilikan :</span>
                                        </div>
                                        <select class="form-control" required name="kepemilikan" id="kepemilikan">
                                            <option value="" selected disabled>-Pilih-</option>
                                            <option value="SEWA">SEWA</option>
                                            <option value="PRIBADI">PRIBADI</option>
                                            <option value="RENTAL">RENTAL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="width: 130px;">Jenis Kendaraan :</span>
                                        </div>
                                        <select class="form-control" required name="jenis" id="jenis">
                                            <option value="" selected disabled>-Pilih-</option>
                                            <option value="PICK-UP">PICK-UP</option>
                                            <option value="TRUCK">TRUCK</option>
                                            <option value="FUSO">FUSO</option>
                                            <option value="KONTAINER">KONTAINER</option>
                                            <option value="BOX">BOX</option>
                                            <option value="WING">WING</option>
                                            <option value="TRONTON">TRONTON</option>
                                            <option value="BALE">BALE</option>
                                            <option value="MOTOR">MOTOR</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="width: 130px;">CATATAN :</span>
                                        </div>
                                        <input type="text" maxlength="100" class="form-control" required placeholder="Catatan..." name="catatan" id="catatan">
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
        <!-- /.modal -->
    </div>
</div>