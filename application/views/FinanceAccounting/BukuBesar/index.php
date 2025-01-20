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
					<form action="" method="get">
						<div class="row">
							<div class="col-lg-12 col-sm-12 pb-4">						
								<select class="form-control form-control-lg select2 px-4" name="coa" id="coa">
									<option value="">Pilih Akun</option>
									<?php foreach ($coa->result() as $row) : ?>
										<option <?= ($row->SysId == (!isset($_GET['coa']) ? "" : $_GET['coa']) ? 'selected' : null) ?> value="<?= $row->SysId ?>"><?= $row->kode_akun ?> - <?= $row->nama_akun ?></option>
									<?php endforeach; ?>
								</select>						
							</div>
						</div>
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
							
						<br>

						<div class="btn-group">
							<input type="submit" name="submit" class="btn btn-success" value="Filter">
						</div>

						<br><br>
					</form>
					
					<div class="row">
						<div class="col-lg-12 col-sm-12">
							<div class="table-responsive">
								<table class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
									<thead style="background-color: #3B6D8C;">
										<tr class="text-white">
											<th class="text-center">No. Jurnal</th>
											<th class="text-center">Tgl Jurnal</th>
											<th>No. Bukti / Referensi</th>
											<th>Keterangan</th>
											<th class="text-center">Tipe</th>
											<th class="text-right">Debet</th>
											<th class="text-right">Kredit</th>
											<th class="text-right">Saldo</th>								
										</tr>
									</thead>
									<tbody>
										<?php echo $report; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="location">

	</div>
</div>
