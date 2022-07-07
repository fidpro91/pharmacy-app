    <div class="col-md-12">
    	<?= form_open("receiving_retur/save", ["method" => "post", "id" => "fm_receiving_retur"], $model) ?>
    	<?= form_hidden("rr_id") ?>
    	<?= create_input("num_retur",[
			"value" 	=> $numretur,
			"readonly"	=> true
		]) ?>
		<?=create_inputDate("rr_date=Tanggal Retur",[
			"format"=>"yyyy-mm-dd",
			"autoclose"=>"true"
		],[
			"value" 	=> date('Y-m-d'),
			"readonly"	=> true
		])?>
		<?=create_select([
			"attr"=>["name"=>"rr_type=Tipe Retur","id"=>"rr_type","class"=>"form-control"],
			"option"=> [["id"=>'1',"text"=>"Kembali Uang"],["id"=>'2',"text"=>"Ganti Barang"]],
		])?>
    	<div class="box box-primary">
    		<div class="box-header">
    			List Item retur
    		</div>
    		<div class="box-body">
    			<div class="list_item"></div>
    		</div>
    	</div>
    	<?= form_close() ?>
    	<div class="box-footer">
    		<button class="btn btn-primary" type="button" onclick="$('#fm_receiving_retur').submit()">Save</button>
    		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
    	</div>
    </div>
    <script type="text/javascript">
    	$("#btn-cancel").click(() => {
    		$("#form_receiving_retur").hide();
    		$("#form_receiving_retur").html('');
    	});
		$("body").on("focus", ".autocom_item_id", function() {
			$(this).autocomplete({
				source: "<?php echo site_url('receiving_retur/get_item');?>",
				select: function (event, ui) {
					$(this).closest('tr').find('.item_id').val(ui.item.item_id);
					$(this).closest('tr').find('.supplier').val(ui.item.supplier_name);
					$(this).closest('tr').find('.id_penerimaan').val(ui.item.rec_id+'|'+ui.item.recdet_id+'|'+ui.item.supplier_id+'|'+ui.item.own_id);
					$(this).closest('tr').find('.rrd_price').val(ui.item.price_item);
					$(this).closest('tr').find('.qty_terima').val(ui.item.qty_unit);
					$(this).closest('tr').find('.stock_saldo').val(ui.item.stock_saldo);
				}
			}).data("ui-autocomplete")._renderItem = function (ul, item) {
				return $("<li>")
					.append("<div class='comment-text'><span class=\"username\"><b>"+
						item.item_name+"|"+item.item_code+
					"</b><span class=\"text-muted pull-right\">"+formatNumeric(item.price_item)+"</span></span><p>"+
					"<span>Supplier : <span class=\"text-muted pull-right\">"+(item.supplier_name)+"</span></span><br>"+
					"<span>No Faktur : <span class=\"text-muted pull-right\">"+(item.rec_num)+"</span></span><br>"+
					"<span>Qty : <span class=\"text-muted pull-right\">"+formatNumeric(item.qty_unit)+"</span></span>"+
					"</div>")
					.appendTo(ul);
			};
		});
    	$(document).ready(() => {
    		$(".list_item").inputMultiRow({
    			column: () => {
    				var dataku;
    				$.ajax({
    					'async': false,
    					'type': "GET",
    					'dataType': 'json',
    					'url': "receiving_retur/show_multiRows",
    					'success': function(data) {
    						dataku = data;
    					}
    				});
    				return dataku;
    			},
    			"data": dataRetur
    		});
    	});
    	<?= $this->config->item('footerJS') ?>
    </script>