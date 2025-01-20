<style>
    #DataTable_Alias_filter {
        float: left;
    }

    #DataTable_Alias_filter label input {
        width: 50vh;
    }
</style>
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
                    <div class="table-responsive">
                        <table id="DataTable" class="table table-sm table-bordered table-striped display" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-center text-white">
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Hpp</th>
                                    <th>Default Curr</th>
                                    <th>Color</th>
                                    <th>Brand</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Group</th>
                                    <th>Barcode Pattern</th>
                                    <th>UoM</th>
                                    <!-- <th>Average Cost</th> -->
                                    <!-- <th>MultiComp</th> -->
                                    <th>Expenses (Biaya/Jasa)</th>
                                    <th>Status</th>
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
        <div class="modal fade" id="modal-form-edit" data-backdrop="static" data-keyboard="false" aria-labelledby="Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title-modal-edit"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                                <input type="hidden" name="state" id="state" value="ADD">
                                <input type="hidden" name="SysId_Item" id="SysId_Item" value="">
                                <div id="input-form">
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label style="font-weight: bold;">Item Code :</label>
                                            <input type="text" class="form-control form-control" readonly name="Item_Code" id="Item_Code" required placeholder="Item Code ...." req>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label style="font-weight: bold;">Tanggal Hpp :</label>
                                            <input type="text" class="form-control form-control flatpickr-input" name="Hpp_Date" id="Hpp_Date" required placeholder="Tanggal Mulai Berlaku ....">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label style="font-weight: bold;">Harga :</label>
                                            <input type="number" class="form-control form-control" name="Hpp" id="Hpp" required placeholder="Harga ...." min="1">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label style="font-weight: bold;">Catatan :</label>
                                            <textarea type="text" class="form-control form-control" rows="10" name="Note" id="Note" placeholder="Catatan ...."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-submit">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location-hst">
        <div class="modal fade" id="modal-history-transaksi">
            <div class="modal-dialog modal-xl" style="max-width: 90%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title-hst" id="modal-title-hst"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <table class="table-sm table-striped table-bordered display compact nowrap" id="tbl_history_stok" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">Tanggal Berlaku</th>
                                        <th class="text-center text-white">Hpp</th>
                                        <th class="text-center text-white">Note</th>
                                        <th class="text-center text-white">Waktu diubah</th>
                                        <th class="text-center text-white">Diubah oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- ====================== Hi dude, i do some magic here ============================== -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</div>