    <div class="col-md-12">
      			<?=form_open("import_harga/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_admin.import_harga"],$model)?>
			<?=create_input("kode")?>
			<?=create_input("nama")?>
			<?=create_input("harga")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_admin.import_harga').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_import_harga").hide();
		$("#form_import_harga").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>