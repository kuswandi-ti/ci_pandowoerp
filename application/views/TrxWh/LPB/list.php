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
                    <form action="#" method="post" id="filter-date">
                        <div class="row">
                            <div class="col-md-1">
                                <p class="">TGL Kirim :</p>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="from" id="from" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-m-01') ?>">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-info"><i class="fas fa-calendar"></i> S/D</button>
                                    </div>
                                    <input type="text" name="to" id="to" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-m-t') ?>">
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
                        <table id="DataTable" class="table-mini" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-center text-dark">#</th>
                                    <th class="text-center text-dark">LPB</th>
                                    <th class="text-center text-dark">PEMBAYARAN</th>
                                    <th class="text-center text-dark">SUPPLIER</th>
                                    <th class="text-center text-dark"><i class="fas fa-calendar"></i> Kirim</th>
                                    <th class="text-center text-dark">GRADER</th>
                                    <th class="text-center text-dark">JUMLAH BUNDLE</th>
                                    <th class="text-center text-dark">TOT.KUBIKASI</th>
                                    <th class="text-center text-dark">PCS</th>
                                    <th class="text-center text-dark">Nilai Lpb</th>
                                    <th class="text-center text-dark">Uang Bongkar</th>
                                    <th class="text-center text-dark">ACTION</th>
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