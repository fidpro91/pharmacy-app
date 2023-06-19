    <div class="col-md-12">
      			<?=form_open("ms_menu/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_ms_menu"],$model)?>
		<?=form_hidden("menu_id")?>
			<?=create_input("menu_code")?>
			<?=create_input("menu_name")?>
			<?=create_input("menu_url")?>
			<?=create_input("menu_parent_id")?>
			<?=create_input("menu_status")?>
			<?=create_input("menu_icon")?>
			<?=create_input("modul_id")?>
			<?=create_input("slug")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_ms_menu').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_ms_menu").hide();
		$("#form_ms_menu").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>