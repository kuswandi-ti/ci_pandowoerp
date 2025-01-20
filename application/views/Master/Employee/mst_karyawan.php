<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <a href="<?= base_url('MasterData/Employee/form_add_karyawan') ?>" class="btn bg-gradient-primary btn-xs">
                        <span class="btn-label"><i class="fas fa-plus"></i></span> | Tambah Data Karyawan
                    </a>
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
                                    <th class="text-center text-white">NIK</th>
                                    <th class="text-center text-white">NAMA</th>
                                    <th class="text-center text-white">INIT</th>
                                    <th class="text-center text-white">TYPE-S</th>
                                    <th class="text-center text-white">TELP</th>
                                    <th class="text-center text-white">JABATAN</th>
                                    <th class="text-center text-white">DEPT</th>
                                    <th class="text-center text-white">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($karyawans->result() as $li) : ?>
                                    <tr id="<?= $li->sysid ?>">
                                        <td class="text-center"><?= $li->nik ?></td>
                                        <td><?= $li->nama ?></td>
                                        <td class="text-center"><?= $li->initial ?></td>
                                        <td v><?= $li->type_pembayaran ?></td>
                                        <td><?= $li->telp1 ?></td>
                                        <td><?= $li->jabatan ?></td>
                                        <td><?= $li->department ?></td>
                                        <td class="text-center">
                                            <?php if ($li->is_active == 1) : ?>
                                                <button class="btn btn-xs bg-gradient-success is-active" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Status: aktif"><i class="fas fa-check-circle"></i></button>&nbsp;
                                                <button class="btn btn-xs bg-gradient-warning btn-edit" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Edit"><i class="far fa-edit"></i></button>
                                            <?php else : ?>
                                                <button class="btn btn-xs bg-gradient-danger is-active" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Status: non-aktif"><i class="fas fa-times-circle"></i></button>&nbsp;
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

    </div>
</div>