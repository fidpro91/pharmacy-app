    <div class="col-md-12">
      	<?=form_open("opname_header/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_opname_header"],$model)?>
			<div class="col-md-3">
				<?=form_hidden("opname_header_id")?>
				<?=create_inputDate("opname_date",[
					"format"=>"yyyy-mm-dd",
					"autoclose"=>"true"
				],[
					"value" 	=> date('Y-m-d'),
					"readonly"	=> true
				])?>
				<?=create_input("opname_no",[
					"value" => $noOpname,
					"readonly"	=> true
				])?>
				<?=create_select2([
					"attr" =>["name"=>"unit_id=Unit","id"=>"unit_id","class"=>"form-control"],
					"model"=>["m_ms_unit" => ["get_ms_unit",["employee_id"=>$this->session->employee_id]],
									"column"  => ["unit_id","unit_name"]
								]
				])?>
				<?=create_select([
					"attr" =>["name"=>"own_id=Kepemilikan","id"=>"own_id","class"=>"form-control"],
					"model"=>["m_ownership" => "get_ownership",
									"column"  => ["own_id","own_name"]
								],
				])?>
				<?=create_textarea("opname_note")?>
			</div>
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List Item</h3>
					</div>
					<div class="list_item box-body" id="list_item">
					</div>
				</div>
			</div>
    </div>
	<div class="box-footer">
		<div class="box-tools pull-right">
			<button class="btn btn-primary" type="button" onclick="$('#fm_opname_header').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
		</div>
    </div>
	<?=form_close()?>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_opname_header").hide();
		$("#form_opname_header").html('');
	});
	$(document).ready(()=>{
        $(".list_item").inputMultiRow({
			column: ()=>{
				var dataku;
				$.ajax({
					'async': false,
					'type': "GET",
					'dataType': 'json',
					'url': "opname_header/show_multiRows",
					'success': function (data) {
						dataku = data;
					}
				});
				return dataku;
				},
			"data":dataItemOpname,
	    });
	});
	
	$("body").on("focus", ".autocom_item_id", function() {
	    $(this).autocomplete({
            source: "<?php echo site_url('opname_header/get_item/');?>"+$("#unit_id").val()+"/"+$("#own_id").val(),
			autoFocus: true,
			minLength:3,
            select: function (event, ui) {
				$('tr[class*="list_item"]').each(function(i,a){
					if($(this).find('.item_id').val() == ui.item.item_id ){
						$(this).eq((i)).closest('tr').find('.qty_opname').focus();
						$(this).last().remove();
						return false;
					}
				});
                $(this).closest('tr').find('.qty_opname').focus();
                $(this).closest('tr').find('.item_id').val(ui.item.item_id);
                $(this).closest('tr').find('.qty_data').val(ui.item.total_stock);
                $(this).closest('tr').find('.item_price').val(ui.item.harga);
				$(this).closest('tr').find('.exp_date').val(ui.item.expired_date);
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div style='color: black' class='comment-text'><span class=\"username\"><b>"+
					item.item_name+"|"+item.item_code+
				"</b><span class=\"text-muted pull-right\">"+formatNumeric(item.harga)+"</span></span><p>"+
				"<span>Kategori Item : <span class=\"text-muted pull-right\">"+(item.classification_name)+"</span></span><br>"+
				"<span>Stok terakhir : <span class=\"text-muted pull-right\">"+formatNumeric(item.total_stock)+"</span></span>"+
				"</div>")
                .appendTo(ul);
        };
	});

	$("body").on("change", ".tb_list_item", function() {
		$(this).find("input:not([class*='autocom_item_id'])").on("keydown",function(e) {
			if (e.which == 13) {
				$(".btnplus_list_item").click();
				$(".autocom_item_id:last").focus();
				e.stopImmediatePropagation();
				return false;
			}
		});
	});
	$("#fm_opname_header").on("submit",function(){
		if (confirm("Simpan data opname?")) {
			return true;
		}else{
			return false;
		}
	});
  <?=$this->config->item('footerJS')?>
  </script>
