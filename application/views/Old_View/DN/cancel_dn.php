<div class="container-fluid">
    <div class="row" id="elem-input-dn">
        <div class="col-lg-4 col-sm-4">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title"><b>Form Cancel/Swap Item Delivery Note</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="dn">Delivery Note Number</label>
                        <div class="input-group input-group-sm shadow">
                            <input type="text" class="form-control form-control-sm" name="dn_number" id="dn_number" placeholder="Delivery Note Number..." required>
                            <div class="input-group-append">
                                <button type="button" id="btn--search--dn" class="btn bg-gradient-info">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="elem-select-product" style="display: none;">
                        <label for="item">Item Delivery Note</label>
                        <select class="form-control form-control-sm shadow" style="width: 100%;" name="product" id="product">
                            <option selected disabled>-Choose-</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer" id="elem-submit" style="display: none;">
                    <div class="form-group">
                        <a href="<?=base_url('CancelDN')?>" class="btn btn-danger btn-lg"><i class="fas fa-times"></i> | Cancel</a>
                        <button type="button" class="btn btn-info float-right btn-lg" id="btn-show-list-loading"><i class="fas fa-list"></i> | SHOW LIST BARCODE</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-lg-8" id="card-list-barcode" style="display: none;">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title"><b>List Barcode Number, No. Loading : <span id="no_loading_span"></span></b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary float-right" id="btn-complete-form"><i class="fas fa-save"></i> | SUBMIT</button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" id="form-list-loading" method="post">
                        <input type="hidden" name="no_loading" id="no_loading">
                        <input type="hidden" name="no_dn" id="no_dn">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="table-responsive">
                            <table id="Tbl_list_Loading" class="table-sm table-striped table-bordered table-valign-middle" style="width: 100%;">
                                <thead style="background-color: #3B6D8C;">
                                    <tr>
                                        <th class="text-center text-white">#</th>
                                        <th class="text-center text-white">NO BARCODE</th>
                                        <th class="text-center text-white">OPTION</th>
                                        <th class="text-center text-white">BARCODE SUBS</th>
                                        <!-- <th class="text-center text-white">HANDLE</th> -->
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>