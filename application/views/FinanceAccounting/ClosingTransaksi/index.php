<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-sm-12">
			<div class="card card-primary card-outline list-data">
				<div class="card-header">
					<h3 class="card-title"><?= $page_title ?></h3>
					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
							<i class="fas fa-minus"></i>
						</button>
					</div>
				</div>
				<div class="card-body">
					<form method="post" enctype="multipart/form-data" action="#" id="main-form">
						<div class="row">
							<div class="col-lg-6 col-sm-12 form-group">
								<label style="font-weight: 500;">Dari Tanggal</label>
								<div class="input-group input-group-sm">
									<input type="text" class="form-control text-center flatpickr-input readonly" name="dari_tanggal" id="dari_tanggal" value="<?= date('Y-m-d') ?>">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 form-group">
								<label style="font-weight: 500;">Sampai Tanggal</label>
								<div class="input-group input-group-sm">
									<input type="text" class="form-control text-center flatpickr-input readonly" name="sampai_tanggal" id="sampai_tanggal" value="<?= date('Y-m-d') ?>">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- =============================== END FORM =========================== -->
						<div class="card-footer text-muted py-3 text-center mt-4">
							<button type="button" href="javascript:void(0);" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Proses & Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
