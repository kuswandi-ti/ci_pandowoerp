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
					<div class="row">
						<div class="col-lg-6 col-sm-6">
							<div class="table-responsive">
								<table class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
									<thead style="background-color: #3B6D8C;">
										<tr class="text-white">
											<th colspan="3">Akun</th>
											<th class="text-right">Jumlah</th>							
										</tr>
									</thead>
									<tbody>
										<?php echo $report_left; ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-6 col-sm-6">
							<div class="table-responsive">
								<table class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
									<thead style="background-color: #3B6D8C;">
										<tr class="text-white">
											<th colspan="3">Akun</th>
											<th class="text-right">Jumlah</th>							
										</tr>
									</thead>
									<tbody>
										<?php echo $report_right; ?>
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
