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
						<div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Tipe Akun :</label>
                                <input type="text" class="form-control form-control-sm" name="nama_tipe_akun" id="nama_tipe_akun" placeholder="Nama Tipe Akun ...." required>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kelompok Akun :</label>
                                <select class="form-control form-control-sm select2" name="kode_tipe_akun" id="kode_tipe_akun" required>
                                    <option value="">Pilih</option>
									<option value="1">ASSET</option>
									<option value="2">COST</option>
									<option value="3">LIABILITY</option>
									<option value="4">INCOME</option>
									<option value="5">EQUITY</option>
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
