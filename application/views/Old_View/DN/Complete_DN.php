<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Complete Delivery Note</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="post" id="filter-date">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" name="customer" id="customer">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="from" id="from" class="form-control datepicker readonly" data-toggle="datetimepicker" data-target="#from" value="<?= date('Y-m-01') ?>">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-info" title="Tanggal Grid" data-toggle="tooltip"><i class="fas fa-calendar"></i> RANGE SEND DATE</button>
                                    </div>
                                    <input type="text" name="to" id="to" class="form-control datepicker readonly" data-toggle="datetimepicker" data-target="#to" value="<?= date('Y-m-t') ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr />
                    <div class="table-responsive">
                        <table id="TableData" class="tbl-xs table-bordered table-hover display compact table-valign-middle" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">DN Number</th>
                                    <th class="text-center text-white">Customer Code</th>
                                    <th class="text-center text-white">Customer Name</th>
                                    <th class="text-center text-white">No.PO</th>
                                    <th class="text-center text-white">No.SO</th>
                                    <th class="text-center text-white">Product Code</th>
                                    <th class="text-center text-white">Product Name</th>
                                    <th class="text-center text-white">Qty</th>
                                    <th class="text-center text-white">Uom</th>
                                    <th class="text-center text-white">No. Loading</th>
                                    <th class="text-center text-white">Send Date</th>
                                    <th class="text-center text-white">Address</th>
                                    <th class="text-center text-white">Att To</th>
                                    <th class="text-center text-white">Vehicle Police Number</th>
                                    <th class="text-center text-white">Driver Name</th>
                                    <th class="text-center text-white">Remark</th>

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

    </div>
</div>