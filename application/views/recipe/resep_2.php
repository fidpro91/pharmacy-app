<style>
   

</style>
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
    <td width="50%"><?php echo $pasien->rcp_no;?></td>
    <td width="15%">Tanggal Resep</td>
    <td width="1%">:</td>
    <td width="34%"><?php echo $pasien->tgl_resep;?></td>
  </tr>
  <tr>
    <td style="vertical-align: top;">Nama Pasien/No.RM</td>
    <td style="vertical-align: top;">:</td>
    <td style="vertical-align: top;"><?php echo $pasien->px_name.' ('.$pasien->px_norm.')';?></td>
    <td>No.sep</td>
    <td>:</td>
    <td><?php echo $pasien->sep_no;?></td>
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
    <td></td>    
    <td>Berat Badan</td>
    <td>:</td>
    <td><?php echo $pasien->bb;?> Kg</td>
  </tr>

  <tr>
    <td>Unit Asal</td>
    <td>:</td>
    <td><?php echo $pasien->asal_layanan;?> </td>    
    <td>Status Resep</td>
    <td>:</td>
    <td><?php 
        if($pasien->jenis_resep=="1"){
            echo "Pulang";
        }elseif($pasien->jenis_resep=="2"){
            echo "Cito";
        }elseif($pasien->jenis_resep=="3"){
            echo "Pelayanan";
        }else{
            echo '';
        }
    ?></td>
  </tr>
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
<div class="container">
    <div class="left-div">
        <div class="item"><b>Non Racikan</b></div>
        <?php
        $no = 1;
        foreach ($resep as $key => $res) {
            if ($res->racikan_qty == null) {
                echo '<div class="item">' . $res->item_name . ' Jml (' . $res->qty . ')' . '<br>' . '<i><b>Aturan ' . $res->dosis . '</b></i>' . '</div>';
            }
        }
        ?>
    </div>

    <?php
    // Div 2: Obat Racikan
    $no = 1;
    $groupedData = array(); // Inisialisasi array groupedData
    foreach ($resep as $key => $res) {
        if ($res->racikan_qty != null) {
            $racikanQty = $res->racikan_qty;
            if (!isset($groupedData[$racikanQty])) {
                $groupedData[$racikanQty] = array();
            }
            $groupedData[$racikanQty][] = array(
                "no" => $no++,
                "item_name" => $res->item_name,
                "dosis" => $res->dosis,
                "racikan_dosis" => $res->racikan_dosis,
                "racikan_qty" => $racikanQty,
                "racikan_id" => $res->racikan_id
            );
        }
    }

    // Cek apakah ada data yang sesuai dengan kondisi
    $dataExists = false;
    foreach ($groupedData as $racikanQty => $group) {
        if (!empty($group)) {
            $dataExists = true;
            break;
        }
    }

    if ($dataExists) {
        echo '<div class="right-div">';
        echo '<div class="item"><b>Racikan</b></div>';

        foreach ($groupedData as $racikanQty => $group) {
           
            echo '<div class="item">'.'Nama Racikan :<b>'.$group[0]['racikan_id'].'</b>  '.'  JML = ' . $racikanQty . '</div>';
            echo '<div class="item"><i><b>Aturan Pakai  ' . $group[0]['dosis']. '</b><i></div>';

            foreach ($group as $item) {
                echo '<div class="item">';
                echo '<div class="item">' . $item["item_name"] . ' Jml (' . $item["racikan_dosis"] . ')</div>';
                
                echo '</div>';
            }
            echo '</div>'; // Tutup racikan-group
        }
        echo '</div>'; // Tutup div Obat Racikan
    }
    ?>
</div>
