<?php
$row = "";
foreach ($dataHistory as $key => $value) {
    $row .= "<tr>
        <td>" . ($key + 1) . "</td>
        <td>$value->visit_date</td>
        <td>$value->unit_name</td>
        <td>$value->diagnosa</td>
        <td>$value->tindakan</td>
        <td>$value->obat</td>
        <td>
            <button class=\"btn btn-info btn-sm btn-hasil\" onclick=\"get_hasil(this,$value->visit_id,1)\">
                <i class=\"fa fa-eye\"></i> Lihat
            </button>
            <br><hr>
            <span class=\"detailHasil\"></span>
        </td>
        <td>
            <button class=\"btn btn-warning btn-sm btn-hasil\" onclick=\"get_hasil(this,$value->visit_id,2)\">
                <i class=\"fa fa-eye\"></i> Lihat
            </button>
            <br><hr>
            <span class=\"detailHasil\"></span>
        </td>
    </tr>";
}
echo $row;
?>
<script>
    function get_hasil(row,visit_id,type) {
        $.get("sale/get_hasil_penunjang/"+visit_id+"/"+type,function(resp){
            if (resp.code == '200') {
                $(row).closest('td').find('.detailHasil').html(resp.response.hasil);
            }
        },'json');
    }
</script>