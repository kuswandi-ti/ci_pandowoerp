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
                    <div class="card-header">
                        <h2 class="card-title mt-2"><?= $page_title ?></h2>
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
                                <input type="text" class="form-control form-control-sm" name="UN_Number" id="UN_Number" value="<?= $Hdr->UN_NUMBER ?>" readonly>
                                <input type="hidden" name="SysId" id="SysId" value="<?= $Hdr->SysId ?>">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Alokasi :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input" name="UN_DATE" id="UN_DATE" required value="<?= $Hdr->UN_DATE ?>">
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
                                    <input type="text" class="form-control form-control-sm flatpickr-input" name="ReceivedDate" id="ReceivedDate" required value="<?= $Hdr->ReceivedDate ?>">
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
                                    <option value="" disabled>- Choose -</option>
                                    <?php foreach ($Item_Categories->result() as $val) : ?>
                                        <option value="<?= $val->SysId ?>" <?= ($Hdr->ItemCategoryType == $val->SysId ? 'selected' : '') ?>><?= $val->Item_Category ?> (<?= $val->Item_Category_Init ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Cost Center/Tujuan Allokasi :</label>
                                <select class="form-control form-control-sm select2" name="cost_center" id="cost_center" required>
                                    <option value="" selected disabled>- Choose -</option>
                                    <?php foreach ($Cost_Centers->result() as $cc) : ?>
                                        <option value="<?= $cc->kode_cost_center ?>" <?= ($Hdr->Cost_Center == $cc->kode_cost_center ? 'selected' : '') ?>><?= $cc->nama_cost_center ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Catatan :</label>
                                <textarea rows="4" class="form-control form-control-sm" name="notes" id="notes"><?= $Hdr->UN_Notes ?></textarea>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12 px-4 table-detail mb-5 pb-5">
                                <div class="d-flex justify-content-between header">
                                    <div class="left d-flex">
                                        <?php if ($Action != 'preview') : ?>
                                            <a href="javascript:void(0);" class="tambah_item_produk mb-2">Tambah Item Produk (<i class="fa fa-plus"></i>)</a>
                                        <?php endif; ?>
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
                                                <?php if ($Action != 'preview') : ?>
                                                    <th><i class="fas fa-trash"></i></th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach ($dtls as $li) : ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" required name="sysid_item[]" value="<?= $li->SysId_Item ?>">
                                                        <input type="hidden" required name="currency[]" value="<?= $li->Currency ?>">
                                                        <input type="hidden" required name="costingmethod[]" value="<?= $li->CostingMethod ?>">
                                                        <p class="mt-1"><?= $i ?></p>
                                                    </td>
                                                    <td>
                                                        <input name="item_code[]" class="input-xs form-control" required type="text" value="<?= $li->Item_Code ?>" readonly>
                                                    </td>
                                                    <td>
                                                        <input name="item_name[]" class="input-xs form-control" required type="text" value="<?= $li->Item_Name ?>" readonly>
                                                    </td>
                                                    <td><?= $li->Item_Category ?></td>
                                                    <td><?= $li->Item_Color ?></td>
                                                    <td><?= $li->Brand ?></td>
                                                    <td><?= $li->Model ?></td>
                                                    <td><?= floatval($li->Item_Length) . ' x ' . floatval($li->Item_Width) . ' x ' . floatval($li->Item_Height) . ' ' . $li->LWH_Unit ?></td>
                                                    <td><?= $li->Uom ?></td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input name="qty[]" type="text" class="input-xs form-control only-number" value="<?= floatval($li->Qty) ?>" id="qty_index_value">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-xs">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="btn btn-xs bg-primary input-group-text append_modal_wh" data-no="<?= $i ?>" data-item="<?= $li->Item_Code ?>">
                                                                    <i class="fas fa-search"></i>
                                                                </button>
                                                            </div>
                                                            <input type="hidden" id="wh_id_row_number" name="wh_id[]" value="<?= $li->Warehouse_ID ?>">
                                                            <input type="text" class="form-control" id="wh_name_row_number" name="wh_name[]" value="<?= $li->Warehouse_Name ?>" readonly>
                                                            <input type="hidden" id="qty_stok_row_number" name="qty_stok[]" class="qty_stok_item" <?= $li->Qty_Stok + $li->Qty ?>>
                                                        </div>
                                                    </td>
                                                    <?php if ($Action != 'preview') : ?>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0);" class="remove_item_dtl">
                                                                <span class="fa fa-times"></span>
                                                            </a>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="text-center mt-4" id="no_data_item"><b>Tidak Ada Data</b></h5>
                            </div>
                        </div>
                        <!-- =============================== END FORM =========================== -->
                        <?php if ($Action == 'form') : ?>
                            <div class="card-footer text-muted py-3 text-center mt-4">
                                <button type="button" href="javascript:void(0);" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
                            </div>
                        <?php endif; ?>
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
                                        <th>Qty</th>
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