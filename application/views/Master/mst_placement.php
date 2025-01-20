<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <button type="button" class="btn bg-gradient-primary btn-xs" data-toggle="modal" data-target="#modal-add-oven">
                        <span class="btn-label"><i class="fas fa-plus"></i></span> | Master Placement
                    </button>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?= $this->session->flashdata('success') ?>
                        </div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('danger')) { ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?= $this->session->flashdata('danger') ?>
                        </div>
                    <?php } ?>
                    <div class="table-responsive">
                        <table id="tbl-master" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">Kategori</th>
                                    <th class="text-center text-white">Nama Lokasi</th>
                                    <th class="text-center text-white">Keterangan</th>
                                    <th class="text-center text-white">Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($placements->result() as $li) : ?>
                                    <tr id="<?= $li->sysid ?>">
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center"><?= $li->kategori ?></td>
                                        <td class="text-center"><?= $li->lokasi ?></td>
                                        <td class="text-center"><?= $li->keterangan ?></td>
                                        <td class="text-center">
                                            <?php if ($li->is_active == 1) : ?>
                                                <button class="btn btn-xs bg-gradient-success is-active" data-pk="<?= $li->sysid ?>" title="Change status"><i class="fas fa-check-circle"></i></button>
                                            <?php else : ?>
                                                <button class="btn btn-xs bg-gradient-danger is-active" data-pk="<?= $li->sysid ?>" title="Change status"><i class="fas fa-times-circle"></i></button>
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
        <div class="modal fade" id="modal-add-oven" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="form-edit" method="POST" action="<?= base_url('Master/store_placement') ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Master Data Placement</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="width: 130px;">Kategori :</span>
                                        </div>
                                        <select class="form-control" required name="kategori" id="kategori">
                                            <option selected disabled>-PILIH KATEGORI-</option>
                                            <option value="BASAH">MATERIAL BASAH</option>
                                            <option value="KERING">MATERIAL KERING</option>
                                            <option value="LAIN-LAIN">LAIN-LAIN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="width: 130px;">Nama Lokasi :</span>
                                        </div>
                                        <input type="text" class="form-control" required placeholder="Nama lokasi..." name="nama" id="nama">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="width: 130px;">Deskripsi :</span>
                                        </div>
                                        <input type="text" class="form-control" required placeholder="Deskripsi lokasi..." name="deskripsi" id="deskripsi">
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