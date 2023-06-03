<style type="text/css">    
.tabel{
    font-size:13px;
}
.tabel th{
    border-bottom: solid 1px black;
    border-top: solid 1px black;
    padding: 3px 3px;
}
td{
    padding: 3px 3px;
}
</style>
<title>Laporan Realisasi Pengadaan</title>
	</head>
	<body>
    <div align="center" style="margin: 3px 1px;">
		  	<span style="font-weight: bold; font-size: 12px;">RSUD IBNU SINA KABUPATEN GRESIK </span> <br / >
			<span style="font-weight: bold; font-size: 12px;">Jl. Dr. Wahidin SH No.243 B Gresik , GRESIK</span> <br / >
			<span style="font-weight: bold; font-size: 13px;">Laporan Realisasi Pengadaan </span>
		</div>
		<div align="center" style="margin: 3px 1px;">
		  	<span style="font-weight: bold; font-size: 13px;">Laporan Realisasi Pengadaan </span>
		</div>
		<div class="text-center" style="margin: 3px 5px;">
			<table width="100%" class="table" cellspacing="0" cellpadding="0" border="1" >
				<tr class="success" style="background-color: green;">
					<th class="text-center" style="font-size: 12px;" rowspan="2">No</th>
					<th class="text-center" style="font-size: 12px;" rowspan="2">Tanggal <br> PO</th>
					<th class="text-center" style="font-size: 12px;" rowspan="2">No. PO</th>
					<th class="text-center" style="font-size: 12px;" rowspan="2">Obat</th>
					<th class="text-center" style="font-size: 12px;" rowspan="2">Satuan</th>
					<th class="text-center" style="font-size: 12px;" colspan="3">Jumlah</th>
					<th class="text-center" style="font-size: 12px;" rowspan="2">Harga</th>
					<th class="text-center" style="font-size: 12px;" rowspan="2">Subtotal <br> PO</th>
					<th class="text-center" style="font-size: 12px;" rowspan="2">Subtotal <br> Terima</th>
				</tr>
				<tr class="success">
					<th class="text-center" style="font-size: 12px;">PO</th>
					<th class="text-center" style="font-size: 12px;">Terima</th>
					<th class="text-center" style="font-size: 12px;">Sisa</th>
				</tr>
				<?php 
					if(!empty($realisasi)) {
						$po_ppn = 0;
					foreach($realisasi as $index => $data) :
				?>
					<tr>
						<td class="text-right"  style="font-size: 12px;"><?php echo ($index+1) ?></td>
						<td class="text-center"  style="font-size: 12px;"><?php echo date("d-m-Y", strtotime($data->po_date)); ?></td>
						<td class="text-left" colspan="10"  style="font-size: 12px;"><?php echo $data->nama ?></td>
					</tr>
					<?php 
						$po_ppn = $data->po_ppn;
						if( !empty($data->detail) ){
							$subTotalPO 	= 0;
							$subTotalTerima = 0;
							foreach($data->detail as $detailPO):
					?>
						<tr>
							<td class="text-right"  style="font-size: 12px;"></td>
							<td class="text-left"   style="font-size: 12px;"></td>
							<td class="text-left" 	style="font-size: 12px;"><?php echo $detailPO->po_no?></td>
							<td class="text-left" 	style="font-size: 12px;"><?php echo $detailPO->item_name?></td>
							<td class="text-left"  	style="font-size: 12px;"><?php echo $detailPO->item_unitofitem?></td>
							<td class="text-right"  style="font-size: 12px;"><?php echo $detailPO->po_qtyunit?></td>
							<td class="text-right"  style="font-size: 12px;">
								<?php 
									$terima = ( $detailPO->po_qtyunit / $detailPO->po_qtypack ) * $detailPO->qtyreceive;
									echo $terima; 
								?>
							</td>
							<td class="text-right"  style="font-size: 12px;"><?php echo ($detailPO->po_qtyunit - $terima) ?></td>
							<td class="text-right"  style="font-size: 12px;"><?php echo convert_currency(round($detailPO->hargaitem,2))?></td>
							<td class="text-right"  style="font-size: 12px;">
								<?php 
									$subTotalPO = $subTotalPO + ( $detailPO->hargaitem * $detailPO->po_qtyunit  );
									echo convert_currency($detailPO->hargaitem * $detailPO->po_qtyunit); 
								?>
							</td>
							<td class="text-right"  style="font-size: 12px;">
								<?php
									$subTotalTerima = $subTotalTerima +  $detailPO->hargaitem * $terima;
									echo convert_currency($detailPO->hargaitem * $terima); 
								?>
							</td>
						</tr>
					<?php 
							endforeach;
						} 
					?>
					<tr style=" background-color: #EFEFEF;">
						<td class="text-right" colspan="9" style="font-size: 12px;"><strong>PPN (10%)</strong></td>
						<td class="text-right" style="font-size: 12px;"><strong><?= convert_currency(($po_ppn/100) * $subTotalPO) ?></strong></td>
						<td class="text-right" style="font-size: 12px;"><strong><?= convert_currency(($po_ppn/100) * $subTotalTerima) ?></strong></td>
					</tr>
					<tr style=" background-color: #EFEFEF;">
						<td class="text-right" colspan="9"  style="font-size: 12px;"><strong>Total</strong></td>
						<td class="text-right" style="font-size: 12px;"><strong><?php echo convert_currency($subTotalPO + (($po_ppn/100) * $subTotalPO)) ?></strong></td>
						<td class="text-right" style="font-size: 12px;"><strong><?php echo convert_currency($subTotalTerima + (($po_ppn/100) * $subTotalTerima)) ?></strong></td>
					</tr>
				<?php 
					endforeach;
					}
				?>
			</table>
		</div>
	</body>
</html>
<script></script>