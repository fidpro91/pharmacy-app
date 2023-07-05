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
	<script src="https://code.responsivevoice.org/responsivevoice.js?key=rbKNLiYL"></script>

	<style>
		/*.tb_antrian td {*/
		/*	overflow: auto; !* this is what fixes the expansion *!*/
		/*	text-overflow: ellipsis; !* not supported in all browsers, but I accepted the tradeoff *!*/
		/*	white-space: nowrap;*/
		/*}*/
		.table-responsive {
			height: 400px;
			width: 100%;
			overflow-y: auto;
			/*border:2px solid #444;*/
		}

		.dataTables_scrollBody {
			overflow-y: auto;
		}
	</style>
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
					<!-- <div class="navbar-custom-menu">
						<ul class="nav navbar-nav"> -->
					<!-- User Account Menu -->
					<!-- <li>
								<?= form_dropdown("unit_id_depo", $unit, '', 'class="form-control select2" id="unit_id_depo"') ?>
							</li>
						</ul>
					</div> -->
					<!-- /.navbar-custom-menu -->
				</div>
				<!-- /.container-fluid -->
			</nav>
		</header>
		<!-- Full Width Column -->
		<div class="content-wrapper">
			<div class="">

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
						<div class="col-md-4">
							<div class="box box-primary box-solid" style="height: 420px">
								<div class="box-body" style="text-align: center !important; min-height:8em !important;">
									<iframe width="100%" height="400" src="https://www.youtube.com/embed/ldZnaV0SRSU?playlist=ldZnaV0SRSU&loop=1&autoplay=1&mute=1">
									</iframe>
									<!-- <img src="<?= base_url("assets") ?>/images/logors.png" style="width: 50%;" alt="IMG"> -->
								</div>
								<!-- /.box-body -->
							</div>
						</div>

						<div class="col-md-8">
							<div class="box box-primary box-solid" style="height: 420px">
								<div class="box-body" style="text-align: center !important; min-height:8em !important;">
									<div class="box-body" style="font-size: 20px">
										<div class="table-responsive">
											<?= create_table("tb_antrian", "M_antrean_recipe", ["class" => "table table-bordered table-striped tb_antrian", "style" => "width:100% !important;"]) ?>
										</div>
									</div>
								</div>
								<!-- /.box-body -->
							</div>
						</div>

						<div class="col-md-12">
							<div class="box box-success box-solid">
								<div class="box-header with-border">
									<div class="row">
										<div class="col-md-10">
											<h3 class="box-title" style="text-align: center">Panggilan Antrian</h3>
										</div>
										<div class="col-md-2">

											<div class="navbar-custom-menu">
												<ul class="nav navbar-nav">
													<!-- User Account Menu -->
													<li>
														<?= form_dropdown("unit_id_depo", $unit, '', 'class="form-control select2" id="unit_id_depo"') ?>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="box box-success">
												<div class="box-header with-border">
													<h3 class="box-title">NOMOR RESEP RACIKAN</h3>
												</div>
												<div class="box-body" style="min-height:8em; text-align: center">
													<h1 id="rcp_racikan_ready" style=" font-weight: bold;">0</h1>
													<h2 id="unit_name">-</h2>
												</div>
												<!-- /.box-body -->
											</div>
										</div>
										<div class="col-md-6">
											<div class="box box-success ">
												<div class="box-header with-border">
													<h3 class="box-title">NOMOR RESEP NON RACIKAN</h3>
												</div>
												<div class="box-body" style="min-height:8em; text-align: center">
													<h1 id="rcp_non_racikan_ready" style=" font-weight: bold;">0</h1>
													<h2 id="unti_name_racika">-</h2>
												</div>
												<!-- /.box-body -->
											</div>
										</div>
										<!-- <div class="col-md-3">
											<div class="box box-success">
												<div class="box-header with-border">
													<h3 class="box-title">NOMOR RESEP RACIKAN PREPARE</h3>
												</div>
												<div class="box-body" style="min-height:8em;">
													<h1 id="rcp_racikan_prepare">0</h1>
												</div>

											</div>
										</div>
										<div class="col-md-3">
											<div class="box box-success ">
												<div class="box-header with-border">
													<h3 class="box-title">NOMOR RESEP NON RACIKAN</h3>
												</div>
												<div class="box-body" style="min-height:8em;">
													<h1 id="rcp_non_racikan_prepare">0</h1>
												</div>

											</div>
										</div> -->
									</div>


								</div>
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

	<audio id="tingtung" src="<?= site_url("assets") ?>/audio/tingtung.mp3"></audio>

	<script>

		var table;
		var speak = false;

		$(document).ready(function() {
			table = $('.tb_antrian').DataTable({
				'scroller': false,
				'scrollY': '300px',
				'scrollCollapse': false,
				'paging': false,
				'searching': false,
				'info': true,

				"processing": true,
				"serverSide": true,
				"order": [
					[2, 'asc']
				],
				"scrollX": true,
				"ajax": {
					"url": "<?php echo site_url('antrean_recipe/get_data') ?>",
					"type": "POST",
					"data": function(f) {
						f.unit_id = $("#unit_id_depo").val();
					}
				},
				'columnDefs': [{
						'targets': [0, 1, -1],
						'searchable': false,
						'orderable': false,
					},
					{
						"visible": false,
						"targets": [0, 5]
					},
					{
						'targets': 0,
						'className': 'dt-body-center',
						'render': function(data, type, full, meta) {
							return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
						}
					}
				],
			});
			$("#unit_id_depo").change(() => {
				table.draw();
				setInterval(() => {
					table.draw();
				}, 50000);

				ambil_antrean();

			})

			var $el = $(".dataTables_scrollBody");

			function anim() {
				var st = $el.scrollTop();
				var sb = $el.prop("scrollHeight") - $el.innerHeight();
				console.log('st' + st);
				console.log('sb' + sb);
				$el.animate({
					scrollTop: st < sb / 2 ? sb : 0
				}, 500000, anim);
			}

			function stop() {
				$el.stop();
			}
			anim();
			$el.hover(stop, anim);
		})

		var loadAntrean;

		function ambil_antrean() {
			clearInterval(loadAntrean);
			loadAntrean = setInterval(() => {
				racikanredy();
				nonracikanredy();
			}, 10000)
		}

		// rcp_racikan_ready

		function racikanredy() {

			$.ajax({
				type: "post",
				url: "<?= base_url() ?>antrean_recipe/noracikanredy",
				dataType: "json",
				data: {
					unit_id: $("#unit_id_depo").val()
				},
				success: function(response) {
					console.log(response.noResepRacikanReady);
					if (response.noResepRacikanReady) {
						$("#rcp_racikan_ready").text(response.noResepRacikanReady.patient_name)
						if (response.noResepRacikanReady.unit_name != ''){
							$("#unit_name").text(response.noResepRacikanReady.unit_name)
						}else{
							$("#unit_name").text('-')
						}

						if (response.noResepRacikanReady.status == 1) {

							var bell = document.getElementById('tingtung');
							bell.pause();
							bell.currentTime = 0;
							bell.play();
							durasi_bell = bell.duration * 770;
							setTimeout(function() {
								responsiveVoice.speak(
									response.noResepRacikanReady.patient_name + ". DARI " + response.noResepRacikanReady.unit_name + " silahkan menuju ke "+response.noResepRacikanReady.depo, "Indonesian Female", {
										pitch: 1,
										rate: 1,
										volume: 2,
										// onstart: voiceStartCallback,
										// onend: voiceEndCallback(no_urut, counter, 7100, queue_id)
									}
								);
							},durasi_bell)
							update_status(response.noResepRacikanReady.sale_id);
						}
					} else {
						$("#rcp_racikan_ready").text(0)
						$("#unit_name").text("-")
					}
				}
			})
		}

		function update_status(sale_id) {
			$.ajax({
				type:"post",
				url:"<?= base_url() ?>antrean_recipe/update_antrian",
				datatype:"json",
				data:{sale_id},
				success:function (param) {
					console.log(param)
				}
			})
		}

		function nonracikanredy() {
			$.ajax({
				type: "post",
				url: "<?= base_url() ?>antrean_recipe/noResepNonRacikanReady",
				dataType: "json",
				data: {
					unit_id: $("#unit_id_depo").val()
				},
				success: function(response) {
					console.log(response.noResepNonRacikanReady);
					if (response.noResepNonRacikanReady) {
						$("#rcp_non_racikan_ready").text(response.noResepNonRacikanReady.patient_name)
						$("#unti_name_racika").text(response.noResepNonRacikanReady.unit_name)
						if (response.noResepNonRacikanReady.status == 1) {
							var bell = document.getElementById('tingtung');
							bell.pause();
							bell.currentTime = 0;
							bell.play();
							durasi_bell = bell.duration * 770;
							setTimeout(function() {
								responsiveVoice.speak(
									response.noResepNonRacikanReady.patient_name + ". DARI " + response.noResepNonRacikanReady.unit_name + " silahkan menuju ke "+response.noResepNonRacikanReady.depo, "Indonesian Female", {
										pitch: 1,
										rate: 1,
										volume: 2,
										// onstart: voiceStartCallback,
										// onend: voiceEndCallback(no_urut, counter, 7100, queue_id)
									}
								);
							},durasi_bell)

							update_status(response.noResepNonRacikanReady.sale_id);
						}
					} else {
						$("#rcp_non_racikan_ready").text(0)
						$("#unti_name_racika").text("-")
					}
				}
			})
		}
	</script>


</body>

</html>
