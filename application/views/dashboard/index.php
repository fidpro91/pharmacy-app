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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
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
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $obat_akan_exp->total; ?></h3>

              <p>obat yang akan expired</p>
            </div>
            <div class="icon">
              <i class="fa fa-medkit"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $obat_exp->total; ?></h3>

              <p>obat expired</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $obat_akan_habis->total ?></h3>

              <p>Stock yang akan habis</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $obat_habis->total; ?></h3>

              <p>stock habis</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <div class="row">
        <div class="col-md-6">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Browser Usage</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="chart-responsive">
                      <script src="https://code.highcharts.com/highcharts.js"></script>
                      <script src="https://code.highcharts.com/modules/exporting.js"></script>
                      <script src="https://code.highcharts.com/modules/export-data.js"></script>
                      <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                      <figure class="highcharts-figure">
                        <div id="container"></div>
                        <!-- <p class="highcharts-description">
                        Pie charts are very popular for showing a compact overview of a
                        composition or comparison. While they can be harder to read than
                        column charts, they remain a popular choice for small datasets.
                      </p> -->
                      </figure>
                      <style>
                        .highcharts-figure,
                        .highcharts-data-table table {
                          min-width: 320px;
                          max-width: 800px;
                          margin: 1em auto;
                        }

                        .highcharts-data-table table {
                          font-family: Verdana, sans-serif;
                          border-collapse: collapse;
                          border: 1px solid #ebebeb;
                          margin: 10px auto;
                          text-align: center;
                          width: 100%;
                          max-width: 500px;
                        }

                        .highcharts-data-table caption {
                          padding: 1em 0;
                          font-size: 1.2em;
                          color: #555;
                        }

                        .highcharts-data-table th {
                          font-weight: 600;
                          padding: 0.5em;
                        }

                        .highcharts-data-table td,
                        .highcharts-data-table th,
                        .highcharts-data-table caption {
                          padding: 0.5em;
                        }

                        .highcharts-data-table thead tr,
                        .highcharts-data-table tr:nth-child(even) {
                          background: #f8f8f8;
                        }

                        .highcharts-data-table tr:hover {
                          background: #f1f7ff;
                        }

                        input[type="number"] {
                          min-width: 50px;
                        }
                      </style>
                      <script>
                        Highcharts.chart('container', {
                          chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                          },
                          title: {
                            text: 'penjualan obat, 2018'
                          },
                          tooltip: {
                            // pointFormat: '{series.name}: <b>{point.percentage:.1f}</b>'
                          },
                          // accessibility: {
                          //   // point: {
                          //   //   valueSuffix: ''
                          //   // }
                          // },
                          plotOptions: {
                            pie: {
                              allowPointSelect: true,
                              cursor: 'pointer',
                              dataLabels: {
                                enabled: true,
                                formatter: function () {
                                  return this.key+ ':' + formatNumeric(this.y);
                                }/* ,
                                format: '<b>{point.name}</b>: {point.y}' */
                              }
                            }
                          },
                          series: [{
                            name: 'Total',
                            colorByPoint: true,
                            data: [
                              <?php foreach ($tot_perjualan_unit as $value) { ?> {
                                  name: "<?=$value->unit_name?>",
                                  y: <?=$value->jumlah?>,
                                  sliced: true,
                                  selected: true
                                },
                               <?php } ?> 
                             
                            ]
                          }]
                        });
                      </script>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"> 5 penjulan Obat Terbayak per unit 2018</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <?php foreach ($tot_penjualan_terbayak_unit_item as $value) { ?>
                      <div class="info-box bg-yellow">
                        <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text"><?php echo $value->unit_name ?></span>
                          <span class="info-box-text"><?php echo $value->item_name ?></span>
                          <span class="info-box-number"><?php echo $value->jumlah ?></span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>

            </div>
          </div>



          <!-- /.info-box -->

        </div>

    </section>
  </div>