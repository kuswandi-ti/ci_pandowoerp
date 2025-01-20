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
                    <h3 class="card-title">Form Data Loading</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_loading" method="post" action="<?= base_url('LoadingForm/store_form_loading') ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Customer :</label>
                                        <select class="form-control form-control-sm" required name="customer" id="customer">
                                            <option selected disabled>-Customer-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Product :</label>
                                        <select class="form-control form-control-sm" required disabled name="product" id="product">
                                            <option selected disabled>-Product-</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Quantity Loading :</label>
                                        <input type="number" class="form-control form-control-sm" name="qty" id="qty" placeholder="Qty loading..." maxlength="3" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>User Loading :</label>
                                        <input type="text" value="<?= $this->session->userdata('impsys_initial') ?>" class="form-control form-control-sm" name="user" id="user" placeholder="User loading..." readonly required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="silang_product" name="silang_product" value="TRUE">
                                        <label class="form-check-label" for="silang_product"><b>Menggunakan Product Customer Lain</b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right" id="submit-form"><i class="fas fa-save"></i> | BUAT NOMOR LOADING</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>