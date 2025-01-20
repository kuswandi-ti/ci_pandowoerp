<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title"><b>Create New Invoice</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_hdr_invoice" method="post" action="#">
                        <input type="hidden" name="Invoice_Number" id="Invoice_Number" readonly value="<?= !empty($tmp_hdr) ? $tmp_hdr->Invoice_Number : 'NEW' ?>">
                        <div class="form-group">
                            <label>Invoice Date :</label>
                            <div class="input-group input-group-sm shadow">
                                <input type="text" class="form-control form-control-sm datepicker" readonly name="invoice_date" id="invoice_date" data-toggle="datetimepicker" data-target="#invoice_date" placeholder="Invoice Date..." required value="<?= !empty($tmp_hdr) ? $tmp_hdr->Invoice_Date : date('Y-m-d') ?>">
                                <div class="input-group-append">
                                    <span id="btn--date" class="input-group-text">&nbsp;<i class="fas fa-calendar"></i>&nbsp;</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Customer :</label>
                            <input type="hidden" name="id_customer" id="id_customer" value="<?= !empty($tmp_hdr) ? $tmp_hdr->Customer_ID : NULL ?>">
                            <input type="hidden" name="customer_code" id="customer_code" value="<?= !empty($tmp_hdr) ? $tmp_hdr->Customer_Code : NULL ?>">
                            <div class="input-group input-group-sm shadow">
                                <input type="text" class="form-control form-control-sm" name="customer_name" id="customer_name" placeholder="Customer..." required readonly value="<?= !empty($tmp_hdr) ? $tmp_hdr->Customer_Name : NULL ?>">
                                <div class="input-group-append">
                                    <button type="button" id="btn--customer" class="btn bg-gradient-info">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Delivery Note :</label>
                            <input type="hidden" name="dn_id" id="dn_id" value="<?= !empty($tmp_hdr) ? $tmp_hdr->DN_ID : NULL ?>">
                            <div class="input-group input-group-sm shadow">
                                <input type="text" class="form-control form-control-sm" name="DN" id="DN" placeholder="Delivery Note..." required readonly value="<?= !empty($tmp_hdr) ? $tmp_hdr->DN_Number : NULL ?>">
                                <div class="input-group-append">
                                    <button type="button" id="btn--dn" class="btn bg-gradient-info">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Purchase Order Number :</label>
                            <input type="hidden" name="id_po" id="id_po" value="<?= !empty($tmp_hdr) ? $tmp_hdr->SO_ID : NULL ?>">
                            <div class="input-group input-group-sm shadow">
                                <input type="text" class="form-control form-control-sm" name="no_po_customer" id="no_po_customer" placeholder="PO Number..." required readonly value="<?= !empty($tmp_hdr) ? $tmp_hdr->No_PO_Customer : NULL ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>SO. Number :</label>
                            <div class="input-group input-group-sm shadow">
                                <input type="text" class="form-control form-control-sm" name="no_po_internal" id="no_po_internal" placeholder="SO Number..." required readonly value="<?= !empty($tmp_hdr) ? $tmp_hdr->SO_Number : NULL ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>NPWP :</label>
                            <div class="input-group input-group-sm shadow">
                                <input type="text" class="form-control form-control-sm" name="NPWP" id="NPWP" placeholder="NPWP..." required readonly value="<?= !empty($tmp_hdr) ? $tmp_hdr->NPWP : NULL ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Customer Address :</label>
                            <input type="hidden" id="id_address" name="id_address" value="<?= !empty($tmp_hdr) ? $tmp_hdr->Address_ID : NULL ?>">
                            <div class="input-group input-group-sm shadow">
                                <textarea class="form-control form-control-sm" readonly name="customer_address" id="customer_address" placeholder="Customer Address..." required rows="3"><?= !empty($tmp_hdr) ? $tmp_hdr->Customer_Address : NULL ?></textarea>
                                <div class="input-group-append">
                                    <button type="button" id="btn--list--address" class="btn bg-gradient-info">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Due Date :</label>
                            <div class="input-group input-group-sm shadow">
                                <input type="number" class="form-control form-control-sm" name="due_date" id="due_date" placeholder="Due Date..." required value="<?= !empty($tmp_hdr) ? $tmp_hdr->Due_Date : NULL ?>">
                                <div class="input-group-append">
                                    <span id="btn--date" class="input-group-text">&nbsp;DAY&nbsp;</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <?php if (empty($tmp_hdr)) : ?>
                        <div class="form-group">
                            <button type="button" class="btn btn-danger float-right btn-lg" id="generate--invoice"><i class="fas fa-tag"></i> | Generate Invoice Number</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!----------------------------------------------- END HEADER ------------------------------------->
        <div class="col-lg-6 col-sm-6" id="location-detail-form" <?= !empty($tmp_hdr) ? NULL : 'style="display: none;"' ?>>
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title"><b>Detail Item Invoice</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_item_invoice" method="post" action="#">
                        <div class="row">
                            <div class="col-sm-5 col-lg-5">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-tag"></i>&nbsp;No. Inv</span>
                                        </div>
                                        <input type="text" class="form-control" required name="Invoice_Number_Show" id="Invoice_Number_Show" placeholder="Invoice Number..." readonly value="<?= !empty($tmp_hdr) ? $tmp_hdr->Invoice_Number : NULL ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7 col-lg-7">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;Item Amount</span>
                                        </div>
                                        <input type="text" class="form-control" required name="Item_Amount" id="Item_Amount" placeholder="Invoice Number..." readonly value="<?= !empty($tmp_hdr) ? number_format($tmp_hdr->Item_Amount, 2) : NULL ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5 col-lg-5">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-percent"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PPn&nbsp;&nbsp;</span>
                                        </div>
                                        <input type="text" class="form-control" required name="PPN" id="PPN" placeholder="PPn..." readonly value="<?= !empty($tmp_hdr) ? $tmp_hdr->PPN : NULL ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7 col-lg-7">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;&nbsp;PPn Amount</span>
                                        </div>
                                        <input type="text" class="form-control" required name="PPN_Amount" id="PPN_Amount" placeholder="PPn Amount..." readonly value="<?= !empty($tmp_hdr) ?  number_format($tmp_hdr->PPN_Amount, 2) : NULL ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5 col-lg-5"></div>
                            <div class="col-sm-7 col-lg-7">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;&nbsp;&nbsp;Inv Amount</span>
                                        </div>
                                        <input type="text" class="form-control" required name="Invoice_Amount" id="Invoice_Amount" placeholder="Invoice Amount..." readonly value="<?= !empty($tmp_hdr) ? number_format($tmp_hdr->Invoice_Amount, 2)  : NULL ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 mt-1">
                                <table class="table-xs table-striped table-bordered table-hover" style="width: 100%;" id="tbl-item-inv">
                                    <thead style="background-color: #3B6D8C;">
                                        <tr class="text-white">
                                            <!-- <th class="text-center">#</th> -->
                                            <th class="text-center">PRODUCT CODE</th>
                                            <th class="text-center">PRODUCT DESCRIPTION</th>
                                            <th class="text-center">UOM</th>
                                            <th class="text-center">PRICE</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">SUB-TOTAL</th>
                                            <th class="text-center"><i class="fas fa-cogs"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- HI DUDE I DO SOME MAGIC HERE -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-lg-12 col-sm-12 mb-2 mt-1">
                                <div class="btn-group btn-group-xs">
                                    <button type="button" class="btn btn-xs btn-primary" id="add-row">
                                        <i class="fas fa-plus"></i> ADD
                                    </button> &nbsp;&nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" id="remove-row">
                                        <i class="fas fa-times"></i> REMOVE
                                    </button>
                                </div>
                            </div>
                        </div> -->
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-danger btn-lg mr-2" id="cancel-invoice"><i class="fas fa-trash"></i> | CANCEL</button>
                        <button type="button" class="btn btn-primary float-right btn-lg" id="submit-finish-invoice"><i class="fas fa-save"></i> | SUBMIT INVOICE</button>
                    </div>
                </div>
            </div>
        </div>
        <!----------------------------------------------- END DETAIL ITEM ------------------------------------->
    </div>
</div>
<div id="location-modal-customer"></div>
<div id="location-modal-dn-outstanding"></div>
<div id="location-modal-address"></div>