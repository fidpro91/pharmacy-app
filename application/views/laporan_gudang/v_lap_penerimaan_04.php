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

	<?php
	$grand_total=0;
	foreach($data as $res):
		?>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th class="text-center" style="font-size: 10px;" width="15%">Nama Supplier</th>
			</tr>
			<tr>
				<td class="text-left"   style="font-size: 10px;"><?php echo strtoupper($res->supplier_name)?></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="4">
					<table class="table table-striped table-bordered table-hover">
						<tr>
							<th class="text-center" style="font-size: 10px;" >No</th>
							<th class="text-center" style="font-size: 10px;" >Tanggal Faktur</th>
							<th class="text-center" style="font-size: 10px;" >No. Faktur</th>
							<th class="text-center" style="font-size: 10px;" >No. Penerimaan</th>
							<th class="text-center" style="font-size: 10px;" >Sub Total</th>
						</tr>
						<?php
						$detail_item = json_decode($res->detail_item); $num=1;$total=0;
						foreach ($detail_item as $metu):
							$data_detail = explode('|', $metu);
							$total += $data_detail[3];
							?>
							<tr>
								<td><?=$num?></td>
								<td><?=$data_detail[2]?></td>
								<td class="text-left"><?=$data_detail[0]?></td>
								<td><?=$data_detail[1]?></td>
								<td class="text-right"><?=number_format($data_detail[3],2,".",",")?></td>
							</tr>
							<?php
							$num++;
						endforeach;
						?>
						<tr>
							<td colspan="3"></td>
							<td class="text-right">Total</td>
							<td class="text-right"><?php $grand_total += $total; echo number_format($total,2,".",".")?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?php
	endforeach;
	?>
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<td width="80%" class="text-right">Total Transaksi Pembelian</td>
			<td width="2%">:</td>
			<td class="text-right"><b><?=number_format($grand_total,2,".",",")?></b></td>
		</tr>
	</table>
</div>
<div class="tex-left">
	<label>Tanggal Cetak : <?=date('d-m-Y')?></label>
</div>
</body>
</html>
