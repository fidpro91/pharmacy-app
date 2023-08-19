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

		.box-header {
			font-size: 3vh !important;
			font-weight: bold !important;
		}

		h1 {
			text-align: center !important;
			font-weight: bold;
			font-size: 12vh;
			padding: 0;
			margin: 0;
		}

		.txt_small {
			font-size: 10pt !important;
			font-style: italic !important;
		}
		/* #tb_antrian td {
			font-size: 24pt !important;
		} */
	</style>
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
	<div class="wrapper">
		<!-- Full Width Column -->
		<div class="content-wrapper">
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs nav-tabs-primary">
							<li class="active"><a href="#tab1" data-toggle="tab">
									<h3 style="font-weight: bold;">DASHBOARD ANTRIAN OBAT</h3>
									<h4 id="clock"></h4>
								</a>
							</li>
							<li style="margin-left:3.5vw;">
								<div class="callout callout-warning" style="margin-top:10px !important;">
									<h4 style="font-weight: bold;"><i class="fa fa-bullhorn"></i> INFORMASI <i class="fa fa-bullhorn"></i></h4>
									<marquee behavior="" direction="">
										<h4>
											Resep Obat Racikan dan Obat Kronis Membutuhkan Waktu Lebih Lama * Mohon Menunggu Dengan Sabar
										</h4>
									</marquee>
								</div>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">
								<div class="row">
									<div class="col-md-5">
										<div class="d-flex align-items-center">
											<div class="pull-left image mr-5">
												<img width="75px" class="user-image" src="<?=base_url("assets")?>/images/logors.png" alt="">
											</div>
											<h3 style="font-weight: bold;">RSUD IBNU SINA KABUPATEN GRESIK</h3>
											<p>Jl. DR. Wahidin Sudiro Husodo No.243B, Kembangan, Klangonan, Kec. Kebomas, Kabupaten Gresik, Jawa Timur 61124</p>
										</div>
										<div class="col-md-12">
											<?= create_select([
												"attr" => ["name" => "unit_id=Unit Farmasi", "id" => "unit_id", "class" => "form-control"],
												"model" => [
													"m_ms_unit" => ["get_ms_unit_farmasi", ["0" => "0"]],
													"column"  => ["unit_id", "unit_name"]
												]
											]) ?>
										</div>
									</div>
									<div class="col-md-2"></div>
									<div class="col-md-5">
										<div class="box box-primary box-solid">
											<div class="box-header text-center">
												PANGGILAN ANTRIAN
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="info-box">
													<span class="info-box-icon bg-aqua">
														RC
													</span>
													<div class="info-box-content">
														<span class="info-box-number text-center">
															<h2 id="rcp_racikan_ready" style=" font-weight: bold; margin:0px; font-size:3em">000</h2>
															<small style="margin: 0px;" id="unit_name"> -
														</small>
														</span>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="info-box">
													<span class="info-box-icon bg-aqua">
														ST
													</span>
													<div class="info-box-content">
														<span class="info-box-number text-center">
															<h2 id="rcp_non_racikan_ready" style=" font-weight: bold; margin:0px; font-size:3em">000</h2>
															<small style="margin: 0px;" id="unti_name_racika">-</small>
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="callout callout-warning">
							<marquee behavior="" direction="">
								<h4>
									<i class="fa fa-bullhorn"></i>
									Resep Obat Racikan dan Obat Kronis Membutuhkan Waktu Lebih Lama * Mohon Menunggu Dengan Sabar
								</h4>
							</marquee>
						</div> -->
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="box box-primary box-solid" style="min-height: 420px !important">
							<div class="box-header text-center">
								ANTRIAN RESEP DILAYANI
							</div>
							<div class="box-body" style="text-align: center !important; min-height:8em !important;">
								<div class="box-body" style="font-size: 20px">
									<?= create_table("tb_antrian", "M_antrean_recipe", ["class" => "table table-bordered table-striped tb_antrian", "style" => "width:100% !important;"]) ?>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-primary box-solid" style="min-height: 420px !important">
							<div class="box-header text-center">
								ANTRIAN RESEP SELESAI
							</div>
							<div class="box-body" style="text-align: center !important; min-height:8em !important;">
								<div class="box-body" style="font-size: 20px">
									<?= create_table("tb_antrian2", "M_antrean_recipe", ["class" => "table table-bordered table-striped tb_antrian", "style" => "width:100% !important;"]) ?>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
				</div>
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
	$.extend(true, $.fn.dataTable.defaults, {
      error: false, // Nonaktifkan pesan error
    });
</script>
	<script>
		var table;
		var speak = false;
		$(document).ready(function() {
			table = $('#tb_antrian').DataTable({
				'scroller': false,
				'scrollY': '300px',
				'scrollCollapse': false,
				'paging': false,
				'searching': false,
				'info': false,
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
						f.unit_id = $("#unit_id").val() || 0;
					}
				},
				'columnDefs': [{
						'targets': [0, 1, -1],
						'searchable': false,
						'orderable': false,
					},
					{
						"visible": false,
						"targets": [0, 1, -1]
					},
					{
						'targets': 0,
						'className': 'dt-body-center'
					}
				],
			});
			table2 = $('#tb_antrian2').DataTable({
				'scroller': false,
				'scrollY': '300px',
				'scrollCollapse': false,
				'paging': false,
				'searching': false,
				'info': false,
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
						f.unit_id = $("#unit_id").val() || 0;
						f.sale_status = 2;
					}
				},
				'columnDefs': [{
						'targets': [0, 1, -1],
						'searchable': false,
						'orderable': false,
					},
					{
						"visible": false,
						"targets": [0, 1, -1]
					},
					{
						'targets': 0,
						'className': 'dt-body-center'
					}
				],
			});

			$("#unit_id").change(() => {
				table.draw();
				table2.draw();
				// ambil_antrean();
			});

			setInterval(() => {
				table.draw();
				table2.draw();
			}, 50000);


			var $el = $(".dataTables_scrollBody");

			function anim() {
				var st = $el.scrollTop();
				var sb = $el.prop("scrollHeight") - $el.innerHeight();
				/* console.log('st' + st);
				console.log('sb' + sb); */
				$el.animate({
					scrollTop: st < sb / 2 ? sb : 0
				}, 150000, anim);
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
						if (response.noResepRacikanReady.unit_name != '') {
							$("#unit_name").text(response.noResepRacikanReady.unit_name)
						} else {
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
									response.noResepRacikanReady.patient_name + ". DARI " + response.noResepRacikanReady.unit_name + " silahkan menuju ke " + response.noResepRacikanReady.depo, "Indonesian Female", {
										pitch: 1,
										rate: 1,
										volume: 2,
										// onstart: voiceStartCallback,
										// onend: voiceEndCallback(no_urut, counter, 7100, queue_id)
									}
								);
							}, durasi_bell)
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
				type: "post",
				url: "<?= base_url() ?>antrean_recipe/update_antrian",
				datatype: "json",
				data: {
					sale_id
				},
				success: function(param) {
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
									response.noResepNonRacikanReady.patient_name + ". DARI " + response.noResepNonRacikanReady.unit_name + " silahkan menuju ke " + response.noResepNonRacikanReady.depo, "Indonesian Female", {
										pitch: 1,
										rate: 1,
										volume: 2,
										// onstart: voiceStartCallback,
										// onend: voiceEndCallback(no_urut, counter, 7100, queue_id)
									}
								);
							}, durasi_bell)

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

	<script>
		function getDayName(dayIndex) {
			const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
			return days[dayIndex];
		}

		function getMonthName(monthIndex) {
			const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			return months[monthIndex];
		}

		function updateTime() {
			const now = new Date();
			const dayIndex = now.getDay();
			const dayName = getDayName(dayIndex);
			const date = now.getDate();
			const monthIndex = now.getMonth();
			const monthName = getMonthName(monthIndex);
			const year = now.getFullYear();
			const hours = now.getHours().toString().padStart(2, '0');
			const minutes = now.getMinutes().toString().padStart(2, '0');
			const seconds = now.getSeconds().toString().padStart(2, '0');

			const timeString = `${dayName}, ${date} ${monthName} ${year} - ${hours}:${minutes}:${seconds}`;
			document.getElementById('clock').innerText = timeString;
		}

		updateTime();
		setInterval(updateTime, 1000); // Update time every second
	</script>
</body>

</html>