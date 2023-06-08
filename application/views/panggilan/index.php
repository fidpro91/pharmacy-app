<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?=ucwords('Stock')?>
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
				<div class="box-tools pull-left col-md-3">
					<?= form_dropdown("unit_id_depo", $unit, '', 'class="form-control select2" id="unit_id_depo"') ?>
				</div>

			</div>
			<div class="box-body" id="kartu_Stok" style="display: none;">
			</div>
			<div class="box-body" id="data_stock">
				<?=create_table("tb_panggilan","M_panggilan",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
			</div>
		</div>
		<!-- /.box -->

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= modal_open("modal_penyesuaian", "Penyesuaian_stok","modal-lg") ?>
<?= modal_close() ?>
<script type="text/javascript">
	var table;
	$(document).ready(function() {
		table = $('#tb_panggilan').DataTable({
			dom: 'Bfrtip',
			"pageLength":100,
			"processing": true,
			"serverSide": true,
			"order": [[2,"ASC"]],
			"scrollX": true,
			"ajax": {
				"url": "<?php echo site_url('panggilan/get_data')?>",
				"type": "POST",
				"data" : function (f) {
					f.unit_id = $("#unit_id_depo").val();
				}
			},
			'columnDefs': [
				{
					'targets': [0,1,-1,-2],
					'searchable': false,
					'orderable': false,
				},

				{ "width": "8%", "targets": -1 },
				{
					'targets': 0,
					'className': 'dt-body-center',
					"visible" : false
				}],
		});

		setInterval(() => {
			table.draw();
		}, 50000)
	});

	$("#unit_id_depo").change(() => {
		table.draw();
	});

	function panggil(sale_id) {
		console.log(sale_id)
		$.ajax({
			url: "<?= base_url() ?>panggilan/panggil",
			method: "POST",
			datatype: "json",
			data: {sale_id:sale_id},
			success: function(data){
				console.log(data)
				table.draw();
			},
			error: function(errMsg) {
				alert(JSON.stringify(errMsg));
			}
		});
	}
</script>
