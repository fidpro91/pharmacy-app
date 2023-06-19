<style>
	table {
		font-size: 12px;
		font-family: Arial;
		word-spacing: 2px;
		page-break-inside:auto;
	}

	.tabel {
		border-collapse: collapse;
	}

	table.tabel , table.tabel  th, table.tabel td {
		border: 1px solid black;
		padding: 3px;
	}

	tr	{ page-break-inside:avoid; page-break-after:auto }

	.tabel th {
		background-color: #AAE97C;
	}
</style>
<div style="text-align: left;">
	<?php echo strtoupper($rs['nama']);?><br>
	<span style="font-size:12px;font-weight: normal;">
		<?php echo ucfirst($rs['nama']);?><br>
		<?php echo ucfirst($rs['kota']);?><br>
		Telp. <?php echo $rs['telp'];?>
	</span>
</div>
<div align="center">
	<strong>
		LAPORAN SLOW/FAST MOVING<br>
		UNIT <?php echo $nama_unit->unit_name;?><br>
		<?php echo $judul;?>
		<br>&nbsp;<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabel">
			<tr>
				<th width="5%">NO</th>
				<th width="10%">KODE OBAT</th>
				<th width="40%" >NAMA OBAT</th>
				<th width="10%">KEPEMILIKAN</th>
				<th width="10%">QTY</th>
			</tr>
			<?php
			$no=1;
			foreach ($slowfast as $data) {
				?>
				<tr>
					<td align="center"><?php echo $no;?>.</td>
					<td align="center"><?php echo $data->item_code;?></td>
					<td align="left"><?php echo $data->item_name;?></td>
					<td align="center"><?php echo $data->own_name;?></td>
					<td align="right"><?php echo $data->qty;?></td>
				</tr>
				<?php
				$no++;
			}
			?>
		</table>
		<p align="right">
			Tgl Cetak <?php echo date('d-m-Y')." pk ".date('H:i:s');?><br />
			Yang mencetak,<br />&nbsp;<br />&nbsp;<br />
			<?php echo ucfirst($username);?>
		</p><br />
</div>
