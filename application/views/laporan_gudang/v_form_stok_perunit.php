<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= ucwords('FORM LAPORAN STOK APOTIK') ?>
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
				<h3 class="box-title">Form Laporan Stok Apotik</h3>
				<div class="box-tools pull-right">
				</div>
				<br>
			</div>
			<?= form_open("laporan_permintaan_gudang/show_laporan_stok_perunit", ["method" => "post", "id" => "formlaporan", "target" => "_blank"]) ?>
			<div class="box-body">
				<div class="col-md-6">

					<?= create_select2([
						"attr" => ["name" => "unit_id=unit", "id" => "unit_id", "class" => "form-control"],
						"model" => [
							"m_ms_unit" => ["get_farmasi_unit", ["0" => "0"]],
							"column" => ["unit_id", "unit_name"]
						]
					]) ?>


				</div>
			</div>
			<?= form_close() ?>
			<div class="box-footer">
				<button class="btn btn-primary" type="button" id="print"><i class="fa fa-search"></i>Tampilkan</button>
				<button class="btn btn-success" type="button" id="excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i>Tampilkan</button>
			</div>
		</div>
	</section>
</div>
<script>
	$("#print").click(function() {
		let value = $("#unit_id").val();
		if(value == ''){
			alert("unit tidak boleh kosong")
			return
		}
		window.open("<?php echo base_url() ?>laporan_permintaan_gudang/show_laporan_stok_perunit/" + value +"/p", "", "width=500, height=300");
	})

	$("#excel").click(function () {
		let value = $("#unit_id").val();
		if(value == ''){
			alert("unit tidak boleh kosong")
			return
		}
		window.open("<?php echo base_url() ?>laporan_permintaan_gudang/show_laporan_stok_perunit/" + value +"/exc", "", "width=500, height=300");
	})
	<?= $this->config->item('footerJS') ?>
</script>
