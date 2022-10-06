<style>
	.list_item {
		min-height: 330px !important;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<?= form_open("sale_return/save", ["method" => "post", "id" => "fm_sale_return"], $model) ?>
		<div class="col-md-3">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Informasi Retur</h3>
				</div>
				<div class="box-body">
					<?= form_hidden("sr_id") ?>
					<?= form_hidden("service_id") ?>
					<?= form_hidden("visit_id") ?>
					<?= create_inputDate("sr_date", [
						"format" => "yyyy-mm-dd",
						"autoclose" => "true"
					],[
						"value"		=> date('Y-m-d'),
						'readonly'	=> true
					])?>
					<?= create_input("sr_num",[
						"readonly"	=>true,
						"value"		=> $sr_num
					]) ?>
					<?= create_input("patient_norm") ?>
					<?= create_input("patient_name") ?>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="box box-primary">
				<div class="box-body">
					<div class="col-xs-3">
						<?= create_inputMask("total_item","IDR",["readonly"=>true]) ?>
					</div>
					<div class="col-xs-3">
						<?= create_inputMask("total_qty","IDR",["readonly"=>true]) ?>
					</div>
					<div class="col-xs-3">
						<?= create_inputMask("embalase","IDR",["readonly"=>true]) ?>
					</div>
					<div class="col-xs-3">
						<?= create_inputMask("total_return","IDR",["readonly"=>true]) ?>
					</div>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Item Retur</h3>
				</div>
				<div class="list_item box-body">
				</div>
			</div>
		</div>
		<?= form_close() ?>
		<div class="box-footer" align="center">
			<button class="btn btn-primary" type="button" onclick="$('#fm_sale_return').submit()">Save</button>
			<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#btn-cancel").click(() => {
		$("#form_sale_return").hide();
		$("#form_sale_return").html('');
	});

	$("body").on("focus", "#patient_norm", function() {
        $(this).autocomplete({
            source: "<?php echo site_url('sale_return/get_no_rm/norm'); ?>",
			autoFocus: true,
			minLength:4,
            select: function(event, ui) {
                $('#patient_norm').val(ui.item.px_norm);
                $('#patient_name').val(ui.item.px_name);
				$('#visit_id').val(ui.item.visit_id);
				$('#service_id').val(ui.item.srv_id);
				$('.list_item').load("sale_return/get_sale_detail/"+ui.item.srv_id)
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li>")
			.append("<div style='color: black' class='comment-text'><span class=\"username\"><b>" +
				item.px_norm + "|" + item.px_name +
				"</b><span class=\"text-muted pull-right\">" + item.unit_name + "</span></span><p>" +
				"<span>Tanggal Kunjung : <span class=\"text-muted pull-right\">" + (item.srv_date) + "</span></span><br>" +
				"<span>Status Kunjung : <span class=\"text-muted pull-right\">" + (item.status_kunjungan) + "</span></span><br>" +
				"<span>Dokter : <span class=\"text-muted pull-right\">" + (item.par_name) + "</span></span>" +
				"</div>")
			.appendTo(ul);
        };
        return false;
    });

	$("#fm_sale_return").on("submit",()=>{
		$("#total_item").attr('readonly',false);
	});

	$('#fm_sale_return').on("submit",function(){
		$.blockUI();
		$.ajax({
			'type': "post",
			'data'	: $(this).serialize()+"&unit_id="+$("#unit_id_depo").val(),
			'dataType': 'json',
			'url': "sale_return/save",
			'success': function (data) {
				$.unblockUI();
				alert(data.message);
				if (data.code == '200') {
					location.reload(true);
				}
			}
		});
		return false;
	});

	function grandTotal(total) {
		let embalase = total/100;
		embalase = (parseFloat(Math.abs(Math.ceil(embalase)-embalase)).toFixed(2))*100;
		total = total+embalase;
		$("#embalase").val(embalase);
		$("#total_return").val(total);
	}
	<?= $this->config->item('footerJS') ?>
</script>
