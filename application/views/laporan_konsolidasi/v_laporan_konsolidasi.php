<html>
<head>
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/global/plugins/tabletoexcel/excelexportjs.js" type="text/javascript"></script>

<style>
    table {
        font-size: 12px;
        font-family: Arial;
        word-spacing: 2px;
    }
    .tabel{
        border-collapse: collapse;
    }

    table.tabel , table.tabel  th {
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

</head>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabel" id="tabel">
        
        <tr>
            <th width="5%" rowspan="3">NO </th>
            <th width="8%" rowspan="3">NAMA ALAT / OBAT</th>
            <th width="5%" rowspan="3">KEPEMILIKAN</th>
            <th width="15%" rowspan="2" colspan="3">SALDO AWAL</th>
            <th width="30%" colspan="6">MUTASI</th>
            <th width="15%" rowspan="2" colspan="3">ADJUSTMENT</th>
            <th width="15%" rowspan="2" colspan="3">SALDO AKHIR</th>
            <th width="5%" rowspan="3">KET</th>
        </tr>
        <tr>
            <th width="15%" colspan="3">MASUK</th>
            <th width="15%" colspan="3">KELUAR</th>
        </tr>
        <tr>
            <th width="5%">Q</th>
            <th width="5%">HARGA</th>
            <th width="5%">TOTAL</th>
            <th width="5%">Q</th>
            <th width="5%">HARGA</th>
            <th width="5%">TOTAL</th>
            <th width="5%">Q</th>
            <th width="5%">HARGA</th>
            <th width="5%">TOTAL</th>
            <th width="5%">Q</th>
            <th width="5%">HARGA</th>
            <th width="5%">TOTAL</th>
            <th width="5%">Q</th>
            <th width="5%">HARGA</th>
            <th width="5%">TOTAL</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6=4x5</th>
            <th>7</th>
            <th>8</th>
            <th>9=7x8</th>
            <th>10</th>
            <th>11</th>
            <th>12=10x11</th>
            <th>13</th>
            <th>14</th>
            <th>15=13x14</th>
            <th>16=4+7-10-13</th>
            <th>17=5+6-11-14</th>
            <th>18=6+9-12-15</th>
            <th>19</th>
        </tr>

        <?php 
            $no=1;
            foreach ($data as $rs) {
                
                echo "<tr>
                    <th>$no</th>
                    <th>$rs->item_name</th>
                    <th>$rs->own_name</th>
                    <th>$rs->stock_awal</th>
                    <th>".number_format($rs->harga_awal*1, 2, ',', ',')."</th>
                    <th>".number_format($rs->stock_awal*$rs->harga_awal, 2, ',', ',')."</th>
                    <th>$rs->masuk</th>
                    <th>".number_format($rs->harga*1, 2, ',', ',')."</th>
                    <th>".number_format($rs->masuk*$rs->harga, 2, ',', ',')."</th>
                    <th>$rs->keluar</th>
                    <th>".number_format($rs->harga*1, 2, ',', ',')."</th>
                    <th>".number_format($rs->keluar*$rs->harga, 2, ',', ',')."</th>
                    <th>$rs->stock_op</th>
                    <th>".number_format($rs->harga_so, 2, ',', ',')."</th>
                    <th>".number_format($rs->stock_op*$rs->harga_so, 2, ',', ',')."</th>
                    <th>".($rs->stock_awal+$rs->masuk-$rs->keluar-$rs->stock_op)."</th>
                    <th>".number_format($rs->harga*1, 2, ',', ',')."</th>
                    <th>".number_format(($rs->stock_awal+$rs->masuk-$rs->keluar-$rs->stock_op)*$rs->harga, 2, ',', '.')."</th>
                    <th></th>
                </tr>";
            $no++;
            }
        ?>
    </table>
    <br>
</html>