
<script type="text/javascript" src="<?php echo base_url()?>assets/global/plugins/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/global/plugins/jquery-barcode/jquery-barcode.js"></script>
<script>
    jQuery(document).ready(function() {
        $("#bcTarget").barcode("<?php echo $detailcetak['noresep'];?>", "code128",{barWidth: 2,
        barHeight: 30,fontSize: 8});
    });
</script>
<style>
 .table-container {
        position: absolute;
        top: 135px; /* Ubah jarak dari atas sesuai kebutuhan Anda */
        right: 55px; /* Ubah jarak dari kanan sesuai kebutuhan Anda */
        width: 20%;
        text-align: center;
        border: 0px solid black;
        font-size:11px;
    }
    body {
        font-size: 8px; /* Gaya font untuk seluruh dokumen */
    }
    
</style>
<page>
<body>
<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font:12px; border-bottom: 1px solid black;">
    <tr>
        <td colspan="3" align="left" style="line-height:16px;">
            <h3 style="padding:0px;margin:0px">E RESEP RSUD IBNUSINA GRESIK</h3>
        </td>  
    </tr>
</table>

<br>
<table width="100%" border="0" cellpadding="5" cellspacing="0" style="border-collapse: collapse; line-height: 0.8;">
  <tr>
    <td width="15%">Nomor Resep</td>
    <td width="1%">:</td>
    <td width="50%"><?php echo "001213";?></td>
    <td width="15%">Tanggal Resep</td>
    <td width="1%">:</td>
    <td width="34%"><?php echo $pasien->tgl_resep;?></td>
  </tr>
  <tr>
    <td style="vertical-align: top;">Nama Pasien</td>
    <td style="vertical-align: top;">:</td>
    <td style="vertical-align: top;"><?php echo $pasien->px_name;?></td>
    <td>No.Rm</td>
    <td>:</td>
    <td><?php echo $pasien->px_norm;?></td>
  </tr>
  <tr>
    <td>Tgl.lahir</td>
    <td>:</td>
    <td><?php echo $pasien->tgl_lahir;?></td>
    <td>Pelayanan</td>
    <td>:</td>
    <td><?=$pasien->unit_name?></td>
  </tr>
  <tr>
    <td>Alamat</td>
    <td>:</td>
    <td><?php echo $pasien->px_address;?></td>    
    <td>Dokter</td>
    <td>:</td>
    <td><?php echo $pasien->dokter;?></td>
  </tr>
  <tr>
    <td>Riwayat Alergi</td>
    <td>:</td>
    <td>-</td>    
    <td>Berat Badan</td>
    <td>:</td>
    <td>3 Kg</td>
  </tr>
</table>

<br/>

 
<table width="70%" style="border-collapse: collapse; border: 0px solid black;" border="0">
        <tr>
            <td colspan="4" ><b>NON RACIKAN</b></td>
        </tr>
        <tr>
            <td>No</td>
            <td>Nama Obat</td>
            <td>Aturan Pakai</td>
            <td>Jumlah</td>
        </tr>
        <?php
        $no = 1;
        foreach ($resep as $key => $res){
            if($res->racikan_qty==null){
                echo "<tr>
                    <td>".$no++."</td>
                    <td>".$res->item_name."</td>
                    <td>".$res->dosis."</td>
                    <td>".$res->qty."</td>
                </tr>";
            }
        }
        ?>        
    </table>
<br>
    <table width="70%" style="border-collapse: collapse; border: 0px solid black;" border="0">
    <tr>
            <td colspan="4" ><b>RACIKAN</b></td>
        </tr>
        <tr>
            <td>No</td>
            <td>Nama Obat</td>
            <td>Aturan Pakai</td>
            <td>Dosis Racik</td>
            <td>Jumlah Racik</td>          
        </tr>
<?php
$no=1;
        foreach ($resep as $key => $res){
            if($res->racikan_qty != null){
                echo "<tr>
                <td>".$no++."</td>
                <td>".$res->item_name."</td>
                <td>".$res->dosis."</td>
                <td>".$res->racikan_dosis."</td>
                <td>".$res->racikan_qty."</td>
            
            </tr>";
            }
           
        }
?>
    </table>  
   
    <div class="table-container">
    <table  style="text-align: center; border-collapse: collapse; border: 1px solid blac;" border="1">
        <tr>
            <td colspan="3" style="padding: 5px;"><b>Telaah Penyiapan/Verifikasi*</b></td>
        </tr>
        <tr>
             <td style="padding: 5px;" ><b>Keterangan<b></td>
             <td  >Ya</td>
             <td >Tidak</td>
        </tr>
        <tr>            
            <td style="padding: 5px;" >Tepat Pasien</td>  
            <td></td>
            <td></td>          
        <tr>
        <td style="padding: 5px;" >Tepat Obat</td>
        <td></td>
            <td></td>
       </tr>
       <tr>
        <td style="padding: 5px;" >Tepat Dosis</td>
        <td></td>
            <td></td>
       </tr>
       <tr>
        <td style="padding: 5px;">Tepat Rute</td>
        <td></td>
            <td></td>
       </tr>
       <tr>
        <td style="padding: 5px;" >Tepat Waktu</td>
        <td></td>
            <td></td>
       </tr>         
        <tr>
        <td colspan="3" style="text-align: left;">
            Petugas :<br>
            <hr style="border-top: 1px solid black; ">
            TTD/Paraf    :
        </td>
        </tr>
        <tr>
            <td colspan="3" style="padding: 5px;"><b>Penerimaan Obat</b></td>
        </tr>
        <tr>
        <td colspan="3" style="text-align: left;">
            Penerima :<br>
            <hr style="border-top: 1px solid black; ">
            TTD/Paraf    :
        </td>
        </tr> 
    </table> 
    </div> 
    </body>   
</page>
<script type="text/JavaScript">
function cetak(tombol){
  tombol.style.visibility='collapse';
  if(tombol.style.visibility=='collapse'){
    window.print();
    window.close();
  }
}
</script>
