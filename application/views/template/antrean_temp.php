<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= SISTEM_NAME ?> | ANTREAN RESEP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= site_url("assets") ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= site_url("assets") ?>/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= site_url("assets") ?>/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= site_url("assets") ?>/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= site_url("assets") ?>/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">

    <header class="main-header">
      <nav class="navbar navbar-static-top">
        <div class="container">
          <div class="navbar-header">
            <a href="<?= site_url("assets") ?>/index2.html" class="navbar-brand"><b>HEAPY</b>RS ANTREAN RESEP</a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
              <i class="fa fa-bars"></i>
            </button>
          </div>

          <!-- /.navbar-collapse -->
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account Menu -->
              <li>
                <?= form_dropdown("unit_id_depo", $unit, '', 'class="form-control select2" id="unit_id_depo"') ?>
              </li>
            </ul>
          </div>
          <!-- /.navbar-custom-menu -->
        </div>
        <!-- /.container-fluid -->
      </nav>
    </header>
    <!-- Full Width Column -->
    <div class="content-wrapper">
      <div class="container">

        <!-- Main content -->
        <style>
          h1 {
            text-align: center !important;
            font-weight: bold;
            font-size: 12vh;
            padding: 0;
            margin: 0;
          }
        </style>
        <section class="content">
          <div class="row">
            <div class="col-md-5">
              <div class="box box-primary box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">NOMOR RESEP RACIKAN</h3>
                </div>
                <div class="box-body" style="min-height:8em;">
                  <h1 id="rcp_racikan">0</h1>
                </div>
                <!-- /.box-body -->
              </div>
              <div class="box box-primary box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">NOMOR RESEP NON RACIKAN</h3>
                </div>
                <div class="box-body" style="min-height:8em;">
                  <h1 id="rcp_non_racikan">0</h1>
                </div>
                <!-- /.box-body -->
              </div>
            </div>
            <div class="col-md-7">
              <div class="box box-primary box-solid">
                <div class="box-body" style="text-align: center !important; min-height:10em;">
                    <img src="<?=base_url("assets")?>/images/logors.png" style="width: 50%;" alt="IMG">
                </div>
                <!-- /.box-body -->
              </div>
            </div>
          </div>
          <div class="row" id="data_antrian">
          </div>
          <!-- /.box -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.container -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="container">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.1.0
        </div>
        <strong><?= FOOT_NOTE ?>
          <!-- /.container -->
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="<?= site_url("assets") ?>/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="<?= site_url("assets") ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- SlimScroll -->
  <script src="<?= site_url("assets") ?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

  <script src="<?= base_url() . "assets/" ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="<?= base_url() . "assets/" ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

  <!-- FastClick -->
  <script src="<?= site_url("assets") ?>/bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= site_url("assets") ?>/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?= site_url("assets") ?>/dist/js/demo.js"></script>
  <script>
    $(document).ready(() => {
      $.fn.dataTable.ext.errMode = 'none';
      get_antrean();

      setInterval(() => {
        get_antrean();
      }, 6000);
    });

    $("#unit_id_depo").change(() => {
      get_antrean();
    });

    function get_antrean() {
      $.get("antrean_recipe/get_data/" + $("#unit_id_depo").val(), function(resp) {
        $("#data_antrian").html(resp.html);
        $("#rcp_racikan").text(resp.noResepRacikan);
        $("#rcp_non_racikan").text(resp.noResepNonRacikan);
      }, 'json');
    }
  </script>
</body>

</html>