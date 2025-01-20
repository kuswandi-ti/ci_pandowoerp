<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="card bd-callout shadow add-data">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <!-- <input type="hidden" name="state">
                    <input type="hidden" name="sysid"> -->
                    <div class="card-header">
                        <h2 class="card-title mt-2"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('TrxWh/NotaHasilProduksi/index') ?>" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Awal :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input" value="<?= date('Y-m-1') ?>" name="startDate" id="startDate" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Akhir :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input" value="<?= date('Y-m-t') ?>" name="endDate" id="endDate" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 px-4 form-group">
                                <label style="font-weight: 500;">Gudang :</label>
                                <div class="input-group input-group-sm">
                                    <select name="Warehouse" id="Warehouse" class="form-control form-control-sm select2">
                                        <option selected value="">ALL</option>
                                        <?php foreach ($Warehouses as $index => $wh): ?>
                                            <option value="<?= $wh->Warehouse_ID ?>"><?= $wh->Warehouse_Name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 px-4 form-group">
                                <label style="font-weight: 500;">Dibuat oleh :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" value="<?= $this->session->userdata('impsys_nama') ?>" name="created_by" id="created_by" readonly>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 px-4 form-group">
                                <label style="font-weight: 500;">Disetujui oleh :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" value="" placeholder="Di setujui oleh..." name="approved_by" id="approved_by">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- =============================== END FORM =========================== -->
                        <div class="card-footer text-muted py-3 text-center mt-4">
                            <button type="button" href="javascript:void(0);" class="btn bg-gradient-danger px-5 btn-lg" id="btn-submit"><i class="fas fa-print"></i> | Print Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>