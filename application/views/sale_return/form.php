<style>
	.list_item {
		min-height: 330px !important;
	}
</style>
<div class="col-md-12">
	<?= form_open("sale_return/save", ["method" => "post", "class" => "form-horizontal", "id" => "fm_sale_return"], $model) ?>
	<div class="col-md-4">
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
				<?= create_input("unit_id") ?>
				<?= create_input("patient_norm") ?>
				<?= create_input("patient_name") ?>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Item Retur</h3>
			</div>
			<div class="list_item box-body">
			</div>
		</div>
	</div>
	<!-- <?= create_input("user_id") ?>
	<?= create_input("own_id") ?>
	<?= create_input("sale_id") ?>
	<?= create_input("sale_type") ?>
	<?= create_input("surety_id") ?>
	<?= create_input("sr_embalase") ?>
	<?= create_input("sr_services") ?>
	<?= create_input("doctor_id") ?>
	<?= create_input("doctor_name") ?>
	<?= create_input("rcp_id") ?>
	<?= create_input("cash_id") ?>
	<?= create_input("verificated") ?>
	<?= create_input("verificator_id") ?>
	<?= create_input("verified_at") ?>
	<?= create_input("rec_id") ?>
	<?= create_input("cashretur_id") ?>
	<?= create_input("sr_total") ?>
	<?= create_input("discount") ?>
	<?= create_input("sr_total_before_discount") ?> -->
	<?= form_close() ?>
	<div class="box-footer">
		<button class="btn btn-primary" type="button" onclick="$('#fm_sale_return').submit()">Save</button>
		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
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
            select: function(event, ui) {
                $('#patient_norm').val(ui.item.px_norm);
                $('#patient_name').val(ui.item.px_name);
				$('#visit_id').val(ui.item.visit_id);
				$('#service_id').val(ui.item.srv_id);
				$('.list_item').load("sale_return/get_sale_detail/"+ui.item.srv_id)
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li>")
			.append("<div class='comment-text'><span class=\"username\"><b>" +
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

	<?= $this->config->item('footerJS') ?>
</script>