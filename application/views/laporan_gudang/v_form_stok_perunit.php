<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= ucwords('FORM LAPORAN PERMINTAAN GUDANG') ?>
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
				<h3 class="box-title">Form Laporan Permintaan Gudang</h3>
				<div class="box-tools pull-right">
				</div>
				<br>
			</div>
			<?= form_open("laporan_permintaan_gudang/show_laporan_stok_perunit", ["method" => "post", "id" => "formlaporan", "target"=>"_blank"]) ?>
			<div class="box-body">
				<div class="col-md-6">

					<?=create_select2(["attr"=>["name"=>"unit_id=unit","id"=>"unit_id","class"=>"form-control"],
						"model"=>[
							"m_ms_unit" =>["get_farmasi_unit",["0"=>"0"]],
							"column"=>["unit_id","unit_name"]
						]
					])?>


				</div>
			</div>
			<?= form_close() ?>
			<div class="box-footer">
				<button class="btn btn-primary" type="button" onclick="$('#formlaporan').submit()">Tampilkan</button>
			</div>
		</div>
	</section>
</div>
<script>
	<?=$this->config->item('footerJS')?>
</script>
