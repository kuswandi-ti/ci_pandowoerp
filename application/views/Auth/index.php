<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->config->item('app_name') ?> | <?= $page_title ?></title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/imp-assets/apple-touch-icon.png" />

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/AdminLTE-master/dist/css/adminlte.min.css">
    <!-- additional plugins -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/sweet-alert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/global-assets/font-awesome/css/fontawesome-all.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="" class="h1"><b><?= $this->config->item('company_initial') ?>-</b><i>SYS</i></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg"><b>PT. <?= $this->config->item('company_name') ?> System</b> Login Page</p>
                <form id="form-login" method="post" action="<?= base_url('Auth/post_login') ?>">
                    <div class="input-group mb-3">
                        <input type="text" name="username" id="username" autofocus required class="form-control form-control-sm" placeholder="Username...">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" required name="password" id="password" class="form-control form-control-sm" placeholder="Password...">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-7">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-5">
                            <button type="submit" id="btn--login" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt mr-1"></i> Sign-In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <div class="social-auth-links text-center mt-2">
                    <hr />
                    <label class="col-12 text-center bg-primary"> Rekomendasi Browser : Google Chrome <i class="fab fa-chrome"></i></label>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url() ?>assets/AdminLTE-master/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>assets/AdminLTE-master/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url() ?>assets/AdminLTE-master/dist/js/adminlte.min.js"></script>
    <!-- additional plugins -->
    <script src="<?= base_url() ?>assets/AdminLTE-master/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="<?= base_url() ?>assets/global-assets/sweet-alert/dist/sweetalert2.min.js"></script>
    <script src="<?= base_url() ?>assets/login-script/index.js"></script>
    <?php if ($this->session->flashdata('error')) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Harap login terlebih dahulu',
                footer: '<a href="javascript:void(0)">Notifikasi System</a>'
            });
        </script>
        <?php session_destroy() ?>
    <?php endif; ?>

</body>

</html>