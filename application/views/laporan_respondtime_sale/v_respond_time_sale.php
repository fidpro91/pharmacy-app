<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?=ucwords('FORM LAPORAN')?>
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
			<?=$this->session->flashdata('message')?>
			<div class="box-header with-border">
				<h3 class="box-title">Form Laporan Retur Penjualan</h3>
				<div class="box-tools pull-right">
				</div>
				<br>
			</div>
			<?=form_open("respondtime_sale/show_laporan",["method"=>"post","id"=>"formlaporan","target"=>"blank"])?>
			<div class="box-body" id="penjualan">

				<div class="col-md-5" >
					<?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>
					<?=create_select(["attr"=>["name"=>"unit_name=UNIT","id"=>"unit_name","class"=>"form-control"],
						"model"=>["m_ms_unit" => "get_farmasi_unit","column"=>["unit_id","unit_name"]]
					])?>
					<?= create_select([
						"attr" => ["name" => "jns_resep=Jenis Resep", "id" => "jns_resep", "class" => "form-control"],
						"option" => [["id" => '', "text" => "Semua"], ["id" => '1', "text" => "Racikan"],["id" => '2', "text" => "Non Racikan"]],
					]) ?>

					<?= create_select([
							"attr" => ["name" => "jenis_layanan=Jenis Layanan", "id" => "jenis_layanan", "class" => "form-control"],
							"option" => [["id" => '', "text" => "Semua"], ["id" => '1', "text" => "Rawat Jalan"],["id" => '2', "text" => "Rawat Inap"]],
					]) ?>

				</div>

			</div>
			<div>
				<button class="btn btn-primary" type="button" onclick="$('#formlaporan').submit()">
					<i class="fa fa-eye" aria-hidden="true"></i>Tampilkan</button>
				<button class="btn btn-warning" type="reset" id="form_reset">
					<i class="fa fa-paint-brush" aria-hidden="true"></i>Reset</button>
			</div>

			<?=form_close()?>


		</div>
	</section>
	<!-- /.content -->
</div>
<script>
	<?=$this->config->item('footerJS')?>
</script>
