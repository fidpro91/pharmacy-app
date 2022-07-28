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
	font-size:14px;
}

body {
	font-family: arial; font-size: 10px;
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

<h4>LAPORAN RETUR PENJUALAN  PER PASIEN</h4>
<h4>RSUD Ibnu Sina Kabupaten Gresik<br></h4>
<h5 style="font-size:12px"></h5>
<p style="margin-top:20px"></p>
<?php
$total_semua_retur=0;
foreach ($data as $res):
?>
<table class="tabel">
	<thead>
		<tr>
			<th align="left">PASIEN</th>
			<th>:</th>
			<th align="left"><?=strtoupper($res->patient_name)?></th>
			<th align="left">NAMA DOKTER</th>
			<th>:</th>
			<th align="left"><?=strtoupper($res->doctor_name)?></th>
			<th align="left">UNIT</th>
			<th>:</th>
			<th align="left"><?=strtoupper($res->unit_name)?></th>
		</tr>
	</thead>
	<tbody>
		<td style="border-left:0px; border-bottom:0px;"></td>
		<td colspan="9">
		<?php
			$detail_retur = json_decode($res->detail_retur);$jml_per_pasien=0;
			foreach($detail_retur as $rs_det_retur):
				$retur = explode('~', $rs_det_retur);
		?>
			<table class="tabel">
				<thead>
					<tr>
						<th>Tgl. Retur</th>
						<th>:</th>
						<th><?=($retur[1])?></th>
						<th>No. Retur</th>
						<th>:</th>
						<th><?=strtoupper($retur[0])?></th>
						<th>No. Penjualan</th>
						<th>:</th>
						<th><?=$retur[2]?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border-left:0px; border-bottom:0px;"></td>
						<td colspan="9">
							<table class="tabel">
								<thead>
									<tr>
										<th width="2%">No</th>
										<th width="10%">Kode Item</th>
										<th width="30%">Nama Item</th>
										<th width="10%">Harga</th>
										<th width="5%">Qty Retur</th>
										<th width="10%">Total Retur</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$no=1;$jml_retur=0;$total_retur=0;
										$detail_item = json_decode($retur[3]);
										foreach($detail_item as $res_item):
											$view_item = explode('|', $res_item);
									?>
									<tr>
										<td align="center"><?=$no?></td>
										<td align="left"><?=$view_item[0]?></td>
										<td align="left"><?=$view_item[1]?></td>
										<td align="right"><?=convert_currency($view_item[3])?></td>
										<td align="center"><?=$view_item[2]?></td>
										<td align="right"><?=convert_currency($view_item[4])?></td>
									</tr>
									<?php
										((int)$jml_retur += (int)$view_item[2]); ((int)$total_retur += (int)$view_item[4]);
										$no++;
										endforeach;
									?>
								</tbody>
								<tfoot>
									<tr>
										<td></td>
										<td colspan="3" align="right">Jumlah Total</td>
										<td align="center"><?=$jml_retur?></td>
										<td align="right"><?=convert_currency($total_retur)?></td>
										<?php
											$jml_per_pasien += $total_retur;
										?>
									</tr>
								</tfoot>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<p></p>
		<?php
			endforeach;
		?>
		<table class="tabel">
			<tr>
				<td colspan="8" style="border-top:0px;border-bottom:0px;border-left:0px;" width="60%"></td>
				<td align="right">Total Nilai Jumlah Retur</td>
				<td align="right"><?=convert_currency($jml_per_pasien)?></td>
			</tr>
		</table>
		</td>
	</tbody>
	<!-- <tfoot>
		<tr>
			<td></td>
			<td colspan="5"></td>
			<td colspan="3" align="right"><b>Jumlah Total Retur</b></td>
			<td align="right"><b><?=$jml_retur?></b></td>
			<td align="right"><b><?=convert_currency($total_retur)?></b></td>
		</tr>
	</tfoot> -->
</table>
<p></p>
<?php
$total_semua_retur += $jml_per_pasien;
endforeach;
?>
<table class="tabel">
	<tr>
		<td colspan="8" style="border-top:0px;border-bottom:0px;border-left:0px;" width="60%"></td>
		<td align="right"><b>Jumlah Total Retur</b></td>
		<td align="right"><b><?=convert_currency($total_semua_retur)?></b></td>
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