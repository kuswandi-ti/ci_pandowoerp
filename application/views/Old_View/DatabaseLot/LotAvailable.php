<style>
    div.dt-buttons {
        clear: both;
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
                    <form action="#" method="post" id="filter-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group input-group-sm">
                                    <select class="form-control form-control-sm" name="material" id="material">
                                        <option value="" selected>-ALL MATERIAL-</option>
                                        <?php foreach ($materials as $li) : ?>
                                            <option value="<?= $li->sysid ?>"><?= $li->kode ?> (<?= $li->deskripsi ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-prepend">
                                        <button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm btn-block">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr />
                    <div class="table-responsive">
                        <table id="DataTable" class="table table-striped table-bordered display compact nowrap" style="width: 100%; height: 50px;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">LPB</th>
                                    <th class="text-center text-white">SUPPLIER</th>
                                    <th class="text-center text-white">LOT</th>
                                    <th class="text-center text-white">BARANG</th>
                                    <th class="text-center text-white">RP/PCS</th>
                                    <th class="text-center text-white">PCS</th>
                                    <th class="text-center text-white">KUBIKASI</th>
                                    <th class="text-center text-white">SUBTOTAL</th>
                                    <th class="text-center text-white">GRADER</th>
                                    <th class="text-center text-white">TGL. GRID</th>
                                    <th class="text-center text-white">STATUS</th>
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

    </div>
</div>