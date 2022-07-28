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

<h4>LAPORAN PENJUALAN KHUSUS</h4>
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
		<td width="10%" colspan="2"><b>Nama Item : </b></td>
		<td width="20%" colspan="4"><b><?=$rs_data->item_name?></b></td>
		<td colspan="2" style="border-top:0px; border-right:0px;"></td>
	</tr>
	<tr>
		<td colspan="8">
			<table class="tabel">
				<thead>
					<tr>
						<th >No</th>
						<th >Tanggal Jam</th>
						<th >Nama Pasien</th>
						<th >Alamat Pasien</th>
						<th >Qty</th>
						<th >Retur</th>
						<th >Harga</th>
						<th >Sub Total</th>
						<th >Dokter</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$no=1;$jml_total=0;
						$detail_jual = json_decode($rs_data->detail_sale);
						$jml_qty=0;
						$jml_retur=0;
						foreach ( $detail_jual as $rest) {
							$res = explode('||', $rest);
							$jml_total += $res[6];
							$jml_qty += $res[3];
							$jml_retur += $res[7];
							echo "<tr>
									<td align='center'>$no</td>
									<td>".$res[0]."</td>
									<td>".strtoupper($res[1])."</td>
									<td align='left'>".strtoupper($res[2])."</td>
									<td align='center'>".$res[3]."</td>
									<td align='center'>".$res[7]."</td>
									<td align='right'>".convert_currency($res[4])."</td>
									<td align='right'>".convert_currency($res[6])."</td>
									<td align='center'>".$res[5]."</td>
							</tr>";
							$no++;
						}
					?>
				<tfoot>
					<tr>
						<td colspan="3" style="border-left:0px;border-bottom:0px;"></td>
						<td><b>Total</b></td>
						<td align="center"><?=$jml_qty?></td>
						<td align="center"><?=$jml_retur?></td>
						<td></td>
						<td align="right"><?=convert_currency($jml_total)?></td>
					</tr>
				</tfoot>
				</tbody>
			</table>
		</td>
	</tr>
</table>
<?php
	$total_all += $jml_total;
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