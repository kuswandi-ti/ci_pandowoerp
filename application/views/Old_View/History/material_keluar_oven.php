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
                    <a class="nav-link active" id="tampilan-by-lot-tab" data-toggle="pill" href="#tampilan-by-lot" role="tab" aria-controls="tampilan-by-lot" aria-selected="false"><i class="fas fa-eye"></i> BY NOMOR LOT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tampilan-by-dekripsi-tab" data-toggle="pill" href="#tampilan-by-deskripsi" role="tab" aria-controls="tampilan-by-deskripsi" aria-selected="true"><i class="fas fa-eye"></i> BY DESKRIPSI UKURAN</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form action="#" method="post" id="filter-form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="oven" id="oven">
                                <option value="" selected>-ALL OVEN-</option>
                                <?php foreach ($ovens as $li) : ?>
                                    <option value="<?= $li->sysid ?>"><?= $li->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="material" id="material">
                                <option value="" selected>-ALL MATERIAL-</option>
                                <?php foreach ($materials as $li) : ?>
                                    <option value="<?= $li->sysid ?>"><?= $li->kode ?> (<?= $li->deskripsi ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
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
                <div class="tab-pane fade active show" id="tampilan-by-lot" role="tabpanel" aria-labelledby="tampilan-by-lot-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable_Lot" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">LOT</th>
                                        <th class="text-center text-white">Deskripsi</th>
                                        <th class="text-center text-white">Kode</th>
                                        <th class="text-center text-white">Suplier</th>
                                        <th class="text-center text-white"><i class="fas fa-user"></i></th>
                                        <th class="text-center text-white"><i class="fas fa-calendar"></i> Finish Grid</th>
                                        <th class="text-center text-white">Pcs</th>
                                        <th class="text-center text-white">Kubikasi</th>
                                        <th class="text-center text-white">OVEN</th>
                                        <th class="text-center text-white"><i class="fas fa-clock"></i> Timer Oven</th>
                                        <th class="text-center text-white"><i class="fas fa-calendar"></i> Masuk</th>
                                        <th class="text-center text-white"><i class="fas fa-clock"></i> Keluar</th>
                                        <th class="text-center text-white">Status</th>
                                    </tr>
                                </thead>
                                <tbody style="color: black;">
                                    <!-- hi dude i dude some magic here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tampilan-by-deskripsi" role="tabpanel" aria-labelledby="tampilan-by-dekripsi-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable_Deskripsi" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">Deskripsi</th>
                                        <th class="text-center text-white">Kode</th>
                                        <th class="text-center text-white">T</th>
                                        <th class="text-center text-white">L</th>
                                        <th class="text-center text-white">P</th>
                                        <th class="text-center text-white">Jumlah lot</th>
                                        <th class="text-center text-white">Jumlah Pcs</th>
                                        <th class="text-center text-white">Kubikasi</th>
                                        <th class="text-center text-white">List Lot</th>
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