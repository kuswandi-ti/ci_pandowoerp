<style>
    div.dt-buttons {
        clear: both;
    }
</style>

<div class="col-lg-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header">
            <h3 class="card-title pt-2">WASTING BARCODE</h3>
            <ul class="nav nav-tabs float-right" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="satuan-tab" data-toggle="pill" href="#satuan" role="tab" aria-controls="satuan" aria-selected="true"><i class="fas fa-eye"></i> BARCODE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history-tab" data-toggle="pill" href="#history" role="tab" aria-controls="history" aria-selected="false"><i class="fas fa-eye"></i> HISTORY</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade active show" id="satuan" role="tabpanel" aria-labelledby="satuan-tab">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4 col-lg-4"></div>
                                        <div class="col-sm-4 col-lg-4">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <button type="button" data-toggle="tooltip" title="Colom Keterangan" class="btn btn-default"><b><i class="fas fa-text-height"></i></b></button>
                                                </div>
                                                <input type="text" class="form-control" id="info" name="info" placeholder="Keterangan...">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-lg-4"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-lg-4"></div>
                                        <div class="col-sm-4 col-lg-4">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" autofocus="autofocus" id="no_barcode" name="no_barcode" placeholder="Barcode...">
                                                <div class="input-group-prepend">
                                                    <button type="button" data-toggle="tooltip" title="Lihat Data Barcode" id="preview--data" class="btn bg-gradient-danger"> &nbsp;&nbsp;&nbsp;<b><i class="fas fa-barcode"></i></b>&nbsp;&nbsp;&nbsp; </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-lg-4"></div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" data-toggle="tooltip" title="nyatakan alokasi ke produksi" class="btn btn-success btn-lg float-center" id="submit--barcode">
                                        <i class="fas fa-trash-alt"></i> | NYATAKAN SEBAGAI WASTING BARCODE</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12" id="container-location">
                            <div class="card card-danger card-outline">
                                <div class="card-header text-center">
                                    <h6 class="text-center"><i>DETAIL DATA BARCODE</i></h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="Detail-Data-Lot" class="table-sm table-striped table-bordered" style="width: 100%;">
                                            <thead style="background-color: #3B6D8C;">
                                                <tr>
                                                    <th class="text-center text-white">CUSTOMER</th>
                                                    <th class="text-center text-white">CUSRTOMER CODE</th>
                                                    <th class="text-center text-white">NUMBER</th>
                                                    <th class="text-center text-white">BARCODE</th>
                                                    <th class="text-center text-white">TGL.PRD</th>
                                                    <th class="text-center text-white">PRODUCT CODE</th>
                                                    <th class="text-center text-white">PRODUCT NAME</th>
                                                    <th class="text-center text-white">LDR. RAKIT</th>
                                                    <th class="text-center text-white">CHECKER</th>
                                                    <th class="text-center text-white">PRINT</th>
                                                    <th class="text-center text-white">STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center" id="CUSTOMER"></td>
                                                    <td class="text-center" id="CUSRTOMER_CODE"></td>
                                                    <td class="text-center" id="NUMBER"></td>
                                                    <td class="text-center" id="BARCODE"></td>
                                                    <td class="text-center" id="TGL_PRD"></td>
                                                    <td class="text-center" id="PRODUCTCODE"></td>
                                                    <td class="text-center" id="PRODUCTNAME"></td>
                                                    <td class="text-center" id="LDRRAKIT"></td>
                                                    <td class="text-center" id="CHECKER"></td>
                                                    <td class="text-center" id="PRINT"></td>
                                                    <td class="text-center" id="STATUS"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="DataTable_Wasting" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
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
                                        <th class="text-center text-white">WASTE AT</th>
                                        <th class="text-center text-white">WASTE BY</th>
                                        <!-- <th class="text-center text-white">Handle</th> -->
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