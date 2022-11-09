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

<h4>LAPORAN RESPOND TIME PENJUALAN <?=strtoupper($unit)?></h4>
<h4>RSUD Ibnu Sina Kabupaten Gresik<br><?=$judul?></h4>
<p style="margin-top:20px"></p>
<table class="tabel">
	<thead>
	<tr>
		<th >NO</th>
		<th >NORM</th>
		<th >NAMA PASIEN</th>
		<th >UNIT LAYANAN</th>
		<!-- <th >DAFTAR</th> -->
		<th >DILAYANI</th>
		<th >PEMBAYARAN <br> OBAT</th>
		<th >OBAT DIBERIKAN</th>
		<th >DURASI(MENIT) <br> (DILAYANI - OBAT DIBERIKAN) </th>
	</tr>
	</thead>
	<tbody>
	<?php
	$no=1;
	foreach ($data as $res) {
		echo "<tr>
						<td align='center'>$no</td>
						<td>$res->px_norm</td>
						<td>".strtoupper($res->px_name)."</td>
						<td>".strtoupper($res->unit_layanan)."</td>
						<!-- <td align='center'>$res->jam_datang</td> -->
						<td align='center'>$res->jam_dilayani</td>
						<td align='center'>$res->jam_bayar</td>
						<td align='center'>$res->jam_selesai</td>
						<td align='center'>$res->respond_time</td>
				</tr>";
		$no++;
	}
	?>
	<!-- <tfoot>
		<tr>
			<td></td>
			<td colspan="5"></td>
			<td colspan="3" align="right"><b>Jumlah Total Retur</b></td>
			<td align="right"><b><?=$jml_retur?></b></td>
			<td align="right"><b><?=$this->money->currency($total_retur)?></b></td>
		</tr>
	</tfoot> -->
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
