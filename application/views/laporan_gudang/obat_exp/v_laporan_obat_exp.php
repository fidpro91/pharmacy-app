<style>
	table {
		font-size: 12px;
		font-family: Arial;
		word-spacing: 2px;
	}
	.tabel{
		border-collapse: collapse;
	}

	table.tabel , table.tabel  th, table.tabel td {
		border: 1px solid black;
		padding: 3px;
	}
	.foo {
		width: 10px;
		height: 10px;
		margin: 0 10px;
		border: 1px solid rgba(0, 0, 0, .2);
		display: inline;
	}
	table { page-break-inside:auto }
	tr    { page-break-inside:avoid; page-break-after:auto }

</style>
<div style="text-align: left;">
	<?php echo strtoupper($profil['nama']);?><br>
	<span style="font-size:12px;font-weight: normal;">
&nbsp;<?php echo ucfirst($profil['alamat']);?><br>
&nbsp;<?php echo ucfirst($profil['kota']);?><br>
&nbsp;Telp. <?php echo $profil['telp'];?>
</span>
</div>
<div align="center">
	<strong>
		LAPORAN OBAT EXPIRED<br>
		UNIT <?php $nama_unit->unit_name;?><br>
		<?php echo $judul;?>

		<br>&nbsp;<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabel">

			<tr>
				<th width="10%">NO </th>
				<th width="10%">TGL EXPIRED</th>
				<th width="10%">KODE OBAT</th>
				<th width="50%" >NAMA OBAT</th>
				<th width="10%">KEPEMILIKAN</th>
				<th width="10%">QTY</th>
			</tr>
			<?php
			$no=1;
			foreach ($expired as $data) {?>
				<tr>
					<th><?php echo $no;?></th>
					<th><?php echo $data->expired_date;?></th>
					<th align="left"><?php echo $data->item_code;?></th>
					<th align="left"><?php echo $data->item_name;?></th>
					<th align="left"><?php echo $data->own_name;?></th>
					<th ><?php echo $data->qty_stock;?></th>
				</tr>
				<?php
				$no++;
			}?>




		</table>



		<p align="right">Tgl Cetak <?php echo date('d-m-Y')." pk ".date('H:i:s');?><br>Yang mencetak,<br>&nbsp;<br>&nbsp;<br><?php echo ucfirst($username);?></p><br>
</div>
