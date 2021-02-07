<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SIPDAK | LOGIN</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/toastr/toastr.min.css'); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css'); ?>">

    <!-- custom css -->
    <link href="<?= base_url('assets/custom/style.min.css'); ?>" rel="stylesheet">

</head>

<body class="hold-transition login-page" style="background-image: url('./assets/img/bg_body.jpg'); background-size: cover; background-repeat: no-repeat; font-family: 'Poppins';">
    <div class=" login-box">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body d-flex">
                <div class="logo__img" style="background-image: url('./assets/img/bg_login.png'); background-size: cover; background-repeat: no-repeat;"></div>
                <div class="box__login">
                    <p class="login-box-msg">Sign in</p>

                    <form id="formLogin" action="<?= site_url('auth/login'); ?>" method="post">
                        <!-- <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" style="display: none"> -->
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="username" placeholder="Username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row btn-login">
                            <div class="col-8"></div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- Toastr -->
    <script src="<?= base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('assets/dist/js/adminlte.min.js'); ?>"></script>
    <!-- Axios -->
    <script src="<?= base_url('assets/dist/js/axio.min.js'); ?>"></script>
    <!-- Js user -->
    <script src="<?= base_url('assets/custom/auth.min.js'); ?>"></script>

</body>

</html>