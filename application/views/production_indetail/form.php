    <div class="col-md-12">
      			<?=form_open("production_indetail/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_production_indetail"],$model)?>
		<?=form_hidden("production_indetail_id")?>
			<?=create_input("production_id")?>
			<?=create_input("item_id")?>
			<?=create_input("qty_item")?>
			<?=create_input("item_price")?>
			<?=create_input("recdet_id")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_production_indetail').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_production_indetail").hide();
		$("#form_production_indetail").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>