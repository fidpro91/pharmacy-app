<head>
	<title>Laporan Retur Penerimaan Gudang</title>
</head>
<body>
<!--<style type="text/css">-->
<!--	/*table , table th , table tr , table td{-->
<!--		border-color: #000 !important;-->
<!--	}*/-->
<!--	.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{-->
<!--		padding: 3px !important;-->
<!--		border-color: #000 !important;-->
<!--		font-size : 10px;-->
<!--	}-->
<!--</style>-->
<br />
<table width="100%" style="text-align: center">
	<tr>
		<td>
			<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['nama'] ?> </span> <br / >
			<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['alamat'].", ".$profil['kota']?></span> <br / >
			<span style="font-weight: bold; font-size: 12px;">Laporan Retur Penerimaan Gudang </span> <br / >
			<span style="font-weight: bold; font-size: 12px;"><?php echo $judul?></span>
		</td>
	</tr>
</table>
<br />
<div class="text-center" style="margin: 3px;">

	<?php
	$grand_total=0;
	foreach($data as $res):
		?>
		<table class="table table-striped table-bordered table-hover" style="border-collapse: collapse" width="100%" border="1">
			<tr>
				<th class="text-center" style="font-size: 12px; text-align: center" width="10%">Tanggal</th>
				<th class="text-center" style="font-size: 12px; text-align: center" width="20%">Nama Supplier</th>
				<th class="text-center" style="font-size: 12px; text-align: center" width="15%">No. Retur</th>
				<th colspan="3" style="border:0px !important; border-color:#fff !important; "></th>
			</tr>
			<tr>
				<td class="text-left" style="font-size: 12px; text-align: left"><?php echo strtoupper($res->supplier_name)?></td>
				<td class="text-left" style="font-size: 12px; text-align: left"><?php echo $res->tgl_retur?></td>
				<td class="text-left" style="font-size: 12px; text-align: left"><?php echo $res->num_retur?></td>
			</tr>
			<tr>
				<td style="border:none !important; border-color:#fff !important;"></td>
				<td colspan="5">
					<table class="table table-striped table-bordered table-hover" border="1" style="border-collapse: collapse" width="100%">
						<tr>
							<th class="text-center" style="font-size: 12px; text-align: center" >No</th>
							<th class="text-center" style="font-size: 12px; text-align: center" >Kode Produk</th>
							<th class="text-center" style="font-size: 12px; text-align: center" >Nama Produk</th>
							<th class="text-center" style="font-size: 12px; text-align: center" >Jumlah</th>
							<th class="text-center" style="font-size: 12px; text-align: center" >Harga</th>
							<th class="text-center" style="font-size: 12px; text-align: center" >Subtotal</th>
						</tr>
						<?php
						$detail_retur = json_decode($res->detail_retur); $num=1;$total=0;$total_disk=0;
						foreach ($detail_retur as $metu):
							$data_detail = explode('||', $metu);
							// echo $metu;
							$total += $data_detail[4];
							?>
							<tr>
								<td><?=$num?></td>
								<td><?=$data_detail[0]?></td>
								<td class="text-left"><?=$data_detail[1]?></td>
								<td><?=$data_detail[2]?></td>
								<td><?=number_format($data_detail[3],2,".",',')?></td>
								<td><?=number_format($data_detail[4],2,'.',',')?></td>
							</tr>
							<?php
							$num++;
						endforeach;
						$grand_total += $total;
						?>
						<tr>
							<td colspan="3"></td>
							<td colspan="2" style="text-align: right">Total</td>
							<td style="text-align: right"><?=number_format($total,2,".",',')?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?php
	endforeach;
	?>
	<table class="table table-striped table-bordered table-hover" border="1" style="border-collapse: collapse" width="100%">
		<tr>
			<td width="80%" style="text-align: right">Total Retur</td>
			<td width="2%">:</td>
			<td style="text-align: right"><b><?=number_format($grand_total,2,'.',',')?></b></td>
		</tr>
	</table>
</div>
</body>
</html>
