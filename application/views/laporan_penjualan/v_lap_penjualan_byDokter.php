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

<h4>LAPORAN PENJUALAN JUMLAH RESEP OBAT DOKTER</h4>
<h4>RSUD Ibnu Sina Kabupaten Gresik<br></h4>
<p style="margin-top:20px"></p>
<table class="tabel">
	<thead>
		<tr>
			<th >No</th>
			<th >Kode Dokter</th>
			<th >Nama Dokter</th>
			<th >Jumlah Resep</th>
			<th >Nilai Rupiah</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no=1;$jml_resep=0;
			foreach ($data as $res) {
				$jml_resep += $res->total_resep;
				echo "<tr>
						<td align='center'>$no</td>
						<td>$res->code_doctor</td>
						<td>".strtoupper($res->dokter)."</td>
						<td align='right'>$res->total_resep Lembar</td>
						<td align='right'>".($res->grand_total)."</td>
				</tr>";
				$no++;
			}
		?>
	<tfoot>
		<tr>
			<td></td>
			<td colspan="2"><b>Jumlah Lembar R/ Dokter</b></td>
			<td align="right"><b><?=$jml_resep.' Lembar'?></b></td>
			<td class="center"></td>
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