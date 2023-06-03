  <!-- Morris chart -->

  <!-- jvectormap -->
  <!-- <link rel="stylesheet" href="<?= base_url() ?>/assets/bower_components/jvectormap/jquery-jvectormap.css"> -->
  <!-- Date Picker -->
  <!-- <link rel="stylesheet" href="<?= base_url() ?>/assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"> -->

  <!-- bootstrap wysihtml5 - text editor -->
  <!-- <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"> -->
  <!-- Morris.js charts -->
  <!-- <link rel="stylesheet" href="<?= base_url() ?>/assets/bower_components/morris.js/morris.css">
<script src="<?= base_url() ?>/assets/bower_components/raphael/raphael.min.js"></script>
<script src="<?= base_url() ?>/assets/bower_components/morris.js/morris.min.js"></script> -->
  <!-- Sparkline -->
  <!-- <script src="<?= base_url() ?>/assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script> -->
  <!-- jvectormap -->
  <!-- <script src="<?= base_url() ?>/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script> -->
  <!-- <script src="<?= base_url() ?>/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->
  <!-- jQuery Knob Chart -->
  <!-- <script src="<?= base_url() ?>/assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script> -->
  <script src="<?= base_url() ?>/assets/bower_components/moment/min/moment.min.js"></script>
  !-- ChartJS -->
  <script src="<?= base_url() ?>/assets/bower_components/chart.js/Chart.js"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <!-- <script src="<?= base_url() ?>/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script> -->
  <!-- Daterange picker -->
  <!-- <link rel="stylesheet" href="<?= base_url() ?>/assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<script src="<?= base_url() ?>/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script> -->
  <!-- datepicker -->
  <!-- <script src="<?= base_url() ?>/assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> -->

  <!-- <script src="<?= base_url() ?>/assets/dist/js/pages/dashboard.js"></script> -->
  
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=SISTEM_LOGOS?>
        <small>Hospital Electronic Application Of Pharmacy</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
          <div class="col-lg-12">
              <div class="box box-success">
                  <div class="box-header with-border">
                      <h3 class="box-title" style="font-weight:bold !important;">Dashboard By Unit</h3>
                      <div class="box-tools pull-right">
                        <?= form_dropdown("unit_id_depo", $unit, '', 'class="form-control select2" id="unit_id_depo"') ?>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div id="pageDashboard">
      </div>
    </section>
  </div>
  <script>
  $(document).ready(()=>{
    $("#unit_id_depo").trigger("change");
  });
  $("#unit_id_depo").on("change",function(){
    loadDashbord($(this).val());
  });
  function loadDashbord(unit) {
    $("#pageDashboard").load("dashboard/view_dashboard/"+unit);
  }
  </script>