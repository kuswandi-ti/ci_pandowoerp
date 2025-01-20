<style>
    #DataTable_Lot_filter {
        float: left;
    }

    #DataTable_Lot_filter label input {
        width: 50vh;
    }

    #DataTable_Deskripsi_filter {
        float: left;
    }

    #DataTable_Deskripsi_filter label input {
        width: 50vh;
    }
</style>
<div class="col-lg-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header">
            <h3 class="card-title pt-2 float-right">REKAP ALOKASI MATERIAL KE PRODUKSI</h3>
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tampilan-by-dekripsi-tab" data-toggle="pill" href="#tampilan-by-deskripsi" role="tab" aria-controls="tampilan-by-deskripsi" aria-selected="true"><i class="fas fa-eye"></i> BY JENIS UKURAN ITEM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tampilan-by-lot-tab" data-toggle="pill" href="#tampilan-by-lot" role="tab" aria-controls="tampilan-by-lot" aria-selected="false"><i class="fas fa-eye"></i> BY NOMOR BUNDLE</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form action="#" method="post" id="filter-form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="cc" id="cc">
                                <option value="" selected>-Cost Center-</option>
                                <?php foreach ($cost_centers as $li) : ?>
                                    <option value="<?= $li->SysId ?>"><?= $li->nama_cost_center ?> (<?= $li->kode_cost_center ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
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
                    <div class="col-md-2">
                        <div class="input-group">
                            <button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp; Cari</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr />
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade active show" id="tampilan-by-deskripsi" role="tabpanel" aria-labelledby="tampilan-by-dekripsi-tab">
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
                                        <th class="text-center text-white">Kode Ukuran</th>
                                        <th class="text-center text-white">Jumlah Bundle</th>
                                        <th class="text-center text-white">Jumlah Pcs</th>
                                        <th class="text-center text-white">Kubikasi</th>
                                        <th class="text-center text-white">List Bundle</th>
                                    </tr>
                                </thead>
                                <tbody style="color: black;">
                                    <!-- hi dude i dude some magic here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tampilan-by-lot" role="tabpanel" aria-labelledby="tampilan-by-lot-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable_Lot" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">Bundle</th>
                                        <th class="text-center text-white">Deskripsi</th>
                                        <th class="text-center text-white">Kode</th>
                                        <th class="text-center text-white">Suplier</th>
                                        <th class="text-center text-white"><i class="fas fa-user"></i></th>
                                        <th class="text-center text-white"><i class="fas fa-calendar"></i> Kirim</th>
                                        <th class="text-center text-white">Finish Grid</th>
                                        <th class="text-center text-white">Pcs</th>
                                        <th class="text-center text-white">Kubikasi</th>
                                        <th class="text-center text-white">Kiln</th>
                                        <th class="text-center text-white">Waktu Kiln</th>
                                        <th class="text-center text-white"><i class="fas fa-clock"></i> Timer Kiln</th>
                                        <th class="text-center text-white"><i class="fas fa-calendar"></i> Alokasi</th>
                                        <th class="text-center text-white">Cost Center</th>
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