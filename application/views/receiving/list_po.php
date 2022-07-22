<table class="table table-bordered" id="table_po">
    <tr>
        <th><input type="checkbox" id="checkAllPo"/></th>
        <th>ITEM</th>
        <th>ED</th>
        <th>SATUAN UNIT</th>
        <th>JML UNIT</th>
        <th>SUDAH DITERIMA</th>
        <th>JML TERIMA</th>
        <th>Harga</th>
        <th>Total Sb Diskon</th>
        <th>Diskon(%)</th>
        <th>Diskon(Rp)</th>
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
                    <td><input $checked type=\"checkbox\" class=\"podet_id\" name=\"div_detail[$key][podet_id]\" value=\"".(isset($value->podet_id)?$value->podet_id:$value->recdet_id)."\"/></td>
                    <td>$value->item_name</td>
                    <td><input readonly type=\"text\" value=\"".(isset($value->expired_date)?$value->expired_date:null)."\"  class=\"inputan form-control input-sm expired_date\" name=\"div_detail[$key][expired_date]\"/></td>
                    <td>$value->po_unititem</td>
                    <td>$value->po_qtyunit</td>
                    <td>".((isset($value->po_qtyreceived)?$value->po_qtyreceived:0))."</td>
                    <td><input readonly type=\"text\" data-inputmask=\"'alias': 'IDR'\" value=\"".(isset($value->qty_unit)?$value->qty_unit:0)."\"  class=\"inputan form-control input-sm qty_unit\" name=\"div_detail[$key][qty_unit]\"/></td>
                    <td><input type=\"text\" data-inputmask=\"'alias': 'IDR'\" value=\"$value->price_item\" readonly class=\"form-control input-sm price_item\" name=\"div_detail[$key][price_item]\"/></td>
					<td><input type=\"text\" data-inputmask=\"'alias': 'IDR'\" value=\"$value->price_item\" readonly class=\"form-control input-sm total_bf_diskon\" name=\"div_detail[$key][total_bf_diskon]\"/></td>
                    <td><input readonly type=\"text\" value=\"".(isset($value->disc_percent)?$value->disc_percent:0)."\" class=\"inputan form-control input-sm disc_percent\" name=\"div_detail[$key][disc_percent]\"/></td>
                    <td><input readonly type=\"text\" data-inputmask=\"'alias': 'IDR'\" value=\"".(isset($value->disc_value)?$value->disc_value:0)."\" class=\"inputan form-control input-sm disc_value\" name=\"div_detail[$key][disc_value]\"/></td>
                    <td><input type=\"text\" data-inputmask=\"'alias': 'IDR'\" value=\"".(isset($value->price_total)?$value->price_total:0)."\" readonly class=\"form-control input-sm price_total\" value=\"0\" name=\"div_detail[$key][price_total]\"/></td>
                    ";
            $num++;
        }
    ?>
</table>
<script>
    $("body").on("focus", ".expired_date", function() {
		$(this).inputmask("99-99-9999",{ "placeholder": "dd-mm-yyyy" });
	});

    $("body").on("keyup", ".qty_unit", function() {
		hitungTotal_terima($(this));
		hitungTotal($(this));
	});

    $("body").on("keyup", ".disc_percent", function() {
		hitungDiskon($(this),'persen');
	});

    $("body").on("keyup", ".disc_value", function() {
		hitungDiskon($(this));
	});

	$("body").on("click", ".podet_id", function() {
		if ($(this).is(':checked')) {
			$(this).closest('tr').find('.inputan').attr("readonly",false);
		} else {
			$(this).closest('tr').find('.inputan').attr("readonly",true);
		}
	});

    $("#ppn").change(()=>{
		hitunggrandTotal();
	});

	$("#checkAllPo").click(() => {
		$(".podet_id").trigger('click');
	});

	function hitungTotal_terima(row) {
		let total_po = parseInt(row.closest('tr').find("td:eq(4)").text());
		let sdh_diterima = parseInt(row.closest('tr').find("td:eq(5)").text());
		let jml_terima = parseInt(($.isNumeric(row.val())?row.val():0));
		let total = sdh_diterima+jml_terima;
		if ($("#rec_id").val() != '') {
			sdh_diterima = 0;
		}
		let sisaPO = total_po - sdh_diterima;
		if (total_po<total) {
			alert("Data terima melebihi total PO");
			row.val(0);
			return false;
		}
	}

    function hitunggrandTotal(){
		let grandtotal=0;
		let totalDiskon=0;
		$(".price_total").each(function(){
			let total = parseFloat($.isNumeric($(this).val())?$(this).val():0);
			totalDiskon += parseFloat($.isNumeric($(this).closest('tr').find('.disc_value').val())?$(this).closest('tr').find('.disc_value').val():0);
			grandtotal += total;
		});

		let ppn = parseInt($("#ppn").val())*($.isNumeric($("#grand_total").val())?$("#grand_total").val():0)/100;
        grandtotal = grandtotal + ppn;
		
		$("#rec_taxes").val(ppn);
		$('#grand_total').val(grandtotal);
		$('#discount_total').val(totalDiskon);
		// $("#ppn").trigger("change");
	}

    function hitungTotal(row) {
		let qty = parseFloat($.isNumeric(row.closest('tr').find('.qty_unit').val())?row.closest('tr').find('.qty_unit').val():0);
		let jml = parseFloat($.isNumeric(row.closest('tr').find('.price_item').val())?row.closest('tr').find('.price_item').val():0);
		let diskon = parseFloat($.isNumeric(row.closest('tr').find('.disc_value').val())?row.closest('tr').find('.disc_value').val():0);
		let total = (qty*jml)-diskon;
		row.closest('tr').find('.price_total').val(total);
		row.closest('tr').find('.total_bf_diskon').val((qty*jml));
        hitunggrandTotal();
		// hitungDiskon(row,'persen');
	}

    function hitungDiskon(row,type = null) {
		if(type == 'persen'){
			let diskon = parseFloat($.isNumeric(row.closest('tr').find('.disc_percent').val())?row.closest('tr').find('.disc_percent').val():0);
			let hargaTotal = parseFloat($.isNumeric(row.closest('tr').find('.price_total').val())?row.closest('tr').find('.price_total').val():0);
			let total = diskon/100*hargaTotal;
			row.closest('tr').find('.disc_value').val(total);
			console.log(diskon+"-"+hargaTotal+"-"+total);
		}else{
			let diskon = parseFloat($.isNumeric(row.closest('tr').find('.disc_value').val())?row.closest('tr').find('.disc_value').val():0);
			let hargaTotal = parseFloat($.isNumeric(row.closest('tr').find('.price_total').val())?row.closest('tr').find('.price_total').val():0);
			let total = diskon/hargaTotal*100;
			row.closest('tr').find('.disc_percent').val(total);
		}
        hitungTotal(row);
	}
	<?=$this->config->item('footerJS')?>
</script>