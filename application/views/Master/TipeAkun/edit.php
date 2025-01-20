<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/TipeAkun/') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="sysid" id="sysid" value="<?= $RowAccount->SysId ?>">
						<div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Tipe Akun :</label>
                                <input type="text" class="form-control form-control-sm" name="nama_tipe_akun" id="nama_tipe_akun" value="<?= $RowAccount->nama_tipe_akun ?>" placeholder="Nama Tipe Akun ...." required>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kelompok Akun :</label>
                                <select class="form-control form-control-sm select2" name="kode_tipe_akun" id="kode_tipe_akun" required>
									<option <?= ($RowAccount->kode_tipe_akun == '' ? 'selected' : '') ?> value="">Pilih</option>
									<option <?= ($RowAccount->kode_tipe_akun == '1' ? 'selected' : '') ?> value="1">HARTA / ASSETS</option>
									<option <?= ($RowAccount->kode_tipe_akun == '2' ? 'selected' : '') ?> value="2">BIAYA / COST</option>
									<option <?= ($RowAccount->kode_tipe_akun == '3' ? 'selected' : '') ?> value="3">UTANG / LIABILITY</option>
									<option <?= ($RowAccount->kode_tipe_akun == '4' ? 'selected' : '') ?> value="4">PENDAPATAN / INCOME</option>
									<option <?= ($RowAccount->kode_tipe_akun == '5' ? 'selected' : '') ?> value="5">MODAL / EQUITY</option>
                                </select>
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
