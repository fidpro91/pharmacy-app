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

<h4>LAPORAN KEPATUHAN PENULISAN RESEP SESUAI FORMULARIUM</h4>
<h4>RSUD IBNUSINA KABUPATEN GRESIK<br></h4>
<h4>PERIODE : <?= $period1 ?> SAMPAI <?= $period2 ?></h4>

<p style="margin-top:20px"></p>

<p style="margin-top:10px"></p>
<table class="tabel">
	<tr>
        <td width="2%" rowspan="2"><b>NO</b></td>
		<td width="5%" rowspan="2"><b> NO.RESEP </b></td>
		<td width="5%" rowspan="2"><b>NO.RM</b></td>
		<td width="10%" rowspan="2"><b>NAMA PASIEN </b></td>
		<td width="20%" rowspan="2"><b>KLINIK/RUANGAN</b></td>
		<td width="15%" rowspan="2"><b>DOKTER </b></td>
        <td width="10%" rowspan="2"><b>TOTAL ITEM OBAT </b></td>
        <td width="20%" colspan="3"><center><b>JUMLAH ITEM OBAT YANG DIRESEPKAN </b></center></td>
        <td width="10%" rowspan="2"><b>KETERANGAN</b></td>
	</tr>
    <tr>
        <td>FORNAS</td>
        <td>FORMULARIUM RS</td>
        <td>NON FORMULARIUM</td>
    </tr>
 
					<?php
						$no = 1; $tot_frs=$tot_form=$tot_non=$tot_item = 0;
						foreach ( $data as $key => $value) {							
							echo "<tr>
                                    <td>".$no++."</td>
									<td>".$value->rcp_no."</td>
                                    <td>".$value->px_norm."</td>
                                    <td>".$value->px_name."</td>
                                    <td>".$value->unit_name."</td>
                                    <td>".$value->person_name."</td>
                                    <td>".$value->total_item."</td>
                                    <td>".$value->formula_rs."</td>
                                    <td>".$value->formula."</td>
                                    <td>".$value->nonformula."</td>
                                    <td style='text-align: left'>".$value->keterangan."</td>
							     </tr>";	
                                 $tot_frs += $value->total_item;
                                 $tot_form += $value->formula_rs;
                                 $tot_non += $value->formula;
                                 $tot_item += $value->nonformula;						
						}
                        
					?>
   <tr>
        <td colspan="6">JUMLAH</td>
        <td ><?= $tot_frs ?></td>
        <td ><?= $tot_form ?></td>
        <td ><?= $tot_non ?></td>
        <td ><?= $tot_item ?></td>
        <td ></td>
        
    </tr>
	
</table>
                    </br> </br> </br> </br>
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