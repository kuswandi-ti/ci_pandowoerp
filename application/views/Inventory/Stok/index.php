<style>
    #Tbl_Stok_filter {
        float: left;
    }

    #Tbl_Stok_filter label input {
        width: 50vh;
    }

    #tbl_history_stok_filter {
        float: left;
    }

    #tbl_history_stok_filter label input {
        width: 50vh;
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
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-3 col-form-label">Warehouse : </label>
                                <div class="col-sm-9">
                                    <select type="email" class="form-control select2" id="Warehouse" name="Warehouse">
                                        <option value="">ALL</option>
                                        <?php foreach ($warehouses as $wh) : ?>
                                            <option value="<?= $wh->Warehouse_ID ?>"><?= $wh->Warehouse_Name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2"><button class="btn btn-danger btn-sm" id="do-filter"><i class="fas fa-search"></i> Tampilkan</button></div>
                    </div>
                    <hr class="devider">
                    <div class="table-responsive">
                        <table id="Tbl_Stok" class="table table-striped table-bordered display compact nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">WH CODE</th>
                                    <th class="text-center text-white">WH NAME</th>
                                    <th class="text-center text-white">ITEM CODE</th>
                                    <th class="text-center text-white">ITEM NAME</th>
                                    <th class="text-center text-white">QTY</th>
                                    <th class="text-center text-white">Uom</th>
                                    <th class="text-center text-white"><i class="fas fa-cogs"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- hi dude i dude some magic here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location">
        <div class="modal fade" id="modal-history-transaksi">
            <div class="modal-dialog" style="max-width: 90%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="#" method="post" id="filter-date">
                            <div class="row">
                                <div class="col-md-2">
                                    <p class="">Tanggal :</p>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="from" id="from" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-m-1') ?>">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-info"><i class="fas fa-calendar"></i> S/D</button>
                                        </div>
                                        <input type="text" name="to" id="to" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-m-t') ?>">
                                    </div>
                                    <input type="hidden" name="Warehouse_ID" id="Warehouse_ID" value="">
                                    <input type="hidden" name="Item_Code" id="Item_Code" value="">
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <button type="button" id="filter-ttrx" class="btn bg-gradient-danger btn-sm">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr class="devider">
                        <div class="container-fluid">
                            <table class="table-sm table-striped table-bordered display compact nowrap" id="tbl_history_stok" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">DOC. TRANSACTION</th>
                                        <th class="text-center text-white">DOC. TYPE</th>
                                        <th class="text-center text-white">DATE TRANSACTION</th>
                                        <th class="text-center text-white">BEGIN BALANCE</th>
                                        <th class="text-center text-white"><i class="fas fa-plus"></i></th>
                                        <th class="text-center text-white"><i class="fas fa-minus"></i></th>
                                        <th class="text-center text-white">END BALANCE</th>
                                        <th class="text-center text-white">Transaction Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- ====================== Hi dude, i do some magic here ============================== -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</div>