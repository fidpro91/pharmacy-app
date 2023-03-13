<style type="text/css">
    body {
        overflow: scroll;
    }

    .table {
        border-collapse: collapse;
    }

    .table,
    table.td,
    table.th {
        border: 1px solid black;
    }
</style>
<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font:12px tahoma;">
    <!-- <tr id="header">
    <td colspan="4" align="left" style="line-height:25px; font-weight:bold;">
      <p><?= ucfirst($detailrs->hsp_name) ?>     <br />
        <?= ucfirst($detailrs->hsp_address) ?> </p>			</td>
      </tr> -->
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font:12px tahoma; border-collapse: collapse;">
    <?php
    $i = 1;
    foreach ($listresep as $row) {
    ?>
        <tr>
            <td colspan="4">
                <!--<hr size="1"/>-->
            </td>
        </tr>

        <!-- <tr>
        <td colspan="4" align="center"><h3><?php echo "Detail Resep Obat "; //."<br>"; print_r($all) ;
                                            ?></h3></td>
      </tr> -->

        <tr style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;">
            <td colspan="3" align="left" style="line-height:25px; font-weight:bold;">
                <p><?php echo ucfirst($detailrs->hsp_name); ?> <br />
                    <?php echo ucfirst($detailrs->hsp_address); ?> </p>
            </td>
            <td style="position: relative;">
                <p style="position: absolute; right: 10px; top: 0px; width: 60px;"><?php echo date("d-m-y"); ?></p>
            </td>
        </tr>
        <tr style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid grey;">
            <td>Nama Pasien</td>
            <td>:&nbsp;</td>
            <td style="text-transform:uppercase;font-size: 12px">&nbsp;<?php echo $detailcetak['namapasien']; ?></td>
            <td style="text-transform:uppercase;font-size: 12px">&nbsp;</td>
        </tr>
        <tr style="border-left: 1px solid black; border-right: 1px solid black;">
            <td width="28%">Nama Obat / Jumlah</td>
            <td>:&nbsp;</td>
            <td style="text-transform:uppercase;font-size: 12px">&nbsp;<?php echo $row->obat_name; ?> / <?php echo $row->obat_qty; ?></td>
            <td style="text-transform:uppercase;font-size: 12px">&nbsp;</td>
        </tr>
        <tr style="border-left: 1px solid black; border-right: 1px solid black;">
            <td>Aturan Pakai</td>
            <td>:</td>
            <td style="text-transform:uppercase;font-size: 12px"><?php echo $row->dosis ?></td>
            <td style="text-transform:uppercase;font-size: 12px"></td>
        </tr>
       
        <tr style="border-left: 1px solid black; border-right: 1px solid black;">
            <td colspan="4" style="text-align: center; font-weight: bold; font-size: 14px;"></td>
        </tr>
       
        <!-- <tr>
        <td colspan="4"><hr></td>
      </tr> -->
    <?php
        $i++;
    }
    ?>

    <!-- <tr>
        <td>Tanggal</td>
        <td>:</td>
        <td>&nbsp;<?php echo $detailcetak['tanggal']; ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="19%">Nomor Resep</td>
        <td width="1%">:&nbsp;</td>
        <td width="51%" >&nbsp;<?php echo $detailcetak['noresep']; ?></td>
        <td width="29%" >&nbsp;</td>
      </tr>
      <tr>
        <td >Nama Pasien</td>
        <td>:&nbsp;</td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;<?php echo $detailcetak['namapasien']; ?></td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;</td>
      </tr>
      <tr>
        <td >Kepemilikan</td>
        <td>:&nbsp;</td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;<?php echo $detailcetak['kepemilikan']; ?></td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;</td>
      </tr> -->

</table>
<br />
<!-- <table class="table table-bordered" width="100%" cellpadding="2" border="1" cellspacing="1" style="font:12px tahoma; ">
        <tr>
          <th>No</th>
          <th>Nama Obat</th>
          <th>Komponen Obat</th>
          <th>qty</th>
          <th>dosis</th>
        </tr>
        <?php
        $i = 1;
        foreach ($listresep as $row) {
        ?>
              <tr>
                <td style="text-align:center;"><?= $i ?></td>
                <td><?= $row->obat_name ?></td>
                 <td><?= str_replace("\\n", "<br/>", $row->komponen_obat) ?></td>
                <td><?= $row->obat_qty ?></td>
                <td><?= $row->dosis ?></td>
              </tr>
              <?php
                $i++;
            }
                ?>
      </table> -->
<br />
<table width="100%" cellpadding="2" accesskey="" cellspacing="1" style="font:12px tahoma; ">
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>
            <!-- <p align='center'>Surabaya, <?php echo date('d-m-Y'); ?></p> -->
            <!-- <p align='center'><?php echo $pencetak; ?></p> -->
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr id="trTombol">
        <td colspan="4" class="noline" align="center">
            <input id="btnPrint" type="button" value="Print/Cetak" onClick="cetak(document.getElementById('trTombol'));" />
            <input id="btnTutup" type="button" value="Tutup" onClick="window.close();" />
        </td>
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