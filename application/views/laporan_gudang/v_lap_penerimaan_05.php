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

	<title>Laporan Penerimaan Gudang</title>
</head>
<body>
<style type="text/css">
	table , table th , table tr , table td{
		border-color: #000 !important;
	}
	.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
		padding: 3px;
		border-color: #000 !important;
		font-size : 10px;
	}
</style>
<br />
<div class="text-center">
	<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['nama'] ?> </span> <br / >
	<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['alamat'].", ".$profil['kota']?></span> <br / >
	<span style="font-weight: bold; font-size: 12px;">Laporan Penerimaan Gudang </span> <br / >
	<span style="font-weight: bold; font-size: 12px;"><?php echo $periode?></span>
</div>
<br />
<div class="text-center" style="margin: 3px;">
	<table class="table table-striped table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center" style="font-size: 10px;" width="5%">No</th>
			<th class="text-center" style="font-size: 10px;" width="25%">Nama Item</th>
			<!-- <th class="text-center" style="font-size: 10px;" width="5%">Harga</th> -->
			<!-- <th class="text-center" style="font-size: 10px;" width="10%">Qty Pack</th> -->
			<th class="text-center" style="font-size: 10px;" width="10%">Qty Unit</th>
			<th class="text-center" style="font-size: 10px;" width="20%">Subtotal</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$grand_total=0;$num=1;
		foreach($data as $res):
			$grand_total += $res->price_total;
			?>
			<tr>
				<td class="text-center"   style="font-size: 10px;"><?=$num?></td>
				<td class="text-left"   style="font-size: 10px;"><?php echo strtoupper($res->item_name)?></td>
				<!-- <td class="text-center" 	style="font-size: 10px;"><?php echo number_format($res->price_pack,2,",",".")?></td> -->
				<!-- <td class="text-center" 	style="font-size: 10px;"><?php echo $res->qty_pack?></td> -->
				<td class="text-center" 	style="font-size: 10px;"><?php echo $res->qty_unit?></td>
				<td class="text-right" 	style="font-size: 10px;"><?php echo number_format($res->price_total,2,",",".")?></td>
			</tr>
			<?php
			$num++;
		endforeach;
		?>
		</tbody>
		<tfoot>
		<tr>
			<td></td>
			<td colspan="2">Total</td>
			<td class="text-right" 	style="font-size: 10px;"><?php echo number_format($grand_total,2,",",".")?></td>
		</tr>
		</tfoot>
	</table>
</div>
<div class="tex-left">
	<label>Tanggal Cetak : <?=date('d-m-Y')?></label>
</div>
</body>
</html>
