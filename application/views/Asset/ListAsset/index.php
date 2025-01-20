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
					<div class="table-responsive">
						<table id="DataTable" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
							<thead style="background-color: #3B6D8C;">
								<tr class="text-center text-white">
									<th style="display:none;">ID</th>
									<th>No. Asset</th>
									<th>Kode Asset</th>
									<th>Nama Asset</th>
									<th>Tgl Perolehan</th>
									<th>Tahun Perolehan</th>
									<th>Harga Perolehan</th>
									<th>Masa Pakai (Tahun)</th>
									<th>Penyusutan per Tahun (%)</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<!-- hi dude i dude some magic here -->
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="card bd-callout shadow add-data">
				<form method="post" enctype="multipart/form-data" action="#" id="main-form">
					<input type="hidden" name="state">
					<input type="hidden" name="sysid">
					<div class="card-header">
						<h2 class="card-title mt-2">Add <?= $page_title ?></h2>
						<div class="card-tools">
							<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
								<i class="fas fa-arrow-left"></i>
							</a>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Nomor Asset</label>
								<input type="text" class="form-control form-control-sm" name="no_asset" id="no_asset" readonly>
							</div>
							<div class="col-lg-6 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Kode Asset</label>
								<input type="text" class="form-control form-control-sm" name="item_code" id="item_code" readonly>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Tgl Perolehan</label>
								<input type="text" class="form-control form-control-sm" name="tgl_perolehan" id="tgl_perolehan" readonly>
							</div>
							<div class="col-lg-3 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Tahun Perolehan</label>
								<input type="text" class="form-control form-control-sm" name="tahun_perolehan" id="tahun_perolehan" readonly>
							</div>
							<div class="col-lg-6 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Nama Asset</label>
								<input type="text" class="form-control form-control-sm" name="item_name" id="item_name" readonly>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Harga Perolehan</label>
								<input type="text" class="form-control form-control-sm" name="harga_perolehan" id="harga_perolehan" readonly>
							</div>
							<div class="col-lg-2 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Masa Pakai (Tahun)</label>
								<input type="text" class="form-control form-control-sm" name="masa_tahun_pakai" id="masa_tahun_pakai">
							</div>
							<div class="col-lg-2 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Penyusutan (%)</label>
								<input type="text" class="form-control form-control-sm" name="nilai_penyusutan" id="nilai_penyusutan">
							</div>
							<div class="col-lg-6 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Satuan</label>
								<input type="text" class="form-control form-control-sm" name="uom" id="uom" readonly>
							</div>
						</div>
						<!-- =============================== END FORM =========================== -->
						<div class="card-footer text-muted py-3 text-center mt-4">
							<button type="button" href="javascript:void(0);" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_list_item" style="z-index: 1050 !important;" aria-labelledby="Label" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">List Dokumen</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="table-responsive">
						<table id="DataTable_Modal_ListItem" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
							<thead style="background-color: #3B6D8C;">
								<tr class="text-center text-white">
									<th>#</th>
									<th>No. Dokumen</th>
									<th>Outstanding Payment</th>
									<th>Tipe Dokumen</th>
								</tr>
							</thead>
							<tbody>
								<!-- hi dude i dude some magic here -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="select_item"><i class="fas fa-check"></i> &nbsp;&nbsp;&nbsp;Select</button>
			</div>
		</div>
	</div>
</div>
