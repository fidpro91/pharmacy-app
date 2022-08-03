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
<!-- <div style="text-align: left;">
<?php echo strtoupper($rs->hsp_name);?><br>
<span style="font-size:12px;font-weight: normal;">
&nbsp;<?php echo ucfirst($rs->hsp_address);?><br>
&nbsp;<?php echo ucfirst($rs->kota);?><br>
&nbsp;Telp. <?php echo $rs->hsp_phone;?>
</span>
</div> -->
<div align="center">
    <strong>
        LAPORAN PENJUALAN OBAT <br><br>
        LAYANAN PASIEN <br>
        <!-- KEPEMILIKAN <br> -->
        </strong><br>
        <br>&nbsp;<br>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabel">
        
        <tr>
            <th width="5%">NO </th>
            <th width="10%">TGL TRANSAKSI</th>
            <th width="10%">NO RESEP</th>
            <!-- <th width="10%" >NO RESEP</th> -->
            <th width="5%">NO RM</th>
            <th width="10%">NAMA</th>
            <th width="5%">CARA BAYAR</th>
            <th width="5%">KSO</th>
            <th width="10%">RUANGAN(POLI)</th>
            <th width="5%">SHIFT</th>
            <th width="5%">JML. RACIKAN</th>
            <th width="5%">JML. NONRACIKAN</th>
            <th width="10%">TOTAL JUAL</th>
            <th width="5%">RETUR</th>
            <th width="10%">SUB TOTAL</th>
            <!-- <th width="5%">DETAIL</th> -->
        </tr>
        <?php 
        $no=1;
        $total_penjualan=$total_racik=$total_nonracik=0;
        foreach ($sale as $data) {?>
        <tr>
            <th width="5%"><?php echo $no;?></th>
            <th width="10%"><?php echo date('d-m-Y h:i:s',strtotime($data->date_act))?></th>
            <th width="10%"><?php echo $data->sale_num;?></th>
            <th width="5%"><?php echo $data->px_norm;?></th>
            <th width="10%" align="left"><?php echo $data->patient_name;?></th>
            <th width="5%"><?php if ($data->sale_type==0) {
                echo "TUNAI";
            }
            else{ echo "KREDIT";}
            ?></th>
            <th width="5%"><?php echo $data->surety_name;?></th>
            <th width="10%"><?php echo $data->unit_name;?></th>
            <th width="5%"><?php echo $data->sale_shift;?></th>
            <th width="5%"><?php echo $data->jml_racikan;?></th>
            <th width="5%"><?php echo $data->jml_nonracik;?></th>
            <th width="10%" align="right"><?php echo convert_currency($data->sale_total);?></th>
            <th width="5%"><?php echo convert_currency($data->sr_total);?></th>
            <th width="5%"><?php echo convert_currency($data->sale_total-$data->sr_total);?></th>
            
        </tr>
        <?php 
        $total_penjualan += $data->sale_total-$data->sr_total;
        $total_nonracik += $data->jml_nonracik;
        $total_racik += $data->jml_racikan;
        $no++;
        }?>
        <tr>
            <th width="5%"></th>
            <th width="10%"></th>
            <th width="10%">TOTAL RESEP</th>
            <th width="10%" ><?php echo ($total);?></th>
            <th width="5%"></th>
            <th width="10%"></th>
            <th width="5%"></th>
            <th width="5%"></th>
            <th width="5%"></th>
            <th width="5%"><?=$total_racik?></th>
            <th width="10%"><?=$total_nonracik?></th>
            <th width="5%" colspan="2">TOTAL</th>
            <th width="10%" align="right"><?php echo convert_currency($total_penjualan);?></th>
            <!-- <th width="5%"></th> -->
            <!-- <th width="5%"></th> -->
        </tr>
        
        
            
    </table>
    
    

    <p align="right">Tgl Cetak <?php echo date('d-m-Y')." pk ".date('H:i:s');?>
    <!-- <br>Yang mencetak,<br>&nbsp;<br>&nbsp;<br><?php echo ucfirst($username);?></p><br> -->
    </div>
