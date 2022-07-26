    <div class="col-md-12">
      			<?=form_open("stock_maxmin_unit/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_stock_maxmin_unit"],$model)?>
		<?=form_hidden("id")?>
			<?=create_input("unit_id")?>
			<?=create_input("own_id")?>
			<?=create_input("item_id")?>
			<?=create_input("stock_max")?>
			<?=create_input("stock_min")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_stock_maxmin_unit').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_stock_maxmin_unit").hide();
		$("#form_stock_maxmin_unit").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>