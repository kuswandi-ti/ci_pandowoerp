<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card bd-callout shadow">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
					<div class="card-header">
                        <h2 class="card-title"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="<?= base_url('MasterData/CostCenter/') ?>" class="btn btn-danger btn-sm" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Kode Cost Center :</label>
                                <input type="text" class="form-control form-control-sm" name="kode_cost_center" id="kode_cost_center" placeholder="Kode Cost Center ...." required>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Cost Center :</label>
                                <input type="text" class="form-control form-control-sm" name="nama_cost_center" id="nama_cost_center" placeholder="Nama Cost Center ...." required>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-lg-12 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Group Cost Center :</label>
                                <select class="form-control form-control-sm select2" name="cc_group_id" id="cc_group_id" required>
                                    <?php foreach ($cc_group as $row) : ?>
                                        <option value="<?= $row->sysid ?>"><?= $row->cc_group_code ?> (<?= $row->cc_group_name ?>)</option>
                                    <?php endforeach; ?>
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
