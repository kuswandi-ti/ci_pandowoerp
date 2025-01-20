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
                        <table id="DataTable" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-center text-white">
                                    <th>Item Category</th>
                                    <th>Item Category Code</th>
                                    <th>Item Hasil Produksi</th>
                                    <th>Material/Component Produksi</th>
                                    <th>Asset</th>
                                    <th>Item Penjualan</th>
                                    <th>Item Pembelian</th>
                                    <th><i class="fas fa-cogs"></i></th>
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
    <div id="location-modal-add">
        <div class="modal fade" id="ModalMainForm" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="ModalMainFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalMainFormLabel">Form New Category Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold;">Item Category :</label>
                                <input type="text" class="form-control form-control-sm" name="Item_Category" id="Item_Category" required placeholder="Category Name ....">
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold;">Item Category Code :</label>
                                <input type="text" class="form-control form-control-sm" maxlength="3" name="Item_Category_Init" id="Item_Category_Init" required placeholder="Category Code ....">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold;">Category untuk hasil produksi ? :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Prod" id="Is_Prod1" value="1">
                                    <label class="form-check-label" for="Is_Prod1">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Prod" id="Is_Prod2" value="0">
                                    <label class="form-check-label" for="Is_Prod2">No</label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold;">Category untuk dialokasikan ke produksi/logistik ? :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Allocation" id="Is_Allocation1" value="1">
                                    <label class="form-check-label" for="Is_Allocation1">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Allocation" id="Is_Allocation2" value="0">
                                    <label class="form-check-label" for="Is_Allocation2">No</label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold;">Category untuk asset ? :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Asset" id="Is_Asset1" value="1">
                                    <label class="form-check-label" for="Is_Asset1">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Asset" id="Is_Asset2" value="0" checked>
                                    <label class="form-check-label" for="Is_Asset2">No</label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold;">Category untuk item penjualan ? :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_So_Item" id="Is_So_Item1" value="1">
                                    <label class="form-check-label" for="Is_So_Item1">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_So_Item" id="Is_So_Item2" value="0">
                                    <label class="form-check-label" for="Is_So_Item2">No</label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: bold;">Category untuk item pembelian ? :</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Po_Item" id="Is_Po_Item1" value="1">
                                    <label class="form-check-label" for="Is_Po_Item1">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" required name="Is_Po_Item" id="Is_Po_Item2" value="0">
                                    <label class="form-check-label" for="Is_Po_Item2">No</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp; Close</button>
                        <button type="button" class="btn btn-primary" id="btn-submit-main"><i class="fas fa-save"></i>&nbsp;&nbsp; Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location">

    </div>
</div>