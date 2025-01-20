<style>
    #modal-list-dtl .desc-hdr {
        font-size: 15.5px;
    }

    #modal-list-dtl .modal-lg {
        max-width: 900px;
    }
    
    .footer-table .col-md-6,
    .footer-table .col-md-1,
    .footer-table .col-md-4,
    .footer-table .col-md-12 {
        margin-top: .5rem;
    }

    .footer-table .col-md-1,
    .footer-table .col-md-4 {
        text-align: right;
        font-weight: bold;
    }

    .border-lite {
        border-bottom: 2.5px solid #6e6e6e33;
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
                                    <th>Nomor Purchase Return</th>
                                    <th>Tanggal Purchase Return</th>
                                    <th>Nomor Penerimaan Barang (PO)</th>
                                    <th>Nama Vendor</th>
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
                <h5 class="modal-title"><b>DETAIL PURCHASE RETURN <span class="desc_pr_number"></span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-1">
                <div class="card-body">
                    <div class="col-md-12 mb-4 desc-hdr">
                        <div class="row">
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>PR Number</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0 desc_pr_number"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>PR Date</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_pr_date"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Vendor</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_vendor"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>RR Number</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_rr_number"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Alamat</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_alamat_vendor"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Catatan</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_catatan"></div>
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
                                    <th class="text-center text-white">Unit</th>
                                    <th class="text-center text-white">Qty Return</th>
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
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->