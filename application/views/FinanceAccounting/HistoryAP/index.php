<style>
	.dataTables_wrapper .dataTables_filter {
		float: right;
		text-align: right;
		visibility: hidden;
	}
</style>

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
					<form action="#" method="post" id="filter">
						<div class="row">
							<div class="col-md-2">
								<p class="">Supplier / Vendor :</p>
							</div>
							<div class="col-md-4">
								<select class="form-control form-control-sm select2" name="id_supplier" id="id_supplier" required>
									<option value="ALL">All</option>
									<?php foreach ($supplier->result() as $row) : ?>
										<option value="<?= $row->SysId ?>"><?= $row->Account_Code ?> - <?= $row->Account_Name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-3">
								<div class="input-group">
									<button type="button" id="do--filter" class="btn bg-gradient-danger btn-sm">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;</button>
								</div>
							</div>
						</div>
					</form>
					<hr />
					<div class="table-responsive">
						<table id="DataTable" class="table-mini" style="width: 100%;">
							<thead>
								<tr>
									<th class="text-center text-dark">#</th>
									<th class="text-center text-dark">SUPPLIER / VENDOR</th>
									<th class="text-center text-dark">NO. RR / LPB</th>
									<th class="text-center text-dark">TYPE DOC</th>
									<th class="text-center text-dark">AMOUNT RR / LPB</th>
									<th class="text-center text-dark">NO. PAYABLE</th>
									<th class="text-center text-dark">AMOUNT PAYABLE</th>
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
