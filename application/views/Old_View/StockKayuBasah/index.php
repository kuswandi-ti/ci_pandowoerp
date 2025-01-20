<style>
    div.dt-buttons {
        clear: both;
    }
</style>

<div class="col-lg-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header">
            <h3 class="card-title pt-2">REKAP STOCK KAYU BASAH</h3>
            <ul class="nav nav-tabs float-right" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tampilan-by-lot-tab" data-toggle="pill" href="#tampilan-by-lot" role="tab" aria-controls="tampilan-by-lot" aria-selected="false"><i class="fas fa-eye"></i> BY NOMOR LOT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tampilan-by-dekripsi-tab" data-toggle="pill" href="#tampilan-by-deskripsi" role="tab" aria-controls="tampilan-by-deskripsi" aria-selected="true"><i class="fas fa-eye"></i> BY DESKRIPSI UKURAN</a>
                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-four-tabContent">
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
                <div class="tab-pane fade active show" id="tampilan-by-lot" role="tabpanel" aria-labelledby="tampilan-by-lot-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable_Lot" class="table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">LOT</th>
                                        <th class="text-center text-white">Deskripsi</th>
                                        <th class="text-center text-white">Kode</th>
                                        <th class="text-center text-white">Suplier</th>
                                        <th class="text-center text-white"><i class="fas fa-user"></i></th>
                                        <th class="text-center text-white"><i class="fas fa-calendar"></i> Kirim</th>
                                        <th class="text-center text-white"><i class="fas fa-calendar"></i> Finish</th>
                                        <th class="text-center text-white">Pcs</th>
                                        <th class="text-center text-white">Kubikasi</th>
                                        <th class="text-center text-white">Penempatan</th>
                                        <th class="text-center text-white"><i class="fas fa-download"></i></th>
                                    </tr>
                                </thead>
                                <tbody style="color: black; font-size: 9pt;">
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