<style>
    .form-control-xs {
        height: calc(1.2em + .375rem + 2px) !important;
        padding: .125rem .25rem !important;
        font-size: .95rem !important;
        line-height: 1.5;
        border-radius: .2rem;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Change Password <?= $this->config->item('app_name') ?> : <?= $this->session->userdata('impsys_nama') ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_change_password" method="post" action="<?= base_url('ReceiveMaterial/store_form_lpb') ?>">
                        <div class="card-body">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Password Sekarang :</label>
                                    <input type="password" class="form-control form-control-sm readonly" placeholder="password..." name="password" id="password" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <hr />
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Password Baru :</label>
                                    <input type="password" class="form-control form-control-sm readonly" placeholder="new password..." name="password1" id="password1" required minlength="5">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ulangi Password Baru :</label>
                                    <input type="password" class="form-control form-control-sm readonly" placeholder="repeat password..." name="password2" id="password2" required minlength="5">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right" id="submit-form"><i class="fas fa-edit"></i> | Simpan Perubahan</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>