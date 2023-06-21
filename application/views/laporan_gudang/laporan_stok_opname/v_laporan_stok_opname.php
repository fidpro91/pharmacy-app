<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= ucwords('Laporan Stok Opname Obat') ?>
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
				<h3 class="box-title">Laporan Stok Opname Obat</h3>
				<div class="box-tools pull-right">
				</div>
				<br>
			</div>
			<?= form_open("laporan_permintaan_gudang/show_stok_opname", ["method" => "post", "id" => "formlaporan", "target" => "_blank"]) ?>
			<div class="box-body">
				<div class="col-md-6">
					<?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>
					<?= create_select2([
						"attr" => ["name" => "unit_id=jenis unit", "id" => "unit_id", "class" => "form-control"],
						"model" => [
							"m_ms_unit" => ["get_farmasi_unit", ["0" => "0"]],
							"column" => ["unit_id", "unit_name"]
						]
					]) ?>
				</div>
			</div>
			<?= form_close() ?>
			<div class="box-footer">
				<button class="btn btn-primary" type="button" id="tampil"><i class="fa fa-search"></i>Tampilkan</button>
				<button class="btn btn-success" type="button" id="excel"><i class="fa fa-excel"></i>excel</button>
			</div>
		</div>
	</section>
</div>
<script>
	$("#tampil").click(function() {
		let tgl = $("#tanggal").val();
		const tanggal = tgl.split("/");
		let tgl_awal = tanggal[0];
		let tgl_akhir = tanggal[1];
		let value = $("#unit_id").val();
		if (value == ''){
			value = 0;
		}
		// console.log(value)
		window.open("<?php echo base_url() ?>laporan_permintaan_gudang/show_stok_opname/" + value +"/"+tgl_awal+"/"+tgl_akhir+"/tampil",'_blank').focus();
	})

	$("#excel").click(function () {
		let tgl = $("#tanggal").val();
		const tanggal = tgl.split("/");
		let tgl_awal = tanggal[0];
		let tgl_akhir = tanggal[1];
		let value = $("#unit_id").val();
		if (value == ''){
			value = 0;
		}
		window.open("<?php echo base_url() ?>laporan_permintaan_gudang/show_stok_opname/" + value +"/"+tgl_awal+"/"+tgl_akhir+"/excel",'_blank').focus();
	})
	<?= $this->config->item('footerJS') ?>
</script>
