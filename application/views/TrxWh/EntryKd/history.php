<style>
    div.dt-buttons {
        clear: both;
    }

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
            <h3 class="card-title pt-2 float-right"><?= $page_title ?></h3>
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tampilan-by-lot-tab" data-toggle="pill" href="#tampilan-by-lot" role="tab" aria-controls="tampilan-by-lot" aria-selected="false"><i class="fas fa-eye"></i> REKAP BY NOMOR BUNDLE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tampilan-by-dekripsi-tab" data-toggle="pill" href="#tampilan-by-deskripsi" role="tab" aria-controls="tampilan-by-deskripsi" aria-selected="true"><i class="fas fa-eye"></i> REKAP BY ITEM</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form action="#" method="post" id="filter-form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control form-control-sm select2" name="oven" id="oven">
                                <option value="" selected>-ALL OVEN-</option>
                                <?php foreach ($kilns as $li) : ?>
                                    <option value="<?= $li->Warehouse_ID ?>"><?= $li->Warehouse_Name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control form-control-sm select2" name="material" id="material">
                                <option value="" selected>-ALL MATERIAL-</option>
                                <?php foreach ($items as $li) : ?>
                                    <option value="<?= $li->SysId ?>"><?= $li->Item_Code ?> (<?= $li->Item_Name ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <input type="text" name="from" id="from" data-toggle="datetimepicker" data-target="#from" required class="form-control text-center datepicker readonly" value="<?= date('Y-m-01') ?>">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-info"><i class="fas fa-calendar"></i> S/D</button>
                                </div>
                                <input type="text" name="to" id="to" data-toggle="datetimepicker" data-target="#to" required class="form-control text-center datepicker readonly" value="<?= date('Y-m-t') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="input-group float-right">
                            <button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm btn-block">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr />
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade active show" id="tampilan-by-lot" role="tabpanel" aria-labelledby="tampilan-by-lot-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable_Lot" class="table-mini dt-nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">BUNDLE</th>
                                        <th class="text-center text-white">ITEM KODE</th>
                                        <th class="text-center text-white">DESKRIPSI</th>
                                        <th class="text-center text-white">SUPPLIER</th>
                                        <th class="text-center text-white"><i class="fas fa-user"></i></th>
                                        <th class="text-center text-white"><i class="fas fa-calendar"></i> KIRIM</th>
                                        <th class="text-center text-white">PCS</th>
                                        <th class="text-center text-white">KUBIKASI</th>
                                        <th class="text-center text-white">KILN</th>
                                        <th class="text-center text-white"><i class="fas fa-clock"></i> TIMER</th>
                                        <th class="text-center text-white"><i class="fas fa-calendar"></i> MASUK</th>
                                        <th class="text-center text-white"><i class="fas fa-clock"></i> KELUAR</th>
                                        <th class="text-center text-white">STATUS</th>
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
                            <table id="DataTable_Deskripsi" class="table-mini dt-nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">ITEM NAME</th>
                                        <th class="text-center text-white">ITEM CODE</th>
                                        <th class="text-center text-white">TINGGI</th>
                                        <th class="text-center text-white">LEBAR</th>
                                        <th class="text-center text-white">PANJANG</th>
                                        <th class="text-center text-white">KODE UKURAN</th>
                                        <th class="text-center text-white">TOTAL BUNDLE</th>
                                        <th class="text-center text-white">TOTAL PCS</th>
                                        <th class="text-center text-white">KUBIKASI</th>
                                        <th class="text-center text-white">LIST BUNDLE</th>
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