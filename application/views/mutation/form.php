    <div class="col-md-12">
    	<?= form_open("mutation/save", ["method" => "post", "class" => "form-horizontal", "id" => "fm_mutation"], $model) ?>
    	<?= form_hidden("mutation_id") ?>
    	<?= create_inputDate("mutation_date=Tgl Mutasi", [
			"format" => "yyyy-mm-dd",
			"autoclose" => "true"
		], [
			"value" 	=> date('Y-m-d'),
			"readonly"	=> true
		]) ?>
    	<?= create_input("mutation_no=No. Mutasi", [
			"value"		=> $mutation_no,
			"readonly"	=> true
		]) ?>
    	<?= create_select([
			"attr" => ["name" => "own_id=Kepemilikan", "id" => "own_id", "class" => "form-control"],
			"model" => [
				"m_ownership" => ["get_ownership", ["0" => '0']],
				"column"  => ["own_id", "own_name"]
			]
		]) ?>
    	<?= create_select2([
			"attr" => ["name" => "unit_require=Unit Minta", "id" => "unit_require", "class" => "form-control"],
			"model" => [
				"m_ms_unit" => ["get_ms_unit_all", ["0" => '0']],
				"column"  => ["unit_id", "unit_name"]
			]
		]) ?>
    	<?= create_select2([
			"attr" => ["name" => "unit_sender=Unit Tujuan", "id" => "unit_sender", "class" => "form-control"],
			"model" => [
				"m_ms_unit" => ["get_ms_unit", ["employee_id"=>$this->session->employee_id]],
				"column"  => ["unit_id", "unit_name"]
			]
		]) ?>
    	<div class="list_item">
    	</div>
    	<?= form_close() ?>
    	<div class="box-footer">
    		<button class="btn btn-primary" type="button" onclick="$('#fm_mutation').submit()">Save</button>
    		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
    	</div>
    </div>
    <script type="text/javascript">
    	$("#btn-cancel").click(() => {
    		$("#form_mutation").hide();
    		$("#form_mutation").html('');
    	});
    	$(document).ready(() => {
    		console.log(mutationDetail);
    		$(".list_item").inputMultiRow({
    			column: () => {
    				var dataku;
    				$.ajax({
    					'async': false,
    					'type': "GET",
    					'dataType': 'json',
    					'url': "mutation/show_multiRows",
    					'success': function(data) {
    						dataku = data;
    					}
    				});
    				return dataku;
    			},
    			"data": mutationDetail
    		});
    	});

    	function hitungTotal_terima(row) {
    		let stock = parseInt($(row).closest('tr').find(".stock_unit").val());
    		let jml_terima = parseInt(($.isNumeric($(row).val()) ? $(row).val() : 0));
    		if (jml_terima > stock) {
    			alert("Jumlah item melebihi stock");
    			$(row).val(0);
    			return false;
    		}
    	}

    	$("#unit_sender, #own_id").on("change", function() {
    		if (confirm("Pilihan sudah sesuai?")) {
    			$(".tb_list_item").find('tbody').find('tr').remove();
    		} else {
    			$(this).val($.data(this, 'current'));
    			return false;
    		}
    		$.data(this, 'current', $(this).val());
    	});

    	$("body").on("focus", ".autocom_item_id", function() {
    		$(this).autocomplete({
    			source: "<?php echo site_url('mutation/get_item'); ?>/" + $("#own_id").val() + "/" + $("#unit_sender").val(),
				autoFocus: true,
				minLength:3,
    			select: function(event, ui) {
    				$(this).closest('tr').find('.item_id').val(ui.item.item_id);
    				$(this).closest('tr').find('.stock_unit').val(ui.item.total_stock);
    				$(this).closest('tr').find('.unit_pack').val(ui.item.item_package);
    				$(this).closest('tr').find('.unit_item').val(ui.item.item_unitofitem);
    				$(this).closest('tr').find('.expired_date').val(ui.item.expired_date);
    			}
    		}).data("ui-autocomplete")._renderItem = function(ul, item) {
    			return $("<li>")
    				.append("<div class='comment-text'><span class=\"username\"><b>" +
    					item.label + "|" + item.item_code +
    					"</b><span class=\"text-muted pull-right\">" + formatNumeric(item.expired_date) + "</span></span><p>" +
    					"<span>Stok terakhir : <span class=\"text-muted pull-right\">" + formatNumeric(item.total_stock) + "</span></span>" +
    					"</div>")
    				.appendTo(ul);
    		};
    	});

    	
    	<?= $this->config->item('footerJS') ?>
    </script>