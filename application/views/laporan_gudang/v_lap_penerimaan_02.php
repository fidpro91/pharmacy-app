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
</style>
<!--<div id="div_tombol" align="left">-->
<!--	<button class="btn btn-default" id="btn-cetak">Cetak</button>-->
<!--	<a class="btn btn-default" href="#" id="btn-excel">Excel</a>-->
<!--</div>-->
<br />
<div class="text-center" style="margin: 3px;">
	<table  width="100%" id="example" style="text-align: center">
		<tr>
			<td>
				<div class="text-center">
					<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['nama'] ?> </span> <br / >
					<span style="font-weight: bold; font-size: 12px;"><?php echo $profil['alamat'].", ".$profil['kota']?></span> <br / >
					<span style="font-weight: bold; font-size: 12px;">Laporan Penerimaan Gudang </span> <br / >
					<span style="font-weight: bold; font-size: 12px;"><?php echo $periode?></span>
				</div>
				<br />
				<?php
				$grand_total=0;
				foreach($data as $res):
					?>
					<table class="table table-striped table-bordered table-hover" width="100%" style="border-collapse: collapse" border="1">
						<tr>
							<th class="text-center" style="font-size: 10px;" width="15%">Nama Supplier</th>
						</tr>
						<tr>
							<td class="text-left"   style="font-size: 10px;"><?php echo strtoupper($res->supplier_name)?></td>
						</tr>

						<tr>
							<td></td>
							<td colspan="4">
								<table class="table table-striped table-bordered table-hover" border="1" width="100%" style="border-collapse: collapse">
									<tr>
										<th class="text-center" style="font-size: 10px;">Tanggal Faktur</th>
										<th class="text-center" style="font-size: 10px;">No. Faktur</th>
										<th class="text-center" style="font-size: 10px;">No. Penerimaan</th>
									</tr>
									<?php
									$detail_item = json_decode($res->detail_item);
									foreach ($detail_item as $metu):
										$data_detail = explode('|', $metu);
										// echo $metu;
										/*$total += $data_detail[8];
										$total_disk += $data_detail[7];*/
										?>
										<tr>
											<td><?=$data_detail[2]?></td>
											<td><?=$data_detail[0]?></td>
											<td class="text-left"><?=$data_detail[1]?></td>
										</tr>
										<tr>
											<td></td>
											<td colspan="4">
												<table class="table table-striped table-bordered table-hover" border="1" width="100%" style="border-collapse: collapse">
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
													$detail_item_sm = json_decode($data_detail[3]); $num=1;$total=0;$total_disk=0;
													foreach ($detail_item_sm as $metu_sm):
														$data_detail_sm = explode('-*-', $metu_sm);
														// echo $metu;
														$total += $data_detail_sm[7];
														$total_disk += $data_detail_sm[6]/100*$data_detail_sm[7];
														?>
														<tr>
															<td><?=$num?></td>
															<td><?=$data_detail_sm[1]?></td>
															<td class="text-left"><?=$data_detail_sm[2]?></td>
															<td><?=$data_detail_sm[3]?></td>
															<td><?=$data_detail_sm[8]?></td>
															<td><?=$data_detail_sm[4]?></td>
															<td><?=number_format($data_detail_sm[5],2,",",",")?></td>
															<td><?=$data_detail_sm[6]?></td>
															<td><?=$data_detail[4]?></td>
															<td class="text-right"><?=number_format($data_detail_sm[7],2,",",",")?></td>
														</tr>
														<?php
														$num++;
													endforeach;
													?>
													<tr>
														<td colspan="7"></td>
														<td colspan="2" class="text-right">Total</td>
														<td class="text-right"><?=number_format($total,2,",",",")?></td>
													</tr>
													<tr>
														<td colspan="7"></td>
														<td colspan="2" class="text-right">Total Diskon</td>
														<td class="text-right"><?=number_format($total_disk,2,",",",")?></td>
													</tr>
													<tr>
														<td colspan="7"></td>
														<td colspan="2" class="text-right">Total PPN</td>
														<td class="text-right"><?php 
														// $ppn = $data_detail[4]/100*($data_detail[5]); 
														echo number_format($data_detail[6],2,",",",")?></td>
													</tr>
													<tr>
														<td colspan="7"></td>
														<td colspan="2" class="text-right">Total Pembelian</td>
														<td class="text-right"><?php 
														// $hasil= $total-($total_disk)+$ppn; 
														$grand_total += $data_detail[5]; echo number_format($data_detail[5],2,",",",");?></td>
													</tr>
												</table>
											</td>
										</tr>
										<?php
										$num++;
									endforeach;
									?>
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
						<td class="text-right" style="text-align: right"><b><?=number_format($grand_total,2,",",",")?></b></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
</body>
</html>
