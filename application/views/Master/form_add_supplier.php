<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Add new supplier</h3>
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
                                    <labe class="col-sm-2 col-form-label">Nama Supplier :</labe>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm form-control-border" required name="nama" id="nama" placeholder="Nama Supplier...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Alamat Supplier :</labe>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm form-control-border" required name="alamat" id="alamat" placeholder="Alamat Supplier...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Telp Supplier :</labe>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm form-control-border" required name="telp" id="telp" placeholder="Kontak Supplier...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Email Supplier :</labe>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control form-control-sm form-control-border" name="email" id="email" placeholder="Email Supplier...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Koresponden :</labe>
                                    <div class="col-sm-10">
                                        <input type="text" required class="form-control form-control-sm form-control-border" name="nama_kontak" id="nama_kontak" placeholder="Koresponden...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">$ Bongkar/Kubik :</labe>
                                    <div class="col-sm-10">
                                        <input type="number" required class="form-control form-control-sm form-control-border" value="0" name="uang_bongkar" id="uang_bongkar" placeholder="Uang Bongkar Perkubik...">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <a href="<?= base_url('Master/index_master_supplier_material') ?>" class="btn btn-danger float-right"><i class="fas fa-backward"></i> | Back</a>
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