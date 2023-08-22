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
						<div class="box box-widget widget-user">
							<!-- Add the bg color to the header using any of the bg-* classes -->
							<div class="pull-right">
								<div class="widget-user-header bg-aqua-active">
									<?= create_select([
										"attr" => ["name" => "unit_id=UNIT FARMASI", "id" => "unit_id", "class" => "form-control"],
										"model" => [
											"m_ms_unit" => ["get_ms_unit_farmasi", ["0" => "0"]],
											"column"  => ["unit_id", "unit_name"]
										]
									]) ?>
								</div>
							</div>
							<div class="widget-user-header bg-aqua-active">
								<h2 style="font-weight: bold;">DASHBOARD ANTRIAN OBAT</h2>
								<h4 id="clock"></h4>
							</div>
							<div class="widget-user-image">
								<img class="img-circle" src="<?= base_url("assets") ?>/images/logors2.png" alt="User Avatar">
							</div>
							<div class="box-footer">
								<div class="row">
									<div class="col-sm-12">
										<h4 style="font-weight: bold;"><i class="fa fa-bullhorn"></i> INFORMASI <i class="fa fa-bullhorn"></i></h4>
										<marquee behavior="" direction="" class="bg-red">
											<h2 style="font-weight: bold; margin:0px !important; padding:0px !important;">
												Resep Obat Racikan dan Obat Kronis Membutuhkan Waktu Lebih Lama * Mohon Menunggu Dengan Sabar
											</h2>
										</marquee>
										<!-- /.description-block -->
									</div>
									<!-- <div class="col-md-6">
										<div class="box box-primary box-solid">
											<div class="box-header text-center">
												PANGGILAN ANTRIAN
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="info-box">
													<span class="info-box-icon bg-aqua" style="width: 50% !important; font-size:22pt !important; font-weight:bold">
														RACIKAN
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
													<span class="info-box-icon bg-aqua" style="width: 50% !important; font-size:22pt !important; font-weight:bold">
														STANDAR
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
									</div> -->
									<!-- /.col -->
								</div>
								<!-- /.row -->
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="box box-primary box-solid">
							<div class="box-header text-center">
								ANTRIAN RESEP DILAYANI
							</div>
							<div class="box-body" style="text-align: center !important;">
								<div class="box-body" style="font-size: 20px">
									<?= create_table("tb_antrian", "M_antrean_recipe", ["class" => "table table-bordered table-striped tb_antrian", "style" => "width:100% !important;"]) ?>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-primary box-solid">
							<div class="box-header text-center">
								ANTRIAN RESEP SELESAI
							</div>
							<div class="box-body" style="text-align: center !important;">
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
	<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

	<audio id="tingtung" src="<?= site_url("assets") ?>/audio/tingtung.mp3"></audio>
	<script>
		// Enable pusher logging - don't include this in production
		Pusher.logToConsole = true;
		var pusher = new Pusher('2c006c80922871a2eef0', {
			cluster: 'ap1'
		});
		var callQueue = [];
		var channel = pusher.subscribe('my-channel');
		channel.bind('my-event', function(data) {
			if (data.response.unit_id === $("#unit_id").val()) {
				callQueue.push(data.response); // Tambahkan data panggilan ke antrian
				if (callQueue.length === 1) {
					panggil_antrean();
				}
			}
		});

		function panggil_antrean() {
			if (callQueue.length === 0) {
				return; // Antrian kosong, tidak ada yang dipanggil
			}
			var data = callQueue[0]; // Ambil data panggilan pertama dari antrian
			/* if (data.kronis == 't') {
				$("#rcp_racikan_ready").text(data.nomor);
			} else {
				$("#rcp_non_racikan_ready").text(data.nomor);
			} */

			var nomorantrean = data.nomor.split("").join(". ");
			var pasien = data.pasien.inisial + " " + data.pasien.nama;
			var unitApotek = $("#unit_id option:selected").text();
			responsiveVoice.speak(
				data.pasien + ", DARI "+data.unit_layanan+". menuju ke " + unitApotek, "Indonesian Female", {
					pitch: 1,
					rate: 1.05,
					volume: 5,
					// onstart: voiceStartCallback,
					onend: function() {
						callQueue.shift(); // Hapus data panggilan pertama dari antrian
						table.draw();
						table2.draw();
						panggil_antrean(); // Panggil antrian berikutnya
					}
				}
			);
		}
	</script>
	<script>
		var table;
		var speak = false;
		$(document).ready(function() {
			table = $('#tb_antrian').DataTable({
				'scroller': false,
				'scrollY': '550px',
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
				'scrollY': '550px',
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

			$('#tb_antrian, #tb_antrian2').on('draw.dt', function() {
                var $scrollBody = $(table.table().node()).parent();
      			$scrollBody.scrollTop($scrollBody.get(0).scrollHeight);
                var $scrollBody2 = $(table2.table().node()).parent();
      			$scrollBody2.scrollTop($scrollBody2.get(0).scrollHeight);
            });

			$("#unit_id").change(() => {
				table.draw();
				table2.draw();
			});

			setInterval(() => {
				table.draw();
				table2.draw();
			}, 50000);
		})
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