<style>
    div.dt-buttons {
        clear: both;
    }
</style>

<div class="col-lg-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header">
            <h3 class="card-title pt-2 float-right"><?= $page_title ?></h3>
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="history_bcd_product-tab" data-toggle="pill" href="#history_bcd_product" role="tab" aria-controls="history_bcd_product" aria-selected="false"><i class="fas fa-eye"></i> &nbsp;Riwayat Print Barcode Satuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history_bcd_group-tab" data-toggle="pill" href="#history_bcd_group" role="tab" aria-controls="history_bcd_group" aria-selected="false"><i class="fas fa-eye"></i> &nbsp;Riwayat Print Barcode Group</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form action="#" method="post" id="filter-form">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <input type="text" name="from" id="from" data-toggle="datetimepicker" data-target="#from" required class="form-control datepicker readonly" value="<?= date('Y-m-01') ?>">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-info"><i class="fas fa-calendar"></i> S/D</button>
                                </div>
                                <input type="text" name="to" id="to" data-toggle="datetimepicker" data-target="#to" required class="form-control datepicker readonly" value="<?= date('Y-m-t') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="input-group float-right">
                            <button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm btn-block">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr />
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade active show" id="history_bcd_product" role="tabpanel" aria-labelledby="history_bcd_product-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">No. Barcode</th>
                                        <th class="text-center text-white">Customer</th>
                                        <th class="text-center text-white">Product</th>
                                        <th class="text-center text-white">Tgl Produksi</th>
                                        <th class="text-center text-white">Checker</th>
                                        <th class="text-center text-white">Leader Rakit</th>
                                        <th class="text-center text-white">Waktu. Print</th>
                                        <th class="text-center text-white">Status</th>
                                        <th class="text-center text-white">Handle</th>
                                    </tr>
                                </thead>
                                <tbody style="color: black;">
                                    <!-- hi dude i dude some magic here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="history_bcd_group" role="tabpanel" aria-labelledby="history_bcd_group-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable_group" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">No. Start</th>
                                        <th class="text-center text-white">No. End</th>
                                        <th class="text-center text-white">Total</th>
                                        <th class="text-center text-white">Customer</th>
                                        <th class="text-center text-white">Product</th>
                                        <th class="text-center text-white">Tgl. Produksi</th>
                                        <th class="text-center text-white">Checker</th>
                                        <th class="text-center text-white">Rakit</th>
                                        <th class="text-center text-white">Tgl Print</th>
                                        <th class="text-center text-white">Handle</th>
                                    </tr>
                                </thead>
                                <tbody style="color: black;">
                                    <!-- hi dude i dude some magic here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div id="location">

    </div>
</div>