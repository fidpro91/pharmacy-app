	<style>
		@page {
			margin: 0px;
		}

		body {
			font-family: "Helvetica", Arial, sans-serif;
			font-size: 16px;
			font-style: normal;
			font-variant: normal;
			font-weight: 500;
		}

		table {
			border-collapse: collapse;

		}

		.tampilan {
			border: 1px solid black;
		}

		small {
			font-size: 11px;
		}
	</style>
	<!-- <?php var_dump($data) ?> -->

	<body>
		<table width="100%" border="0" cellpadding="1" cellspacing="0">
			<tr>

				<td valign="center" align="left">
					<h3><?= $data['hsp_name'] ?></h3>
					<small><?= ucfirst($data['hsp_address']) . ' Kecamatan ' . ucfirst($data['hsp_district']) . ' ' . ucfirst($data['hsp_city']) . ' Provinsi ' . ucfirst($data['hsp_prov']) ?>
						<br>
						Email / Phone : <?= $data['hsp_email'] . ' / ' . $data['hsp_phone'] ?>
						<br>
						Website : <?= $data['hsp_website'] ?>
					</small>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr style="border: 2px solid black;">
				</td>
			</tr>

		</table>
		<?php foreach ($DataUnit as $row) : ?>
			<table style="text-align:center" border="0" width="100%">
				<tr>
					<td colspan="2" align="center" height="">
						<h3 style="font-size:12px">DAFTAR DISTRIBUSI OBAT DARI <?php echo strtoupper($row->asal); ?></h3>
					</td>
				</tr>
				<tr>
					<td width="50%" align="right" style="font-size:11px">Tanggal Permintaan </td>
					<td width="50%" align="left" style="font-size:11px"> : <?php echo $row->mutation_date; ?></td>
				</tr>
				<tr>
					<td align="right" style="font-size:11px">Nomor Permintaan </td>
					<td align="left" style="font-size:11px"> : <?php
						if (!empty($row->mutation_no)){
							echo $row->mutation_no;
						}else{
							echo $row->bon_no;
						}
						?></td>
				</tr>
			</table>
			<p style="height:5px">&nbsp;</p>

			<table width="100%" border="1" cellpadding="1" cellspacing="0" style="text-align:center">
				<tr class="headtable">
					<td width="5%" style="font-size:10px; font-weight:bold;">No</td>
					<td width="10%" style="font-size:10px; font-weight:bold;">Kode Obat</td>
					<td width="30%" style="font-size:10px; font-weight:bold;">Nama Obat</td>
					<td width="10%" style="font-size:10px; font-weight:bold;">Satuan </td>
					<!-- <td width="15%">Kepemilikan </td> -->
					<td width="8%" style="font-size:10px; font-weight:bold;"> QTY Minta </td>
					<td width="8%" style="font-size:10px; font-weight:bold;"> QTY Terima </td>
					<td width="10%" style="font-size:10px; font-weight:bold;"> Keterangan </td>
				</tr>
				<tr>

					<?php
					$no = 1;
					foreach ($DataDetail as $rows) : ?>
				<tr>
					<td style="text-align:center; font-size:10px"><?php echo $no; ?></td>
					<td style="text-align:center; font-size:10px"><?php echo $rows->item_code; ?></td>
					<td style="text-align:left; font-size:10px"><?php echo $rows->item_name; ?></td>
					<td style="text-align:center; font-size:10px"><?php echo $rows->item_unitofitem; ?></td>
					<!-- <td style="text-align:center"><?php echo $row->tujuan; ?></td> -->
					<td style="text-align:center; font-size:10px"><?php echo $rows->qty_request; ?></td>
					<td style="text-align:center; font-size:10px"><?php if ($rows->qty_send) echo $rows->qty_send; ?></td>
					<td style="text-align:center; font-size:10px"></td>
				</tr>
			<?php
						$no++;
					endforeach; ?>


			</tr>

			</table>
			<p style="font-size:11px"> <strong>&raquo; Tanggal Cetak :</strong> <?php echo date("d-m-Y"); ?> <strong>&raquo; User :</strong> <?php echo $username; ?> </p>

			<table style="text-align:center" border="0" width="100%">
				<tr>
					<td width="50%" align="center" style="font-size:11px">TTD Approval</td>
					<td width="50%" align="center" style="font-size:11px">TTD Unit </td>
				</tr>
				<tr>
					<td align="center"> </td>
					<td align="center"> </td>
				</tr>
			</table>
			<p style="height:5px">&nbsp;</p>
		<?php endforeach; ?>

		<!-- <script type="text/javascript">
			window.print();
			setTimeout(window.close, 0);
		</script> -->

	</body>
