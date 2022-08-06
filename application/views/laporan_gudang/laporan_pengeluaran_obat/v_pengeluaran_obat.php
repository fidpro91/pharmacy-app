<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= ucwords('FORM LAPORAN PENGUELUARAN OBAT') ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Layout</a></li>
			<li class="active">Fixed</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="box">
			<?= $this->session->flashdata('message') ?>
			<div class="box-header with-border">
				<h3 class="box-title">Form Laporan Pengeluaran Obat</h3>
				<div class="box-tools pull-right">
				</div>
				<br>
			</div>
			<?= form_open("laporan_permintaan_gudang/cetak_po", ["method" => "post", "id" => "formlaporan", "target" => "_blank"]) ?>
			<div class="box-body">
				<div class="col-md-6">

					<?= create_select2([
						"attr" => ["name" => "unit_id=unit", "id" => "unit_id", "class" => "form-control"],
						"model" => [
							"m_laporan_gudang" => "get_unit_asal",
							"column" => ["unit_id", "unit_name"]
						]
					]) ?>

					<?= create_select2([
							"attr" => ["name" => "unit_peminta=unit_peminta", "id" => "unit_peminta", "class" => "form-control"],
							"model" => [
									"m_laporan_gudang" => "get_unit_peminta",
									"column" => ["unit_id", "unit_name"]
							]
					]) ?>

					<?=create_select2(["attr"=>["name"=>"own_id","id"=>"own_id","class"=>"form-control"],
							"model"=>[
									"m_ownership" =>"get_ownership",
									"column"=>["own_id","own_name"]
							]
					])?>
					<?=create_select2(["attr"=>["name"=>"jns_laporan","id"=>"jns_laporan","class"=>"form-control"],
							"option" => [
									["id" => '1', "text" => "Laporan By Item"],
									["id" => '2', "text" => "Laporan Detail Mutasi"],
									["id" => '3', "text" => "Laporan Rekap Mutasi"]
							],
					])?>
					<?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>
				</div>
			</div>
			<?= form_close() ?>
			<div class="box-footer">
				<button class="btn btn-primary" type="button" id="tampil"><i class="fa fa-search"></i>Tampilkan</button>
				<button class="btn btn-primary" type="button" id="excel"><i class="fa fa-search"></i>excel</button>
			</div>
		</div>
	</section>
</div>
<script>
	$("#tampil").click(function() {
		let unit_id = $("#unit_id").val();
		if (unit_id == ''){
			alert("unit tidak boleh kosong");return;
		}
		let unit_peminta = $("#unit_peminta").val();
		if (unit_peminta == ''){
			unit_peminta = 0;
		}
		let own_id = $("#own_id").val();
		if (own_id == ''){
			own_id = "semua";
		}
		let jns_laporan = $("#jns_laporan").val();
		if(jns_laporan == ''){
			alert("jenis tidak boleh kosong");return;
		}
		let tgl = $("#tanggal").val();
		let tanggal = tgl.split("/");
		let tgl_awal = tanggal[0];
		let tgl_akhir = tanggal[1];
		window.open("<?php echo base_url() ?>laporan_permintaan_gudang/show_pengeluaran_obat/" + unit_id +"/"+unit_peminta+"/"+own_id+"/"+jns_laporan+"/"+tgl_awal+"/"+tgl_akhir + "/1", "_blank");
	})

	$("#excel").click(function () {
		let unit_id = $("#unit_id").val();
		if (unit_id == ''){
			alert("unit tidak boleh kosong");return;
		}
		let unit_peminta = $("#unit_peminta").val();
		if (unit_peminta == ''){
			unit_peminta = 0;
		}
		let own_id = $("#own_id").val();
		if (own_id == ''){
			own_id = "semua";
		}
		let jns_laporan = $("#jns_laporan").val();
		if(jns_laporan == ''){
			alert("jenis tidak boleh kosong");return;
		}
		let tgl = $("#tanggal").val();
		let tanggal = tgl.split("/");
		let tgl_awal = tanggal[0];
		let tgl_akhir = tanggal[1];
		window.open("<?php echo base_url() ?>laporan_permintaan_gudang/show_pengeluaran_obat/" + unit_id +"/"+unit_peminta+"/"+own_id+"/"+jns_laporan+"/"+tgl_awal+"/"+tgl_akhir+"/2", "_blank");
	})
	<?= $this->config->item('footerJS') ?>
</script>
