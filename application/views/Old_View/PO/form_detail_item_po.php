<style>
    .form-control-xs {
        height: calc(1.2em + .375rem + 2px) !important;
        padding: .125rem .25rem !important;
        font-size: .95rem !important;
        line-height: 1.5;
        border-radius: .2rem;
    }

    input.form-control.input-mini {
        width: 80%;
        height: calc(1.2em + .375rem + 2px) !important;
        padding: .125rem .25rem !important;
        font-size: .95rem !important;
        line-height: 1.5;
        border-radius: .2rem;
    }
</style>
<div class="card card-info">
    <div class="card-header">
        <h5 class="card-title"><b>Add Detail Item PO <span id="No_Po_Internal_Span"><?= $No_Doc ?></span></b></h5>
    </div>
    <div class="card-body">
        <form id="form-detail-po" action="#">
            <input type="hidden" id="No_Po_Internal" name="No_Po_Internal" value="<?= $No_Doc ?>">
            <div class="col-lg-12 col-sm-12 mt-2">
                <table class="table table-sm table-striped table-bordered table-hover" style="width: 100%;" id="tbl-detail-po">
                    <thead style="background-color: #3B6D8C;">
                        <tr class="text-white">
                            <th class="text-center">#</th>
                            <th class="text-center">PRODUCT</th>
                            <th class="text-center">UOM</th>
                            <th class="text-center">PRICE</th>
                            <th class="text-center">QTY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($items as $li) : ?>
                            <tr data-pk="<?= $li->SysId ?>" id="row_<?= $li->SysId ?>">
                                <td><?= $li->Flag ?></td>
                                <td>
                                    <select class="form-control form-control-xs" style="width: 100%;" name="Product[]" data-pk="<?= $li->SysId ?>">
                                        <option selected disabled>-Choose-</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-xs text-center" name="Uom[]" data-pk="<?= $li->SysId ?>" readonly value="">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-xs text-center" name="Price[]" data-pk="<?= $li->SysId ?>" readonly value="<?= "Rp " . number_format(floatval($li->Product_Price), 2, ',', '.') ?>">
                                </td>
                                <td class="text-center">
                                    <u><a href="javascript:void(0)" data-pk="<?= $li->SysId ?>" class="editable_qty"><?= floatval($li->Qty_Order)  ?></a></u>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-12 col-sm-12 mb-2">
                <div class="btn-group btn-group-xs">
                    <button type="button" class="btn btn-xs btn-primary" id="add-row">
                        <i class="fas fa-plus"></i> ADD
                    </button> &nbsp;&nbsp;
                    <button type="button" class="btn btn-xs btn-danger" id="remove-row">
                        <i class="fas fa-times"></i> REMOVE
                    </button>
                </div>
            </div>
            <div class="card-footer mt-4">
                <div class="col-lg-12 col-sm-12">
                    <button type="button" class="btn btn-primary float-right" id="submit-form"><i class="fas fa-save"></i> | SAVE DATA PO CUSTOMER</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?= base_url() ?>assets/global-assets/x-editable/dist/bootstrap4-editable/js/bootstrap-editable.min.js"></script>
<script src="<?= base_url() ?>assets/PO/form_detail_po.js"></script>