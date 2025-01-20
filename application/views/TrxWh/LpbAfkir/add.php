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
                    <!-- <input type="hidden" name="state">
                    <input type="hidden" name="sysid"> -->
                    <div class="card-header">
                        <h2 class="card-title mt-2"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('TrxWh/LpbAfkir/index') ?>" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">No. Document Afkir :</label>
                                <input type="text" class="form-control form-control-sm" name="Doc_Afkir" id="Doc_Afkir" placeholder="Doc Number Akan Otomatis : AFR<?= date('ymd') ?>-XXXX" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Afkir :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input" value="<?= date('Y-m-d') ?>" name="Date_Afkir" id="Date_Afkir" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Note Afkir :</label>
                                <div class="input-group input-group-sm">
                                    <textarea name="Note" id="Note" class="form-control" required placeholder="catatan afkir...."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 px-4 table-detail">
                                <div class="d-flex justify-content-between header">
                                    <div class="d-flex">
                                        <a href="javascript:void(0);" class="search-bundle btn bg-gradient-danger mb-2">Pilih Bundle &nbsp;<i class="fab fa-searchengin"></i></a>
                                    </div>
                                </div>
                                <div class="table-mini-container">
                                    <table class="table table-mini" style="width: 100%;" id="table_bundle_selected">
                                        <thead style="background-color: #3B6D8C;">
                                            <tr class="text-white">
                                                <th class="text-center">#</th>
                                                <th class="text-center">Bundle</th>
                                                <th class="text-center">Kode Item</th>
                                                <th class="text-center">Nama Item</th>
                                                <th class="text-center">Kode Ukuran</th>
                                                <th class="text-center">Tebal</th>
                                                <th class="text-center">Lebar</th>
                                                <th class="text-center">Panjang</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Qty Afkir</th>
                                                <th class="text-center">Remark</th>
                                                <th class="text-center"><i class="fas fa-trash"></i></th>
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
<div id="location-modal-lot">
    <div class="modal fade" id="modal_list_lot" aria-labelledby="Label" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 95%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Bundle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="#" method="post" id="filter-date">
                            <div class="row">
                                <div class="col-md-2">
                                    <p class="">Tgl Penerimaan LPB :</p>
                                </div>
                                <div class="col-lg-4 col-sm-8">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="from" id="from" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-01-01') ?>">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-info"><i class="fas fa-calendar"></i> S/D</button>
                                        </div>
                                        <input type="text" name="to" id="to" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-12-t') ?>">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr class="devider">
                        <div class="table-responsive">
                            <table id="Tbl_List_Lot" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr class="text-white">
                                        <th>#</th>
                                        <th>Bundle</th>
                                        <th>Tgl Kirim</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Kode Ukuran</th>
                                        <th>Tebal</th>
                                        <th>Lebar</th>
                                        <th>Panjang</th>
                                        <th>Qty</th>
                                        <th>Status</th>
                                        <th>flag</th>
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
                    <button type="button" class="btn btn-primary" id="select_data"><i class="fas fa-check"></i> &nbsp;&nbsp;&nbsp;Select</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div id="location-modal-stok">
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
</div> -->