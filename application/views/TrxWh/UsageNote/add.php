<style>
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
            <div class="card bd-callout shadow add-data">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <input type="hidden" name="state">
                    <input type="hidden" name="sysid">
                    <div class="card-header">
                        <h2 class="card-title mt-2">Add <?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('TrxWh/UsageNote/index') ?>" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nomor Alokasi :</label>
                                <input type="text" class="form-control form-control-sm" name="UN_Number" id="UN_Number" placeholder="Doc Number Akan Otomatis : SUN<?= date('ymd') ?>-XXXX" readonly>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Alokasi :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input" value="<?= date('Y-m-d') ?>" name="UN_DATE" id="UN_DATE" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal diterima :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input" value="<?= date('Y-m-d') ?>" name="ReceivedDate" id="ReceivedDate" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Category :</label>
                                <select class="form-control form-control-sm select2" name="ItemCategoryType" id="ItemCategoryType" required>
                                    <option value="" selected disabled>- Choose -</option>
                                    <?php foreach ($Item_Categories->result() as $val) : ?>
                                        <option value="<?= $val->SysId ?>"><?= $val->Item_Category ?> (<?= $val->Item_Category_Init ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Cost Center/Tujuan Allokasi :</label>
                                <select class="form-control form-control-sm select2" name="cost_center" id="cost_center" required>
                                    <option value="" selected disabled>- Choose -</option>
                                    <?php foreach ($Cost_Centers->result() as $cc) : ?>
                                        <option value="<?= $cc->kode_cost_center ?>"><?= $cc->nama_cost_center ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Catatan :</label>
                                <textarea rows="4" class="form-control form-control-sm" name="notes" id="notes" placeholder="Catatan ..."></textarea>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12 px-4 table-detail">
                                <div class="d-flex justify-content-between header">
                                    <div class="left d-flex">
                                        <a href="javascript:void(0);" class="tambah_item_produk mb-2">Tambah Item (<i class="fa fa-plus"></i>)</a>
                                        <!-- <a href="javascript:void(0);" class="hapus_item_produk">Hapus Item Produk (<i class="fa fa-minus"></i>)</a> -->
                                    </div>
                                    <!-- <div class="right d-flex">
                                        <p class="mb-4 mt-1 mr-2">Search</p>
                                        <input type="text" id="search-list-item" class="form-control form-control-sm" placeholder="...">
                                    </div> -->
                                </div>
                                <div class="table-mini-container">
                                    <table class="table-mini" id="table_item">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Group Category</th>
                                                <th>Color</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Dimension</th>
                                                <th>Uom</th>
                                                <th>Qty</th>
                                                <th>Warehouse</th>
                                                <th><i class="fas fa-trash"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="text-center mt-4" id="no_data_item"><b>Tidak Ada Data</b></h5>
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
<div id="location-modal-item">
    <div class="modal fade" id="modal_list_item" data-backdrop="static" data-keyboard="false" aria-labelledby="Label" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table id="DataTable_Modal_ListItem" class="table-mini" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Category Group</th>
                                        <th>Color</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>Dimensi</th>
                                        <th>Qty Global</th>
                                        <th>Uom</th>
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
                    <button type="button" class="btn btn-primary" id="select_item"><i class="fas fa-check"></i> &nbsp;&nbsp;&nbsp;Select</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="location-modal-stok">
    <div class="modal fade" id="modal_list_stok" data-backdrop="static" data-keyboard="false" aria-labelledby="Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-titl" id="modal-title-stok"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table id="DataTable_Modal_Stok" class="table-mini" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Nama Gudang</th>
                                        <th>Kode Gudang</th>
                                        <th>Stok Item</th>
                                        <th>Uom</th>
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
                    <button type="button" class="btn btn-primary" id="select_wh"><i class="fas fa-check"></i> &nbsp;&nbsp;&nbsp;Select</button>
                </div>
            </div>
        </div>
    </div>
</div>