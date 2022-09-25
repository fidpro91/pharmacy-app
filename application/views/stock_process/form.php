    <div class="col-md-12">
      			<?=form_open("stock_process/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_stock_process"],$model)?>
			<?=create_input("item_id")?>
			<?=create_input("own_id")?>
			<?=create_input("unit_id")?>
			<?=create_input("date_trans")?>
			<?=create_input("date_act")?>
			<?=create_input("trans_num")?>
			<?=create_input("trans_type")?>
			<?=create_input("stock_before")?>
			<?=create_input("debet")?>
			<?=create_input("kredit")?>
			<?=create_input("stock_after")?>
			<?=create_input("item_price")?>
			<?=create_input("total_price")?>
			<?=create_input("description")?>
			<?=create_input("type_act")?>
			<?=create_input("stockprocess_id")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_stock_process').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_stock_process").hide();
		$("#form_stock_process").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>