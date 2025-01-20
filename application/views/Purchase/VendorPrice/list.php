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
                                    <th>VPR Number</th>
                                    <th>VPR Date</th>
                                    <th>VPR Notes</th>
                                    <th>Vendor</th>
                                    <th>Status</th>
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
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Doc Number</label>
                                <input type="text" class="form-control form-control-sm" name="doc_no" placeholder="Doc Number Akan Otomatis di isikan Oleh system." readonly>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr" name="vpr_date">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Vendor :</label>
                                <select class="form-control form-control-sm select2" name="vendor" required>
                                    <option value="" selected disabled>- Choose -</option>
                                    <?php foreach ($List_Vendor->result() as $val) : ?>
                                        <option value="<?= $val->SysId ?>"><?= $val->Account_Name ?> (<?= $val->Account_Code ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Item Category :</label>
                                <select class="form-control form-control-sm select2" name="item_category" required>
                                    <option value="" selected disabled>- Choose -</option>
                                    <?php foreach ($List_Item_Category->result() as $val) : ?>
                                        <option value="<?= $val->SysId ?>"><?= $val->Item_Category ?> (<?= $val->Item_Category_Init ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-lg-12 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Catatan</label>
                                <textarea rows="4" class="form-control form-control-sm" name="notes" placeholder="Tulis Notes ..."></textarea>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12 px-4 table-detail">
                                <div class="d-flex justify-content-between header">
                                    <div class="left d-flex">
                                        <a href="javascript:void(0);" class="tambah_item_produk">Tambah Item Produk (<i class="fa fa-plus"></i>)</a>
                                        <!-- <a href="javascript:void(0);" class="hapus_item_produk">Hapus Item Produk (<i class="fa fa-minus"></i>)</a> -->
                                    </div>
                                    <div class="right d-flex">
                                        <p class="mb-4 mt-1 mr-2">Search</p>
                                        <input type="text" id="search-list-item" class="form-control form-control-sm" placeholder="...">
                                    </div>
                                </div>
                                <table id="table_item" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Kode Item</th>
                                            <th>Nama Item</th>
                                            <th>Tipe Item</th>
                                            <th>Unit</th>
                                            <th>Harga</th>
                                            <th>Mata Uang</th>
                                            <th>Effective Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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

<div class="modal fade" id="modal_list_item" style="z-index: 1050 !important;" aria-labelledby="Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                        <table id="DataTable_Modal_ListItem" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-center text-white">
                                    <th>#</th>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                    <th>Unit</th>
                                    <th>Category Group</th>
                                    <th>Mata Uang</th>
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