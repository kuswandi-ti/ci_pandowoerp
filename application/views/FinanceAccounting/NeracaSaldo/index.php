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
							<div class="col-lg-4 col-sm-12 form-group">
								<label style="font-weight: 500;">Dari Tanggal</label>
								<div class="input-group input-group-sm">
									<input type="text" class="form-control text-center flatpickr-input readonly" name="dari_tanggal" id="dari_tanggal" value="<?= !isset($_GET['dari_tanggal']) ? date('Y-m-d') : $_GET['dari_tanggal'] ?>">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-sm-12 form-group">
								<label style="font-weight: 500;">Sampai Tanggal</label>
								<div class="input-group input-group-sm">
									<input type="text" class="form-control text-center flatpickr-input readonly" name="sampai_tanggal" id="sampai_tanggal" value="<?= !isset($_GET['sampai_tanggal']) ? date('Y-m-d') : $_GET['sampai_tanggal'] ?>">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-sm-12 form-group">
								<label style="font-weight: 500;">&nbsp;</label>
								<div class="input-group input-group-sm">
									<input type="submit" name="submit" class="btn btn-success" value="Filter">
								</div>
							</div>
						</div>

						<br><br>
					</form>

					<div class="col-lg-12 col-sm-12">
						<div class="table-responsive">
							<table class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
								<thead style="background-color: #3B6D8C;">
									<tr class="text-white">
										<th colspan="3">Akun</th>
										<!--<th class="text-right">Saldo Awal</th>-->
										<th class="text-right">Debet</th>
										<th class="text-right">Kredit</th>
										<!--<th class="text-right">Saldo Akhir</th>-->
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

	<div id="location">

	</div>
</div>
