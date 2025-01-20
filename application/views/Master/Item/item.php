<style>
    #DataTable_Alias_filter {
        float: left;
    }

    #DataTable_Alias_filter label input {
        width: 50vh;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><?= $page_title ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="DataTable" class="table table-sm table-bordered table-striped display" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-center text-white">
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Color</th>
                                    <th>Brand</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Group</th>
                                    <th>Barcode Pattern</th>
                                    <th>UoM</th>
                                    <th>Default Curr</th>
                                    <!-- <th>Average Cost</th> -->
                                    <!-- <th>MultiComp</th> -->
                                    <th>Expenses (Biaya/Jasa)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- hi dude i dude some magic here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location">
        <div class="modal fade" id="modal_item_code_alias" data-backdrop="static" data-keyboard="false" aria-labelledby="Label" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form method="post" enctype="multipart/form-data" action="#" id="detail-form">
                                <input type="hidden" name="state" id="state" value="ADD">
                                <input type="hidden" name="SysId" id="SysId" value="">
                                <div class="row">
                                    <div class="col-lg-3 form-group">
                                        <input class="form-control form-control-sm text-center" required name="Item_Code_Internal" id="Item_Code_Internal" readonly>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <input class="form-control form-control-sm" required name="Item_Name_Internal" id="Item_Name_Internal" readonly>
                                    </div>
                                </div>
                                <hr />
                                <div id="input-form">
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label style="font-weight: bold;">Customer :</label>
                                            <select class="form-control form-control-sm select2" name="Account_ID" id="Account_ID" required>
                                                <option value="" selected>-Customer-</option>
                                                <?php foreach ($List_Cust as $li): ?>
                                                    <option value="<?= $li->SysId ?>"><?= $li->AccountTitle_Code . '. ' . $li->Account_Name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 form-group">
                                            <label style="font-weight: bold;">Item Code Customer :</label>
                                            <input type="text" class="form-control form-control-sm" name="Item_CodeAlias" id="Item_CodeAlias" required placeholder="Item Code Customer ....">
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label style="font-weight: bold;">Item Name Customer :</label>
                                            <input type="text" class="form-control form-control-sm" name="Item_NameAlias" id="Item_NameAlias" required placeholder="Item Name Customer ....">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <button class="btn btn-primary" id="submit-detail-form"><i class="fas fa-download"></i>&nbsp;&nbsp;Save & Submit</button>
                                            <button class="btn btn-danger" id="cancel_form"><i class="fas fa-times"></i>&nbsp;&nbsp; Cancel</button>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="DataTable_Alias" class="table table-sm table-bordered table-striped display" style="width: 100%;">
                                    <thead style="background-color: #3B6D8C;">
                                        <tr class="text-center text-white">
                                            <th>#</th>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Item Code Alias</th>
                                            <th>Customer Alias</th>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>