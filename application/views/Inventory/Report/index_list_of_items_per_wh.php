<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-sm-8">
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
                                <label style="font-weight: 500;">Range Tanggal Transaksi:</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="from" id="from" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-m-01') ?>">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-info"><i class="fas fa-calendar"></i> S/D</button>
                                    </div>
                                    <input type="text" name="to" id="to" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-m-t') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Category : </label>
                                <select class="form-control form-control-sm select2" name="item_category" id="Item_Category" required>
                                    <option selected disabled>- Pilih -</option>
                                    <?php foreach ($Categories->result() as $cat) : ?>
                                        <option value="<?= $cat->SysId ?>"><?= $cat->Item_Category ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Group : </label>
                                <select class="form-control form-control-sm select2" name="item_category_group" id="Item_Category_Group" required>
                                    <option selected value="">- Pilih Category Terlebih Dahulu -</option>
                                    <!-- <option value="ALL">ALL</option>
                                    <php foreach ($Groups->result() as $group) : ?>
                                        <option value="<= $group->SysId ?>"><= $group->Group_Name . ' (' . $group->Grouping_Code . ')' ?></option>
                                    <php endforeach; ?> -->
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Gudang :</label>
                                <div class="input-group input-group-sm">
                                    <select name="Warehouse" id="Warehouse" class="form-control form-control-sm select2">
                                        <option selected value="">- Pilih Category Terlebih Dahulu -</option>
                                        <!-- <option selected value="ALL">ALL</option>
                                        <php foreach ($Warehouses as $index => $wh): ?>
                                            <option value="<= $wh->Warehouse_ID ?>"><= $wh->Warehouse_Name ?></option> -->
                                        <php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group row">
                                <label style="font-weight: 500;">Sumber Nilai Harga Barang:</label>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="source_value" id="source_value1" value="sales">
                                        <label class="form-check-label" for="source_value1">
                                            Rata-rata Penjualan
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="source_value" id="source_value2" value="purchase">
                                        <label class="form-check-label" for="source_value2">
                                            Rata-rata Pembelian
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="source_value" id="source_value3" value="hpp">
                                        <label class="form-check-label" for="source_value3">
                                            Harga Pokok Produksi (HPP)
                                        </label>
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