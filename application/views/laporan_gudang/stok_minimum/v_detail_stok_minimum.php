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

	<title>Laporan Stok Obat < Stok Minimum</title>
</head>
<body>
<br />
<table width="100%" style="text-align: center">
	<tr>
		<td>
			<div class="text-center">
				<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['nama'] ?> </span> <br / >
				<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['alamat'].", ".$profil['kota']?></span> <br / >
				<span style="font-weight: bold; font-size: 12px;">Laporan Stok Obat &#60; Stok Minimum</span>
				<span style="font-weight: bold; font-size: 12px;"><?php echo ( empty($unit) ) ? '' : $unit->unit_name ?> </span>
			</div>
		</td>
	</tr>
</table>

<br />
<div class="text-center" style="margin: 3px;">
	<table class="table table-striped table-bordered table-hover" width="100%" border="1" style="border-collapse: collapse">
		<tr>
			<th class="text-center" style="font-size: 12px;">No</th>
			<th class="text-center" style="font-size: 12px;">Kode Obat</th>
			<th class="text-center" style="font-size: 12px;">Nama Obat</th>
			<th class="text-center" style="font-size: 12px;">Kepemilikan</th>
			<th class="text-center" style="font-size: 12px;">Stok</th>
			<th class="text-center" style="font-size: 12px;">Jml. Mutasi <br> Per bulan</th>
			<th class="text-center" style="font-size: 12px;">Rata2 Mutasi</th>
			<th class="text-center" style="font-size: 12px;">Stok All Unit</th>
		</tr>
		<?php
		foreach($datas as $index => $data) :
			$arf = array($data->item_id, $data->own_id);
			?>
			<tr>
				<td class="text-center" style="font-size: 12px;"><?php echo ($index+1) ?></td>
				<td class="text-center" style="font-size: 12px;"><?php echo $data->item_code?></td>
				<td class="text-left" style="font-size: 12px;"><?php echo $data->item_name?></td>
				<td class="text-center" style="font-size: 12px;"><?php echo $data->own_name?></td>
				<td class="text-right" style="font-size: 12px;">
					<a href="javascript:void(0)" onclick="show_kartu_stok(<?php echo implode(',' ,$arf); ?>)" data-toggle="tooltip" data-placement="top" title="Klik untuk melihat kartu stok">
						<?php echo $data->stock_summary; ?>
					</a>
				<td class="text-right" style="font-size: 12px;"><?php echo ( $data->jml_keluar ) ? $data->jml_keluar  : 0 ; ?></td>
				<td class="text-right" style="font-size: 12px;"><?php echo ( $data->rata2_keluar ) ? $data->rata2_keluar  : 0 ; ?></td>
				<td class="text-right" style="font-size: 12px;"><?php echo ( $data->stock_all_unit ) ? $data->stock_all_unit  : 0 ; ?></td>
			</tr>
		<?php endforeach;?>
	</table>
	<form id="stok" method="post" action="<?php echo base_url(); ?>farmasi/laporan_apotek/kartu_stok" target="_blank" style="display:none;">
		<input type="hidden" name="item_id" id="item_id" />
		<input type="hidden" name="own_id" id="own_id" />
		<input type="hidden" name="unit_id" id="unit_id" value="<?=$unit->unit_id?>" />
	</form>
</div>
</body>
</html>
<script type="text/javascript">
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
	});

	function show_kartu_stok(item_id, own_id){
		$('#item_id').val( item_id );
		$('#own_id').val( own_id );
		$('#stok').submit();
	}
</script>
