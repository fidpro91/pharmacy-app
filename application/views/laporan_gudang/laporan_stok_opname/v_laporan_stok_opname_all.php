<?php
//if ($act == 'excel') {
//	header("Content-type: application/vnd-ms-excel");
//	header("Content-Disposition: attachment; filename=laporan_stockOpname_".$unit_name."(".$judul.").xls");
//}
?>
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
<div align="center">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr height="50">
			<td width="30%" style="border-top:1px solid; border-left:1px solid; border-bottom:1px solid;">
				<table cellspacing="0" cellpadding="0" border="0">
					<tbody>
					<tr>
						<td width="30%" algin="center"><!--<img src = "" alt="logo" title="logo">--></td>
						<td width="70%" style="vertical-align: top;font-size: 14px;font-weight: bold;"><?php echo strtoupper($rs['nama']);?><span style="font-size:12px;font-weight: normal;"><!--<?php echo ucfirst($rs['alamat']);?><br />&nbsp;<?php echo ucfirst($rs['kota']);?>--><br />Telp. <?php echo $rs['telp'];?></span>
						</td>
					</tr>
					</tbody>
				</table>
			</td>
			<td width="70%" align="center" style="border-top:1px solid; border-left:1px solid;border-right:1px solid; border-bottom:1px solid;font-size: 14px; font-weight: bold"><b>LAPORAN STOCK OPNAME OBAT - <?php echo $unit_name;?></b><br><b><?php echo $judul;?></b>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="left" style="font-size:13px;"></td>
			<td align="right" style="font-size:13px;">YANG MENCETAK : <b><?php echo strtoupper($username);?></b></td>
		</tr>
	</table>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabel">

		<tr>
			<th width="5%" rowspan="2">NO </th>
			<th width="20%" rowspan="2">NAMA OBAT</th>
			<th width="10%" rowspan="2">HPP</th>
			<th width="20%" colspan="2">STOCK SISTEM</th>
			<th width="20%" colspan="2">STOCK OPNAME</th>
			<th width="20%" colspan="2">SELISIH</th>
			<th width="25%" rowspan="2">KET</th>
		</tr>

		<tr>
			<th width="5%">JML</th>
			<th width="10%">NILAI</th>
			<th width="5%">JML</th>
			<th width="10%" >NILAI</th>
			<th width="5%">JML</th>
			<th width="10%">NILAI</th>
		</tr>
		<?php
		$no = 1;
		$total_stock_sistem=$total_stock_opname=$total_stock_adj=$total_nilai_stock_sistem=$total_nilai_stock_opname=$total_nilai_adj=0;
		foreach ($stok as $data){
			$total_stock_adj += $data->qty_adj;
			$total_stock_sistem += $data->qty_data;
			$total_stock_opname += $data->qty_opname;

			$total_nilai_adj += $data->nilai_adj;
			$total_nilai_stock_sistem += $data->nilai_sistem;
			$total_nilai_stock_opname += $data->nilai_opname;
			?>
			<tr>
				<td align="center"><?php echo $no;?></td>
				<td><?php echo $data->item_name;?></td>
				<td><?php echo number_format($data->item_price,2,'.',',');?></td>
				<td align="center"><?php echo $data->qty_data;?></td>
				<td align="right"><?php echo number_format($data->nilai_sistem,2,'.',',');?></td>
				<td align="center"><?php echo $data->qty_opname;?></td>
				<td align="right"><?php echo number_format($data->nilai_opname,2,'.',',');?></td>
				<td align="center"><?php echo $data->qty_adj;?></td>
				<td align="right"><?php echo number_format($data->nilai_adj,2,'.',',');?></td>
				<td align="left"><?php echo $data->opname_note;?></td>
			</tr>
			<?php
			$no++;}?>
		<tr>
			<td></td>
			<td colspan="2"><b>Total</b></td>
			<td align="center"></td>
			<td align="right"><?=number_format($total_nilai_stock_sistem,2,'.',',')?></td>
			<td align="center"></td>
			<td align="right"><?=number_format($total_nilai_stock_opname,2,'.',',')?></td>
			<td align="center"></td>
			<td align="right"><?=number_format($total_nilai_adj,2,'.',',')?></td>
			<td></td>
		</tr>
	</table>
</div>
