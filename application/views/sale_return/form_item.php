<?= form_open("", ["method" => "post", "id" => "fm_post_item"]) ?>
<table class="table table-bordered" id="table_item">
    <thead>
        <tr>
            <th><input type="checkbox" id="checkAllitem" /></th>
            <th>ITEM CODE</th>
            <th>ITEM NAME</th>
            <th>Harga</th>
            <th>QTY</th>
            <th>SUDAH DIRETUR</th>
            <th>QTY RETUR</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $num = 1;
        foreach ($data as $key => $value) {
            echo "<tr>
                        <td><input type=\"checkbox\" class=\"itemdet_id\" name=\"div_detail[$key][itemdet_id]\" value=\"$value->sale_id|$value->saledetail_id|$value->item_id\"/></td>
                        <td>$value->item_code</td>
                        <td>$value->item_name</td>
                        <td>" .form_input([
                            "type"  => "hidden",
                            "class" => "sale_price",
                            "value" => $value->harga,
                            "name"  => "div_detail[$key][sale_price]"
                        ]).convert_currency($value->harga) . "</td>
                        <td>$value->sale_qty</td>
                        <td>" .((isset($value->sale_return) ? $value->sale_return : 0)) . "</td>
                        <td>" .
                form_input([
                    "class" => "form-control input-sm qty_retur",
                    "name"  => "div_detail[$key][qty_return]",
                    "value" => 0
                ]) . "</td>
                        <td>" .
                form_input([
                    "class" => "form-control input-sm total_retur money",
                    "name"  => "div_detail[$key][total_return]",
                    "value" => 0,
                    "readonly" => true
                ]) . "</td>
                        </tr>
                        ";
            $num++;
        }
        ?>
    </tbody>
</table>
<?= form_close() ?>
<div class="box-footer" align="center">
    <button class="btn btn-success" id="btn-add-item" type="button"><i class="fa fa-plus"></i> Tambah</button>
</div>
<script>
    $("#btn-add-item").click(() => {
        $.post("sale_return/set_item_retur",$("#fm_post_item").serialize(),function(resp){
            alert(resp.message);
            $("#total_item").val(resp.totalItem);
            $("#total_qty").val(resp.totalQty);
            grandTotal(resp.totalRp);
        },'json');
    });

    $("body").on("keyup", ".qty_retur", function() {
        hitungTotal($(this));
    });

    $("#checkAllitem").click(() => {
        if ($("#checkAllitem").is(':checked')) {
            $("#table_item input[type='checkbox']").attr("checked", true);
        } else {
            $("#table_item input[type='checkbox']").attr("checked", false);
        }
    });

    function hitungTotal(row) {
        let qty = parseFloat($.isNumeric(row.closest('tr').find('.qty_retur').val()) ? row.closest('tr').find('.qty_retur').val() : 0);
        let harga = parseFloat($.isNumeric(row.closest('tr').find('.sale_price').val()) ? row.closest('tr').find('.sale_price').val() : 0);
        let total = (qty * harga);
        row.closest('tr').find('.total_retur').val(total);
        hitunggrandTotal();
        // hitungDiskon(row,'persen');
    }
    $(document).ready(() => {
        $(".money").inputmask('IDR');
        $("#table_item").DataTable({
            "paging": false,
            "sorting": false,
            "ordering": false
        });
    })
</script>