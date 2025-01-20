<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <button type="button" class="btn bg-gradient-primary btn-xs" data-toggle="modal" data-target="#modal-add">
                        <span class="btn-label"><i class="fas fa-plus"></i></span> | Master Customer
                    </button>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-master" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">Customer Code</th>
                                    <th class="text-center text-white">Customer Name</th>
                                    <th class="text-center text-white">NPWP</th>
                                    <th class="text-center text-white">Koresponden</th>
                                    <th class="text-center text-white">Addressing</th>
                                    <th class="text-center text-white">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($Customers as $li) : ?>
                                    <tr id="<?= $li->SysId ?>">
                                        <td class="text-center"><?= $i; ?></td>
                                        <td class="text-center"><?= $li->Customer_Code ?></td>
                                        <td class="text-center"><?= $li->Customer_Name ?></td>
                                        <td class="text-center"><?= $li->NPWP ?></td>
                                        <td class="text-center"><?= $li->Koresponden ?></td>
                                        <td class="text-center">
                                            <button data-toggle="tooltip" data-pk="<?= $li->SysId ?>" data-original-title="add address" class="btn btn-sm btn-warning btn-add-address"><i class="fas fa-plus"></i></button>&nbsp;
                                            <button data-toggle="tooltip" data-pk="<?= $li->SysId ?>" data-original-title="List address" class="btn btn-sm btn-dark btn-list-address"><i class="fas fa-map-signs"></i></button>
                                        </td>
                                        <td class="text-center">
                                            <!-- <div class="btn-group"> -->
                                            <?php if ($li->is_active == 1) : ?>
                                                <button data-toggle="tooltip" data-pk="<?= $li->SysId ?>" data-original-title="Non-aktifkan" class="btn btn-sm bg-gradient-success is-active blink_me"><i class="fas fa-check"></i></button>&nbsp;
                                            <?php else : ?>
                                                <button data-toggle="tooltip" data-pk="<?= $li->SysId ?>" data-original-title="Aktifkan" class="btn btn-sm bg-gradient-secondary is-active"><i class="fas fa-times"></i></button>&nbsp;
                                            <?php endif; ?>
                                            <button data-toggle="tooltip" data-pk="<?= $li->SysId ?>" data-original-title="Edit data customer" class="btn btn-sm btn-info btn-edit"><i class="fas fa-edit"></i></button>
                                            <!-- </div> -->
                                        </td>
                                    </tr>
                                <?php $i++;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-add" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form-edit" method="POST" enctype="multipart/form-data" action="<?= base_url('Master/store_customer') ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Master Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Customer Code :</span>
                                    </div>
                                    <input type="text" class="form-control" required placeholder="Code..." name="customer_code" id="customer_code" style="text-transform: uppercase;" maxlength="4" minlength="3">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Customer Name :</span>
                                    </div>
                                    <input type="text" class="form-control" required placeholder="Name..." name="customer_name" id="customer_name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">NPWP :</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="NPWP..." name="npwp" id="npwp">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Koresponden :</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Koresponden..." name="koresponden" id="koresponden">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-add-address" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form-add-address" method="POST" enctype="multipart/form-data" action="<?= base_url('Master/customer_add_address') ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Customer Address</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <input type="hidden" name="sysid_customer" id="sysid_customer">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Address :</span>
                                    </div>
                                    <textarea type="text" class="form-control" required placeholder="Address..." name="Address" id="Address" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Kota/Kabupaten :</span>
                                    </div>
                                    <input type="text" class="form-control" required placeholder="Kota/Kabupaten ..." name="City" id="City">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Postal Code :</span>
                                    </div>
                                    <input type="text" class="form-control" required placeholder="Postal Code..." name="Postal_Code" id="Postal_Code">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Phone :</span>
                                    </div>
                                    <input type="number" class="form-control" required placeholder="Phone..." name="Phone" id="Phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Email :</span>
                                    </div>
                                    <input type="email" class="form-control" placeholder="Email..." name="Email" id="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Fax :</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Fax..." name="Fax" id="Fax">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="width: 130px;">Koresponden :</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Koresponden..." name="Koresponden" id="Koresponden">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
<div id="location"></div>