    <div class="col-md-12">
      			<?=form_open("stock/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_stock"],$model)?>
		<?=form_hidden("id")?>
			<?=create_input("item_id")?>
			<?=create_input("own_id")?>
			<?=create_input("unit_id")?>
			<?=create_input("stock_summary")?>
			<?=create_input("total_price")?>
			<?=create_input("update_date")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_stock').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_stock").hide();
		$("#form_stock").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>