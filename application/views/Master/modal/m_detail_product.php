<div class="modal fade" id="modal-detail" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <form id="form-edit" method="POST" enctype="multipart/form-data" action="<?= base_url('Master/store_structure_product') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Structure Product : <?= $Hdr->Nama ?></h5>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="add" class="btn btn-primary btn-xs"><i class="fas fa-plus"></i> Add Material</button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="height: 70vh;">
                    <input type="hidden" name="sysid_hdr" id="sysid_hdr" value="<?= $Hdr->sysid ?>">
                    <?php if (empty($Dtls)) : ?>
                        <div class="card-body card-body-structure">
                            <button type="button" class="btn btn-danger btn-xs btn-delete-material mb-2"><i class="fas fa-times"></i> Delete Material</button>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="#">Bentuk Material</label>
                                        <select type="email" class="form-control form-control-sm" required id="Bentuk" name="Bentuk[]">
                                            <?php foreach ($materials as $li) : ?>
                                                <option value="<?= $li->bentuk ?>"><?= $li->bentuk ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="#">Qty Penggunaan</label>
                                        <input type="number" class="form-control form-control-sm" required id="Pcs" name="Pcs[]" placeholder="Qty...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="#">Keterangan</label>
                                        <textarea type="text" rows="2" class="form-control form-control-sm" id="Remark" name="Remark[]" placeholder="Keterangan..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="#">Tebal (cm)</label>
                                        <input type="number" class="form-control form-control-sm" required id="Tebal" name="Tebal[]" placeholder="Tebal...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="#">Lebar (cm)</label>
                                        <input type="number" class="form-control form-control-sm" required id="Lebar" name="Lebar[]" placeholder="Lebar...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="#">Panjang (cm)</label>
                                        <input type="number" class="form-control form-control-sm" required id="Panjang" name="Panjang[]" placeholder="Panjang...">
                                    </div>
                                </div>
                            </div>
                            <hr style="border: solid black 2px;">
                        </div>
                    <?php else : ?>
                        <?php foreach ($Dtls as $dtl) : ?>
                            <div class="card-body card-body-structure">
                                <button type="button" class="btn btn-danger btn-xs btn-delete-material mb-2"><i class="fas fa-times"></i> Delete Material</button>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#">Bentuk Material</label>
                                            <select type="email" class="form-control form-control-sm" required id="Bentuk" name="Bentuk[]">
                                                <?php foreach ($materials as $li) : ?>
                                                    <option value="<?= $li->bentuk ?>" <?php if ($li->bentuk == $dtl->Bentuk) echo 'selected' ?>><?= $li->bentuk ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#">Qty Penggunaan</label>
                                            <input type="number" class="form-control form-control-sm" required id="Pcs" name="Pcs[]" placeholder="Qty..." value="<?= floatval($dtl->Pcs) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#">Keterangan</label>
                                            <textarea type="text" rows="2" class="form-control form-control-sm" id="Remark" name="Remark[]" placeholder="Keterangan..."><?= $dtl->Remark ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#">Tebal (cm)</label>
                                            <input type="number" class="form-control form-control-sm" required id="Tebal" name="Tebal[]" placeholder="Tebal..." value="<?= floatval($dtl->Tebal) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#">Lebar (cm)</label>
                                            <input type="number" class="form-control form-control-sm" required id="Lebar" name="Lebar[]" placeholder="Lebar..." value="<?= floatval($dtl->Lebar) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#">Panjang (cm)</label>
                                            <input type="number" class="form-control form-control-sm" required id="Panjang" name="Panjang[]" placeholder="Panjang..." value="<?= floatval($dtl->Panjang) ?>">
                                        </div>
                                    </div>
                                </div>
                                <hr style="border: solid black 2px;">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
<script>
    $(document).ready(function() {
        $('.btn-delete-material').click(function() {
            console.log($('.card-body-structure').length)
            if ($('.card-body-structure').length == 1) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Structure Terakhir tidak dapat di hapus !',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
            } else {
                $(this).parent().remove();
            }
        });

        $('#add').on('click', function() {
            $('.card-body-structure:last').clone().insertAfter("div.card-body-structure:last");
            Fn_reset_form();
        })

        function Fn_reset_form() {
            $('select[name="Bentuk[]"]:last').val('');
            $('input[name="Tebal[]"]:last').val('');
            $('input[name="Lebar[]"]:last').val('');
            $('input[name="Panjang[]"]:last').val('');
            $('input[name="Pcs[]"]:last').val('');
            $('textarea[name="Remark[]"]:last').val('');

        }
    })
</script>