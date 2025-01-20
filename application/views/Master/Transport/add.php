<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/Transport/') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Transportasi :</label>
                                <input type="text" class="form-control form-control-sm" name="transport_name" id="transport_name" placeholder="Nama Transportasi ...." required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-8 px-4 form-group mt-2">
                            <label style="font-weight: 500;">Status Transportasi :</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1" id="StatusTrue" name="status" checked>
                                <label class="form-check-label" for="StatusTrue">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="0" id="StatusFalse" name="status">
                                <label class="form-check-label" for="StatusFalse">
                                    Tidak Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted py-3 text-center mt-4">
                        <button type="button" href="#" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>