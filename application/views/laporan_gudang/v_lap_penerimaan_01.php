<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
//echo $profil['alamat'].", ".$profil['kota'];$periode
//if ($tombol == '2') {
//	header("Content-type: application/vnd-ms-excel");
//	header("Content-Disposition: attachment; filename=laporan_penerimaan_gudang.xls");
//}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
</style>
<!--<div id="div_tombol" align="left">-->
<!--	<button class="btn btn-default" id="btn-cetak">Cetak</button>-->
<!--	<a class="btn btn-default" href="#" id="btn-excel">Excel</a>-->
<!--</div>-->
<div class="text-center" style="margin: 3px;">
	<table width="100%" id="example" style="text-align: center">
		<tr>
			<td>
				<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['nama'] ?> </span> <br / >
				<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['alamat'].", ".$profil['kota']?></span> <br / >
				<span style="font-weight: bold; font-size: 12px;">Laporan Penerimaan Gudang </span> <br / >
				<span style="font-weight: bold; font-size: 12px;"><?php echo $periode?></span>
			</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td>
				<?php
				$grand_total=0;
				foreach($data as $res):
					?>
					<table class="table table-striped table-bordered table-hover" border="1" width="100%" style="border-collapse: collapse">
						<tr>
							<th class="text-center" style="font-size: 10px; text-align: center" width="10%">Nama Supplier</th>
							<th class="text-center" style="font-size: 10px; text-align: center" width="5%">Tanggal Faktur</th>
							<th class="text-center" style="font-size: 10px; text-align: center" width="20%">No. Faktur</th>
							<th class="text-center" style="font-size: 10px; text-align: center" width="20%">No. Penerimaan</th>
						</tr>
						<tr>
							<td class="text-center"   style="font-size: 10px; text-align: center" width="10%"><?php echo strtoupper($res->supplier_name)?></td>
							<td class="text-center" 	style="font-size: 10px; text-align: center" width="5%"><?php
								$tgl = ($res->rec_date)?date('d-m-Y',strtotime($res->rec_date)):'';
								echo $tgl;?></td>
							<td class="text-center" 	style="font-size: 10px; text-align: center" width="20%"><?php echo $res->rec_num?></td>
							<td class="text-center" 	style="font-size: 10px; text-align: center" width="20%"><?php echo $res->receiver_num?></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="4">
								<table class="table table-striped table-bordered table-hover" width="100%" border="1" style="border-collapse: collapse">
									<tr>
										<th class="text-center" style="font-size: 10px;" rowspan="2">No</th>
										<th class="text-center" style="font-size: 10px;" rowspan="2">Kode Produk</th>
										<th class="text-center" style="font-size: 10px;" rowspan="2">Nama Produk</th>
										<th class="text-center" style="font-size: 10px;" colspan="3">Jumlah</th>
										<th class="text-center" style="font-size: 10px;" rowspan="2">Harga</th>
										<th class="text-center" style="font-size: 10px;" rowspan="2">Disk(%)</th>
										<th class="text-center" style="font-size: 10px;" rowspan="2">PPN(%)</th>
										<th class="text-center" style="font-size: 10px;" rowspan="2">Subtotal</th>
									</tr>
									<tr>
										<th class="text-center" style="font-size: 10px;">Pack</th>
										<th class="text-center" style="font-size: 10px;">Per Pack</th>
										<th class="text-center" style="font-size: 10px;">Item</th>
									</tr>
									<?php

									$detail_item = json_decode($res->detail_item); $num=1;$total=0;$total_disk=0;

									foreach ($detail_item as $metu):
										$data_detail = explode('|', $metu);
//										 echo $metu;
										$total += $data_detail[8];
										$total_disk += !empty($data_detail[7])?$data_detail[7]:0;
										?>
										<tr>
											<td><?=$num?></td>
											<td><?=$data_detail[0]?></td>
											<td class="text-left"><?=$data_detail[1]?></td>
											<td><?=$data_detail[3]?></td>
											<td><?=$data_detail[4]?></td>
											<td><?=$data_detail[2]?></td>
											<td><?=number_format($data_detail[5],2,".",",")?></td>
											<td><?=$data_detail[6]?></td>
											<td><?=$res->po_ppn?></td>
											<td class="text-right"><?=number_format($data_detail[8],2,",",".")?></td>
										</tr>
										<?php
										$num++;
									endforeach;
									?>
									<tr>
										<td colspan="7"></td>
										<td colspan="2" class="text-right">Total</td>
										<td class="text-right"><?=number_format($total,2,",",".")?></td>
									</tr>
									<tr>
										<td colspan="7"></td>
										<td colspan="2" class="text-right">Total Diskon</td>
										<td class="text-right"><?=number_format($total_disk,2,",",".")?></td>
									</tr>
									<tr>
										<td colspan="7"></td>
										<td colspan="2" class="text-right">Total PPN</td>
										<td class="text-right"><?php $ppn = $res->po_ppn/100*($total-$total_disk); echo number_format($ppn,2,",",".")?></td>
									</tr>
									<tr>
										<td colspan="7"></td>
										<td colspan="2" class="text-right">Total Pembelian</td>
										<td class="text-right"><?php $hasil= $total-$total_disk+$ppn; $grand_total += $hasil; echo number_format($hasil,2,",",".");
											?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				<?php
				endforeach;
				?>
				<table class="table table-striped table-bordered table-hover" width="100%" border="1" style="border-collapse: collapse">
					<tr>
						<td width="80%" class="text-right" style="text-align: right">Total Transaksi Pembelian</td>
						<td width="2%">:</td>
						<td class="text-right" style="text-align: right"><b>
								<?=number_format($grand_total,2,",",".")?></b></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
</body>
</html>
