<style>
    .form-group label {
        font-weight: 500;
    }

    .table-detail .table-responsive {
        overflow-x: auto;
    }

    .table-detail .table-responsive #table_item {
        min-width: 1159px;
    }

    .footer-table .col-md-5,
    .footer-table .col-md-1,
    .footer-table .col-md-6,
    .footer-table .col-md-12 {
        margin-top: .5rem;
    }

    .footer-table .col-md-5,
    .footer-table .col-md-1 {
        padding-top: .3rem;
    }

    .border-lite {
        border-bottom: 2.5px solid #6e6e6e33;
    }
    
    .table-detail .header .left a {
        margin-right: 1rem;
        color: red;
    }

    .table-detail .header .left a:hover {
        text-decoration: revert;
    }

    .table-detail .header .left a>i {
        font-size: 11px;
    }

    .remove_item_dtl {
        color: red;
    }

    .select-currency {
        width: auto !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline list-data">
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
                                    <th>No</th>
                                    <th>Nomor PR</th>
                                    <th>Tanggal PR</th>
                                    <th>Vendor</th>
                                    <th>Nomor Penerimaan Barang (PO)</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal Approve</th>
                                    <th>Cancel</th>
                                    <th>Status Approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- hi dude i dude some magic here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card bd-callout shadow add-data">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <input type="hidden" name="state">
                    <input type="hidden" name="sysid">
                    <div class="card-header">
                        <h2 class="card-title mt-2"><span class="txt_add">Add</span> <?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- LEFT -->
                            <div class="col-lg-6 col-sm-12 px-4">
                                <div class="form-group">
                                    <label>Purchase Return No</label>
                                    <input type="text" class="form-control form-control-sm" name="pr_no" placeholder="RR Number Akan Otomatis di isikan Oleh system." readonly>
                                </div>

                                <div class="form-group">
                                    <label>Nama Vendor</label>
                                    <input type="hidden" name="vendor_id" required>
                                    <input type="text" class="form-control form-control-sm" name="vendor" required readonly>
                                </div>

                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input type="text" class="form-control form-control-sm" name="vendor_address" required readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea rows="4" class="form-control form-control-sm" name="notes" placeholder="Tulis Catatan ..."></textarea>
                                </div>
                            </div>
                            <!-- LEFT - END -->

                            <!-- RIGHT -->
                            <div class="col-lg-6 col-sm-12 px-4">
                                <div class="form-group">
                                    <label>Tanggal Purchase Return</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm flatpickr" name="pr_date">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>No Penerimaan Barang (PO)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="rr_number">
                                        <input type="hidden" name="po_no">
                                        <input type="text" class="form-control form-control-sm" name="rr_number" required readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="btn-browse-rr" type="button">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- RIGHT - END -->
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-12 px-4 table-detail">
                                <div class="d-flex justify-content-between header">
                                    <div class="a"></div>
                                    <div class="right d-flex">
                                        <p class="mb-4 mt-1 mr-2">Search</p>
                                        <input type="text" id="search-list-item" class="form-control form-control-sm" placeholder="...">
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="table_item" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="15%">Kode Item</th>
                                                <th width="24%">Nama Item</th>
                                                <th width="12.5%">Unit</th>
                                                <th width="12.5%" class="th-qty-rr">QTY RR</th>
                                                <th width="12.5%" class="th-add">Outstanding</th>
                                                <th width="12.5%" class="th-add">Balance</th>
                                                <th width="12.5%">QTY Return</th>
                                                <th width="1%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="text-center" id="no_data_item"><b>Tidak Ada Data</b></h5>
                            </div>
                        </div>
                        <!-- =============================== END FORM =========================== -->
                        <div class="card-footer text-muted py-3 text-center mt-4">
                            <button type="button" href="javascript:void(0);" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="browse_rr_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Browse No Penerimaan Barang (PO)</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="table-browse-rr" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.8rem;">
                                    <thead>
                                        <tr>
                                            <th>RR Number</th>
                                            <th>RR Date</th>
                                            <th>PO Number</th>
                                            <th>Vendor</th>
                                            <th>Alamat Vendor</th>
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
                <div class="modal-footer">
                    <button id="btn-select-rr" type="button" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>