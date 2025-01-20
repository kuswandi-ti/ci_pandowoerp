<div class="row">
    <div class="col-lg-6 col-sm-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><b>Header Delivery Note</b></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="form_hdr_dn" method="post" action="#">
                    <div class="form-group">
                        <label>Customer :</label>
                        <input type="hidden" name="id_customer" id="id_customer">
                        <input type="hidden" name="customer_code" id="customer_code">
                        <div class="input-group input-group-sm shadow">
                            <input type="text" class="form-control form-control-sm" name="customer_name" id="customer_name" placeholder="Customer..." required readonly>
                            <div class="input-group-append">
                                <button type="button" id="btn--customer" class="btn bg-gradient-info">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Purchase Order Number :</label>
                        <input type="hidden" name="id_po" id="id_po">
                        <div class="input-group input-group-sm shadow">
                            <input type="text" class="form-control form-control-sm" name="no_po_customer" id="no_po_customer" placeholder="PO Number..." required readonly>
                            <div class="input-group-append">
                                <button type="button" id="btn--po" class="btn bg-gradient-info">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>SO. Number :</label>
                        <div class="input-group input-group-sm shadow">
                            <input type="text" class="form-control form-control-sm" name="no_po_internal" id="no_po_internal" placeholder="SO Number..." required readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Customer Address :</label>
                        <input type="hidden" id="id_address" name="id_address">
                        <div class="input-group input-group-sm shadow">
                            <textarea class="form-control form-control-sm" readonly name="customer_address" id="customer_address" placeholder="Customer Address..." required rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>ATT to :</label>
                        <div class="input-group input-group-sm shadow">
                            <input type="text" class="form-control form-control-sm" name="att_to" id="att_to" placeholder="Attention To..." required>
                            <div class="input-group-append">
                                <span class="input-group-text">&nbsp;<i class="fas fa-users"></i>&nbsp;</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Send Date :</label>
                        <div class="input-group input-group-sm shadow">
                            <input type="text" class="form-control form-control-sm datepicker" readonly name="tgl_kirim" id="tgl_kirim" data-toggle="datetimepicker" data-target="#tgl_kirim" placeholder="Send Date..." required value="<?php echo date('Y-m-d') ?>">
                            <div class="input-group-append">
                                <span id="btn--date" class="input-group-text">&nbsp;<i class="fas fa-calendar"></i>&nbsp;</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Vehicle Plate Number :</label>
                        <input type="hidden" name="id_kendaraan" id="id_kendaraan">
                        <div class="input-group input-group-sm shadow">
                            <input type="text" class="form-control form-control-sm" name="no_kendaraan" id="no_kendaraan" placeholder="Plate Number..." required readonly>
                            <div class="input-group-append">
                                <button type="button" id="btn--kendaraan" class="btn bg-gradient-info">&nbsp;<i class="fas fa-truck"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Driver :</label>
                        <input type="hidden" name="init_driver" id="init_driver">
                        <div class="input-group input-group-sm shadow">
                            <input type="text" class="form-control form-control-sm" name="nama_driver" id="nama_driver" placeholder="Driver..." required readonly>
                            <div class="input-group-append">
                                <button type="button" id="btn--driver" class="btn bg-gradient-info">&nbsp;<i class="fas fa-user"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <button type="button" class="btn btn-primary float-right btn-lg" id="generate--dn"><i class="fas fa-envelope"></i> | Generate DN Number</button>
                </div>
            </div>
        </div>
    </div>
    <!----------------------------------------------- END HEADER ------------------------------------->
    <div class="col-lg-6 col-sm-6" id="location-detail-form">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><b>Detail Item Delivery Note</b></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="form_item_dn" method="post" action="#">
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <input type="hidden" required name="DN_Number" id="DN_Number" readonly>
                            <input type="text" class="form-control" required name="DN_Number_Show" id="DN_Number_Show" placeholder="DN Number..." readonly>
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i>&nbsp;&nbsp; DN. Number</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 mt-1">
                            <table class="table table-sm table-striped table-bordered table-hover" style="width: 100%;" id="tbl-item-dn">
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
                                    <!-- HI DUDE I DO SOME MAGIC HERE -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
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
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <button type="button" class="btn btn-primary float-right btn-lg" id="submit-finish-dn"><i class="fas fa-envelope"></i> | SAVE DELIVERY NOTE</button>
                </div>
            </div>
        </div>
    </div>
    <!----------------------------------------------- END DETAIL ITEM ------------------------------------->
</div>