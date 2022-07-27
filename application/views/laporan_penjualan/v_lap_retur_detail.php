<style type="text/css">
.tabel{
  border-collapse:collapse;
  width:100%;}
.tabel th{
  color:#000;
  border:#000000 solid 1px;
  padding:3px;
  font-size: 14px;
}
.tabel td{
  border:#000000 solid 1px;
  padding:3px;
  font-size: 12px;
}
h4,h5 {
	margin: 0px;
	padding: 0px;
	text-align: center;
}

body {
	font-family: arial;
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
    margin: 2%;
}
</style>

<h4>LAPORAN DETAIL RETUR PENJUALAN </h4>
<h4>RSUD Ibnu Sina Kabupaten Gresik<br></h4>
<p style="margin-top:20px"></p>
<table class="tabel">
	<thead>
		<tr>
			<th >No</th>
			<th >Tanggal Retur</th>
			<th >Nama Pasien</th>
			<th >Nama Dokter</th>
			<th >No. Penjualan</th>
			<th >No. Retur</th>
			<th >Kode Obat</th>
			<th >Nama Obat</th>
			<th >Harga</th>
			<th >Jumlah Retur</th>
			<th >Total Retur</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no=1;$jml_retur=0;$total_retur=0;
			foreach ($data as $res) {
				$total_retur 	+= $res->total_return;
				$jml_retur 		+= $res->qty_return;
				echo "<tr>
						<td align='center'>$no</td>
						<td>$res->tgl_retur</td>
						<td>".strtoupper($res->patient_name)."</td>
						<td>".strtoupper($res->doctor_name)."</td>
						<td align='right'>$res->sale_num</td>
						<td align='right'>$res->sr_num</td>
						<td align='right'>$res->item_code</td>
						<td align='right'>".strtoupper($res->item_name)."</td>
						<td align='right'>".convert_currency($res->sale_price)."</td>
						<td align='right'>$res->qty_return</td>
						<td align='right'>".convert_currency($res->total_return)."</td>
				</tr>";
				$no++;
			}
		?>
	<tfoot>
		<tr>
			<td></td>
			<td colspan="5"></td>
			<td colspan="3" align="right"><b>Jumlah Total Retur</b></td>
			<td align="right"><b><?=$jml_retur?></b></td>
			<td align="right"><b><?=convert_currency($total_retur)?></b></td>
		</tr>
	</tfoot>
	</tbody>
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