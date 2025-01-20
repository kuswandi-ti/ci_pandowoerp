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
                    <input type="hidden" name="state" id="state" value="<?= $action ?>">
                    <input type="hidden" name="SysId_Hdr" id="SysId_Hdr" value="<?= $Hdr->SysId ?>">
                    <div class="card-header">
                        <h2 class="card-title mt-2"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('TrxWh/Adjustment/index') ?>" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">No. Document :</label>
                                <input type="text" class="form-control form-control-sm" name="DocNo" id="DocNo" placeholder="<?= $Hdr->DocNo ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Penyesuaian :</label>
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
                                <label style="font-weight: 500;">Note Penyesuaian :</label>
                                <div class="input-group input-group-sm">
                                    <textarea name="Note" id="Note" class="form-control" required placeholder="catatan penyesuaian...."><?= $Hdr->Note ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 px-4 table-detail">

                                <?php if ($action == 'update'): ?>
                                    <div class="d-flex justify-content-between header">
                                        <div class="d-flex">
                                            <a href="javascript:void(0);" class="search-data btn bg-gradient-danger mb-2">Pilih Item &nbsp;<i class="fab fa-searchengin"></i></a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="table-mini-container">
                                    <table class="table table-mini" style="width: 100%;" id="table_data_selected">
                                        <thead style="background-color: #3B6D8C;">
                                            <tr class="text-white">
                                                <th class="text-center align-middle">#</th>
                                                <th class="text-center align-middle">Kode Item</th>
                                                <th class="text-center align-middle">Nama Item</th>
                                                <th class="text-center align-middle">Qty</th>
                                                <th class="text-center align-middle">Uom</th>
                                                <th class="text-center align-middle">Curr</th>
                                                <th class="text-center align-middle">Nilai Barang</th>
                                                <th class="text-center align-middle">Total Nilai</th>
                                                <th class="text-center align-middle">Tipe Penyesuaian</th>
                                                <th class="text-center align-middle">Warehouse</th>
                                                <th class="text-center align-middle">Cost Center</th>
                                                <?php if ($action == 'update'): ?>
                                                    <th class="text-center align-middle"><i class="fas fa-trash"></i></th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody><?php $i = 1; ?>
                                            <?php foreach ($Dtls as $li) : ?>
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <input type="hidden" required name="SysId[]" value="<?= $li->SysId_Item ?>">
                                                        <p class="mt-1"><?= $i ?></p>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <input class="text-center" type="hidden" readonly name="Item_Code[]" id="Item_Code_<?= $li->SysId_Item ?>" value="<?= $li->Item_Code ?>"><?= $li->Item_Code ?>
                                                    </td>
                                                    <td class="text-center align-middle"><?= $li->Item_Name ?></td>
                                                    <td class="text-center align-middle" style="width: 150px;">
                                                        <div class="input-group input-group-xs">
                                                            <input type="text" class="form-control" name="Qty[]" id="Qty_<?= $li->SysId_Item ?>" value="<?= $this->help->FormatIdr(floatval($li->Qty)) ?>" placeholder="kuantitas...">
                                                        </div>
                                                        <?php
                                                        $Stok = $this->db->query("SELECT COALESCE(SUM(Item_Qty),0) as Qty_stok
                                                            FROM t_stok_wh_item 
                                                            WHERE Item_Code = '$li->Item_Code'
                                                            GROUP BY Item_Code
                                                            ")->row();
                                                        ?>
                                                        <input type="hidden" class="Qty_stok" name="Qty_stok[]" id="Qty_stok_<?= $li->SysId_Item ?>" value="<?= floatval($Stok->Qty_stok) ?>">
                                                    </td>
                                                    <td class="text-center align-middle"><?= $li->Uom ?></td>
                                                    <td class="text-center align-middle"><?= $li->Currency ?></td>
                                                    <td class="text-center align-middle" style="width: 175px;">
                                                        <div class="input-group input-group-xs"><input type="text" class="form-control price" name="Price[]" id="Price_<?= $li->SysId_Item ?>" placeholder="harga/nilai item..." value="<?= $this->help->FormatIdr(floatval($li->Item_Price)) ?>"></div>
                                                    </td>
                                                    <td class="text-center align-middle amount" id="amount_<?= $li->SysId_Item ?>"><?= $this->help->FormatIdr(floatval($li->Total_Price)) ?></td>
                                                    <td class="text-center align-middle">
                                                        <div class="input-group input-group-xs">
                                                            <select class="form-control aritmatics" name="aritmatic[]" id="aritmatic_<?= $li->SysId_Item ?>">
                                                                <option value="">-Pilih-</option>
                                                                <option <?= ($li->Aritmatics == '+') ? 'selected' : ''; ?> value="+">Penyesuaian Plus (+)</option>
                                                                <option <?= ($li->Aritmatics == '-') ? 'selected' : ''; ?> value="-">Penyesuaian Minus (-)</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <div class="input-group input-group-xs">
                                                            <select class="form-control select2 whs" name="wh_id[]" id="wh_id_<?= $li->SysId_Item ?>">
                                                                <option disabled value="">-Pilih-</option>
                                                                <?php foreach ($Warehouses as $wh): ?>
                                                                    <option <?= ($li->Warehouse_ID == $wh->Warehouse_ID) ? 'selected' : ''; ?> value="<?= $wh->Warehouse_ID ?>"><?= $wh->Warehouse_Name ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <div class="input-group input-group-xs">
                                                            <select class="form-control select2 ccs" name="ccs[]" id="ccs_<?= $li->SysId_Item ?>">
                                                                <option selected disabled value="">-Pilih-</option>
                                                                <?php foreach ($Ccs as $cc): ?>
                                                                    <option <?= ($li->Cost_Center_ID == $cc->SysId) ? 'selected' : ''; ?> value="<?= $cc->SysId ?>"><?= $cc->nama_cost_center ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <?php if ($action == 'update'): ?>
                                                        <td class="text-center align-middle"><button type="button" class="remove-row btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td>
                                                    <?php endif; ?>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="text-center mt-4" id="no_data_selected" style="display: none;"><b>Tidak Ada Data</b></h5>
                            </div>
                        </div>
                        <!-- =============================== END FORM =========================== -->
                        <?php if ($action == 'update'): ?>
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
<div id="location-modal-lot">
    <div class="modal fade" id="modal_list_browse" aria-labelledby="Label" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 95%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Browse Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table id="Tbl_Browse_Data" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr class="text-white">
                                        <th>#</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Uom</th>
                                        <th>Item Group</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>Warna</th>
                                        <th>Dimensi</th>
                                        <th>Qty Keseluruhan</th>
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