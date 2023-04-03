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
    echo "<tr>
    <td>No Resep</td>
    <td>:&nbsp;</td>
    <td style='text-transform:uppercase;font-size: 12px'>&nbsp;<?php echo $detailcetak->px_name ?></td>
    <td style='text-transform:uppercase;font-size: 12px'>&nbsp;</td> ";
      
     foreach($racikan as $row){   
        echo "<tr>
        <td>Nama Obat</td>
        <td>$row->item_name</td> </tr>";
     }
    "</tr>";
    ?>
</table>