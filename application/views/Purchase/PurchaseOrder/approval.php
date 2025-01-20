<style>
    #modal-list-dtl .desc-hdr {
        font-size: 15.5px;
    }

    #modal-list-dtl .modal-lg {
        max-width: 900px;
    }
    
    #modal-list-dtl .modal-lg .table-responsive {
        overflow-x: auto;
    }

    #modal-list-dtl .modal-lg .table-responsive #tbl-modal-dtl {
        min-width: 1500px;
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
                                    <th>Doc No</th>
                                    <th>Doc Rev</th>
                                    <th>Doc Date</th>
                                    <th>Vendor</th>
                                    <th>Address</th>
                                    <th>ETA</th>
                                    <th>ETD</th>
                                    <th>Currency</th>
                                    <th>Amount</th>
                                    <th>Remark</th>
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
                <h5 class="modal-title"><b>DETAIL PURCHASE ORDER</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-1">
                <div class="card-body">
                    <div class="col-md-12 mb-4 desc-hdr">
                        <div class="row">
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-3 px-0"><b>Doc Number</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_doc_number"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Doc Date</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_doc_date"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-3 px-0"><b>Nama Vendor</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_vendor"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Person</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_person"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-3 px-0"><b>Alamat Vendor</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_alamat_vendor"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Currency</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_currency"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-3 px-0"><b>ETA</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_eta"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Rate Currency</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_rate_currency"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-3 px-0"><b>ETD</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_etd"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-4 px-0"><b>Is Import</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7 px-0" id="desc_is_import"></div>
                            </div>
                            <div class="col-md-6 d-flex px-0">
                                <div class="col-md-3 px-0"><b>Catatan</b></div>
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
                                    <th class="text-center text-white">Cost Center</th>
                                    <th class="text-center text-white">Qty</th>
                                    <th class="text-center text-white">Discount %</th>
                                    <th class="text-center text-white">Discount</th>
                                    <th class="text-center text-white">Tax 1</th>
                                    <th class="text-center text-white">Tax 2</th>
                                    <th class="text-center text-white">Unit Price</th>
                                    <th class="text-center text-white">Base Unit Price</th>
                                    <th class="text-center text-white">Total Price</th>
                                    <th class="text-center text-white">Total Base Price</th>
                                    <th class="text-center text-white">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- hi dude i dude some magic here -->
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <!-- ini mau di taro paling kanan min -->
                    <div class="col-md-4 ml-auto footer-table">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Total Amount</label>
                            </div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-4" id="v_total_amount">
                                30,000,123
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <label class="mr-3">Discount</label>
                                    <b id="v_discount_percent">
                                        (30%)
                                    </b>
                                </div>
                            </div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-4" id="v_discount">
                                30,000,123
                            </div>

                            <div class="col-md-6">
                                <label>Total Tax 1</label>
                            </div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-4" id="v_tax_1">
                                30,000,123
                            </div>

                            <div class="col-md-6">
                                <label>Total Tax 2</label>
                            </div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-4" id="v_tax_2">
                                30,000,123
                            </div>

                            <div class="col-md-12">
                                <div class="border-lite"></div>
                            </div>
                            
                            <div class="col-md-6">
                                <label>Grand Total</label>
                            </div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-4" id="v_grand_total">
                                30,000,123
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->