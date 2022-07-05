    <div class="col-md-12">
      			<?=form_open("price/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_price"],$model)?>
		<?=form_hidden("item_id")?>
		<?=form_hidden("own_id")?>
		<?=form_hidden("price_id")?>
			<?=create_input("nama_obat")?>
			<?=create_input("price_buy")?>			
			<?=create_input("price_sell")?>
			<?=create_input("profit")?>
			<?=create_input("stock_min")?>
			<?=create_input("stock_max")?>				
			
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_price').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_price").hide();
		$("#form_price").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>