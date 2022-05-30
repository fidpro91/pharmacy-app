<div class="col-md-12">
      			<?=form_open("sale/save",["method"=>"post","id"=>"fm_farmasi.sale"],$model)?>
		<?=form_hidden("sale_id")?>
    <?=create_input("patient_name")?>
    <?=create_input("patient_norm")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_farmasi.sale').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_sale").hide();
		$("#form_sale").html('');
	});
	$("body").on("focus", "#patient_name", function () {
		$(this).autocomplete({
			source: "<?php echo site_url('sale/get_no_rm/norm');?>",
			select: function (event, ui) {
				$('#patient_name').val(ui.item.px_name);
				$('#patient_norm').val(ui.item.px_norm);			
			}
		});

	});
  <?=$this->config->item('footerJS')?>
</script>