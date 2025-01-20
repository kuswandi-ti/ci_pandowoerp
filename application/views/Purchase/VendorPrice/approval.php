<style>
    #modal-list-dtl .desc-hdr {
        font-size: 15.5px;
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
                        <table id="DataTable" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-center text-white">
                                    <th>VPR Number</th>
                                    <th>VPR Date</th>
                                    <th>VPR Notes</th>
                                    <th>Vendor</th>
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
</div>

<div class="modal fade" id="modal-list-dtl" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"> 
            <div class="modal-header">
                <h5 class="modal-title"><b>DETAIL VENDOR PRICE</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="col-md-12 mb-4 desc-hdr">
                        <div class="row">
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-3 px-0"><b>Doc Number</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_doc_number">VPR202334</div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Date</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_date">19 June 2023</div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-3 px-0"><b>Vendor</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_vendor">19 June 2023</div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Item Category</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_item_category">RAW MATERIAL</div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-3 px-0"><b>Notes</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_notes">TESTING BRADA UHUYYYY</div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tbl-modal-dtl" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">Kode Item</th>
                                    <th class="text-center text-white">Nama Item</th>
                                    <th class="text-center text-white">Tipe Item</th>
                                    <th class="text-center text-white">Unit</th>
                                    <th class="text-center text-white">Harga</th>
                                    <th class="text-center text-white">Mata Uang</th>
                                    <th class="text-center text-white">Effective Date</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->