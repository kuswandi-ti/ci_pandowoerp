<style>
    #DataTable_Detail_filter {
        float: left;
    }

    #DataTable_Detail_filter label input {
        width: 50vh;
    }
</style>
<div class="col-lg-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header">
            <h3 class="card-title pt-2 float-right" id="card-title"><?= $page_title ?></h3>
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="tampilan-by-lot-tab" data-toggle="pill" href="#tampilan-by-lot" role="tab" aria-controls="tampilan-by-lot" aria-selected="false"><i class="fas fa-eye"></i> Detail RR/Penerimaan Barang</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form action="#" method="post" id="filter-form">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="basic-url">Tanggal Penerimaan :</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="from" id="from" data-toggle="datetimepicker" data-target="#from" required class="form-control text-center datepicker readonly" value="<?= date('Y-m-01') ?>">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-info"><i class="fas fa-calendar"></i> S/D</button>
                                </div>
                                <input type="text" name="to" id="to" data-toggle="datetimepicker" data-target="#to" required class="form-control text-center datepicker readonly" value="<?= date('Y-m-t') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="basic-url">&nbsp;</label>
                        <div class="input-group">
                            <button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm"><i class="fas fa-search"></i> Tampilkan</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr />
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade active show" id="tampilan-by-lot" role="tabpanel" aria-labelledby="tampilan-by-lot-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable_Detail" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">RR Number</th>
                                        <th class="text-center text-white">RR Date</th>
                                        <th class="text-center text-white">PO Number</th>
                                        <th class="text-center text-white">Vendor</th>
                                        <th class="text-center text-white">Status</th>
                                        <th class="text-center text-white">Item Code</th>
                                        <th class="text-center text-white">Item Name</th>
                                        <th class="text-center text-white">Qty</th>
                                        <th class="text-center text-white">Curr</th>
                                        <th class="text-center text-white">Unit Price</th>
                                        <th class="text-center text-white">Amount</th>
                                        <th class="text-center text-white">warehouse</th>
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