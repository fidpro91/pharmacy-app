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
              <h3>150</h3>

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
              <h3>200</h3>

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
              <h3>44</h3>

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
              <h3>65</h3>

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
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                          },
                          accessibility: {
                            point: {
                              valueSuffix: '%'
                            }
                          },
                          plotOptions: {
                            pie: {
                              allowPointSelect: true,
                              cursor: 'pointer',
                              dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                              }
                            }
                          },
                          series: [{
                            name: 'Brands',
                            colorByPoint: true,
                            data: [{
                              name: 'Chrome',
                              y: 61.41,
                              sliced: true,
                              selected: true
                            }, {
                              name: 'Internet Explorer',
                              y: 11.84
                            }, {
                              name: 'Firefox',
                              y: 10.85
                            }, {
                              name: 'Edge',
                              y: 4.67
                            }, {
                              name: 'Safari',
                              y: 4.18
                            }, {
                              name: 'Sogou Explorer',
                              y: 1.64
                            }, {
                              name: 'Opera',
                              y: 1.6
                            }, {
                              name: 'QQ',
                              y: 1.2
                            }, {
                              name: 'Other',
                              y: 2.61
                            }]
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
          <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">retur penjualan</span>
              <span class="info-box-number">5,200</span>

              <div class="progress">
                <div class="progress-bar" style="width: 50%"></div>
              </div>
              <span class="progress-description">
                50% Increase in 30 Days
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Mentions</span>
              <span class="info-box-number">92,050</span>

              <div class="progress">
                <div class="progress-bar" style="width: 20%"></div>
              </div>
              <span class="progress-description">
                20% Increase in 30 Days
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Downloads</span>
              <span class="info-box-number">114,381</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
              <span class="progress-description">
                70% Increase in 30 Days
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Direct Messages</span>
              <span class="info-box-number">163,921</span>

              <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
              </div>
              <span class="progress-description">
                40% Increase in 30 Days
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
        </div>

    </section>
  </div>
  <script type="text/javascript">
    // The Calender
    $('#calendar').datepicker("setDate", '1d');
    /* Morris.js Charts */
    // Sales chart
    /* var area = new Morris.Area({
      element   : 'revenue-chart',
      resize    : true,
      data      : [
        { y: '2011 Q1', item1: 2666, item2: 2666 },
        { y: '2011 Q2', item1: 2778, item2: 2294 },
        { y: '2011 Q3', item1: 4912, item2: 1969 },
        { y: '2011 Q4', item1: 3767, item2: 3597 },
        { y: '2012 Q1', item1: 6810, item2: 1914 },
        { y: '2012 Q2', item1: 5670, item2: 4293 },
        { y: '2012 Q3', item1: 4820, item2: 3795 },
        { y: '2012 Q4', item1: 15073, item2: 5967 },
        { y: '2013 Q1', item1: 10687, item2: 4460 },
        { y: '2013 Q2', item1: 8432, item2: 5713 }
      ],
      xkey      : 'y',
      ykeys     : ['item1', 'item2'],
      labels    : ['Item 1', 'Item 2'],
      lineColors: ['#a0d0e0', '#3c8dbc'],
      hideHover : 'auto'
    }); */
    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var salesChart = new Chart(salesChartCanvas);

    var salesChartData = {

      labels: ['January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July'
      ],
      datasets: [{
          label: 'Electronics',
          fillColor: 'rgb(210, 214, 222)',
          strokeColor: 'rgb(210, 214, 222)',
          pointColor: 'rgb(210, 214, 222)',
          pointStrokeColor: '#c1c7d1',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgb(220,220,220)',
          data: [65, 59, 80, 81, 56, 55, 40]
        }

        ,
        {
          label: 'Digital Goods',
          fillColor: 'rgba(60,141,188,0.9)',
          strokeColor: 'rgba(60,141,188,0.8)',
          pointColor: '#3b8bba',
          pointStrokeColor: 'rgba(60,141,188,1)',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: [28, 48, 40, 19, 86, 27, 90]
        }

      ]
    }

    ;

    var salesChartOptions = {
      // Boolean - If we should show the scale at all
      showScale: true,
      // Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: false,
      // String - Colour of the grid lines
      scaleGridLineColor: 'rgba(0,0,0,.05)',
      // Number - Width of the grid lines
      scaleGridLineWidth: 1,
      // Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      // Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      // Boolean - Whether the line is curved between points
      bezierCurve: true,
      // Number - Tension of the bezier curve between points
      bezierCurveTension: 0.3,
      // Boolean - Whether to show a dot for each point
      pointDot: false,
      // Number - Radius of each point dot in pixels
      pointDotRadius: 4,
      // Number - Pixel width of point dot stroke
      pointDotStrokeWidth: 1,
      // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius: 20,
      // Boolean - Whether to show a stroke for datasets
      datasetStroke: true,
      // Number - Pixel width of dataset stroke
      datasetStrokeWidth: 2,
      // Boolean - Whether to fill the dataset with a color
      datasetFill: true,
      // String - A legend template
      legendTemplate: '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      // Boolean - whether to make the chart responsive to window resizing
      responsive: true
    }

    ;

    // Create the line chart
    salesChart.Line(salesChartData, salesChartOptions);
  </script>