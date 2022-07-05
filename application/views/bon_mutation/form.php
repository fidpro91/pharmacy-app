    <div class="col-md-12">
    	<?= form_open("bon_mutation/save", ["method" => "post", "class" => "form-horizontal", "id" => "fm_mutation"], $model) ?>
    	<?= form_hidden("mutation_id") ?>
    	<?= create_inputDate("mutation_date=Tgl.Mutasi", [
			"format" => "yyyy-mm-dd",
			"autoclose" => "true"
		]) ?>
    	<?= create_input("bon_no",[
							"value"=>$norec,
							"readonly"=>true]) ?>
    	<?= create_select2(["attr" => ["name" => "own_id=Kepemilikan", "id" => "own_id", "class" => "form-control"],
			"model" => ["m_surety_ownership" => ["get_kepemilikan", ["0" => '0']],
				"column"  => ["own_id", "own_name"]
			]
		]) ?>
    	<?= create_select2([
			"attr" => ["name" => "unit_require=Unit Minta", "id" => "unit_require", "class" => "form-control"],
			"model"=>["m_mutation" => ["get_user_in_unit",[0,"u.user_id"=>$this->session->user_id]],
				"column"  => ["unit_id", "unit_name"]
			]
		]) ?>
    	<?= create_select2([
			"attr" => ["name" => "unit_sender=Unit Tujuan", "id" => "unit_sender", "class" => "form-control"],
			"model" => [
				"m_ms_unit" => "get_ms_unit",
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
    					'url': "bon_mutation/show_multiRows",
    					'success': function(data) {
    						dataku = data;
    					}
    				});
    				return dataku;
    			},
				"data" : mutationDetail
    		});
    	});
    	$("body").on("focus", ".autocom_item_id", function() {
    		$(this).autocomplete({
    			source: "<?php echo site_url('bon_mutation/get_item'); ?>/" + $("#own_id").val() + "/" + $("#unit_sender").val(),
    			select: function(event, ui) {
    				$(this).closest('tr').find('.item_id').val(ui.item.item_id);
    				$(this).closest('tr').find('.stock_unit').val(ui.item.total_stock);
    				$(this).closest('tr').find('.unit_pack').val(ui.item.item_package);
    				$(this).closest('tr').find('.unit_item').val(ui.item.item_unitofitem);
    			}
    		}).data("ui-autocomplete")._renderItem = function(ul, item) {
    			return $("<li>")
    				.append('<a>' +
    					'<table class="table"><tr>' +
    					'<td style="width:150px">' + item.value + '</td>' +
    					'<td style="width:40px">' + item.item_package + '</td>' +
    					'</tr></table></a>')
    				.appendTo(ul);
    		};
    	});
    	<?= $this->config->item('footerJS') ?>
    </script>