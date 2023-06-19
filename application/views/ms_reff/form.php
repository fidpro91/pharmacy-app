    <div class="col-md-12">
      			<?=form_open("ms_reff/save",["method"=>"post","class"=>"form-horizontal"])?>
		<?=form_hidden("reff_id")?>
			<?=create_input("reff_code")?>
			<?=create_input("reff_name")?>
			<?=create_input("reff_active")?>
			<?=create_input("refcat_id")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" onclick="$('form').submit()">Save</button>
      		<button class="btn btn-warning" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_ms_reff").hide();
		$("#form_ms_reff").html('');
	});
</script>