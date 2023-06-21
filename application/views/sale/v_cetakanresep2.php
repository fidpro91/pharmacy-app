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
  letter-spacing: 7px !important;
  font-size: 10px;
  font-family: arial;
}

</style>
<script type="text/javascript" src="<?php echo base_url()?>assets/global/plugins/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/global/plugins/jquery-barcode/jquery-barcode.js"></script>
<script>
    jQuery(document).ready(function() {
        $("#bcTarget").barcode("<?php echo $detailcetak['noresep'];?>", "code128",{barWidth: 2,
        barHeight: 30,fontSize: 8});
    });
</script>

<page>
<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font:12px; ">
  <tr id="header">
    <td colspan="3" align="left" style="line-height:16px;">
      <h3 style="padding:0px;margin:0px"><?php echo "FAKTUR PENJUALAN OBAT ".strtoupper($detailcetak['unit_name']);?></h3>
      <?= ucfirst($detailrs->hsp_name) ?><br />
        <?= ucfirst($detailrs->hsp_address) ?></td>
    <td align="center" style="line-height:12px;">
        <div id="bcTarget"></div>
    </td>
    <td colspan="2" align="center" style="line-height:12px;">
      <b>No. Antrian :</b> <br>
      <br>
      <h2 style="font-size: 30px; font-weight:bold;padding:0px;margin:0px"><?php $no_antrian = explode('/', $detailcetak['noresep']);
        echo $no_antrian[1];
        ?></h2>
    </td>
    </tr>
</table>

<hr width="100%" size="1px" color="black" />
    <table width="100%" border="0" cellpadding="2" cellspacing="1" class="pasien">
      <tr>
        <td width="15%" >Nomor Resep</td>
        <td width="1%">:&nbsp;</td>
        <td width="50%"><?php echo $detailcetak['noresep'];?></td>
        <td width="11%" >Tanggal</td>
        <td width="1%">:&nbsp;</td>
        <td width="34%"><?php echo $detailcetak['tanggal'];?></td>
      </tr>
      <tr>
        <td >Nama Pasien</td>
        <td>:&nbsp;</td>
        <td style="text-transform:uppercase;"><?php echo $detailcetak['namapasien'];?></td>
        <td >Penjamin</td>
        <td>:&nbsp;</td>
        <td style="text-transform:uppercase;"><?php echo $detailcetak['kepemilikan'];?></td>
      </tr>
      <tr>
        <td >Dokter</td>
        <td>:&nbsp;</td>
        <td style="text-transform:uppercase;"><?php echo $detailcetak['doctor_name'];?></td>
        <td>Pelayanan</td>
        <td>:&nbsp;</td>
        <td style="text-transform:uppercase;"><?=$detailcetak['layanan']?></td>
      </tr>
      <?php
          if ($detailcetak['kepemilikan'] == 'BPJS') {
      ?>
        <tr>
          <td>No. BPJS</td>
          <td>:&nbsp;</td>
          <td style="text-transform:uppercase;"><?php echo isset($detailcetak['pxsurety_no'])?$detailcetak['pxsurety_no']:"";?></td>
          <td>No. SEP</td>
          <td>: </td>
          <td style="text-transform:uppercase;"><?php echo isset($detailcetak['sep_no'])?$detailcetak['sep_no']:""?></td>
        </tr>
      <?php
          }
      ?>
    </table>
<br/>
      <table class="table" width="100%" cellpadding="2" border="0" cellspacing="1" style="font:8px arial; ">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Obat</th>
            <th>Komponen Obat</th>
            <th>qty</th>
            <!-- <th>dosis</th> -->
            <th>Harga</th>
            <th>Sub Total</th>
          </tr>
        </thead>
        <tbody class="body-tabel">
          <?php
            $i = 1;$total_obat=0;$sale_service=0;$sale_embalase=0;
            $racikan = "";
            $dosis = "";
            $cols=1;$get_row=1;
            foreach ($listresep as $row) {
              $sale_service = str_replace('$', '', str_replace(',', '', $row->sale_services));
              $sale_embalase = str_replace('$', '', str_replace(',', '', $row->sale_embalase));
              $total_obat += str_replace('$', '', str_replace(',', '', $row->subtotal));
              $embalase_all = $row->embalase_item_sale;
              if(!$row->racikan_id) {
                $row->racikan_id = $row->item_name;
                $row->item_name = '-';
                $cols=1;
                $get_row=1;
              }else{
                $get_row = $this->db->where('sale_id',$detailcetak['sale_id'])
                                    ->where('racikan_id',$row->racikan_id)
                                    ->select("DISTINCT item_id",false)
                                    ->get('farmasi.sale_detail')->num_rows();
              }

              if ($racikan != $row->racikan_id) {
                  $racikan = $row->racikan_id;
                  $isi = $racikan;
                  $cols = $get_row;
              }else{
                  $isi="";
                  $cols=1;
              }
        
        if ($dosis != $row->dosis) {
                  $dosis = $row->dosis;
                  $isi2 = $dosis;
                  $cols2 = $get_row;
              }else{
                  $isi2="";
                  $cols2=1;
              }
                ?>
                <tr>
                  <td style="text-align:center;" width="2%"><?=$i?></td>
                  <td rowspan="<?=$cols?>" width="30%"
                  <?php
                      if($cols == 1 && $isi=="") echo 'hidden="true"';
                  ?>
                  ><?= $isi?></td>
                  <td width="20%"><?= $row->item_name?></td>
                  <td align="center" width="5%"><?= $row->sale_qty?></td>
                  <td align="right" width="5%"><?= str_replace('$', '', $row->sale_price) ?></td>
                  <td align="right" width="15%"><?= str_replace('$', '', $row->subtotal) ?></td>
                </tr>
                <?php
                $i++;
            }
          ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" align="right" style="border-top: 1px solid black;">Total Obat |</td>
            <td align="right" style="border-top: 1px solid black;"><?=number_format($total_obat)?></td>
          </tr>
          <tr>
            <td colspan="5" align="right">Biaya Racik |</td>
            <td align="right"><?=number_format($sale_service)?></td>
          </tr>
          <tr>
            <td colspan="5" align="right">Embalase Item |</td>
            <td align="right"><?=number_format($embalase_all)?></td>
          </tr>
          <!-- <tr>
            <td colspan="5" align="right">Biaya Pembulatan</td>
            <td align="right"><?=number_format($sale_embalase)?></td>
          </tr> -->
          <tr>
            <td colspan="5" align="right">Grand Total |</td>
            <!--<td align="right"><?=number_format( $sale_embalase + $sale_service + $total_obat +$embalase_all )?></td>-->
            <td align="right"><?=number_format( $detailcetak['sale_total'] )?></td>
          </tr>
        </tfoot>
      </table>
    <br/>
    <table width="100%" cellpadding="2" accesskey="" cellspacing="1" style="font:11px arial; ">
      <tr>
        <td align="right">
          <p align='center'>Gresik, <?php echo date('d-m-Y H:i:s'); ?></p>
          <br><br>
          <p align='center'><?php echo $pencetak;?></p>
        </td>
      </tr>
      <tr id="trTombol">
        <td colspan="4" class="noline" align="center">
          <input id="btnPrint" type="button" value="Print/Cetak" onClick="cetak(document.getElementById('trTombol'));"/>
          <input id="btnTutup" type="button" value="Tutup" onClick="window.close();"/>        </td>
        </tr>
</table>
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
