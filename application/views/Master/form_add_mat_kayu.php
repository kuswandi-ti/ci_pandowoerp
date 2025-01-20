<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Add new Material Kayu | Note : Untuk bilangan decimal harap gunakan (.) Titik.</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form class="form-horizontal" id="form_add">
                            <div class="card-body">
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Tebal (CM) :</labe>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control form-control-sm form-control-border onlyfloat" required name="tebal" id="tebal" placeholder="Tebal...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Lebar (CM) :</labe>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control form-control-sm form-control-border onlyfloat" required name="lebar" id="lebar" placeholder="Lebar...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Panjang (CM) :</labe>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control form-control-sm form-control-border onlyfloat" required name="panjang" id="panjang" placeholder="Panjang...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Deskripsi :</labe>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm form-control-border" style="text-transform:uppercase" autocapitalize="word" required name="deskripsi" id="deskripsi" placeholder="Deskripsi...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Std jumlah Tumpukan :</labe>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control form-control-sm form-control-border onlyfloat" required name="std_qty_lot" id="std_qty_lot" placeholder="Qty...">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <a href="<?= base_url('Master/index_material_kayu') ?>" class="btn btn-danger float-right"><i class="fas fa-backward"></i> | Back</a>
                                <button type="submit" class="btn btn-info" id="submit-form"><i class="fab fa-wpforms"></i> | SUBMIT</button>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>