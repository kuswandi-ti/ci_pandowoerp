<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><?= $page_title ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="post" id="filter-date">
                        <div class="row">
                            <div class="col-md-1">
                                <p class="">Tanggal Akhir Pakai</p>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="tgl_finish" id="tgl_finish" class="form-control text-center flatpickr-input readonly" value="<?= date('Y-m-01') ?>">
									<button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr />
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
									<th class="text-dark">Lama Asset Terpakai (Tahun)</th>
									<th class="text-dark">Nilai Asset Berkurang</th>
									<th class="text-dark">Nilai Asset Sekarang</th>
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
        </div>
    </div>
    <div id="location">

    </div>
</div>
