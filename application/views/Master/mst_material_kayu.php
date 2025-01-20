<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">
                        <a href="<?= base_url('Master/form_add_material_kayu') ?>" class="btn bg-gradient-primary btn-xs">
                            <span class="btn-label"><i class="fas fa-plus"></i></span> | Data Material Kayu
                        </a>
                    </h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="Tbl-material-kayu" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">Kode</th>
                                    <th class="text-center text-white">Inisial</th>
                                    <th class="text-center text-white">Deskripsi</th>
                                    <th class="text-center text-white">T</th>
                                    <th class="text-center text-white">L</th>
                                    <th class="text-center text-white">P</th>
                                    <th class="text-center text-white">Std Psc/Lot</th>
                                    <th class="text-center text-white">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($materials->result() as $li) : ?>
                                    <tr id="<?= $li->sysid ?>">
                                        <td><?= $li->kode ?></td>
                                        <td><?= $li->inisial_kode ?></td>
                                        <td><?= $li->deskripsi ?></td>
                                        <td><?= floatval($li->tebal) ?> Cm</td>
                                        <td><?= floatval($li->lebar) ?> Cm</td>
                                        <td><?= floatval($li->panjang) ?> Cm</td>
                                        <td id="<?= $li->sysid ?>"><?= floatval($li->std_qty_lot) ?></td>
                                        <td class="text-center">
                                            <?php if ($li->is_active == 1) : ?>
                                                <button class="btn btn-xs bg-gradient-success is-active" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Status: aktif"><i class="fas fa-check-circle"></i></button>&nbsp;
                                                <button class="btn btn-xs bg-gradient-warning btn-edit" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Edit"><i class="far fa-edit"></i></button>
                                            <?php else : ?>
                                                <button class="btn btn-xs bg-gradient-danger is-active" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Status: non-aktif"><i class="fas fa-times-circle"></i></button>&nbsp;
                                            <?php endif; ?>
                                            <!-- &nbsp;<button class="btn btn-xs bg-gradient-info history" data-pk="<?= $li->sysid ?>"><i class="fas fa-map-signs"></i></button> -->
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

    </div>
</div>