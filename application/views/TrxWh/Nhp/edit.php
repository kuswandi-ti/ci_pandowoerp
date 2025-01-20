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
                    <input type="hidden" name="state" id="state" value="<?= $Action ?>">
                    <input type="hidden" name="sysid" id="sysid" value="<?= $Hdr->SysId ?>">
                    <div class="card-header">
                        <h2 class="card-title mt-2"><?= $page_title ?> : <?= $Hdr->DocNo ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('TrxWh/NotaHasilProduksi/index') ?>" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">No. Document :</label>
                                <input type="text" class="form-control form-control-sm" name="Doc_No" id="Doc_No" value="<?= $Hdr->DocNo ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Produksi :</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr-input" value="<?= $Hdr->DocDate ?>" name="DocDate" id="DocDate" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Note Produksi :</label>
                                <div class="input-group input-group-sm">
                                    <textarea name="Note" id="Note" class="form-control" placeholder="catatan...."><?= $Hdr->Note ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 px-4 table-detail">
                                <?php if ($Action == 'update'): ?>
                                    <div class="d-flex justify-content-between header">
                                        <div class="d-flex">
                                            <a href="javascript:void(0);" class="search-item btn bg-gradient-danger mb-2">Pilih Item Produksi &nbsp;<i class="fab fa-searchengin"></i></a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="table-mini-container">
                                    <table class="table-mini" style="width: 100%;" id="table_item_selected">
                                        <thead style="background-color: #3B6D8C;">
                                            <tr class="text-white">
                                                <th class="text-center">#</th>
                                                <th class="text-center">Kode Item</th>
                                                <th class="text-center">Nama Item</th>
                                                <th class="text-center">Dimensi</th>
                                                <th class="text-center">Uom</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Warehouse</th>
                                                <th class="text-center">Cost Center</th>
                                                <th class="text-center">Remark</th>
                                                <th class="text-center"><i class="fas fa-trash"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach ($Dtls as $index => $li) : ?>
                                                <tr>
                                                    <td class="text-center align-middle"><input type="hidden" required name="SysId[]" value="<?= $li->SysId_Item ?>">
                                                        <p class="mt-1"><?= $i; ?></p>
                                                    </td>
                                                    <td class="text-center align-middle"><input type="hidden" name="item_codes[]" id="item_code_<?= $index ?>" value="<?= $li->Item_Code ?>"><?= $li->Item_Code ?></td>
                                                    <td class="text-center align-middle"><?= $li->Item_Name ?></td>
                                                    <td class="text-center align-middle"><?= $li->Item_Dimensions ?></td>
                                                    <td class="text-center align-middle"><?= $li->Uom ?></td>
                                                    <td class="align-middle" style="width:120px;">
                                                        <div class="input-group input-group-xs"><input name="Qty[]" type="number" class="form-control only-number text-center" value="<?= floatval($li->Qty) ?>" id="qty<?= $index ?>" placeholder="Quantitas...">
                                                    </td>

                                                    <td class="align-middle">
                                                        <div class="input-group input-group-xs">
                                                            <select class="form-control select2 whs" name="wh_id[]" id="wh_id_<?= $index ?>">
                                                                <?php foreach ($Warehouses as $wh) : ?>
                                                                    <option value="<?= $wh->Warehouse_ID ?>" <?= ($wh->Warehouse_ID == $li->Warehouse_ID) ? 'selected' : '' ?>><?= $wh->Warehouse_Name ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </td>

                                                    <td class="align-middle">
                                                        <div class="input-group input-group-xs">
                                                            <select class="form-control select2 ccs" name="ccs[]" id="ccs_<?= $index ?>">
                                                                <?php foreach ($Ccs as $cc): ?>
                                                                    <option value="<?= $cc->SysId ?>" <?= ($cc->SysId == $li->CostCenter_ID) ? 'selected' : '' ?>><?= $cc->nama_cost_center ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td style="width:150px;">
                                                        <div class="input-group input-group-xs"><textarea class="form-control" placeholder="remark...." name="remark[]" id="remark_<?= $index ?>"><?= $li->Remark ?></textarea>
                                                    </td>
                                                    <td class="text-center align-middle"><button type="button" class="remove-row btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td>
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
                        <?php if ($Action == 'update'): ?>
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
                    <h5 class="modal-title">List Item Produksi</h5>
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
                    <button type="button" class="btn btn-primary" id="select_data"><i class="fas fa-check"></i> &nbsp;&nbsp;&nbsp;Select</button>
                </div>
            </div>
        </div>
    </div>
</div>