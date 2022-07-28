<style type="text/css">
.tabel{
  border-collapse:collapse;
  width:100%;}
.tabel th{
  color:#000;
  border:#000000 solid 1px;
  padding:3px;
  font-size: 11px;
}
.tabel td{
  border:#000000 solid 1px;
  padding:3px;
  font-size: 10px;
}
h4,h5 {
	margin: 0px;
	padding: 0px;
	text-align: center;
}

h4 {
	font-size: 14px;
}
body {
	font-family: arial;
	font-size: 10px;
}
.center{
	text-align: center;
}
/* style sheet for "A4" printing */
/*@media print and (width: 21cm) and (height: 29.7cm) {
     @page {
        margin: 3cm;
     }
}*/

/* style sheet for "letter" printing */
/*@media print and (width: 8.5in) and (height: 11in) {
    @page {
        margin: 1in;
    }
}*/

/* A4 Landscape*/
@page {
    size: A4 potrait;
    margin: 2%;;
}
</style>

<h4>LAPORAN PENJUALAN (Summary Customer)</h4>
<h4>RSUD Ibnu Sina Kabupaten Gresik<br></h4>
<h5 style="font-size:12px"></h5>
<p style="margin-top:20px"></p>
<?php
$total_all=0;
foreach($data as $rs_data) :
?>
<p style="margin-top:10px"></p>
<table class="tabel">
	<tr>
		<td width="5%"><b>NORM</b></td>
		<td width="1%" align="center">:</td>
		<td width="10%"><b><?=$rs_data->px_norm?></b></td>
		<td width="5%"><b>Nama Pasien</b></td>
		<td width="1%" align="center">:</td>
		<td width="20%"><b><?=strtoupper($rs_data->px_name)?></b></td>
	</tr>
	<tr>
		<td colspan="6">
			<table class="tabel">
				<thead>
					<tr>
						<th rowspan="2">No</th>
						<th rowspan="2">Kode Produk</th>
						<th rowspan="2">Nama Produk</th>
						<th colspan="2">Jumlah</th>
						<th rowspan="2">Harga</th>
						<th rowspan="2">Total Retur</th>
						<th rowspan="2">Sub Total</th>
					</tr>
					<tr>
						<th>Terjual</th>
						<th>Retur</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$no=1;$jml_total=0;$non_racikan=$racikan=0;
						$detail_jual = json_decode($rs_data->detail_jual);
						foreach ( $detail_jual as $rest) {
							$res = explode('|', $rest);
							$jml_total += ((int)$res[4]- (int)$res[5]); //((int)$item['quantity'] * (int)$product['price']);
							if($res[7]){
								$racikan++;
							}else{
								$non_racikan++;
							}
							echo "<tr>
									<td align='center'>$no</td>
									<td>".$res[0]."</td>
									<td>".strtoupper($res[1])."</td>
									<td align='center'>".$res[2]."</td>
									<td align='center'>".$res[6]."</td>
									<td align='right'>".convert_currency($res[3])."</td>
									<td align='right'>".convert_currency($res[5])."</td>
									<td align='right'>".convert_currency((int)$res[4]-(int)$res[5])."</td>
							</tr>";
							$no++;
						}
					?>
					<tr>
						<td align="center"><?=$no?></td>
						<td>-</td>
						<td>Embalase</td>
						<td align='center'><?=$non_racikan?></td>
						<td></td>
						<td align='right'><?php $embalase=0; $embalase=($non_racikan!=0)?($rs_data->embalase_item_sale/$non_racikan):0; echo convert_currency($embalase); ?></td>
						<td></td>
						<td align='right'><?php echo convert_currency($rs_data->embalase_item_sale);?></td>
					</tr>
					<tr>
						<td align="center"><?=$no+1?></td>
						<td>-</td>
						<td>Embalase Racikan</td>
						<td align='center'><?=$racikan?></td>
						<td></td>
						<td align='right'><?php $embalase=$rs_data->sale_services; $embalase=($racikan!=0)?($rs_data->sale_services/$racikan):0; echo convert_currency($embalase); ?></td>
						<td></td>
						<td align='right'><?php echo convert_currency($rs_data->sale_services);?></td>
					</tr>
				<tfoot>
					<tr>
						<td></td>
						<td colspan="6"><b>Total</b></td>
						<td align="right"><?=convert_currency($jml_total+$rs_data->embalase_item_sale+$rs_data->sale_services)?></td>
					</tr>
				</tfoot>
				</tbody>
			</table>
		</td>
	</tr>
</table>
<?php
	$total_all += $jml_total+$rs_data->embalase_item_sale+$rs_data->sale_services;
	endforeach;
?>
<br>
<table>
	<tr>
		<td width="20%"> Tanggal / Jam Cetak</td>
		<td width="2%">:</td>
		<td width="60%"><?=date('d/m/Y || H:i:s')?></td>
		<td width="10%">Total Penjualan</td>
		<td width="2%">:</td>
		<td><?=convert_currency($total_all)?></td>
	</tr>
</table>
<div align="center" id="group-aksi">
	<button onclick="cetak()">Cetak</button>
	<button onclick="tutup()">tutup</button>
</div>


<script type="text/javascript">
function cetak() {
	document.getElementById('group-aksi').remove();
	window.print();
	window.close();
}

function tutup() {
	window.close();
}
</script>