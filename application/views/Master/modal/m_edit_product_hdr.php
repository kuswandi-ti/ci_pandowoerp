<div class="modal fade" id="modal-edit" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-edit" method="POST" enctype="multipart/form-data" action="<?= base_url('Master/edit_product') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product <?= $Hdr->Nama ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" name="sysid" id="sysid" value="<?= $Hdr->sysid ?>">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Nama :</span>
                                </div>
                                <input type="text" class="form-control" required placeholder="Nama..." name="Nama" id="Nama" value="<?= $Hdr->Nama ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Kode :</span>
                                </div>
                                <input type="text" class="form-control" required placeholder="Kode..." name="Kode" id="Kode" value="<?= $Hdr->Kode ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Uom :</span>
                                </div>
                                <input type="text" class="form-control" required placeholder="Uom..." name="uom" id="uom" value="<?= $Hdr->uom ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Deskripsi :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Deskripsi..." name="Deskripsi" id="Deskripsi" value="<?= $Hdr->Deskripsi ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Tebal (MM) :</span>
                                </div>
                                <input type="number" maxlength="3" class="form-control" required placeholder="Tebal..." name="Tebal" id="Tebal" value="<?= floatval($Hdr->Tebal) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Lebar (MM) :</span>
                                </div>
                                <input type="number" maxlength="5" class="form-control" required placeholder="Lebar..." name="Lebar" id="Lebar" value="<?= floatval($Hdr->Lebar) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Panjang (MM) :</span>
                                </div>
                                <input type="number" maxlength="5" class="form-control" required placeholder="Panjang..." name="Panjang" id="Panjang" value="<?= floatval($Hdr->Panjang) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Harga (Rp.) :</span>
                                </div>
                                <input type="number" class="form-control" required placeholder="Price..." name="Price" id="Price" value="<?= floatval($Hdr->Price) ?>">
                            </div>
                        </div>
                        <br />
                        <br />
                        <?php if (!empty($Hdr->Image)) : ?>
                            <div class="form-group">
                                <a href="<?= base_url() ?>assets/Master/<?= $Hdr->Image ?>" target="_blank">
                                    <img src="<?= base_url() ?>assets/Master/<?= $Hdr->Image ?>" style="width: 100px;" class="user-image img-circle elevation-2" alt="Image"></a>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Image/Photo :</span>
                                </div>
                                <input type="file" accept=".png, .jpg, .jpeg" class="form-control" placeholder="Photo..." name="Photo" id="Photo">
                                <input type="hidden" name="old_Photo" id="old_Photo" value="<?= $Hdr->Image ?>">
                            </div>
                        </div>
                        <?php if (!empty($Hdr->Attachment)) : ?>
                            <div class="form-group">
                                <a href="<?= base_url() ?>assets/Master/<?= $Hdr->Attachment ?>" target="_blank"><?= $Hdr->Attachment ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Attachment/Doc :</span>
                                </div>
                                <input type="file" accept=".pdf, .doc, .ppt, .docx, .pptx, .xls, .xlsx, .png, .jpg, .jpeg" class="form-control" placeholder="Attachment..." name="Attachment" id="Attachment">
                                <input type="hidden" name="old_Attachment" id="old_Attachment" value="<?= $Hdr->Attachment ?>">
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