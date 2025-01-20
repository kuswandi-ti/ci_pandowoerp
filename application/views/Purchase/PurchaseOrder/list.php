<style>
    .form-group label {
        font-weight: 500;
    }

    .table-detail .table-responsive {
        overflow-x: auto;
    }

    .table-detail .table-responsive #table_item {
        min-width: 2000px;
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

    .select-costcenter,
    .select-tax1,
    .select-tax2 {
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
                        <table id="DataTable" class="table-mini table-sm table-bordered table-striped" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-center text-white">
                                    <th>No</th>
                                    <th>Doc No</th>
                                    <th>Doc Rev</th>
                                    <th>Doc Date</th>
                                    <th>Vendor</th>
                                    <th>Alamat</th>
                                    <th>ETA</th>
                                    <th>ETD</th>
                                    <th>Currency</th>
                                    <th>Amount</th>
                                    <th>Remark</th>
                                    <th>Close</th>
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
                                    <label>Doc Number</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm" name="doc_no" placeholder="Doc Number Akan Otomatis di isikan Oleh system." readonly>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text doc_rev">
                                                01.00
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nama Vendor</label>
                                    <input type="hidden" name="vendor_id">
                                    <input type="text" class="form-control form-control-sm" name="vendor" required readonly>
                                </div>

                                <div class="form-group">
                                    <label>Alamat Vendor</label>
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="vendor_address_id">
                                        <input type="text" class="form-control form-control-sm" name="vendor_address" required readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="btn-list-address" type="button">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>ETA</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm flatpickr" name="eta">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>ETD</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm flatpickr" name="etd">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea rows="4" class="form-control form-control-sm" name="notes" placeholder="Tulis Notes ..."></textarea>
                                </div>
                            </div>
                            <!-- LEFT - END -->

                            <!-- RIGHT -->
                            <div class="col-lg-6 col-sm-12 px-4">
                                <div class="form-group">
                                    <label>Doc Date</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm flatpickr" name="doc_date">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Person</label>
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="person_id">
                                        <input type="text" class="form-control form-control-sm" name="person" required readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="btn-list-person" type="button">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Currency</label>

                                    <select class="form-control form-control-sm select2" name="currency" required>
                                        <?php foreach ($List_Currency->result() as $val) : ?>
                                            <option value="<?= $val->Currency_ID ?>"><?= $val->Currency_ID ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Rate Currency</label>
                                    <input type="text" class="form-control form-control-sm only-number" name="rate" required>
                                </div>

                                <div class="form-group">
                                    <label>IsImport</label>
                                    <div class="d-flex">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="isImport1" name="isImport" value="1">
                                            <label for="isImport1" class="custom-control-label">Ya</label>
                                        </div>

                                        <div class="custom-control custom-radio ml-3">
                                            <input class="custom-control-input" type="radio" id="isImport2" name="isImport" value="0">
                                            <label for="isImport2" class="custom-control-label">Tidak</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>IsAsset</label>
                                    <div class="d-flex">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="isAsset1" name="isAsset" value="1">
                                            <label for="isAsset1" class="custom-control-label">Ya</label>
                                        </div>

                                        <div class="custom-control custom-radio ml-3">
                                            <input class="custom-control-input" type="radio" id="isAsset2" name="isAsset" value="0">
                                            <label for="isAsset2" class="custom-control-label">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- RIGHT - END -->

                            <div class="col-md-4 px-4">
                                <div class="form-group">
                                    <label>Custom Field 1</label>
                                    <textarea rows="4" class="form-control form-control-sm" name="custom_field_1" placeholder="Tulis Custom Field ..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 px-4">
                                <div class="form-group">
                                    <label>Custom Field 2</label>
                                    <textarea rows="4" class="form-control form-control-sm" name="custom_field_2" placeholder="Tulis Custom Field ..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 px-4">
                                <div class="form-group">
                                    <label>Custom Field 3</label>
                                    <textarea rows="4" class="form-control form-control-sm" name="custom_field_3" placeholder="Tulis Custom Field ..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12 px-4 table-detail">
                                <div class="d-flex justify-content-between header">
                                    <div class="left d-flex">
                                        <a href="javascript:void(0);" class="tambah_item">Tambah Item (<i class="fa fa-plus"></i>)</a>
                                    </div>
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
                                                <th width="5.5%">Kode Item</th>
                                                <th width="9%">Nama Item</th>
                                                <th width="3%">Unit</th>
                                                <th width="7%">Cost Center</th>
                                                <th width="3.5%">Qty</th>
                                                <th width="5%">Discount %</th>
                                                <th width="5%">Discount</th>
                                                <th width="7%">Tax 1</th>
                                                <th width="7%">Tax 2</th>
                                                <th width="6.6%">Unit Price</th>
                                                <th width="6.6%">Base Unit Price</th>
                                                <th width="6.6%">Total Price</th>
                                                <th width="6.6%">Total Base Price</th>
                                                <th width="7%">Remark</th>
                                                <th width="1%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="col-md-6 offset-md-6 footer-table">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>Total Amount</label>
                                        </div>
                                        <div class="col-md-1">:</div>
                                        <div class="col-md-6">
                                            <input class="form-control form-control-sm" type="text" name="total_amount" readonly>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="d-flex">
                                                <label class="mr-3">Discount</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control form-control-sm only-number" value="" name="percent_discount_all">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            %
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">:</div>
                                        <div class="col-md-6">
                                            <input class="form-control form-control-sm" type="text" name="total_discount" readonly>
                                        </div>

                                        <div class="col-md-5">
                                            <label>Total Tax 1</label>
                                        </div>
                                        <div class="col-md-1">:</div>
                                        <div class="col-md-6">
                                            <input class="form-control form-control-sm" type="text" name="total_tax_1" readonly>
                                        </div>

                                        <div class="col-md-5">
                                            <label>Total Tax 2</label>
                                        </div>
                                        <div class="col-md-1">:</div>
                                        <div class="col-md-6">
                                            <input class="form-control form-control-sm" type="text" name="total_tax_2" readonly>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="border-lite"></div>
                                        </div>

                                        <div class="col-md-5">
                                            <label>Grand Total</label>
                                        </div>
                                        <div class="col-md-1">:</div>
                                        <div class="col-md-6">
                                            <input class="form-control form-control-sm" type="text" name="grand_total" readonly>
                                        </div>
                                        <div class="col-md-4 offset-md-8 mt-3">
                                            <a href="javascript:void(0);" class="btn btn-success px-3 btn-md float-right" id="calculate"><i class="fas fa-calculator mr-2"></i>Calculate</a>
                                        </div>
                                    </div>
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

<div class="modal fade" id="personModal" tabindex="-1" role="dialog" aria-labelledby="personModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title" id="personModalLabel">Pilih Person</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-person" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.8rem;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Kontak</th>
                                        <th>Inisial</th>
                                        <th>Title Job</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- hi dude i dude some magic here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-select-person" type="button" class="btn btn-primary">Pilih</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressModalLabel">Pilih Alamat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="table-address" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.8rem;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Alamat</th>
                                            <th>Area</th>
                                            <th>Deskripsi</th>
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
                    <button id="btn-select-address" type="button" class="btn btn-primary">Pilih</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_tambah_item" style="z-index: 1050 !important;" aria-labelledby="Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row vendor_input_modal_item mb-4">
                        <div class="col-md-6 form-group pr-3">
                            <label>Vendor :</label>
                            <select class="form-control form-control-sm select2" name="vendor_modal" required>
                                <option value="" selected disabled>- Choose -</option>
                                <?php foreach ($List_Vendor->result() as $val) : ?>
                                    <option value="<?= $val->SysId ?>"><?= $val->Account_Name ?> (<?= $val->Account_Code ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <h5><b>List Item</b></h5>
                        <hr>
                        <table id="DataTable_Modal_ListItem" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-center text-white">
                                    <th>#</th>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                    <th>Category Item</th>
                                    <th>Unit</th>
                                    <th>Effective Date</th>
                                    <th>Currency</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- hi dude i dude some magic here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="select_item"><i class="fas fa-check"></i> &nbsp;&nbsp;&nbsp;Pilih</button>
            </div>
        </div>
    </div>
</div>