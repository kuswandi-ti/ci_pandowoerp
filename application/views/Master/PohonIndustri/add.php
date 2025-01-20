<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/PohonIndustri/') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Pohon Industri :</label>
                                <input type="text" class="form-control form-control-sm" name="Nama_Pohon_Kayu" id="Nama_Pohon_Kayu" placeholder="Nama Jenis Kayu ...." required>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Pattern Code Jenis Kayu :</label>
                                <input type="text" class="form-control form-control-sm" name="Grouping_Code" id="Grouping_Code" placeholder="Pattern Code ...." required>
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