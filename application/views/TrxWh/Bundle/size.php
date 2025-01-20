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
            <h3 class="card-title pt-2">REKAP STOK KAYU BERDASARKAN UKURAN</h3>
            <ul class="nav nav-tabs float-right" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tampilan-by-dekripsi-tab" data-toggle="pill" href="#tampilan-by-deskripsi" role="tab" aria-controls="tampilan-by-deskripsi" aria-selected="true"><i class="fas fa-eye"></i> REKAP BERDASARKAN UKURAN ITEM</a>
                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade active show" id="tampilan-by-deskripsi" role="tabpanel" aria-labelledby="tampilan-by-dekripsi-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable_Deskripsi" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">Deskripsi</th>
                                        <th class="text-center text-white">Kode Item</th>
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
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div id="location">

    </div>
</div>