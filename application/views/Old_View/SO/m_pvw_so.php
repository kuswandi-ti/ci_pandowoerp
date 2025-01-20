<div class="modal fade" id="m_preview_sales_order" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">NO. PO CUSTOMER : <?= $Hdr->No_Po_Customer ?> (<?= $Hdr->Doc_No_Internal ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title"><b>Detail Preview PO Receive</b></h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Purchase Order Number :</label>
                                                    <input type="text" class="form-control form-control-sm" name="po_number" id="po_number" value="<?= $Hdr->No_Po_Customer ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Customer :</label>
                                                    <select class="form-control form-control-sm" required name="customer" id="customer">
                                                        <option selected><?= $Cust->Customer_Name ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Date Document Receive :</label>
                                                    <input type="text" readonly class="form-control form-control-sm datepicker" data-toggle="datetimepicker" data-target="#tgl_terbit" name="tgl_terbit" id="tgl_terbit" value="<?= $Hdr->Tgl_Terbit ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Term Of Payment :</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" min="7" maxlength="3" class="form-control form-control-sm" name="term_of_payment" id="term_of_payment" placeholder="Term Of Payment..." value="<?= $Hdr->Term_Of_Payment ?>" required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><b>DAY</b> &nbsp;&nbsp; <i class="fas fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Condition TOP :</label>
                                                    <select class="form-control form-control-sm" required name="condition_top" id="condition_top">
                                                        <option <?php if ($Hdr->Remark_TOP == 'AFTER INVOICE RECEIVED') echo 'selected'; ?> value="AFTER INVOICE RECEIVED">AFTER INVOICE RECEIVED</option>
                                                        <option <?php if ($Hdr->Remark_TOP == 'AFTER PO CLOSE') echo 'selected'; ?> value="AFTER PO CLOSE">AFTER PO CLOSE</option>
                                                        <option <?php if ($Hdr->Remark_TOP == 'AFTER GOODS RECEIVED NOTE') echo 'selected'; ?> value="AFTER GOODS RECEIVED NOTE">AFTER GOODS RECEIVED NOTE</option>
                                                        <option <?php if ($Hdr->Remark_TOP == 'AFTER PO RECEIVED') echo 'selected'; ?> value="AFTER PO RECEIVED">AFTER PO RECEIVED</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Term Of Delivery :</label>
                                                    <input type="text" readonly class="form-control form-control-sm datepicker" data-toggle="datetimepicker" data-target="#term_of_delivery" name="term_of_delivery" id="term_of_delivery" placeholder="Term of delivery..." value="<?= $Hdr->Term_Of_Delivery ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Customer Address :</label>
                                                    <input type="hidden" id="id_address" name="id_address">
                                                    <div class="input-group input-group-sm">
                                                        <textarea class="form-control form-control-sm" readonly name="customer_address" id="customer_address" placeholder="Customer Address" required rows="3"><?= $Hdr->Customer_Address ?></textarea>
                                                        <!-- <div class="input-group-append">
                                                            <button type="button" id="btn--list--address" class="btn btn-success">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Koresponden :</label>
                                                    <textarea class="form-control form-control-sm" name="koresponden" id="koresponden" placeholder="Name Sender/Contact/Email/Hp/WhatsApp & Etc...." required rows="3"><?= $Hdr->Koresponden ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Note :</label>
                                                    <textarea class="form-control form-control-sm" name="note" id="note" placeholder="Note..." required rows="3"><?= $Hdr->Note ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>No. SO :</label>
                                                    <input type="text" class="form-control" name="no_Doc" id="no_Doc" placeholder="No. SO Internal..." value="<?= $Hdr->Doc_No_Internal ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    <label><?= $PPn->Name ?> :</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="PPn" id="PPn" type="checkbox" <?php if ($Hdr->PPn != '0') echo 'checked' ?> value="<?= floatval($PPn->Persentase) ?>">
                                                        <label class="form-check-label"><?= floatval($PPn->Persentase) ?> %</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="card-detail-item">
                                        <!-- Hi dude i do some magic here ! -->
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h5 class="card-title"><b>Detail Item PO : <span id="No_Po_Internal_Span"><?= $Hdr->No_Po_Customer ?> (<?= $Hdr->Doc_No_Internal ?>)</span></b></h5>
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="No_Po_Internal" name="No_Po_Internal" value="<?= $Hdr->Doc_No_Internal ?>">
                                                <div class="col-lg-12 col-sm-12 mt-2">
                                                    <table class="table table-striped table-bordered table-hover table-valign-middle" style="width: 100%;" id="tbl-detail-po">
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
                                                            <?php foreach ($Dtl as $li) : ?>
                                                                <tr data-pk="<?= $li->SysId ?>" id="row_<?= $li->SysId ?>">
                                                                    <td><?= $li->Flag ?></td>
                                                                    <td>
                                                                        <select class="form-control form-control-xs" style="width: 100%;" name="Product[]" data-pk="<?= $li->SysId ?>">
                                                                            <option selected value="<?= $li->Product_ID ?>"><?= $li->Product_Name ?></option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control form-control-xs text-center" name="Uom[]" data-pk="<?= $li->SysId ?>" readonly value="<?= $li->Uom ?>">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control form-control-xs text-center" name="Price[]" data-pk="<?= $li->SysId ?>" readonly value="<?= "Rp " . number_format(floatval($li->Product_Price), 2, ',', '.') ?>">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <a href="javascript:void(0)" data-pk="<?= $li->SysId ?>" class="editable_qty text-dark"><?= floatval($li->Qty_Order)  ?></a>
                                                                    </td>
                                                                </tr>
                                                                <?php $i++; ?>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(function() {
        $('input').prop('readonly', true)
        $('textarea').prop('readonly', true)
        $('select').prop('disabled', true)
    })
</script>