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

    .select-warehouse {
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
                                    <th>RR Number</th>
                                    <th>RR Date</th>
                                    <th>PO Number</th>
                                    <th>Vendor</th>
                                    <th>Faktur & BC</th>
                                    <!-- <th>BC Type</th>
                                    <th>BC Number</th>
                                    <th>BC Date</th>
                                    <th>Faktur Number</th>
                                    <th>Faktur Date</th> -->
                                    <th>Status</th>
                                    <th>Is Cancel</th>
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
                        <h2 class="card-title mt-2"><span id="title-add-hdr">Add</span> <?= $page_title ?></h2>
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
                                    <label>RR Number</label>
                                    <input type="text" class="form-control form-control-sm" name="rr_no" placeholder="RR Number Akan Otomatis di isikan Oleh system." readonly>
                                </div>

                                <div class="form-group">
                                    <label>Nama Vendor</label>
                                    <input type="hidden" name="vendor_id" required>
                                    <input type="text" class="form-control form-control-sm" name="vendor" required readonly>
                                </div>

                                <div class="form-group">
                                    <label>Alamat Vendor</label>
                                    <input type="text" class="form-control form-control-sm" name="vendor_address" required readonly>
                                </div>

                                <div class="form-group">
                                    <label>Transport With</label>
                                    <select class="form-control form-control-sm select2" name="transpot_with" required>
                                        <option value="" selected disabled>- Choose -</option>
                                        <?php foreach ($List_Transport_With->result() as $val) : ?>
                                            <option value="<?= $val->SysId ?>"><?= $val->Transport_Name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Vendor Serial Number</label>
                                    <input type="text" class="form-control form-control-sm" name="vendor_sn" required>
                                </div>
                            </div>
                            <!-- LEFT - END -->

                            <!-- RIGHT -->
                            <div class="col-lg-6 col-sm-12 px-4">
                                <div class="form-group">
                                    <label>RR Date</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm flatpickr" name="rr_date">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>PO Number</label>
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="po_no_id">
                                        <input type="hidden" name="isAsset">
                                        <input type="hidden" name="base_amount">
                                        <input type="text" class="form-control form-control-sm" name="po_no" required readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="btn-browse-po" type="button">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>PO Date</label>
                                    <input type="text" class="form-control form-control-sm" name="po_date" required readonly>
                                </div>

                                <div class="form-group">
                                    <label>Nopol Kendaraan</label>
                                    <input type="text" class="form-control form-control-sm" name="nopol" required>
                                </div>

                                <div class="form-group">
                                    <label>Vendor Serial Number Date</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm flatpickr" name="vendor_sn_date">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- RIGHT - END -->

                            <div class="col-lg-6 px-4">
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea rows="4" class="form-control form-control-sm" name="notes" placeholder="Tulis Catatan ..."></textarea>
                                </div>
                            </div>
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
                                                <th width="8.5%">Kode Item</th>
                                                <th width="13%">Nama Item</th>
                                                <th width="5%">Unit</th>
                                                <th width="5%" class="tbl_add">PO Qty</th>
                                                <th width="7%">Gudang</th>
                                                <th width="6%" class="tbl_add">Outstanding</th>
                                                <th width="8%" class="tbl_add">Balance</th>
                                                <th width="8%">Received Now</th>
                                                <th width="9%">PO Unit Price</th>
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

<div class="modal fade" id="POModal" tabindex="-1" role="dialog" aria-labelledby="POModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title">Select PO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="table-po" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.8rem;">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>PO Date</th>
                                            <th>Vendor</th>
                                            <th>Alamat Vendor</th>
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
                <div class="modal-footer">
                    <button id="btn-select-po" type="button" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CLOSE -->
<div class="modal fade" id="Close_Modal" tabindex="-1" role="dialog" aria-labelledby="POModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Alasan Cancel</label>
                                <input type="hidden" name="sysid_cancel">
                                <textarea rows="4" class="form-control form-control-sm" name="reason" placeholder="Tulis Alasan ..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-submit-reason" type="button" class="btn btn-primary">Kirim</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BC & FAKTUR -->
<div class="modal fade" id="modal_faktur_bc" tabindex="-1" aria-labelledby="detailPEB_BCLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Form Faktur & Bea Cukai (BC)</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_faktur_bc">
                    <input type="hidden" name="sysid_modal_faktur_bc">
                    <div class="row">
                        <div class="col-12">
                            <div class="fh text-muted mb-0"><b>Faktur</b></div>
                            <hr class="my-2">
                        </div>
                    </div>
                    <!-- Input fields go here -->
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 form-group">
                            <label class="text-muted" style="font-weight: 500;">Faktur Number</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" name="faktur_number" type="text" placeholder="Masukan Nomor Faktur">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Date" style="font-weight: 500;">Faktur Date Info</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm flatpickr" placeholder="Masukan Tanggal Faktur Info" name="faktur_date_info" type="text">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Date" style="font-weight: 500;">Faktur Number Info</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" placeholder="Masukan Nomor Faktur Info" name="faktur_number_info" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="fh fw-bold text-muted"><b>Bea Cukai (BC)</b></div>
                            <hr class="my-2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 form-group">
                            <label class="text-muted" style="font-weight: 500;">Nomor Bea Cukai</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" name="bc_number" type="text" placeholder="Masukan Nomor Bea Cukai">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12 form-group">
                            <label class="text-muted" style="font-weight: 500;">Nomor Bea Cukai Info</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" name="bc_number_info" type="text" placeholder="Masukan Nomor Bea Cukai Info">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12 form-group">
                            <label class="text-muted" style="font-weight: 500;">Tanggal Bea Cukai Info</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm flatpickr" name="bc_date_info" type="text" placeholder="Masukan Tanggal Bea Cukai Info">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12 form-group">
                            <label class="text-muted" style="font-weight: 500;">Jenis Bea Cukai</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control form-control-sm select2" name="bc_type_info">
                                    <option value="">---Pilih Jenis Bea Cukai---</option>
                                    <?php foreach ($bc_types->result() as $key) : ?>
                                        <option value="<?= $key->kode_cukai; ?>"> <?= $key->nama_cukai; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="save_faktur_bc">Simpan</button>
            </div>
        </div>
    </div>
</div>