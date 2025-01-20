<style>
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
                                    <th>No. Jurnal</th>
                                    <th>Tgl Jurnal</th>
                                    <th>Referensi</th>
									<th>Status</th>
									<th>Debit</th>
									<th>Kredit</th>
                                    <th>Keterangan</th>
									<th>No. Jurnal Cancel</th>
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
                                <label style="font-weight: 500;">Nomor Jurnal</label>
                                <input type="text" class="form-control form-control-sm" name="no_jurnal" id="no_jurnal" placeholder="Generate Otomatis" readonly>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Jurnal</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm flatpickr" name="tgl_jurnal" id="tgl_jurnal">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="col-lg-12 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">No Bukti / Referensi</label>
                                <input type="text" class="form-control form-control-sm" name="reff_desc" id="reff_desc" placeholder="No Bukti / Referensi ...">
                            </div>
                            <div class="col-lg-12 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Keterangan</label>
                                <textarea rows="4" class="form-control form-control-sm" name="keterangan" id="keterangan" placeholder="Keterangan ..."></textarea>
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
                                <table id="table_item" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Kode Akun</th>
                                            <th>Nama Akun</th>
                                            <th>Debit</th>
                                            <th>Kredit</th>
											<th>Note</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <h5 class="text-center" id="no_data_item"><b>Tidak Ada Data</b></h5>
                            </div>
                        </div>

						<div class="col-md-6 offset-md-6 footer-table" style="">
							<div class="row">
								<div class="col-md-5">
									<label>Total Debit</label>
								</div>
								<div class="col-md-1">:</div>
								<div class="col-md-6">
									<input class="form-control form-control-sm" type="text" name="total_debit" readonly="">
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<label>Total Kredit</label>
								</div>
								<div class="col-md-1">:</div>
								<div class="col-md-6">
									<input class="form-control form-control-sm" type="text" name="total_kredit" readonly="">
								</div>
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
                <h5 class="modal-title">List Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
					<label style="font-weight: 500;">Akun Induk</label>
					<select class="form-control form-control-sm select2" name="filter_akun_induk" id="filter_akun_induk">
						<?php foreach ($akun_induk->result() as $row) : ?>
							<option value="<?= $row->SysId ?>">
								<?= $row->kode_akun ?> - <?= $row->nama_akun ?>
							</option>
						<?php endforeach; ?>
					</select>
					<br>
                    <div class="table-responsive">
                        <table id="DataTable_Modal_ListItem" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-center text-white">
                                    <th>#</th>
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
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
