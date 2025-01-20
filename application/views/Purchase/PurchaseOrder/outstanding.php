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
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Vendor</th>
                                    <th>Qty PO</th>
                                    <th>QTY RR</th>
                                    <th>QTY Outstanding</th>
                                    <th>Currency</th>
                                    <th>Amount PO</th>
                                    <th>Amount RR</th>
                                    <th>Amount Outstanding</th>
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

<!-- Modal -->
<div class="modal fade" id="modal-list-dtl" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"> 
            <div class="modal-header">
                <h5 class="modal-title"><b>DETAIL PURCHASE ORDER OUTSTANDING <span id="txt_doc"></span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="DataTableDetail" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.8rem;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>RR Number</th>
                                        <th>RR Date</th>
                                        <th>Qty RR</th>
                                        <th>Amount RR</th>
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
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->