<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= ucwords('Laporan Mutasi Obat dan Alkes') ?>
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
				<h3 class="box-title">Form Laporan Mutasi Obat dan Alkes</h3>
				<div class="box-tools pull-right">
				</div>
				<br>
			</div>
			<?= form_open("laporan_permintaan_gudang/detil_stok_minimum", ["method" => "post", "id" => "formlaporan", "target" => "_blank"]) ?>
			<div class="box-body">
				<div class="col-md-6">
					<?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>
					<?= create_select2([
						"attr" => ["name" => "unit_id=jenis unit", "id" => "unit_id", "class" => "form-control","required"=>"required"],
						"model" => [
							"m_ms_unit" => ["get_farmasi_unit", ["0" => "0"]],
							"column" => ["unit_id", "unit_name"]
						]
					]) ?>
					<?=create_select2(["attr"=>["name"=>"own_id=kepemilikan","id"=>"own_id","class"=>"form-control","required"=>"required"],
							"model"=>[
									"m_ownership" =>"get_ownership",
									"column"=>["own_id","own_name"]
							]
					])?>
					<?=create_select2(["attr"=>["name"=>"golongan","id"=>"golongan","class"=>"form-control"],
						"model"=>[
							"m_laporan_gudang" =>"get_golongan",
							"column"=>["gol","gol"]
						]
					])?>
					<?=create_select2(["attr"=>["name"=>"tampilan","id"=>"tampilan","class"=>"form-control"],
						"option" => [
							["id" => 'satuan', "text" => "satuan"],
							["id" => 'kemasan', "text" => "kemasan"]
						],
					])?>

				</div>
			</div>
			<?= form_close() ?>
			<div class="box-footer">
				<button class="btn btn-primary" type="button" id="tampil"><i class="fa fa-search"></i>Search</button>
				<button class="btn btn-success" type="button" id="excel"><i class="fa fa-file-excel-o"></i>Search</button>
			</div>
		</div>
	</section>
</div>
<script>
	$("#tampil").click(function() {
		let tgl = $("#tanggal").val();
		let tanggal = tgl.split("/");
		let tgl_awal = tanggal[0];
		let tgl_akhir = tanggal[1];
		let unit_id = $("#unit_id").val();
		let own_id = $("#own_id").val();
		let golongan = $("#golongan").val();
		let tampilan = $("#tampilan").val();
		if (unit_id == ''){
			alert("Jenis Unit Tidak boleh Kosong")
			return
		}
		if (own_id == ''){
			alert("Kepemilikan tidak boleh kosong")
			return;
		}
		if (golongan == ''){
			golongan = 'semua'
		}
		window.open("<?php echo base_url() ?>laporan_permintaan_gudang/show_laporan_stok_konsolidasi/" + tgl_awal +"/"+tgl_akhir+"/"+unit_id+"/"+own_id+"/"+golongan+"/"+tampilan+"/1", "_blank");
	})

	$("#excel").click(function () {
		let tgl = $("#tanggal").val();
		let tanggal = tgl.split("/");
		let tgl_awal = tanggal[0];
		let tgl_akhir = tanggal[1];
		let unit_id = $("#unit_id").val();
		let own_id = $("#own_id").val();
		let golongan = $("#golongan").val();
		let tampilan = $("#tampilan").val();
		if (unit_id == ''){
			alert("Jenis Unit Tidak boleh Kosong")
			return
		}
		if (own_id == ''){
			alert("Kepemilikan tidak boleh kosong")
			return;
		}
		if (golongan == ''){
			golongan = 'semua'
		}
		window.open("<?php echo base_url() ?>laporan_permintaan_gudang/show_laporan_stok_konsolidasi/" + tgl_awal +"/"+tgl_akhir+"/"+unit_id+"/"+own_id+"/"+golongan+"/"+tampilan+"/2", "_blank");
	})
	<?= $this->config->item('footerJS') ?>
</script>
