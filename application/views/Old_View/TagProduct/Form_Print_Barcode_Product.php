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
                    <h3 class="card-title"><?= $page_title ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_print_barcode_product" method="post" action="#">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Customer :</label>
                                        <select class="form-control form-control-sm" required name="customer" id="customer">
                                            <option selected disabled>- customer -</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Product :</label>
                                            <select class="form-control form-control-sm" required name="product" id="product">
                                                <option selected disabled>- product -</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Leader Rakit :</label>
                                        <select class="form-control form-control-sm" required name="leader_rakit" id="leader_rakit"></select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Checker Rakit :</label>
                                            <select class="form-control form-control-sm" required name="checker_rakit" id="checker_rakit"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal Produksi :</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control form-control-sm readonly datepicker" data-toggle="datetimepicker" data-target="#tgl_prd" required name="tgl_prd" id="tgl_prd" placeholder="Tanggal Produksi..." value="<?= date('Y-m-d') ?>">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Jumlah Barcode Produk :</label>
                                        <select class="form-control form-control-sm select2" required name="qty" id="qty">
                                            <option selected disabled>- Jumlah Print -</option>
                                            <?php for ($i = 1; $i <= 150; ++$i) : ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary" id="submit-form"><i class="fas fa-barcode"></i> | PRINT BARCODE</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>