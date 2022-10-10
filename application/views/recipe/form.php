    <div class="col-md-12">
      			<?=form_open("recipe/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_recipe"],$model)?>
		<?=form_hidden("rcp_id")?>
			<?=create_input("rcp_date")?>
			<?=create_input("services_id")?>
			<?=create_input("rcp_status")?>
			<?=create_input("user_id")?>
			<?=create_input("doctor_id")?>
			<?=create_input("unit_id")?>
			<?=create_input("rcp_no")?>
			<?=create_input("diagnosa_id")?>
			<?=create_input("verificated")?>
			<?=create_input("verificator_id")?>
			<?=create_input("verified_at")?>
			<?=create_input("racikan_txt")?>
			<?=create_input("px_id")?>
			<?=create_input("visit_id")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_recipe').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_recipe").hide();
		$("#form_recipe").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>