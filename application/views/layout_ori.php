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
    <script src="<?= base_url() ?>assets/AdminLTE-master/plugins/jquery/jquery.min.js"></script>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed text-sm sidebar-mini">
    <!-- layout-footer-fixed -->
    <!-- Site wrapper -->
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="<?php echo base_url(); ?>assets/imp-assets/apple-touch-icon.png" alt="IMPSYS-LOGO" height="60" width="60">
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
                            </div>
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
                                <small><?= $this->session->userdata('impsys_nama') ?></small>
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
                        <?php if ($this->session->userdata('impsys_jabatan') == 'DIRECTOR' or $this->session->userdata('impsys_jabatan') == 'ADMINISTRATOR OFFICE' or $this->session->userdata('impsys_jabatan') == 'PJOVEN') : ?>
                            <li class="nav-item <?= ($this->uri->segment(1) == 'TemperaturOven') ? 'menu-is-opening menu-open' : '' ?>">
                                <a href="#" class="nav-link <?php if ($this->uri->segment(1) == 'TemperaturOven') echo 'active' ?>">
                                    <i class="nav-icon fas fa-thermometer-three-quarters"></i>
                                    <p>Temperatur Oven <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php $qOven = $this->db->get_where('tmst_identity_oven', ['is_active' => 1])->result(); ?>

                                    <?php foreach ($qOven as $ovens) : ?>
                                        <li class="nav-item">
                                            <a href="<?= base_url('TemperaturOven/index/' . $ovens->sysid) ?>" class="nav-link <?php if ($this->uri->segment(3) == $ovens->sysid) echo 'active' ?>">
                                                <i class="fas fa-angle-right"></i>
                                                <i class="fas fa-thermometer"></i>
                                                <p><?= $ovens->nama ?></p>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                    <li class="nav-item">
                                        <a href="<?= base_url('TemperaturOven/Monitoring_history_temp_oven') ?>" class="nav-link <?php if ($this->uri->segment(2) == 'Monitoring_history_temp_oven') echo 'active' ?>">
                                            <i class="fas fa-angle-right"></i>
                                            <i class="fas fa-tv"></i>
                                            <p>Monitoring Data Temperatur</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->session->userdata('impsys_jabatan') == 'DIRECTOR' or $this->session->userdata('impsys_jabatan') == 'ADMINISTRATOR OFFICE') : ?>
                            <li class="nav-header font-weight-bold" style="color: white;">Main-Menu</li>
                            <li class="nav-item <?php if (
                                                    $this->uri->segment(1) == 'DatabaseLpb'
                                                    or $this->uri->segment(1) == 'DatabaseLot'
                                                    or $this->uri->segment(1) == 'LotAvail'
                                                    or $this->uri->segment(1) == 'MonitoringMaterialSupplier'
                                                ) echo 'menu-is-opening menu-open' ?>">
                                <a href="#" class="nav-link <?php if (
                                                                $this->uri->segment(1) == 'DatabaseLpb'
                                                                or $this->uri->segment(1) == 'DatabaseLot'
                                                                or $this->uri->segment(1) == 'LotAvail'
                                                                or $this->uri->segment(1) == 'MonitoringMaterialSupplier'
                                                            ) echo 'active' ?>">
                                    <i class="nav-icon fas fa-globe"></i>
                                    <p>
                                        Monitoring Material
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('DatabaseLpb') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'DatabaseLpb') echo 'active' ?>">
                                            <i class="fas fa-angle-right"></i>
                                            <i class="fas fa-archive"></i>
                                            <p>Monitoring L.P.B</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('DatabaseLot') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'DatabaseLot') echo 'active' ?>">
                                            <i class="fas fa-angle-right"></i>
                                            <i class="fas fa-align-left"></i>
                                            <p>Monitoring L O T</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('LotAvail') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'LotAvail') echo 'active' ?>">
                                            <i class="fas fa-angle-right"></i>
                                            <i class="fas fa-map-pin"></i>
                                            <p>LOT Available</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('MonitoringMaterialSupplier') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'MonitoringMaterialSupplier') echo 'active' ?>">
                                            <i class="fas fa-angle-right"></i>
                                            <i class="fas fa-truck"></i>
                                            <p>Rekap Material Supplier</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="<?= base_url('TemperaturOven') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'TemperaturOven') echo 'active' ?>">
                                    <i class="nav-icon fas fa-thermometer-three-quarters"></i>
                                    <p>Oven</p>
                                </a>
                            </li> -->
                            <?php if ($this->session->userdata('impsys_jabatan') == 'DIRECTOR' or $this->session->userdata('impsys_jabatan') == 'ADMINISTRATOR OFFICE' or $this->session->userdata('impsys_jabatan') == 'PJOVEN') : ?>
                                <li class="nav-item <?= ($this->uri->segment(1) == 'TemperaturOven') ? 'menu-is-opening menu-open' : '' ?>">
                                    <a href="#" class="nav-link <?php if ($this->uri->segment(1) == 'TemperaturOven') echo 'active' ?>">
                                        <i class="nav-icon fas fa-thermometer-three-quarters"></i>
                                        <p>Temperatur Oven <i class="right fas fa-angle-left"></i></p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <?php $qOven = $this->db->get_where('tmst_identity_oven', ['is_active' => 1])->result(); ?>

                                        <?php foreach ($qOven as $ovens) : ?>
                                            <li class="nav-item">
                                                <a href="<?= base_url('TemperaturOven/index/' . $ovens->sysid) ?>" class="nav-link <?php if ($this->uri->segment(3) == $ovens->sysid) echo 'active' ?>">
                                                    <i class="fas fa-angle-right"></i>
                                                    <i class="fas fa-thermometer"></i>
                                                    <p><?= $ovens->nama ?></p>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                        <li class="nav-item">
                                            <a href="<?= base_url('TemperaturOven/Monitoring_history_temp_oven') ?>" class="nav-link <?php if ($this->uri->segment(2) == 'Monitoring_history_temp_oven') echo 'active' ?>">
                                                <i class="fas fa-angle-right"></i>
                                                <i class="fas fa-tv"></i>
                                                <p>Monitoring Data Temperatur</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <li class="nav-header font-weight-bold" style="color: white;">Penerimaan Bahan Baku</li>
                            <li class="nav-item
                        <?php if (
                                $this->uri->segment(1) == 'ReceiveMaterial'
                                or $this->uri->segment(1) == 'CheckGrading'
                                or $this->uri->segment(1) == 'TodayGrading'
                            ) echo 'menu-is-opening menu-open' ?>">
                                <a href="#" class="nav-link 
                        <?php if (
                                $this->uri->segment(1) == 'ReceiveMaterial'
                                or $this->uri->segment(1) == 'CheckGrading'
                                or $this->uri->segment(1) == 'TodayGrading'
                            ) echo 'active' ?>">
                                    <i class="nav-icon fas fa-download"></i>
                                    <p>
                                        Penerimaan
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('ReceiveMaterial/index') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'ReceiveMaterial') echo 'active' ?>">
                                            <i class="fas fa-angle-right"></i>
                                            <i class="fas fa-clipboard"></i>
                                            <p>Generate L.P.B</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('CheckGrading/index') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'CheckGrading') echo 'active' ?>">
                                            <i class="fas fa-angle-right"></i>
                                            <i class="fas fa-dolly-flatbed"></i>
                                            <?php
                                            $buka = $this->db->get_where('ttrx_hdr_lpb_receive', ['status_lpb' => 'BUKA'])->num_rows();
                                            ?>
                                            <p>Grade <span class="left ml-2 badge badge-danger blink_me"><?= $buka ?> LPB</span></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('TodayGrading/index') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'TodayGrading') echo 'active' ?>">
                                            <i class="fas fa-angle-right"></i>
                                            <i class="far fa-calendar-alt"></i>
                                            <p>Today LPB</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('StockKayuBasah/index') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'StockKayuBasah') echo 'active' ?>">
                                    <i class="nav-icon fas fa-tree"></i>
                                    <p>
                                        Stok Kayu Basah
                                    </p>
                                </a>
                            </li>





                            <li class="nav-header font-weight-bold" style="color: white;">WIP KD</li>
                            <li class="nav-item <?= ($this->uri->segment(1) == 'BarcodeInOven' || $this->uri->segment(1) == 'HistoryOven' || $this->uri->segment(1) == 'BarcodeOutOven' || $this->uri->segment(1) == 'HistoryOutOven') ? 'menu-is-opening menu-open' : '' ?>">
                                <a href="#" class="nav-link <?php if ($this->uri->segment(1) == 'BarcodeInOven') echo 'active' ?>">
                                    <i class="nav-icon fas fa-thermometer-three-quarters"></i>
                                    <p>WIP KD <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('BarcodeInOven') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'BarcodeInOven') echo 'active' ?>">
                                            <i class="nav-icon fas fa-barcode"></i>
                                            <p> Masuk KD <i class="fas fa-download"></i></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('HistoryOven') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'HistoryOven') echo 'active' ?>">
                                            <i class="nav-icon fas fa-history"></i>
                                            <p> History Masuk KD</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('BarcodeOutOven') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'BarcodeOutOven') echo 'active' ?>">
                                            <i class="nav-icon fas fa-barcode"></i>
                                            <p> Keluar KD <i class="fas fa-external-link-alt"></i></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('HistoryOutOven') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'HistoryOutOven') echo 'active' ?>">
                                            <i class="nav-icon fas fa-share"></i>
                                            <p> History Keluar KD</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('StockInOven') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'StockInOven') echo 'active' ?>">
                                    <i class="nav-icon fas fa-cube"></i>
                                    <p>
                                        Stok In KD
                                    </p>
                                </a>
                            </li>






                            <li class="nav-header font-weight-bold" style="color: white;">Material Kering</li>
                            <li class="nav-item">
                                <a href="<?= base_url('StockMtrlKering') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'StockMtrlKering') echo 'active' ?>">
                                    <i class="nav-icon fas fa-cubes"></i>
                                    <p>
                                        Stok Kayu Kering
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('BarcodeAlloc') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'BarcodeAlloc') echo 'active' ?>">
                                    <i class="nav-icon fas fa-barcode"></i>
                                    <p>Alokasikan ke Produksi</p>
                                </a>
                            </li>

                            <li class="nav-header font-weight-bold" style="color: white;">Alokasi Material</li>
                            <li class="nav-item">
                                <a href="<?= base_url('TodayAllocPrd') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'TodayAllocPrd') echo 'active' ?>">
                                    <i class="nav-icon fas fa-pallet"></i>
                                    <p>Today Alloc Prd</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('AllocPrd') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'AllocPrd') echo 'active' ?>">
                                    <i class="nav-icon fas fa-archive"></i>
                                    <p>History Alloc prd</p>
                                </a>
                            </li>
                            <li class="nav-header font-weight-bold" style="color: white;">Tag Produk</li>
                            <li class="nav-item">
                                <a href="<?= base_url('PrintBarcodeProduct') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'PrintBarcodeProduct') echo 'active' ?>">
                                    <i class="nav-icon fas fa-barcode"></i>
                                    <p>Barcode Product</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('HistoryPrintBarcode') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'HistoryPrintBarcode') echo 'active' ?>">
                                    <i class="nav-icon fas fa-sticky-note"></i>
                                    <p>History Print Barcode</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('WasteBarcode') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'WasteBarcode') echo 'active' ?>">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                    <p>Waste Barcode</p>
                                </a>
                            </li>
                            <li class="nav-header font-weight-bold" style="color: white;">Finish Good</li>
                            <li class="nav-item">
                                <a href="<?= base_url('FinishGood') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'FinishGood') echo 'active' ?>">
                                    <i class="nav-icon fas fa-boxes"></i>
                                    <p>Stok Finish Good</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('SertifikasiProduct') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'SertifikasiProduct') echo 'active' ?>">
                                    <i class="nav-icon far fa-newspaper"></i>
                                    <p>Sertifikasi Product</p>
                                </a>
                            </li>
                            <li class="nav-header font-weight-bold" style="color: white;">Pengiriman</li>
                            <li class="nav-item">
                                <a href="<?= base_url('LoadingForm') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'LoadingForm') echo 'active' ?>">
                                    <i class="nav-icon fab fa-wpforms"></i>
                                    <p>Form Loading</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('Loading') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'Loading') echo 'active' ?>">
                                    <i class="nav-icon fas fa-truck-loading"></i>
                                    <p>On Loading</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('LoadingFinish') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'LoadingFinish') echo 'active' ?>">
                                    <i class="nav-icon fas fa-shipping-fast"></i>
                                    <p>Finish Loading</p>
                                </a>
                            </li>
                            <li class="nav-header font-weight-bold" style="color: white;">SO & DN</li>
                            <li class="nav-item">
                                <a href="<?= base_url('InputPO') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'InputPO') echo 'active' ?>">
                                    <i class="nav-icon far fa-file-powerpoint"></i>
                                    <p>Form Penerimaan PO</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('SalesOrder') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'SalesOrder') echo 'active' ?>">
                                    <i class="nav-icon fas fa-tasks"></i>
                                    <p>Database SO</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('SoOutstanding') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'SoOutstanding') echo 'active' ?>">
                                    <i class="nav-icon fas fa-shopping-cart"></i>
                                    <p>SO Outstanding</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('CreateDeliveryNote') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'CreateDeliveryNote') echo 'active' ?>">
                                    <i class="nav-icon fas fa-people-carry"></i>
                                    <p>Create Delivery Note</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('DnOutstanding') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'DnOutstanding') echo 'active' ?>">
                                    <i class="nav-icon fas fa-truck-moving"></i>
                                    <p>DN Outstanding</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('CompleteDN') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'CompleteDN') echo 'active' ?>">
                                    <i class="nav-icon fab fa-dochub"></i>
                                    <p>Complete Delivery Note</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('CancelDN') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'CancelDN') echo 'active' ?>">
                                    <i class="nav-icon fas fa-calendar-times"></i>
                                    <p>Cancel/Swap Item DN</p>
                                </a>
                            </li>
                            <!-- PO Outstanding -->
                            <li class="nav-header font-weight-bold" style="color: white;">Invoice</li>
                            <li class="nav-item">
                                <a href="<?= base_url('CreateInvoice') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'CreateInvoice') echo 'active' ?>">
                                    <i class="nav-icon fas fa-tag"></i>
                                    <p>Create Invoice</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('ApprovalInvoice') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'ApprovalInvoice') echo 'active' ?>">
                                    <i class="nav-icon fas fa-tags"></i>
                                    <p>Approval Invoice</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('CompleteInvoice') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'CompleteInvoice') echo 'active' ?>">
                                    <i class="nav-icon fas fa-flag-checkered"></i>
                                    <p>Complete Invoice</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('RejectedInvoice') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'RejectedInvoice') echo 'active' ?>">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                    <p>Rejected Invoice</p>
                                </a>
                            </li>
                            <li class="nav-header font-weight-bold" style="color: white;">Data Master</li>
                            <li class="nav-item">
                                <a href="<?= base_url('Master') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'Master') echo 'active' ?>">
                                    <i class="nav-icon fas fa-server"></i>
                                    <p>Master Data</p>
                                </a>
                            </li>
                            <li class="nav-header font-weight-bold" style="color: white;">Control Access</li>
                            <li class="nav-item">
                                <a href="<?= base_url('Access'); ?>" class="nav-link <?php if ($this->uri->segment(1) == 'Access') echo 'active' ?>">
                                    <i class="nav-icon fas fa-unlock-alt"></i>
                                    <p>Manage Access</p>
                                </a>
                            </li>
                        <?php else : ?>
                            <?php
                            $this->db->select('b.sysid_group, b.label_group');
                            $this->db->from('ttrx_authority_access_menu AS a');
                            $this->db->join('tmst_grop_menu AS b', 'a.sysid_group = b.sysid_group');
                            $this->db->where('b.is_active', '1');
                            $this->db->where('a.nik', $this->session->userdata('impsys_nik'));
                            $this->db->group_by(["b.sysid_group", " b.label_group"]);
                            $groups_menu = $this->db->get()->result();
                            ?>
                            <?php foreach ($groups_menu as $group) : ?>
                                <li class="nav-header font-weight-bold" style="color: white;"><?= $group->label_group ?></li>
                                <?php
                                $this->db->select('b.sysid_parent, b.label_parent, b.class_icon, b.having_child, b.url_parent, b.controller');
                                $this->db->from('ttrx_authority_access_menu AS a');
                                $this->db->join('tmst_parent_menu AS b', 'a.sysid_parent = b.sysid_parent');
                                $this->db->where('b.is_active', '1');
                                $this->db->where('a.sysid_group', $group->sysid_group);
                                $this->db->where('a.nik', $this->session->userdata('impsys_nik'));
                                $this->db->group_by(["b.sysid_parent", "b.label_parent", "b.class_icon", "b.having_child", "b.url_parent", "b.controller"]);
                                $parents_menu = $this->db->get()->result();
                                ?>
                                <?php foreach ($parents_menu as $parent) : ?>
                                    <?php if ($parent->having_child == '0') : ?>
                                        <li class="nav-item">
                                            <a href="<?= base_url() ?><?= $parent->url_parent ?>" class="nav-link <?php if ($this->uri->segment(1) == $parent->controller) echo 'active' ?>">
                                                <i class="nav-icon <?= $parent->class_icon ?>"></i>
                                                <p><?= $parent->label_parent ?></p>
                                            </a>
                                        </li>
                                    <?php else : ?>
                                        <?php
                                        $this->db->select('b.sysid_child, b.label_child, b.class_icon, b.url_child, b.controller');
                                        $this->db->from('ttrx_authority_access_menu AS a');
                                        $this->db->join('tmst_child_menu AS b', 'a.sysid_child = b.sysid_child');
                                        $this->db->where('b.is_active', '1');
                                        $this->db->where('a.sysid_parent', $parent->sysid_parent);
                                        $this->db->where('a.nik', $this->session->userdata('impsys_nik'));
                                        $this->db->group_by(["b.sysid_child", "b.label_child", "b.class_icon", "b.url_child", "b.controller"]);
                                        $childs_menu = $this->db->get()->result_array();
                                        $controllers = array_column($childs_menu, 'controller');
                                        ?>
                                        <li class="nav-item <?php if (in_array($this->uri->segment(1), $controllers, true)) echo 'menu-is-opening menu-open' ?>">
                                            <a href="<?= base_url() ?><?= $parent->url_parent ?>" class="nav-link <?php if (in_array($this->uri->segment(1), $controllers, true)) echo 'active' ?>">
                                                <i class="nav-icon <?= $parent->class_icon ?>"></i>
                                                <p>
                                                    <?= $parent->label_parent ?>
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <?php foreach ($childs_menu as $child) : ?>
                                                    <li class="nav-item">
                                                        <a href="<?= base_url() ?><?= $child['url_child'] ?>" class="nav-link <?php if ($this->uri->segment(1) == $child['controller']) echo 'active' ?>">
                                                            <i class="fas fa-angle-right"></i>
                                                            <i class="fas <?= $child['class_icon'] ?>"></i>
                                                            <p><?= $child['label_child'] ?></p>
                                                        </a>
                                                    </li>
                                                <?php endforeach ?>
                                            </ul>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            <?php endforeach; ?>
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
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } else if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } else if ($this->session->flashdata('warning')) { ?>
                    <div class="alert alert-warning">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Warning!</strong> <?php echo $this->session->flashdata('warning'); ?>
                    </div>
                <?php } else if ($this->session->flashdata('info')) { ?>
                    <div class="alert alert-info">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Info!</strong> <?php echo $this->session->flashdata('info'); ?>
                    </div>
                <?php } ?>
                <?php $this->load->view($page_content) ?>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer text-sm fixed">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2022 <a href="#">IMPSYS</a>.</strong> All rights reserved.
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
    <?php if ($this->uri->segment(2) == 'check_detail_lpb' or $this->uri->segment(2) == 'preview_detail_lpb' or $this->uri->segment(2) == 'loading_product') : ?>
        <script src="<?= base_url() ?>assets/global-assets/x-editable/dist/bootstrap4-editable/js/bootstrap-editable.min.js"></script>
    <?php endif; ?>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?= base_url() ?>assets/AdminLTE-master/plugins/moment/moment.min.js"></script>
    <script src="<?= base_url() ?>assets/AdminLTE-master/plugins/moment/locale/id.js"></script>
    <script src="<?= base_url() ?>assets/global-assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/global-assets/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="<?= base_url() ?>assets/AdminLTE-master/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <?= $script_page ?>
</body>

</html>