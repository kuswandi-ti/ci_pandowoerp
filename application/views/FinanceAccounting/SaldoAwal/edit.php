<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('FinanceAccounting/SaldoAwal/') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
					<div class="card-body">
						<input type="hidden" name="sysid" id="sysid" value="<?= $RowData->SysId ?>">
						<div class="row">
                            <div class="col-lg-12 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Akun :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowData->kode_akun ?> - <?= $RowData->nama_akun ?>" name="id_coa" id="id_coa" readonly>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Debet :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowData->debit ?>" name="debit" id="debit" placeholder="Saldo Debet" required>
                            </div>
							<div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kredit :</label>
                                <input type="text" class="form-control form-control-sm" value="<?= $RowData->credit ?>" name="credit" id="credit" placeholder="Saldo Kredit" required>
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
