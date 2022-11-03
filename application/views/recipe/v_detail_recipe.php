<table class="table">
    <tr>
        <th style="text-align: center !important;" colspan="4">DETAIL PELAYANAN RESEP</th>
    </tr>
    <tr>
        <th>NO</th>
        <th>SALE NUM</th>
        <th>DETAIL OBAT</th>
        <th>#</th>
    </tr>
    <?php
        $row="";
        foreach ($sale as $key => $value) {
            $row .= "<tr>
                <td>".($key+1)."</td>
                <td>$value->sale_num</td>
                <td>$value->detail_obat</td>
                <td><a href=\"#\" onclick=\"delete_sale($value->sale_id)\" class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i></td>
            </tr>";
        }
        echo $row;
    ?>
</table>