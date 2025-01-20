<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <button class="btn bg-gradient-primary btn-xs" data-pk="modal-list-karyawan" id="btn--add">
                        <span class="btn-label"><i class="fas fa-plus"></i></span> | PENANGGUNG JAWAB GRADE LPB
                    </button>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-master-karyawan" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">NIK</th>
                                    <th class="text-center text-white">NAMA</th>
                                    <th class="text-center text-white">INIT</th>
                                    <th class="text-center text-white">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($checker->result() as $li) : ?>
                                    <tr id="<?= $li->sysid ?>">
                                        <td><?= $i; ?></td>
                                        <td><?= $li->nik ?></td>
                                        <td><?= $li->nama ?></td>
                                        <td><?= $li->initial ?></td>
                                        <td>
                                            <button class="btn btn-xs bg-gradient-danger btn-delete" data-pk="<?= $li->nik ?>" data-toggle="tooltip" title="Delete authority checker"><i class="fas fa-trash"></i></button>
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