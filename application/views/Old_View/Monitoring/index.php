<style>
    div.dt-buttons {
        clear: both;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Monitoring Material Per Supplier</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="post" id="filter-form">
                        <div class="row">
                            <div class="form-group col-md-5">
                                <select class="form-control" required name="supplier" id="supplier"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="from" id="from" required class="form-control datepicker readonly" value="<?= date('Y-m-01') ?>">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-info"><i class="fas fa-calendar"></i> S/D</button>
                                    </div>
                                    <input type="text" name="to" id="to" required class="form-control datepicker readonly" value="<?= date('Y-m-t') ?>">
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
                        <table id="DataTable" class="table-striped table-bordered display compact nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">LPB</th>
                                    <th class="text-center text-white">SUPPLIER</th>
                                    <th class="text-center text-white">LOT</th>
                                    <th class="text-center text-white">BARANG</th>
                                    <th class="text-center text-white">RP/PCS</th>
                                    <th class="text-center text-white">PCS</th>
                                    <th class="text-center text-white">KUBIKASI</th>
                                    <th class="text-center text-white">SUBTOTAL</th>
                                    <th class="text-center text-white">GRADER</th>
                                    <th class="text-center text-white">KIRIM</th>
                                    <th class="text-center text-white">FINISH</th>
                                    <th class="text-center text-white">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot style="background-color: #3B6D8C;" id="footer">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-center text-white">TOTAL</th>
                                <th></th>
                                <th class="text-center text-white" id="tot-kubikasi"></th>
                                <th class="text-white" id="tot-rupiah"></th>
                                <th class="text-center text-white" colspan="4"></th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location">

    </div>
</div>