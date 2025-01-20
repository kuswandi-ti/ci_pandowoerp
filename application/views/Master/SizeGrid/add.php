<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/SizeGrid/index') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Pilih Item :</label>
                                <select type="text" class="form-control form-control-sm" name="Item_ID" id="Item_ID" required>
                                    <option value="">-PIlih Item-</option>
                                </select>
                            </div> -->
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kode Ukuran :</label>
                                <input type="text" class="form-control form-control-sm" name="Size_Code" id="Size_Code" placeholder="Otomatis di buatkan system..." readonly>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Categori Ukuran :</label>
                                <select type="text" class="form-control form-control-sm" name="Size_Category" id="Size_Category" required>
                                    <option selected disabled>-PIlih Item-</option>
                                    <option value="PAPAN">PAPAN</option>
                                    <option value="BALOK">BALOK</option>
                                    <option value="KUBUS">KUBUS</option>
                                    <option value="STIK">STIK</option>
                                    <option value="STIK">LEMBARAN</option>
                                    <option value="SEGITIGA">SEGITIGA</option>
                                    <option value="LETTER-L">LETTER-L</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tebal :</label>
                                <input type="number" min="0.01" class="form-control form-control-sm" name="Item_Height" id="Item_Height" required placeholder="Tebal CM...">
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Lebar :</label>
                                <input type="number" min="0.01" class="form-control form-control-sm" name="Item_Width" id="Item_Width" required placeholder="Lebar CM...">
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Panjang :</label>
                                <input type="number" min="0.01" class="form-control form-control-sm" name="Item_Length" id="Item_Length" required placeholder="Panjang CM...">
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kubikasi :</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" name="Cubication" id="Cubication" required readonly placeholder="Kubikasi M3...">
                                    <div class="input-group-append">
                                        <button class="btn btn-danger" type="button" id="calculate-volume" data-toggle="tooltip" data-placement="top" title="Hitung Volume"><i class="fas fa-calculator"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
            </div>
            <div class="card-footer text-muted py-3 text-center mt-4">
                <button type="button" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>