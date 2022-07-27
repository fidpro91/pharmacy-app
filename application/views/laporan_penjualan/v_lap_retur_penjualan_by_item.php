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

@page {
    size: A4 potrait;
    margin: 2%;
}
</style>

<h4>LAPORAN RETUR PENJUALAN PER ITEM</h4>
<h4>RSUD Ibnu Sina Kabupaten Gresik<br></h4>
<h5 style="font-size:12px"></h5>
<p style="margin-top:20px"></p>
<?php
	foreach($data as $rs_retur):
?>
<table class="tabel">
	<thead>
		<tr>
			<th align="left">Kode Item</th>
			<th>:</th>
			<th align="left"><?=($rs_retur->item_code)?></th>
			<th align="left">Nama Item</th>
			<th>:</th>
			<th align="left"><?=strtoupper($rs_retur->item_name)?></th>
			<th colspan="3" width="40%" style="border-top:0px;border-right:0px;"></th>
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
							<th width="10%">Tgl. Retur</th>
							<th width="10%">No.Retur</th>
							<th width="20%">Nama Pasien</th>
							<th width="20%">Unit Layanan</th>
							<th width="20%">Dokter</th>
							<th width="10%">Qty Retur</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$no=1;$jml_retur=0;$total_retur=0;
							$detail_pas = json_decode($rs_retur->retur_detail);
							foreach($detail_pas as $res_pas):
								$view_pas = explode('|', $res_pas);
						?>
						<tr>
							<td align="center"><?=$no?></td>
							<td align="left"><?=$view_pas[1]?></td>
							<td align="left"><?=$view_pas[0]?></td>
							<td align="left"><?=($view_pas[2])?></td>
							<td align="left"><?=$view_pas[5]?></td>
							<td align="left"><?=($view_pas[3])?></td>
							<td align="center"><?=($view_pas[6])?></td>
						</tr>
						<?php
							$no++;
							$jml_retur += $view_pas[6];
							endforeach;
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5"></td>
							<td align="right">Jumlah Total</td>
							<td align="center"><?=$jml_retur?></td>
							<!-- <td align="right"><?=convert_currency($total_retur)?></td>
							<?php
								$jml_per_pasien += $total_retur;
							?> -->
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
<!-- <table class="tabel">
	<tr>
		<td colspan="8" style="border-top:0px;border-bottom:0px;border-left:0px;" width="60%"></td>
		<td align="right">Total Nilai Jumlah Retur</td>
		<td align="right"><?=convert_currency($jml_per_pasien)?></td>
	</tr>
</table> -->
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