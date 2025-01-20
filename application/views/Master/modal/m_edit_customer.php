<div class="modal fade" id="modal-edit" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-edit" method="POST" enctype="multipart/form-data" action="<?= base_url('Master/edit_customer') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer <?= $Customer->Customer_Code ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" name="sysid" id="sysid" value="<?= $Customer->SysId ?>">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Customer Code :</span>
                                </div>
                                <input type="text" class="form-control" readonly required placeholder="Code..." name="customer_code" id="customer_code" style="text-transform: uppercase;" value="<?= $Customer->Customer_Code ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Customer Name :</span>
                                </div>
                                <input type="text" class="form-control" readonly required placeholder="Name..." name="customer_name" id="customer_name" value="<?= $Customer->Customer_Name ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">NPWP :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="NPWP..." name="npwp" id="npwp" value="<?= $Customer->NPWP ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Koresponden :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Koresponden..." name="koresponden" id="koresponden" value="<?= $Customer->Koresponden ?>">
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