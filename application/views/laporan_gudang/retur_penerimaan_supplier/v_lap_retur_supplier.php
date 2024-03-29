<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= ucwords('FORM LAPORAN RETUR SUPPLIER') ?>
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
				<h3 class="box-title">Form Laporan Retur Supplier</h3>
				<div class="box-tools pull-right">
				</div>
				<br>
			</div>
			<?= form_open("laporan_permintaan_gudang/show_retur", ["method" => "post", "id" => "formlaporan", "target" => "_blank"]) ?>
			<div class="box-body">
				<div class="col-md-6">
					<?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>

					<?= create_select([
						"attr" => ["name" => "unit_id=unit", "id" => "unit_id", "class" => "form-control","required"=>true],
						"model" => [
							"m_laporan_gudang" => "get_unit_retur",
							"column" => ["unit_id", "unit_name"]
						]
					]) ?>

					<?= create_select2([
						"attr" => ["name" => "supplier_id=supplier", "id" => "supplier_id", "class" => "form-control"],
						"model" => [
							"m_laporan_gudang" => "get_supplier",
							"column" => ["supplier_id", "supplier_name"]
						]
					]) ?>

					<?= create_select2([
							"attr" => ["name" => "item_id=item", "id" => "item_id", "class" => "form-control"],
							"model" => [
									"m_laporan_gudang" => "get_item",
									"column" => ["item_id", "item_name"]
							]
					]) ?>

				</div>
			</div>
			<?= form_close() ?>
			<div class="box-footer">
				<button class="btn btn-primary" type="button" onclick="$('#formlaporan').submit()"><i class="fa fa-search"></i>Tampilkan</button>
			</div>
		</div>
	</section>
</div>
<script>
	<?= $this->config->item('footerJS') ?>
</script>
