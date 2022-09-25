<div class="col-md-12">
	<?=form_open("surety_ownership/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_surety_ownership"],$model)?>
		<?=form_hidden("surety_id")?>
		<?=form_hidden("own_id")?>
		<?=create_input("priority")?>
		<?=create_input("percent_profit")?>
	<?=form_close()?>
	<div class="box-footer">
		<button class="btn btn-primary" type="button" onclick="$('#fm_surety_ownership').submit()">Save</button>
		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
	</div>
</div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_surety_ownership").hide();
		$("#form_surety_ownership").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>