<style>
	.table-detail .table-responsive {
		overflow-x: auto;
	}

	.table-detail .table-responsive #table_item {
		min-width: 1500px;
	}


	.table-detail .header .left a {
		margin-right: 1rem;
		color: red;
	}

	.table-detail .header .left a:hover {
		text-decoration: revert;
	}

	.table-detail .header .left a>i {
		font-size: 11px;
	}

	.remove_item_dtl {
		color: red;
	}
</style>

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
									<th>No. Dokumen</th>
									<th>Tgl Dokumen</th>
									<th>Customer</th>
									<th>Total</th>
									<th>Keterangan</th>
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
					<input type="hidden" name="sysid_hdr">
					<div class="card-header">
						<h2 class="card-title mt-2"><span id="title-add-hdr">Add</span> <?= $page_title ?></h2>
						<div class="card-tools">
							<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
								<i class="fas fa-arrow-left"></i>
							</a>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Nomor Dokumen</label>
								<input type="text" class="form-control form-control-sm" name="doc_number" id="doc_number" placeholder="Generate Otomatis" readonly>
							</div>
							<div class="col-lg-6 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Tanggal Dokumen</label>
								<div class="input-group input-group-sm">
									<input type="text" class="form-control form-control-sm flatpickr" name="doc_date" id="doc_date">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Customer</label>
								<select class="form-control form-control-sm select2" name="id_customer" id="id_customer" required>
									<?php foreach ($customer->result() as $row) : ?>
										<option value="<?= $row->id_customer ?>"><?= $row->code_customer ?> - <?= $row->name_customer ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-lg-12 col-sm-12 px-4 form-group">
								<label style="font-weight: 500;">Keterangan</label>
								<textarea rows="4" class="form-control form-control-sm" name="keterangan" id="keterangan" placeholder="Keterangan ..."></textarea>
							</div>
							<div class="col-lg-12 col-sm-12 px-4 form-group">
								<div class="form-group clearfix">
									<div class="icheck-primary d-inline">
										<input type="checkbox" name="is_lunas" id="is_lunas">
										<label for="is_lunas">Lunas ?</label>
									</div>
								</div>
								<div class="form-group">
									<label style="font-weight: 500;">Note Lunas</label>
									<textarea rows="4" class="form-control form-control-sm" name="note_lunas" id="note_lunas" placeholder="Note Lunas ..."></textarea>
								</div>
							</div>
						</div>

						<div class="row mt-5">
							<div class="col-12 px-4 table-detail">
								<div class="d-flex justify-content-between header">
									<div class="left d-flex">
										<a href="javascript:void(0);" class="tambah_detail">Tambah Detail (<i class="fa fa-plus"></i>)</a>
									</div>
									<div class="right d-flex">
										<p class="mb-4 mt-1 mr-2">Search</p>
										<input type="text" id="search-list-item" class="form-control form-control-sm">
									</div>
								</div>
								<div class="table-responsive">
									<table id="table_item" class="table table-striped">
										<thead>
											<tr>
												<th width="5%">#</th>
												<th width="20%">No. Invoice</th>
												<th width="35%">Outstanding Receive</th>
												<th width="35%">Total Receive</th>
												<th>Tipe Dokumen</th>
												<th width="5%">Action</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<h5 class="text-center" id="no_data_item"><b>Tidak Ada Data</b></h5>
							</div>							
						</div>
						<div class="row">
							<div class="col-lg-12 col-sm-12 px-4 form-group text-right">
								<label style="font-weight: 500;">Total Receive</label>
								<input type="text" class="form-control form-control text-right" name="total" id="total" placeholder="0" readonly>
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
				<h5 class="modal-title">List Invoice</h5>
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
									<th>No. Invoice</th>
									<th>Outstanding Invoice</th>
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
