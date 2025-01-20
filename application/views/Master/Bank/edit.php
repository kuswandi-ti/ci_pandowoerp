<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/Bank/') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
					<div class="card-body">
						<input type="hidden" name="sysid" id="sysid" value="<?= $RowData->SysId ?>">
						<div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Bank :</label>
                                <select class="form-control form-control-sm select2" name="id_bank" id="id_bank" required>
                                    <?php foreach ($bank->result() as $row) : ?>
										<option <?= ($RowData->id_bank == $row->SysId ? 'selected' : null) ?> value="<?= $row->SysId ?>"><?= $row->kode_bank ?> - <?= $row->nama_bank ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
							<div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Currency :</label>
                                <select class="form-control form-control-sm select2" name="currency_bank" id="currency_bank" required>
                                    <?php foreach ($currency->result() as $row) : ?>
										<option <?= ($RowData->currency_bank == $row->Currency_ID ? 'selected' : null) ?> value="<?= $row->Currency_ID ?>"><?= $row->Currency_ID ?> - <?= $row->Currency_Description ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-lg-12 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kode Bank :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowData->kode_bank ?>" name="kode_bank" id="kode_bank" placeholder="Kode Bank" required>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nomor Rekening Bank :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowData->nomor_rekening_bank ?>" name="nomor_rekening_bank" id="nomor_rekening_bank" placeholder="Nomor Rekening Bank" required>
                            </div>
							<div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Rekening Bank :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowData->nama_rekening_bank ?>" name="nama_rekening_bank" id="nama_rekening_bank" placeholder="Nama Rekening Bank" required>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-lg-12 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Cabang :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowData->cabang_bank ?>" name="cabang_bank" id="cabang_bank" placeholder="Cabang" required>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-lg-12 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Akun :</label>
                                <select class="form-control form-control-sm select2" name="id_coa" id="id_coa" required>
                                    <?php foreach ($coa->result() as $row) : ?>
										<option <?= ($RowData->id_coa == $row->SysId ? 'selected' : null) ?> value="<?= $row->SysId ?>"><?= $row->kode_akun ?> - <?= $row->nama_akun ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-lg-12 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Keterangan :</label>
                                <textarea type="text" class="form-control form-control-sm" name="catatan" id="catatan" placeholder="Keterangan"><?= $RowData->catatan ?></textarea>
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
