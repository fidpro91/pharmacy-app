
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
<style type="text/css">
	.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
		padding: 3px;
		border-color: #000;
	}
</style>
<body>
<br />
<table width="100%" style="text-align: center">
	<tr>
		<td>
			<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['nama'] ?> </span> <br / >
			<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['alamat'].", ".$profil['kota']?></span> <br / >
			<span style="font-weight: bold; font-size: 12px;">Laporan Penerimaan Gudang </span> <br / >
			<span style="font-weight: bold; font-size: 12px;">Periode <?php echo $periode?></span>
		</td>
	</tr>
</table>

<br />
<div class="text-center" style="margin: 3px;text-align: center">
	<table class="table table-striped table-bordered table-hover" style="border-collapse: collapse", width="100%" border="1">
		<tr>
			<th class="text-center" style="font-size: 12px;">No</th>
			<th class="text-center" style="font-size: 12px;">Tanggal</th>
			<th class="text-center" style="font-size: 12px;">Supplier</th>
			<th class="text-center" style="font-size: 12px;">No. Gudang</th>
			<th class="text-center" style="font-size: 12px;">No. Faktur</th>
			<th class="text-center" style="font-size: 12px;">Obat</th>
			<th class="text-center" style="font-size: 12px;">Kepemilikan</th>
			<th class="text-center" style="font-size: 12px;">Expired</th>
			<th class="text-center" style="font-size: 12px;">Qty</th>
			<th class="text-center" style="font-size: 12px;">Satuan</th>
			<th class="text-center" style="font-size: 12px;">Harga <br/>Satuan</th>
			<th class="text-center" style="font-size: 12px;">Sub <br/>Total</th>
			<th class="text-center" style="font-size: 12px;">Disk <br/>(%)</th>
			<th class="text-center" style="font-size: 12px;">Disk <br/>(Rp.)</th>
			<th class="text-center" style="font-size: 12px;">DPP</th>
			<th class="text-center" style="font-size: 12px;">PPN</th>
			<th class="text-center" style="font-size: 12px;">DPP+PPN</th>
		</tr>
		<?php foreach($datas as $index => $data) :?>
			<tr>
				<td class="text-right"  style="font-size: 12px;"><?php echo ($index+1) ?></td>
				<td class="text-center" style="font-size: 12px;"><?php echo date("d-m-y", strtotime($data->receiver_date)); ?></td>
				<td class="text-left"   style="font-size: 12px;"><?php echo $data->supplier_name?></td>
				<td class="text-left" 	style="font-size: 12px;"><?php echo $data->receiver_num?></td>
				<td class="text-left" 	style="font-size: 12px;"><?php echo $data->rec_num?></td>
				<td class="text-left" 	style="font-size: 12px;"><?php echo $data->item_name?></td>
				<td class="text-center" style="font-size: 12px;"><?php echo $data->own_name?></td>
				<td class="text-center" style="font-size: 12px;"><?php echo date("d-m-y", strtotime($data->expired_date)); ?></td>
				<td class="text-right"  style="font-size: 12px;"><?php echo $data->qty_unit?></td>
				<td class="text-center" style="font-size: 12px;"><?php echo $data->item_unit?></td>
				<td class="text-right"  style="font-size: 12px;"><?php echo number_format($data->price_item,2,'.',',')?></td>
				<td class="text-right"  style="font-size: 12px;"><?php echo number_format($data->price_total,2,'.',',')?></td>
				<td class="text-right"  style="font-size: 12px;"><?php echo $data->disc_percent?></td>
				<td class="text-right"  style="font-size: 12px;"><?php echo number_format($data->disc_value,2,',','.')?></td>
				<td class="text-right"  style="font-size: 12px;"><?php echo number_format($data->dpp,2,'.',',')?></td>
				<td class="text-right"  style="font-size: 12px;"><?php echo number_format($data->ppn,2,'.',',')?></td>
				<td class="text-right"  style="font-size: 12px;"><?php echo number_format($data->total,2,'.',',')?></td>
			</tr>
		<?php endforeach;?>
	</table>
</div>
</body>
</html>
