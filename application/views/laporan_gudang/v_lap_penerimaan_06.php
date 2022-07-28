<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>

	<link href="<?php echo base_url(); ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">

	<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/global/plugins/tabletoexcel/excelexportjs.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#btn-excel").on('click', function () {
				$('.table .table-striped .table-bordered .table-hover').attr('border',1);
				var uri = $("#example").excelexportjs({
					containerid: "example"
					, datatype: 'table'
					, returnUri: true
				});
				$("#btn-excel").attr('download', $('title').text()+'.xls').attr('href', uri).attr('target', '_blank');
			});
			$("#btn-cetak").on('click', function () {
				$('#div_tombol').hide();
				window.print();
				window.close();
			});
		});
	</script>

	<title>Laporan Penerimaan Gudang</title>
</head>
<body>

<style type="text/css">
	table , table th , table tr , table td{
		border-color: #000 !important;
	}
	.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
		padding: 3px !important;
		border-color: #000 !important;
		font-size : 10px;
	}

	table th { text-align: center; }
</style>
<!--<div id="div_tombol" align="left">-->
<!--	<button class="btn btn-default" id="btn-cetak">Cetak</button>-->
<!--	<a class="btn btn-default" href="#" id="btn-excel">Excel</a>-->
<!--</div>-->
<br />
<table class="table table-bordered" width="100%" id="example" border="1" style="border-collapse: collapse">
	<tr>
		<th width="5%">NO</th>
		<th width="10%">TANGGAL FAKTUR</th>
		<th width="10%">NO FAKTUR</th>
		<th width="40%">SUPPLIER</th>
		<th width="10%">TOTAL</th>
	</tr>
	<?PHP
	$total=0;
	foreach ($data as $key => $value) {
		$total += $value->total;
		echo "<tr>
							<td align=\"center\">".($key+1)."</td>
							<td>".$value->rec_date."</td>
							<td>".$value->no_faktur."</td>
							<td>".$value->supplier_name."</td>
							<td align=\"right\">".number_format($value->total,2,'.',',')."</td>
					</tr>";
	}
	?>
	<tr>
		<td></td>
		<td colspan="3">Jumlah Total</td>
		<td align="right"><?=number_format($total,2,'.',',')?></td>
	</tr>
</table>
</body>
</html>
