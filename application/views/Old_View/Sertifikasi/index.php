<style>
    div.dt-buttons {
        clear: both;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h6 class="text-center" style="font-weight: bold;"><?= $page_title ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3 col-lg-3"></div>
                        <div class="col-sm-6 col-lg-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" autofocus="autofocus" id="no_barcode" value="" name="no_barcode">
                                <div class="input-group-prepend">
                                    <button type="button" data-toggle="tooltip" title="Data sertifikasi" id="submit--barcode" class="btn bg-gradient-danger"> &nbsp;&nbsp;&nbsp;<b><i class="fas fa-barcode"></i></b>&nbsp;&nbsp;&nbsp; </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-lg-3"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/DataTables/FixedColumns-4.0.1/css/fixedColumns.dataTables.min.css"> -->
        <div class="container-fluid" id="location">
        </div>
    </div>
</div>