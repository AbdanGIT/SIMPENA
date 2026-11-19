<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SIMSS | Log in</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/iCheck/square/blue.css') ?>">

  <style>
    body.login-page {
      background-image: url('<?= base_url('assets/images/Background_rsss.png') ?>') !important;
      background-size: cover !important;
      background-position: center !important;
      background-repeat: no-repeat !important;
      background-attachment: fixed !important;
    }

    /* Tampilan HP: kotak login tidak melebihi lebar layar */
    @media (max-width: 480px) {
      .login-box {
        width: auto !important;
        margin: 15px !important;
      }

      .login-box-body {
        padding: 18px !important;
      }

      .login-box-msg img {
        max-width: 100% !important;
        height: auto !important;
      }
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div>
      <?php if (session()->getFlashdata('msg')): ?>
        <?= session()->getFlashdata('msg') ?>
      <?php endif; ?>
    </div>

    <div class="login-box-body">
      <p class="login-box-msg">
        <img src="<?= base_url('assets/images/RSUD Sultan Suriansyah Logo.png') ?>" alt="Logo RSUD"
             style="width: 320px; max-width: 100%; height: auto;">
      </p>
      <hr />

      <form action="<?= base_url('login/auth') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group has-feedback">
          <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="username">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
          <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck"></div>
            <label>
              <input type="checkbox" name="remember"> Remember Me
            </label>
          </div>
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
        </div>

        <div class="row" style="margin-top: 10px;">
          <div class="col-xs-12">
             <a href="<?= base_url() ?>" class="btn btn-default btn-block btn-flat">Kembali</a>
          </div>
        </div>
      </form>

      <hr />
      <center>
        Copyright <?= date('Y') ?> by nadifa nabila <br /> All Right Reserved
      </center>
    </div>
  </div>

  <script src="<?= base_url('assets/plugins/jQuery/jquery-2.2.3.min.js') ?>"></script>
  <script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/iCheck/icheck.min.js') ?>"></script>
  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
      });
    });
  </script>
</body>
</html>