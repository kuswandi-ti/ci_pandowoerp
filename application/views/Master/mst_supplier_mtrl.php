<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">
                        <a href="<?= base_url('Master/form_add_supplier') ?>" class="btn bg-gradient-primary btn-xs">
                            <span class="btn-label"><i class="fas fa-plus"></i></span> | Data supplier
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
                        <table id="tbl-supplier-mtrl" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">NAMA</th>
                                    <th class="text-center text-white">ALAMAT</th>
                                    <th class="text-center text-white">TELP</th>
                                    <th class="text-center text-white">EMAIL</th>
                                    <th class="text-center text-white">KORESPONDEN</th>
                                    <th class="text-center text-white">$ BONGKAR/KUBIK</th>
                                    <th class="text-center text-white">HANDLE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($suppliers->result() as $li) : ?>
                                    <tr id="<?= $li->sysid ?>">
                                        <td><?= $li->nama ?></td>
                                        <td><?= $li->alamat ?></td>
                                        <td><?= $li->telp ?></td>
                                        <td><?= $li->email ?></td>
                                        <td><?= $li->nama_kontak ?></td>
                                        <td>Rp. &nbsp;&nbsp;&nbsp;<?= number_format($li->uang_bongkar) ?></td>
                                        <td class="text-center">
                                            <?php if ($li->is_active == 1) : ?>
                                                <button class="btn btn-xs bg-gradient-success is-active" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Status: aktif"><i class="fas fa-check-circle"></i></button>&nbsp;
                                                <button class="btn btn-xs bg-gradient-warning btn-edit" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Edit"><i class="far fa-edit"></i></button>&nbsp;
                                                <button class="btn btn-xs bg-gradient-info btn-history" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="History perubahan harga material">&nbsp;<i class="fas fa-history"></i>&nbsp;</button>
                                                <button class="btn btn-xs bg-gradient-primary btn-manage" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Manage Harga Material">&nbsp;&nbsp;&nbsp;<i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;&nbsp;</button>
                                            <?php else : ?>
                                                <button class="btn btn-xs bg-gradient-danger is-active" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="Status: non-aktif"><i class="fas fa-times-circle"></i></button>&nbsp;
                                                <button class="btn btn-xs bg-gradient-info btn-history" data-pk="<?= $li->sysid ?>" data-toggle="tooltip" title="History perubahan harga material">&nbsp;<i class="fas fa-history"></i>&nbsp;</button>
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