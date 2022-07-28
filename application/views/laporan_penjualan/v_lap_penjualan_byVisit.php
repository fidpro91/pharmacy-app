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

<h4>LAPORAN PENJUALAN PER PASIEN</h4>
<h4>RSUD Ibnu Sina Kabupaten Gresik<br></h4>
<h5 style="font-size:12px"></h5>
<p style="margin-top:20px"></p>

<table class="tabel">
	<thead>
		<tr>
			<th >Tanggal Jam</th>
			<th >No. Transaksi</th>
			<th >Nama Pasien</th>
			<th >Nama Dokter</th>
			<th >Total</th>
			<th >Tunai</th>
			<th >Piutang</th>
			<th >Terbayar</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no=1;$jml_total=0;$embalase=0;
			foreach ($data as $res):
			$jml_total += $res['sale_total']-$res['sr_total'];
			$embalase = $res['embalase_item_sale'];
		?>
		<tr>
			<th><?=$res['tgl_sale']?></th>
			<th><?=$res['nomor_resep']?></th>
			<th><?=strtoupper($res['px_name'])?></th>
			<th><?=strtoupper($res['par_name'])?></th>
			<th><?=convert_currency($res['sale_total']-$res['sr_total'])?></th>
			<th>0</th>
			<th><?=convert_currency($res['sale_total']-$res['sr_total'])?></th>
			<th>0</th>
		</tr>
		<tr>
			<td></td>
			<td colspan="5">
				<table class="tabel">
					<tr>
						<th rowspan="2">No</th>
						<th rowspan="2">Kode Produk</th>
						<th rowspan="2">Nama Produk</th>
						<th rowspan="2">Satuan</th>
						<th colspan="2">Jumlah</th>
						<th rowspan="2">Harga</th>
						<th rowspan="2">Total Retur</th>
						<th rowspan="2">Subtotal</th>
					</tr>
					<tr>
						<th>Terjual</th>
						<th>Retur</th>
					</tr>
					<?php
						$no=1;$non_racikan=$racikan=0;
						foreach ($res['detail_sale'] as $res_det):
							if($res_det->racikan_id){
								$racikan++;
							}else{
								$non_racikan++;
							}
					?>
						<tr>
							<td><?=$no?></td>
							<td><?=$res_det->item_code?></td>
							<td><?=strtoupper($res_det->item_name)?></td>
							<td><?=strtoupper($res_det->satuan)?></td>
							<td><?=($res_det->sale_qty)?></td>
							<td><?=($res_det->qty_return)?></td>
							<td><?=convert_currency($res_det->harga)?></td>
							<td><?=convert_currency($res_det->total_return)?></td>
							<td><?=convert_currency($res_det->subtotal-$res_det->total_return)?></td>
						</tr>
					<?php
						$no++;
						// $jml_total += $res['embalase_item_sale'];
						endforeach;
					?>
					<tr>
						<td></td>
						<td colspan="3">Embalase Item</td>
						<td><?=$non_racikan?></td>
						<td></td>
						<td><?=convert_currency(($non_racikan!=0)?($res['embalase_item_sale']/$non_racikan):0)?></td>
						<td></td>
						<td><?=convert_currency($res['embalase_item_sale'])?></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="3">Embalase Racikan</td>
						<td><?=$racikan?></td>
						<td></td>
						<td><?=convert_currency(($racikan!=0)?($res['sale_service']/$racikan):0)?></td>
						<td></td>
						<td><?=convert_currency($res['sale_service'])?></td>
					</tr>
				</table>
			</td>
			<td colspan="2" ></td>
		</tr>
		<tr>
			<td colspan="8"></td>
		</tr>
		<?php
			endforeach;
		?>
	</tbody>
	<tfoot>
		<th colspan="6" align="right" style="border-top:1px">Jumlah keseluruhan</th>
		<th><?=convert_currency($jml_total)?></th>
		<th>0</th>
	</tfoot>
</table>
<br>
<table>
	<tr>
		<td> Tanggal / Jam Cetak</td>
		<td>:</td>
		<td><?=date('d/m/Y || H:i:s')?></td>
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