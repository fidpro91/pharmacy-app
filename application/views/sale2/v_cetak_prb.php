<style type="text/css">
.table {
    border-collapse: collapse;
}

.table th {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
}

tfoot:last-child tr:last-child td {
  border-bottom: 1px solid black;
}

tfoot:first-child tr:first-child td {
  border-top: 1px solid black;
}
.header th,td {
  padding:0px; margin:0px;
}

.header th,td {
  padding:0px; margin:0px;
  font-size: 11px;
}

h3 {
  font-weight: 150pt;
}
body {
  letter-spacing: 2px !important;
  font-size: 12px;
  font-family: arial;
}
.table-container {
      display: flex;
    }
    .table-container .table {
      flex: 1;
      margin-right: 10px;
    }

</style>
<script type="text/javascript" src="<?php echo base_url()?>assets/global/plugins/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/global/plugins/jquery-barcode/jquery-barcode.js"></script>
<script>
  <?php 
function tgl_indo($tanggal){
    $bulan = array (
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
  $pecahkan = explode('-', $tanggal);	
   return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
?>
</script>


<table border="0" width="100%">
		<tr>
			<td width="10%"><img src="<?php echo base_url();?>assets/images/bpjs-logo.jpg" width="200px" height="50px"></td>
			<td width="33%"><strong>SURAT RUJUK BALIK (PRB)<br>RSUD IBNUSINA GRESIK</strong></td>
			<td width="33%"><strong>No.SRB. <?php echo $param['no_srb']?><br> Tanggal. <?php echo tgl_indo($param['tgl_srb'])?></strong></td> 
		</tr> 
	</table>
	<body>
	<tr>
	  <th align="left"></br>Kepada Yth : Benjeng (00000000)</br></br>Mohon Pemeriksaan Dan Penanganan Lebih Lanjut :</th>      
      </tr>
	
  <div class="table-container">
    <table  width="100%">    
      <tr>
        <td >No. Kartu</td>
        <td  >:</td>
		<td ><?= $param['noka']?></td>
      </tr>
	  <tr>
        <td >Nama Peserta</td>
        <td >:</td>
		<td ><?= $param['nama']?></td>
      </tr>
	  <tr>
        <td >Tgl.Lahir</td>
        <td >:</td>	
		<td ><?php echo tgl_indo($param['tgl_lahir'])?></td>  
      </tr>
	  <tr>
        <td >Diagnosa</td>
        <td>:</td>	  
		<td></td>
      </tr>
	  <tr>
        <td >Program PRB</td>
        <td >:</td>
		<td><?= $param['prb']?></td>	  
      </tr>
	  <tr>
        <td >Keterangan</td>
        <td >:</td>	
		<td><?= $param['keterangan']?></td>  
      </tr>

    </table>
    <table style='margin-top: 20px;margin-bottom: 30px;' width="70%">
	<?php
	$no = 1;
	foreach ($param['obat'] as $key => $value) {
		echo "<tr>
		<td>".$no++."</td>
		<td>".$value->f3."</td>
		<td>".$value->f1."</td>
		<td>".$value->f2."</td>
		</tr>
		";
	}
	?>
    </table>
  </div>
  <table width= "100%" border="0">
<tr>
	<td>Saran Pengelolaan lanjutan di FKTP : </br>
<?=$param['saran']?></td>
</tr>
<tr>
	<td>Demikian atas bantuannya, diucapkan terima kasih.</br>tgl cetak, <?php  $currentDateTime = new DateTime(); 
	echo $currentDateTime->format('Y-m-d H:i:s');?></td>	
	<td style="text-align: center;">Mengetahui,</br></br></br></br></br></br>_________________</td>
</tr>


  </table>
</body>
    <table>
	<tr id="trTombol">
        <td colspan="4" class="noline" align="center">
          <input id="btnPrint" type="button" value="Print/Cetak" onClick="cetak(document.getElementById('trTombol'));"/>
          <input id="btnTutup" type="button" value="Tutup" onClick="window.close();"/>        </td>
        </tr>
	</table>
<script type="text/JavaScript">
function cetak(tombol){
  tombol.style.visibility='collapse';
  if(tombol.style.visibility=='collapse'){
    window.print();
    window.close();
  }
}
</script>
