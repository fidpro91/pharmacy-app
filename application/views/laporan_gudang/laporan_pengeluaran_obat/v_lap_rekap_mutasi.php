<?php
if (isset($button) && $button=="excel") {
	$now = date('d-m-Y');
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Laporan_Pengeluaran-".$now.".xls");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>

	<link href="<?php echo base_url(); ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">

	<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

	<title>Laporan Penerimaan Gudang</title>
</head>
<body>
<br />
<table width="100%" style="text-align: center">
	<tr>
		<td><div class="text-center">
				<span style="font-weight: bold; font-size: 12px;"><?php echo $rs['nama'] ?> </span> <br / >
				<span style="font-weight: bold; font-size: 12px;"><?php echo $rs['alamat'].", ".$rs['kota']?></span> <br / >
				<span style="font-weight: bold; font-size: 12px;">Laporan Pengeluaran Obat Unit <?php if( !empty($unit_asal->unit_name) ) echo $unit_asal->unit_name; ?></span> <br / >
				<span style="font-weight: bold; font-size: 12px;">Periode <?php echo $periode?></span>
			</div></td>
	</tr>
</table>

<br />
<div class="text-center" style="margin: 3px;">
	<table class="table table-striped table-bordered table-hover" width="100%" style="border-collapse: collapse" border="1">
		<tr>
			<th class="text-center" style="width: 20% !important;">Ruang</th>
			<?php
			$bulan = array(
				1 => 'Januari',
				2 => 'Februari',
				3 => 'Maret',
				4 => 'April',
				5 => 'Mei',
				6 => 'Juni',
				7 => 'Juli',
				8 => 'Agustus',
				9 => 'September',
				10 => 'Oktober',
				11 => 'November',
				12 => 'Desember',
			);
//			$this->load->library('fungsi_umum');
//			$bulan = $this->fungsi_umum->get_month_name();
			foreach ($bulan as $key => $value) {
				echo "<th class=\"text-center\">".$value."</th>";
			}
			?>
			<th class="text-center">Total</th>
		</tr>
		<?php
		$totalSemua=0;
		foreach ($data as $key => $r) {
			echo "<tr>
				 <td class=\"text-left\">$r->unit_name</td>";
			$ardat = json_decode($r->detail);
			$total_s = 0;
			
			foreach ($bulan as $x => $b) {
				$nilai[$x][$key]=0;
				if ($ardat) {
					foreach ($ardat as $y => $d) {
						if ($d->f1 == $x) {
							$nilai[$x][$key] = $d->f2;
							break;
						}
					}
				}
				echo "<td>".number_format($nilai[$x][$key],2,',',',')."</td>";
				$total_s += $nilai[$x][$key];
			}
			$totalSemua += $total_s;
			echo "<td>".number_format($total_s,2,",",',')."</td></tr>";
		}
		?>
		<tr>
			<td class="text-left"><b>Sub Total</b></td>
			<?php
			foreach($nilai as $st) {
				$total = 0;
				foreach($st as $value) {
					$total = $total + $value;
				}
				echo '<td align="right"> <b>'.number_format($total,2,",",",").'</b></td>';
			}
			echo "<td><b>".number_format($totalSemua,2,",",",")."</b></td>";
			?>
		</tr>
	</table>
</div>
</body>
</html>
