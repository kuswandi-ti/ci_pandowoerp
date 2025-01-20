<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="base_url" content="<?= base_url() ?>">

	<title><?= $this->config->item('app_name') ?> | <?= $page_title ?></title>
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/imp-assets/apple-touch-icon.png" />

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/fontawesome-free/css/all.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/load-table.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/custom-table.css">
	<!-- =================================== ADDITIONAL PLUGIN ===================================== -->
	<!-- DataTables -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-select/css/select.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-rowgroup/css/rowGroup.bootstrap4.min.css">
	<!-- Additinal script -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/sweet-alert/dist/sweetalert2.min.css">
	<!-- datepicker Bootstrap 4 -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/flatpickr/flatpickr.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/toastr/toastr.css">
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/jquery/jquery.min.js"></script>
	<style>
		.modal-backdrop {
			z-index: 1036;
		}

		.select2 {
			width: 100% !important;
		}

		.select2-selection {
			font-size: 14px;
		}

		.select2-selection__rendered,
		.select2-selection__arrow {
			margin-top: 5px;
		}
	</style>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed text-sm sidebar-mini sidebar-collapse">
	<!-- layout-footer-fixed -->
	<!-- Site wrapper -->
	<div class="wrapper">

		<div class="preloader flex-column justify-content-center align-items-center">
			<img class="animation__shake" src="<?php echo base_url(); ?>assets/imp-assets/apple-touch-icon.png" alt="PERUSAHAAN-LOGO" height="60" width="60">
		</div>

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="javascript:void(0)" class="nav-link"><?= $page_title ?></a>
				</li>
			</ul>

			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<!-- Navbar Search -->
				<li class="nav-item">
					<a class="nav-link" data-widget="navbar-search" href="#" role="button">
						<i class="fas fa-search"></i>
					</a>
					<div class="navbar-search-block">
						<form class="form-inline">
							<div class="input-group input-group-sm">
								<input class="form-control form-control-navbar" readonly type="text" placeholder="Search" aria-label="Search">
								<div class="input-group-append">
									<button class="btn btn-navbar" type="submit">
										<i class="fas fa-search"></i>
									</button>
									<button class="btn btn-navbar" type="button" data-widget="navbar-search">
										<i class="fas fa-times"></i>
									</button>
								</div>
							</div>5@
						</form>
					</div>
				</li>

				<!-- Messages Dropdown Menu -->

				<!-- Notifications Dropdown Menu -->
				<li class="nav-item">
					<a class="nav-link" data-widget="fullscreen" href="#" role="button">
						<i class="fas fa-expand-arrows-alt"></i>
					</a>
				</li>
				<li class="nav-item dropdown user-menu">
					<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
						<img src="<?= base_url() ?>assets/imp-assets/user-default/male.png" class="user-image img-circle elevation-2" alt="User Image">
						<span class="d-none d-md-inline"><?= $this->session->userdata('impsys_initial') ?></span>
					</a>
					<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<!-- User image -->
						<li class="user-header bg-primary">
							<img src="<?= base_url() ?>assets/imp-assets/user-default/male.png" class="img-circle elevation-2" alt="User Image">
							<p>
								<?= $this->session->userdata('impsys_initial') ?><br />
								<small><?= $this->session->userdata('impsys_nama') ?>-<?= $this->session->userdata('impsys_nik') ?></small>
								<small><?= $this->session->userdata('impsys_jabatan') ?></small>
							</p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<a href="<?= base_url('User/form_change_password') ?>" class="btn btn-warning btn-flat text-center"><i class="fas fa-key"></i> Password</a>
							<a href="<?= base_url('Auth/logout') ?>" class="btn btn-danger btn-flat float-right">Sign out</a>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="<?= base_url() ?>Dashboard" class="brand-link brand-link">
				<img src="<?php echo base_url(); ?>assets/imp-assets/apple-touch-icon.png" class="brand-image img-circle elevation-3" style="opacity: .8">
				<span class="brand-text font-weight-light"><small><strong><?= $this->config->item('company_name') ?></strong></small></span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- SidebarSearch Form -->
				<div class="form-inline mt-2">
					<div class="input-group" data-widget="sidebar-search">
						<input class="form-control form-control-sidebar" readonly type="search" placeholder="Search" aria-label="Search">
						<div class="input-group-append">
							<button class="btn btn-sidebar">
								<i class="fas fa-search fa-fw"></i>
							</button>
						</div>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-child-indent nav-collapse-hide-child fixed mb-5" data-widget="treeview" role="menu" data-accordion="false">
						<li class="nav-item">
							<a href="<?= base_url('Dashboard') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'Dashboard') echo 'active' ?>">
								<i class="nav-icon fas fa-chart-pie"></i>
								<p>
									Dashboard
									<span class="right badge badge-danger">Real-time</span>
								</p>
							</a>
						</li>
						<?php if ($this->session->userdata('impsys_jabatan') == 'ADMINISTRATOR OFFICE') : ?>
							<?php
							$groups_menu = $this->db->query("SELECT b.*
											FROM tmst_grop_menu AS b
											WHERE b.is_active = '1'
											GROUP BY b.sysid_group, b.label_group
											ORDER BY b.sort;
											");
							?>

							<?php if ($groups_menu->num_rows() > 0) : ?>
								<li class="nav-header font-weight-bold" style="color: white;">MAIN MENU</li>
							<?php endif; ?>
							<?php foreach ($groups_menu->result() as $group) : ?>
								<!-- BEGIN GROUP MENU -->
								<li class="nav-item">
									<a href="javascript:void(0)" class="nav-link" data-toggle="modal" data-target="#<?= $group->target . '_' . $group->sysid_group ?>">
										<i class="nav-icon <?= $group->class_icon ?>"></i>
										<p><?= $group->label_group ?></p>
									</a>
								</li>
								<div class="modal fade" style="z-index: 1050 !important;" id="<?= $group->target . '_' . $group->sysid_group ?>" aria-labelledby="<?= $group->target ?>Label" aria-hidden="true">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="Title_<?= $group->label_group ?>"><i class="<?= $group->class_icon ?>"></i> <?= $group->label_group ?> Menu</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="container-fluid">
													<div class="row">
														<?php $Menus = $this->db->order_by('sort', 'ASC')->get_where('tmst_parent_menu', ['is_active' => 1, 'pk_group' => $group->sysid_group]); ?>
														<?php foreach ($Menus->result() as $menu) : ?>
															<div class="col-lg-3 col-sm-6">
																<div class="card card-primary card-outline">
																	<?php if ($menu->having_child == 0) : ?>
																		<a href="<?= base_url($menu->controller . '/' . $menu->url_parent) ?>">
																			<div class="card-body text-center">
																				<i class="<?= $menu->class_icon ?> text-dark fa-2x"></i>
																				<p class="card-text text-dark text-center mt-2"><strong style="font-size: 12pt;"><?= $menu->label_parent ?></strong></p>
																			</div>
																		</a>
																	<?php else : ?>
																		<div class="card-body text-center">
																			<div class="btn-group">
																				<button type="button" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
																					<strong>List Menu</strong> <i class="<?= $menu->class_icon ?>"></i>
																				</button>
																				<?php $Childs = $this->db->order_by('sort')->get_where('tmst_child_menu', ['is_active' => 1, 'pk_parent' => $menu->sysid_parent]); ?>
																				<div class="dropdown-menu">
																					<?php foreach ($Childs->result() as $child) : ?>
																						<a class="dropdown-item text-dark" href="<?= base_url($child->controller . '/' . $child->url_child) ?>"><strong><i class="<?= $child->class_icon ?>"></i> <?= $child->label_child ?></strong></a>
																					<?php endforeach; ?>
																				</div>
																			</div>
																			<p class="card-text text-dark text-center mt-2"><strong style="font-size: 11pt;"><?= $menu->label_parent ?></strong></p>
																		</div>
																	<?php endif; ?>
																</div>
															</div>
														<?php endforeach; ?>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> &nbsp;&nbsp;&nbsp;Close</button>
											</div>
										</div>
									</div>
								</div>
								<!-- END GROUP MENU -->
							<?php endforeach; ?>

						<?php else :  ?>
							<?php
							$NIK = $this->session->userdata('impsys_nik');
							$groups_menu = $this->db->query(
								"SELECT b.*
										FROM ttrx_authority_access_menu AS a
										JOIN tmst_grop_menu AS b ON a.sysid_group = b.sysid_group
										WHERE b.is_active = '1'
										AND a.nik = '$NIK'
										GROUP BY b.sysid_group, b.label_group
										ORDER BY b.sort"
							);
							?>
							<?php if ($groups_menu->num_rows() > 0) : ?>
								<li class="nav-header font-weight-bold" style="color: white;">MAIN MENU</li>
							<?php endif; ?>
							<?php foreach ($groups_menu->result() as $group) : ?>
								<!-- BEGIN GROUP MENU -->
								<li class="nav-item">
									<a href="javascript:void(0)" class="nav-link" data-toggle="modal" data-target="#<?= $group->target . '_' . $group->sysid_group ?>">
										<i class="nav-icon <?= $group->class_icon ?>"></i>
										<p><?= $group->label_group ?></p>
									</a>
								</li>
								<div class="modal fade" style="z-index: 1050 !important;" id="<?= $group->target . '_' . $group->sysid_group ?>" aria-labelledby="<?= $group->target ?>Label" aria-hidden="true">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="Title_<?= $group->label_group ?>"><i class="<?= $group->class_icon ?>"></i> <?= $group->label_group ?> Menu</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="container-fluid">
													<div class="row">
														<?php $Menus = $this->db->query(
															"SELECT *
															FROM tmst_parent_menu
															WHERE is_active = 1
															AND pk_group = '$group->sysid_group'
															AND sysid_parent IN (SELECT DISTINCT sysid_parent FROM ttrx_authority_access_menu where nik = '$NIK')
															ORDER BY sort ASC"
														); ?>
														<?php foreach ($Menus->result() as $menu) : ?>
															<div class="col-lg-3 col-sm-3">
																<div class="card card-primary card-outline">
																	<?php if ($menu->having_child == 0) : ?>
																		<a href="<?= base_url($menu->controller . '/' . $menu->url_parent) ?>">
																			<div class="card-body text-center">
																				<i class="<?= $menu->class_icon ?> text-dark fa-2x"></i>
																				<p class="card-text text-dark text-center mt-2"><strong style="font-size: 12pt;"><?= $menu->label_parent ?></strong></p>
																			</div>
																		</a>
																	<?php else : ?>
																		<div class="card-body text-center">
																			<div class="btn-group">
																				<button type="button" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
																					<strong>List Menu</strong> <i class="<?= $menu->class_icon ?>"></i>
																				</button>
																				<?php $Childs = $this->db->query(
																					"SELECT * 
																					FROM tmst_child_menu
																					WHERE is_active = 1 AND pk_parent = $menu->sysid_parent
																					AND sysid_child IN (SELECT DISTINCT sysid_child FROM ttrx_authority_access_menu where nik = '$NIK' and sysid_parent = $menu->sysid_parent)
																					ORDER BY sort"
																				); ?>
																				<div class="dropdown-menu">
																					<?php foreach ($Childs->result() as $child) : ?>
																						<a class="dropdown-item text-dark" href="<?= base_url($child->controller . '/' . $child->url_child) ?>"><strong><i class="<?= $child->class_icon ?>"></i> <?= $child->label_child ?></strong></a>
																					<?php endforeach; ?>
																				</div>
																			</div>
																			<p class="card-text text-dark text-center mt-2"><strong style="font-size: 11pt;"><?= $menu->label_parent ?></strong></p>
																		</div>
																	<?php endif; ?>
																</div>
															</div>
														<?php endforeach; ?>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> &nbsp;&nbsp;&nbsp;Close</button>
											</div>
										</div>
									</div>
								</div>
								<!-- END GROUP MENU -->
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if ($this->session->userdata('impsys_jabatan') == 'ADMINISTRATOR OFFICE') : ?>
							<li class="nav-header font-weight-bold" style="color: white;">CONTROL ACCESS</li>
							<li class="nav-item">
								<a href="<?= base_url('Access'); ?>" class="nav-link <?php if ($this->uri->segment(1) == 'Access') echo 'active' ?>">
									<i class="nav-icon fas fa-unlock-alt"></i>
									<p>Manage Access</p>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid" id="append--page">

				</div>
			</section>

			<!-- Main content -->
			<div class="content">
				<?php $this->load->view($page_content) ?>
			</div>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<footer class="main-footer text-sm fixed">
			<div class="float-right d-none d-sm-block">
				<b>Version</b> 1.0.0
			</div>
			<strong>Copyright &copy; 2022 <a href="#">REKAP DATA INDONESIA</a>.</strong> All rights reserved.
		</footer>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>
		<!-- /.control-sidebar -->
	</div>
	<!-- ./wrapper -->

	<!-- jQuery -->
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<!-- <script>
$.widget.bridge('uibutton', $.ui.button)
</script> -->
	<!-- Bootstrap 4 -->
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url() ?>assets/AdminLTE-master/dist/js/adminlte.min.js"></script>
	<!-- ================================== ADDITIONAL PLUGIN ================================ -->
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/popper/popper.min.js"></script>
	<!-- DataTables  & Plugins -->
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/jszip/jszip.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/pdfmake/pdfmake.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-select/js/dataTables.select.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/pdfmake/vfs_fonts.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-buttons/js/buttons.print.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-rowgroup/js/rowGroup.bootstrap4.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/datatables-rowgroup/js/dataTable.rowSpanGroup.js"></script>
	<script src="<?= base_url() ?>assets/global-assets/DataTables/FixedColumns-4.0.1/js/dataTables.fixedColumns.min.js"></script>
	<!-- additional script -->
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/jquery-validation/jquery.validate.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/select2/js/select2.full.min.js"></script>
	<script src="<?= base_url() ?>assets/global-assets/sweet-alert/dist/sweetalert2.min.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/moment/moment.min.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/moment/locale/id.js"></script>
	<script src="<?= base_url() ?>assets/global-assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<script src="<?= base_url() ?>assets/global-assets/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
	<script src="<?= base_url() ?>assets/AdminLTE-master/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<script src="<?= base_url() ?>assets/global-assets/flatpickr/flatpickr.min.js"></script>
	<script src="<?= base_url() ?>assets/global-assets/accounting.js"></script>
	<script src="<?= base_url() ?>assets/global-assets/toastr/toastr.min.js"></script>
	<script>
		$(function() {
			$('[data-toggle="tooltip"]').tooltip()
		});

		flatpickr();

		function flatpickr() {
			// --- flat picker untuk project LPB jangan di ubah.
			$(".flatpickr-input").flatpickr({
				dateFormat: "Y-m-d",
				allowInput: true,
			});
			$(".flatpickr-time").flatpickr({
				dateFormat: "Y-m-d H:i",
				enableTime: true,
				time_24hr: true,
				allowInput: true,
			});

			$(".flatpickr").flatpickr({
				dateFormat: "d F Y"
			});
			$(".flatpickr").val(moment().format("DD MMMM YYYY")).removeAttr('readonly');
		}

		$('.select2').select2()

		window.roundToTwoDecimals = function(num) {
			return (Math.round(num * 100) / 100).toFixed(2);
		};

		window.roundToFourDecimals = function(num) {
			return parseFloat(num).toFixed(4);
		}

		window.roundToSixDecimals = function(num) {
			return parseFloat(num).toFixed(6);
		}

		window.formatIdr = function(num) {
			var rounded = (Math.round(num * 100) / 100).toFixed(2);
			return rounded.replace(/\d(?=(\d{3})+\.)/g, '$&,');
		};

		window.formatIdrAccounting = function(num) {
			var rounded = (Math.round(num * 100) / 100).toFixed(2);
			return rounded.replace(/\d(?=(\d{3})+\.)/g, '$&,');
		};

		function formatAritmatika(str) {
			return str ? str.replace(/,/g, '') : '0';
		}
	</script>
	<?php if ($this->session->flashdata('success')) { ?>
		<script>
			Swal.fire({
				icon: 'success',
				title: 'Success!',
				text: `<?php echo $this->session->flashdata('success'); ?>`,
				showConfirmButton: true
			});
		</script>
	<?php } else if ($this->session->flashdata('error')) { ?>
		<script>
			Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: `<?php echo $this->session->flashdata('error'); ?>`,
				showConfirmButton: true
			});
		</script>
	<?php } else if ($this->session->flashdata('warning')) { ?>
		<script>
			Swal.fire({
				icon: 'warning',
				title: 'Warning!',
				text: `<?php echo $this->session->flashdata('warning'); ?>`,
				showConfirmButton: true
			});
		</script>
	<?php } else if ($this->session->flashdata('info')) { ?>
		<script>
			Swal.fire({
				icon: 'info',
				title: 'Info!',
				text: `<?php echo $this->session->flashdata('info'); ?>`,
				showConfirmButton: true
			});
		</script>
	<?php } ?>
	<?= $script_page ?>
</body>

</html>