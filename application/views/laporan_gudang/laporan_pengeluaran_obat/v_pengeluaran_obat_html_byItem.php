
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
<br />
<table style="text-align: center" width="100%">
	<tr>
		<td>
			<div class="text-center">
				<span style="font-weight: bold; font-size: 12px;"><?php echo $rs['nama'] ?> </span> <br / >
				<span style="font-weight: bold; font-size: 12px;"><?php echo $rs['alamat'].", ".$rs['kota']?></span> <br / >
				<span style="font-weight: bold; font-size: 12px;">Laporan Pengeluaran Obat Unit <?php if( !empty($unit_asal->unit_name) ) echo $unit_asal->unit_name; ?></span> <br / >
				<span style="font-weight: bold; font-size: 12px;">Periode <?php echo $periode?></span>
			</div>
		</td>
	</tr>
</table>

<br />
<div class="text-center" style="margin: 3px;">
	<table class="table table-striped table-bordered table-hover" width="100%" border="1" style="border-collapse: collapse">
		<tr>
			<th class="text-center" style="font-size: 12px;">No</th>
			<th class="text-center" style="font-size: 12px;">Kode</th>
			<th class="text-center" style="font-size: 12px;">Obat</th>
			<th class="text-center" style="font-size: 12px;">Satuan</th>
			<th class="text-center" style="font-size: 12px;">Jumlah</th>
			<th class="text-center" style="font-size: 12px;">Harga</th>
			<th class="text-center" style="font-size: 12px;">Total</th>
		</tr>
		<?php $total_jml_qty=0; $jml_tot = 0; foreach($datas as $index => $data) : $total_jml_qty += $data->jml_qty; ?>
			<tr>
				<td class="text-right"  style="font-size: 12px;"><?php echo ($index+1) ?></td>
				<td class="text-left" 	style="font-size: 12px;"><?php echo $data->item_code?></td>
				<td class="text-left" 	style="font-size: 12px;"><?php echo $data->item_name?></td>
				<td class="text-center" style="font-size: 12px;"><?php echo $data->satuan ?></td>
				<td class="text-center"  style="font-size: 12px;"><?php echo $data->jml_qty?></td>
				<td class="text-right" style="font-size: 12px;"><?php echo number_format($data->harga,2,",",",")?></td>
				<td class="text-right" style="font-size: 12px;"><?php $tot= $data->jml_qty * $data->harga; echo $tot ?></td>
			</tr>
		<?php $jml_tot +=$tot; endforeach;?>
		<tr>
			<td class="text-right"  style="font-size: 12px;"></td>
			<td class="text-right"  style="font-size: 12px;"></td>
			<td class="text-left" 	style="font-size: 12px;" colspan="2">Grand Total</td>
			<td class="text-right"  style="font-size: 12px;"><?php echo $total_jml_qty;?></td>
			<td class="text-right" style="font-size: 12px;"></td>
			<td class="text-right" style="font-size: 12px;"><?=$jml_tot; ?></td>
		</tr>
	</table>
</div>
</body>
</html>
