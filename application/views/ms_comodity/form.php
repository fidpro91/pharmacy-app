    <div class="col-md-12">
      			<?=form_open("ms_comodity/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_ms_comodity"],$model)?>
			<?=create_input("comodity_id")?>
			<?=create_input("comodity_code")?>
			<?=create_input("comodity_name")?>
			<?=create_input("comodity_active")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_ms_comodity').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_ms_comodity").hide();
		$("#form_ms_comodity").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>