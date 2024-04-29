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
  text-align: center;
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

<h4>LAPORAN TELAAH PERESEPAN</h4>
<h4>RSUD IBNUSINA KABUPATEN GRESIK<br></h4>
<h4>PERIODE : <?= $period1 ?> SAMPAI <?= $period2 ?></h4>

<p style="margin-top:20px"></p>

<p style="margin-top:10px"></p>
<table class="tabel">
	<tr>
    <td width="2%" rowspan="2"><b>NO</b></td>
    <td width="5%" rowspan="2"><b>WAKTU PERESEPAN</b></td>
		<td width="5%" rowspan="2"><b> NO.RM </b></td>
		<td width="5%" rowspan="2"><b>NAMA PASIEN</b></td>
		<td width="10%" rowspan="2"><b>KLINIK/RUANGAN </b></td>
		<td width="10%" rowspan="2"><b>NAMA DOKTER</b></td>	       
        <td width="20%" colspan="9"><center><b>HASIL VERIFIKASI </b></center></td>
        <td width="10%" rowspan="2"><center><b>LEMBAR RESEP</b></center></td>
        <td width="20%" rowspan="2"><b>CATATAN VERIFIKASI</b></td>	 
	</tr>
    <tr>
        <td>Lengkap Penulisan Resep</td>
        <td>Jelas Tulisan</td>
        <td>Tepat Pasien</td>
        <td>Tepat Obat</td>
        <td>Tepat Dosis</td>
        <td>Tepat Rute</td>
        <td>Tepat Waktu</td>
        <td>Duplikasi</td>
        <td>Interaksi</td>         
    </tr>
  <?php 
  $no = 1;
  $jml1 = 0; 
  $jml2 = 0;
        foreach ($data as $key => $value) {
            if($value->penulisan_resep == 1){$penulisan_resep = "YA";}else{$penulisan_resep = "TIDAK";}
            if($value->kejelasan_tulisan == 1){$kejelasan_tulisan = "YA";}else{$kejelasan_tulisan = "TIDAK";}
            if($value->ketepatan_pasien == 1){$ketepatan_pasien = "YA";}else{$ketepatan_pasien = "TIDAK";}
            if($value->ketepatan_obat == 1){$ketepatan_obat = "YA";}else{$ketepatan_obat = "TIDAK";}
            if($value->ketepatan_dosis == 1){$ketepatan_dosis = "YA";}else{$ketepatan_dosis = "TIDAK";}
            if($value->ketepatan_rute == 1){$ketepatan_rute = "YA";}else{$ketepatan_rute = "TIDAK";}
            if($value->ketepatan_waktu == 1){$ketepatan_waktu = "YA";}else{$ketepatan_waktu = "TIDAK";}
            if($value->duplikasi == 1){$duplikasi = "YA";}else{$duplikasi = "TIDAK";}
            if($value->interaksi_obat == 1){$interaksi_obat = "YA";}else{$interaksi_obat = "TIDAK";}
            if($value->identifikasi == 1 ){$identifikasi="TERIDENTIFIKASI";}else{$identifikasi="TIDAK TERIDENTIFIKASI";}
           echo "<tr> 
                        <td>".$no++."</td>
                        <td>$value->rcp_date</td>
                        <td>$value->px_norm</td>
                        <td>$value->px_name</td>
                        <td>$value->unit_name</td>
                        <td>$value->person_name</td>
                        <td>$penulisan_resep</td>
                        <td>$kejelasan_tulisan</td>
                        <td>$ketepatan_pasien</td>
                        <td>$ketepatan_obat</td>
                        <td>$ketepatan_dosis</td>
                        <td>$ketepatan_rute</td>
                        <td>$ketepatan_waktu</td>
                        <td>$duplikasi</td>
                        <td>$interaksi_obat</td> 
                        <td>$identifikasi</td>
                        <td>$value->note_recipe</td>
                       
                </tr>";
               

                if($identifikasi=="TERIDENTIFIKASI"){
                  $jml1++;
                }else{
                  $jml2++;
                }
               
                
            
        }
        
       
 
  ?>
				
	
</table>
</br> </br> </br> </br>
<table>
  <tr>
    <td colspan="3">Rekap Jumlah Lembar Resep </td>
  </tr>
  <tr>
    <td>TERIDENTIFIKASI</td>
    <td>:</td>
    <td><?= $jml1 ?></td>
  </tr>
  <tr>
    <td>TIDAK TERIDENTIFIKASI</td>
    <td>:</td>
    <td><?= $jml2 ?></td>
  </tr>

</table>
             <br>      
<table width="100%"> 
    <tr>
        <td style='text-align: center'>
            Ka. Instalasi Farmasi,</br> </br> </br> </br>
            .............................
       </td> 
       <td>&nbsp;</td>
       <td>&nbsp;</td> 
       <td>&nbsp;</td>
       <td  style='text-align:left'>
            Gresik,..........</br> 
            Yang Membuat Laporan </br> </br> </br> 
            .............................
       </td>
    <tr>
        
</table>

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