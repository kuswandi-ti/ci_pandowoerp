<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><b>Form Reception Document PO From Customer</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_hdr_po" method="post" action="#">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Purchase Order Number :</label>
                                    <input type="text" class="form-control form-control-sm" name="po_number" id="po_number" placeholder="PO Number..." required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Customer :</label>
                                    <select class="form-control form-control-sm" required name="customer" id="customer">
                                        <option selected disabled>-Choose-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Date Document Receive :</label>
                                    <input type="text" readonly class="form-control form-control-sm datepicker" data-toggle="datetimepicker" data-target="#tgl_terbit" name="tgl_terbit" id="tgl_terbit" placeholder="Date PO Received..." required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Term Of Payment :</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" min="7" maxlength="3" class="form-control form-control-sm" name="term_of_payment" id="term_of_payment" placeholder="Term Of Payment..." required>
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
                                        <option value="" selected disabled>-Choose-</option>
                                        <option value="AFTER INVOICE RECEIVED">AFTER INVOICE RECEIVED</option>
                                        <option value="AFTER PO CLOSE">AFTER PO CLOSE</option>
                                        <option value="AFTER GOODS RECEIVED NOTE">AFTER GOODS RECEIVED NOTE</option>
                                        <option value="AFTER PO RECEIVED">AFTER PO RECEIVED</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Term Of Delivery :</label>
                                    <input type="text" readonly class="form-control form-control-sm datepicker" data-toggle="datetimepicker" data-target="#term_of_delivery" name="term_of_delivery" id="term_of_delivery" placeholder="Term of delivery..." required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Customer Address :</label>
                                    <input type="hidden" id="id_address" name="id_address">
                                    <div class="input-group input-group-sm">
                                        <textarea class="form-control form-control-sm" readonly name="customer_address" id="customer_address" placeholder="Customer Address" required rows="3"></textarea>
                                        <div class="input-group-append">
                                            <button type="button" id="btn--list--address" class="btn btn-success">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Koresponden :</label>
                                    <textarea class="form-control form-control-sm" name="koresponden" id="koresponden" placeholder="Name Sender/Contact/Email/Hp/WhatsApp & Etc...." required rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Note :</label>
                                    <textarea class="form-control form-control-sm" name="note" id="note" placeholder="Note..." required rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>No. SO :</label>
                                    <input type="text" class="form-control" name="no_Doc" id="no_Doc" placeholder="No. SO Internal..." readonly>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label><?= $PPn->Name ?> :</label>
                                    <div class="form-check">
                                        <input class="form-check-input" name="PPn" id="PPn" type="checkbox" checked value="<?= floatval($PPn->Persentase) ?>">
                                        <label class="form-check-label"><?= floatval($PPn->Persentase) ?> %</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-block btn-primary btn-lg" id="submit--hdr"><i class="fas fa-download"></i> GENERATE NO. SO INTERNAL</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="card-detail-item">
                        <!-- Hi dude i do some magic here ! -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location">
        <!-- location modal list address customer -->
    </div>
</div>