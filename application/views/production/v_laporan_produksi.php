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

<h4>LAPORAN PRODUKSI FARMASI</h4>
<h4>RSUD Ibnu Sina Kabupaten Gresik<br></h4>

<p style="margin-top:20px"></p>
<?php
$total_all=0;
foreach($data as $rs_data) :
?>
<p style="margin-top:10px"></p>
<table class="tabel">
	<tr>
		<td width="10%"><b>Tanggal : </b></td>
		<td width="20%"><b><?=$rs_data->tgl_produksi?></b></td>
		<td width="5%"><b>No Produksi : </b></td>
		<td width="40%"><b><?=$rs_data->production_no?></b></td>
		<td width="5%"><b>Kepemilikan : </b></td>
		<td width="10%"><b><?=$rs_data->own_name?></b></td>
	</tr>
	<tr>
		<td style="border-left:0px; border-bottom:0px;"></td>
		<td colspan="4">
			<table class="tabel">
				<thead>
					<tr>
						<th colspan="3" align="left">BAHAN PRODUKSI</th>
						<th colspan="3" style="border-top:0px; border-right:0px;"></th>
					</tr>
					<tr>
						<th >No</th>
						<th >Kode Item</th>
						<th >Nama Item</th>
						<th >Harga</th>
						<th >Qty</th>
						<th >Sub Total</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$no=1;$jml_total=0;
						$detail_bahan = json_decode($rs_data->bahan);
						foreach ( $detail_bahan as $rest) {
							$res = explode('|', $rest);
							$jml_total += ($res[2]*$res[3]);
							echo "<tr>
									<td align='center'>$no</td>
									<td>".$res[0]."</td>
									<td>".strtoupper($res[1])."</td>
									<td align='left'>".strtoupper($res[3])."</td>
									<td align='right'>".($res[2])."</td>
									<td align='right'>".($res[2]*$res[3])."</td>
							</tr>";
							$no++;
						}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" style="border-left:0px;border-bottom:0px;"></td>
						<td colspan="2"><b>Total</b></td>
						<td align="right"><?=($jml_total)?></td>
					</tr>
				</tfoot>
			</table>
			<p></p>
			<table class="tabel">
				<thead>
					<tr>
						<th colspan="3" align="left">HASIL PRODUKSI</th>
						<th colspan="3" style="border-top:0px; border-right:0px;"></th>
					</tr>
					<tr>
						<th >No</th>
						<th >Kode Item</th>
						<th >Nama Item</th>
						<th >Harga</th>
						<th >Qty</th>
						<th >Sub Total</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$no=1;$jml_total_hasil=0;
						$detail_hasil = json_decode($rs_data->hasil);
						foreach ( $detail_hasil as $rest) {
							$res = explode('|', $rest);
							$jml_total_hasil += ($res[2]*$res[3]);
							echo "<tr>
									<td align='center'>$no</td>
									<td>".$res[0]."</td>
									<td>".strtoupper($res[1])."</td>
									<td align='left'>".strtoupper($res[3])."</td>
									<td align='right'>".($res[2])."</td>
									<td align='right'>".($res[2]*$res[3])."</td>
							</tr>";
							$no++;
						}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" style="border-left:0px;border-bottom:0px;"></td>
						<td colspan="2"><b>Total</b></td>
						<td align="right"><?=($jml_total_hasil)?></td>
					</tr>
				</tfoot>
			</table>
		</td>
		<td style="border-right:0px; border-bottom:0px;"></td>
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