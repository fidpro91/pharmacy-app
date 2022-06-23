<table class="table table-bordered" id="table_item">
    <tr>
        <th><input type="checkbox" id="checkAllitem"/></th>
        <th>ITEM CODE</th>
        <th>ITEM NAME</th>
        <th>Harga</th>
        <th>QTY</th>
        <th>SUDAH DIRETUR</th>
        <th>QTY RETUR</th>
        <th>Total</th>
    </tr>
    <?php
        $num = 1;
        foreach ($data as $key => $value) {
			if(isset($value->recdet_id)){
				$checked = 'checked';
			}else{
				$checked = '';
			}
            echo "<tr>
                    <td><input $checked type=\"checkbox\" class=\"itemdet_id\" name=\"div_detail[$key][itemdet_id]\" value=\"$value->sale_id|$value->saledetail_id\"/></td>
                    <td>$value->item_code</td>
                    <td>$value->item_name</td>
                    <td isi=\"$value->harga\">".convert_currency($value->harga)."</td>
                    <td>$value->sale_qty</td>
                    <td>".((isset($value->total_qty_returned)?$value->total_qty_returned:0))."</td>
                    <td>".
                    form_input([
                        "class" => "form-control input-sm qty_retur",
                        "name"  => "div_detail[$key][qty_retur]",
                        "value" => 0
                    ])."</td>
                    <td>".
                    form_input([
                        "class" => "form-control input-sm total_retur",
                        "name"  => "div_detail[$key][total_retur]",
                        "value" => 0,
                        "readonly" => true
                    ])."</td>
                    </tr>
                    ";
            $num++;
        }
    ?>
</table>
<script>
    
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

    function hitunggrandTotal(){
		let grandtotal=0;
		let totalDiskon=0;
		$(".total_retur").each(function(){
			let total = parseFloat($.isNumeric($(this).val())?$(this).val():0);
			grandtotal += total;
		});
		let embalase = grandtotal/100;
		embalase = Math.abs(Math.ceil(embalase)-embalase)*100;
		grandtotal = grandtotal+embalase;
		$('#grand_total').val(grandtotal);
		// $("#ppn").trigger("change");
	}

    function hitungTotal(row) {
		let qty = parseFloat($.isNumeric(row.closest('tr').find('.qty_retur').val())?row.closest('tr').find('.qty_retur').val():0);
		let harga = parseFloat($.isNumeric(row.closest('tr').find('td:eq(3)').attr('isi'))?row.closest('tr').find('td:eq(3)').attr('isi'):0);
		let total = (qty*harga);
		row.closest('tr').find('.total_retur').val(total);
        hitunggrandTotal();
		// hitungDiskon(row,'persen');
	}
</script>