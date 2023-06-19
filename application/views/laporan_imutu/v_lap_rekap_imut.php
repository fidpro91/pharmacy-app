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
<table id="table_excel" width="100%">
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td width="5%">
						<img src="http://192.168.1.245/ehos//berkas/logo-rsud-ibnu-sina-gresik.png" width="100" height="80">
					</td>
					<td width="45%" style="text-align:left;">
						<h4 style="text-align: left;">
							RUMAH SAKIT UMUM DAERAH IBNU SINA<br>
							KABUPATEN GRESIK<br>
						</h4>
						<u style="font-size: 12px"><?=$rs->hsp_address.' Telp '.$rs->hsp_phone.' Fax. '.$rs->hsp_fax?></u>
					</td>
					<td width="45%" style="text-align:right;font-size: 10px;">
						FM-437.76.81-44<br>
						Revisi : 00
					</td>
				</tr>
			</table>
			<p></p>
			<center>
				<h4>LAPORAN KEPATUHAN PENGGUNAAN FORNAS</h4>
				<h4>RSUD IBNU SINA KABUPATEN GRESIK</h4>
				<h5 style="font-size:12px">MINGGU.......BULAN......TAHUN.......</h5>
				<p style="margin-top:20px"></p>
			</center>
			<table class="tabel">
				<thead>
				<tr>
					<th width="2%" rowspan="2">NO</th>
					<th width="13%" rowspan="2">DEPO</th>
					<th width="20%" colspan="2">JUMLAH</th>
					<th width="13%" rowspan="2">KETERANGAN</th>
				</tr>
				<tr>
					<th>ITEM OBAT YANG<br>DITERAPIKAN</th>
					<th>ITEM OBAT YANG DIERAPIKAN<br>SESUAI FORNAS</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$jml_1=$jml_2=0;
				foreach ($data as $key => $value) {
					echo "<tr>
									<td align=\"center\">".($key+1)."</td>
									<td>$value->apotek</td>
									<td align=\"center\">$value->jml_item</td>
									<td align=\"center\">$value->fornas</td>
									<td align=\"center\"></td>
							</tr>";
					$jml_1 += $value->jml_item;
					$jml_2 += $value->fornas;
				}
				?>

				</tbody>
				<tfoot>
				<tr>
					<td colspan="2"><b>TOTAL</b></td>
					<td align="center"><?=$jml_1?></td>
					<td align="center"><?=$jml_2?></td>
					<td align="center"></td>
				</tr>
				</tfoot>
			</table>
			<p></p>
</table>
<table width="100%" style="font-size: 12px">
	<tr>
		<td align="center" width="50%">
			Verifikator,
			<br><br><br><br>
			...............
		</td>
		<td align="center" width="50%">
			Gresik, <?=date('d-m-Y')?><br>
			Penanggung Jawab(PIC) Data Instalasi Farmasi
			<br><br><br><br>
			...............
		</td>
	</tr>
</table>
</td>
</tr>
</table>
<div align="center" id="group-aksi">
	<button onclick="cetak()">Cetak</button>
	<button onclick="tutup()">tutup</button>
</div>
<script src="<?php echo base_url()?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/global/plugins/tabletoexcel/excelexportjs.js" type="text/javascript"></script>
<?php
$tombol = $this->input->post('tombol');
if ($tombol == 2) {
	?>
	<script type="text/javascript">
		function exportGrid() {
			$('#tabel').attr('border',1);
			var uri = $("#table_excel").excelexportjs({
				containerid: "table_excel"
				, datatype: 'table'
				, returnUri: true
			});
			var a = document.createElement('a');
			a.id = 'tempLink';
			a.href = uri;
			a.download ="LAPORAN KEPATUHAN PENGGUNAAN FORNAS.xls";
			document.body.appendChild(a);
			a.click(); // Downloads the excel document
			document.getElementById('tempLink').remove();
			$(this).blur(function(){
				window.close();
			})
		}
		$(document).ready(function() {
			$('#group-aksi').hide();
			setTimeout(function(){
				exportGrid();
			},100);
		});
	</script>
	<?php
}
?>
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
