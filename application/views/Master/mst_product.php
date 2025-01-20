<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <button type="button" class="btn bg-gradient-primary btn-xs" data-toggle="modal" data-target="#modal-add">
                        <span class="btn-label"><i class="fas fa-plus"></i></span> | Master Product
                    </button>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <table id="tbl-master" class="table-sm table-bordered table-striped display" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">Customer</th>
                                    <th class="text-center text-white">Nama Product</th>
                                    <th class="text-center text-white">Kode</th>
                                    <th class="text-center text-white">Uom</th>
                                    <th class="text-center text-white">Deskripsi</th>
                                    <th class="text-center text-white">Tebal</th>
                                    <th class="text-center text-white">Lebar</th>
                                    <th class="text-center text-white">Tinggi</th>
                                    <th class="text-center text-white">Harga</th>
                                    <th class="text-center text-white">Image</th>
                                    <th class="text-center text-white">Attachment</th>
                                    <th class="text-center text-white">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($Products->result() as $li) : ?>
                                    <tr id="<?= $li->sysid ?>">
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center"><?= $li->Customer_Name ?></td>
                                        <td class="text-center"><?= $li->Nama ?></td>
                                        <td class="text-center"><?= $li->Kode ?></td>
                                        <td class="text-center"><?= $li->uom ?></td>
                                        <td class="text-center"><?= $li->Deskripsi ?></td>
                                        <td class="text-center"><?= floatval($li->Tebal) ?></td>
                                        <td class="text-center"><?= floatval($li->Lebar) ?></td>
                                        <td class="text-center"><?= floatval($li->Panjang) ?></td>
                                        <td class="text-center">Rp. <?= number_format($li->Price, 2, ',', '.')  ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url() ?>assets/Master/<?= $li->Image ?>" target="_blank">
                                                <img src="<?= base_url() ?>assets/Master/<?= $li->Image ?>" style="width: 100px;" class="user-image img-circle elevation-2" alt="Image"></a>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url() ?>assets/Master/<?= $li->Attachment ?>" target="_blank">
                                                <?= $li->Attachment ?>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <?php if ($li->is_active == 1) : ?>
                                                    <button data-toggle="tooltip" data-pk="<?= $li->sysid ?>" data-original-title="Non-aktifkan" class="btn btn-sm bg-gradient-success is-active"><i class="fas fa-check"></i></button>&nbsp;
                                                <?php else : ?>
                                                    <button data-toggle="tooltip" data-pk="<?= $li->sysid ?>" data-original-title="Aktifkan" class="btn btn-sm bg-gradient-secondary is-active"><i class="fas fa-times"></i></button>&nbsp;
                                                <?php endif; ?>
                                                <button data-toggle="tooltip" data-pk="<?= $li->sysid ?>" data-original-title="Edit Info Product" class="btn btn-sm btn-warning btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                            </div>
                                            <div class="btn-group mt-1">
                                                <button data-toggle="tooltip" data-pk="<?= $li->sysid ?>" data-original-title="Table Product" class="btn btn-sm btn-dark btn-list"><i class="fas fa-eye"></i></button>&nbsp;
                                                <button data-toggle="tooltip" data-pk="<?= $li->sysid ?>" data-original-title="Edit Detail Struktur Product" class="btn btn-sm btn-info btn-detail"><i class="fas fa-edit"></i></button>
                                                <!-- <button data-toggle="tooltip" data-pk="<= $li->sysid ?>" data-original-title="Hapus Data" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></button> -->
                                            </div>
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
    <div class="modal fade" id="modal-add" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form-edit" method="POST" enctype="multipart/form-data" action="<?= base_url('Master/store_product') ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Master Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group form-group-sm">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Customer :</span>
                                    </div>
                                    <select type="text" class="form-control" required name="customer" id="customer" style="width: 60%;">
                                        <option selected disabled>-Choose Customer-</option>
                                        <?php foreach ($customers as $cust) : ?>
                                            <option value="<?= $cust->SysId ?>"><?= $cust->Customer_Name ?> (<?= $cust->Customer_Code ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Nama Produk :</span>
                                    </div>
                                    <input type="text" class="form-control" required placeholder="Nama..." name="Nama" id="Nama">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Kode :</span>
                                    </div>
                                    <input type="text" class="form-control" required placeholder="Kode..." name="Kode" id="Kode">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">UOM :</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Uom..." name="uom" id="uom">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Deskripsi :</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Deskripsi..." name="Deskripsi" id="Deskripsi">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Tebal (MM) :</span>
                                    </div>
                                    <input type="number" maxlength="3" class="form-control" required placeholder="Tebal..." name="Tebal" id="Tebal">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Lebar (MM) :</span>
                                    </div>
                                    <input type="number" maxlength="5" class="form-control" required placeholder="Lebar..." name="Lebar" id="Lebar">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Panjang (MM) :</span>
                                    </div>
                                    <input type="number" maxlength="5" class="form-control" required placeholder="Panjang..." name="Panjang" id="Panjang">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Harga (Rp) :</span>
                                    </div>
                                    <input type="number" class="form-control" required placeholder="Harga..." name="Price" id="Price">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Image/Photo :</span>
                                    </div>
                                    <input type="file" accept=".png, .jpg, .jpeg" class="form-control" placeholder="Photo..." name="Photo" id="Photo">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Attachment/Doc :</span>
                                    </div>
                                    <input type="file" accept=".pdf, .doc, .ppt, .docx, .pptx, .xls, .xlsx, .png, .jpg, .jpeg" class="form-control" placeholder="Attachment..." name="Attachment" id="Attachment">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
<div id="location"></div>