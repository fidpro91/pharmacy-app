<style type="text/css">
    .table {
        border-collapse: collapse;
    }

    .table,
    table.th {
        border: 1px solid black;
    }

    .body-tabel td {
        border: 0px !important;
    }

    .header th,
    td {
        padding: 0px;
        margin: 0px;
    }

    body {
        letter-spacing: 7px !important;
        font-size: 10px;
        font-family: arial;
    }
</style>
<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font:12px arial; ">
    <tr id="header">
        <td colspan="4" align="left" style="line-height:25px; font-weight:bold;">
            <h3 style="padding:0px;margin:0px"><?php echo "DETAIL RETUR PENJUALAN "; //."<br>"; print_r($all) ;
                                                ?></h3>
            <?= ucfirst($data['hsp_name']) ?> <br />
            <?= ucfirst($data['hsp_address']) ?>
        </td>
        <td colspan="2" align="center">
        </td>
    </tr>
</table>
<hr width="100%" size="1px" color="black" />
<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font:12px tahoma; ">
    <tr>
        <td>Tanggal</td>
        <td>:</td>
        <td>&nbsp;<?php echo $respond->tanggal_retur; ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td width="19%">Nomor Retur</td>
        <td width="1%">:&nbsp;</td>
        <td width="51%">&nbsp;<?php echo $respond->sr_num ?></td>
        <td width="29%">&nbsp;</td>
    </tr>
    <tr>
        <td>Nama Pasien</td>
        <td>:&nbsp;</td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;<?php echo $respond->patient_name; ?></td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;</td>
    </tr>
    <!-- <tr>
        <td >Kepemilikan</td>
        <td>:&nbsp;</td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;<?php echo $detailcetak['kepemilikan']; ?></td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;</td>
      </tr>
      <tr>
        <td >Dokter</td>
        <td>:&nbsp;</td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;<?php echo $detailcetak['doctor_name']; ?></td>
        <td style="text-transform:uppercase;font-size: 12px">&nbsp;</td>
      </tr> -->

</table>
<br />
<table class="table table-bordered" width="100%" cellpadding="2" border="1" cellspacing="1" style="font:12px tahoma; ">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Obat</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Biaya Retur</th>
            <th>Sub Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $total_obat = 0;
        $sale_service = 0;
        $sale_embalase = 0;
        $sum_retur = 0;
        $sum_beli = 0;
        $sum_cost = 0;
        foreach ($respond->detail as $row) {
            $sum_retur += str_replace('$', '', str_replace(',', '', $row->total_return));
            $sum_beli += $row->total_beli;
            $sum_cost += $row->biaya_retur;
            $jadi_sub = str_replace('$', '', str_replace(',', '', $row->total_return)) - $row->biaya_retur;
        ?>
            <tr>
                <td style="text-align:center;"><?= $i ?></td>
                <td><?= strtoupper($row->item_name) ?></td>
                <td align="center"><?= $row->qty_return ?></td>
                <td align="right"><?= number_format($row->harga,2,".",",") ?></td>
                <td align="right"><?= number_format($row->biaya_retur,2,".",",") ?></td>
                <td align="right"><?= number_format($jadi_sub,2,".",",") ?></td>
            </tr>
        <?php
            $i++;
        }
        ?>
    </tbody>
    <tfoot>
       
        <tr>
            <td colspan="5" align="right">Total Retur Obat</td>
            <td align="right"><?= number_format($sum_retur, 2, ".", ",") ?></td>
        </tr>
        <tr>
            <td colspan="5" align="right">Total Biaya Retur</td>
            <td align="right"><?= number_format($sum_cost,2,".",",") ?></td>
        </tr>
        <!-- <tr>
            <td colspan="6" align="right">Biaya Racik</td>
            <td align="right"><?= number_format($sale_service,2,".",",") ?></td>
          </tr> -->
        <tr>
            <td colspan="5" align="right">Biaya Pembulatan</td>
            <td align="right"><?= number_format($respond->embalase_value,2,".",",") ?></td>
        </tr>
        <tr>
            <td colspan="5" align="right">Grand Total</td>
            <td align="right"><?= number_format(($sum_retur - $sum_cost) + $respond->embalase_value,2,".",",") ?></td>
        </tr>
    </tfoot>
</table>
<br />
<table width="100%" cellpadding="2" accesskey="" cellspacing="1" style="font:12px tahoma; ">
    <tr>
        <td colspan="6" align="right">
            <p align='right'>Gresik, <?php echo date('d-m-Y'); ?></p>
            <p align='right'><?php echo $user; ?></p>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>