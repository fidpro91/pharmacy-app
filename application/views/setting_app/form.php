    <div class="col-md-12">
      			<?=form_open("setting_app/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_setting_app"],$model)?>
		<?=form_hidden("setting_id")?>
			<?=create_input("setting_name")?>
			<?=create_input("setting_value")?>
			<?=create_input("is_active")?>
			<?=create_input("setting_type")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_setting_app').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_setting_app").hide();
		$("#form_setting_app").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>