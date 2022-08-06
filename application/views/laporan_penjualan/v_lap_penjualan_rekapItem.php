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

<h4>LAPORAN PENJUALAN KHUSUS RACIKAN</h4>
<h4>RSUD Ibnu Sina Kabupaten Gresik<br></h4>
<h5 style="font-size:12px"></h5>
<p style="margin-top:20px"></p>
<table class="tabel">
	<thead>
		<tr>
			<th >No</th>
			<th >Kode Obat</th>
			<th >Nama Obat</th>
			<th >Qty</th>
			<th >Total</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$no=1;$jml_total=$jml_qty=0;
			foreach ($data as $r) {
				echo "<tr>
					<td>$no</td>
					<td>".$r->item_code."</td>
					<td>".$r->item_name."</td>
					<td align='center'>".$r->sale_qty."</td>
					<td align='right'>".convert_currency($r->total_harga_obat)."</td>
				</tr>";
				$jml_total += $r->total_harga_obat;
				$jml_qty += $r->sale_qty;
			$no++;
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td align="center"><?=$jml_qty?></td>
			<td align="right"><?=convert_currency($jml_total)?></td>
		</tr>
	</tfoot>
</table>