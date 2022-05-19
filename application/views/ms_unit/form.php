    <div class="col-md-12">
      			<?=form_open("ms_unit/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_ms_unit"],$model)?>
		<?=form_hidden("unit_id")?>
			<?=create_input("unit_code")?>
			<?=create_input("unit_name")?>
			<?=create_input("unit_nickname")?>
			<?=create_input("unit_level")?>
			<?=create_input("unit_islast")?>
			<?=create_input("unit_active")?>
			<?=create_input("unit_type")?>
			<?=create_input("unit_inpatient_status")?>
			<?=create_input("unit_support_status")?>
			<?=create_input("unit_id_parent")?>
			<?=create_input("ut_id")?>
			<?=create_input("kodeaskes")?>
			<?=create_input("inap")?>
			<?=create_input("is_vip")?>
			<?=create_input("no_retrib")?>
			<?=create_input("group_finder")?>
			<?=create_input("show_pm")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_ms_unit').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_ms_unit").hide();
		$("#form_ms_unit").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>