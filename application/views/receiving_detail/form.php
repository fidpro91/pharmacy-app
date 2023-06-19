    <div class="col-md-12">
      			<?=form_open("receiving_detail/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_farmasi.receiving_detail"],$model)?>
		<?=form_hidden("recdet_id")?>
			<?=create_input("rec_id")?>
			<?=create_input("item_id")?>
			<?=create_input("batch_num")?>
			<?=create_input("expired_date")?>
			<?=create_input("item_pack")?>
			<?=create_input("qty_pack")?>
			<?=create_input("item_unit")?>
			<?=create_input("qty_unit")?>
			<?=create_input("unit_per_pack")?>
			<?=create_input("price_pack")?>
			<?=create_input("price_total")?>
			<?=create_input("disc_percent")?>
			<?=create_input("disc_value")?>
			<?=create_input("disc_extra")?>
			<?=create_input("qty_stock")?>
			<?=create_input("qty_retur")?>
			<?=create_input("price_item")?>
			<?=create_input("hpp")?>
			<?=create_input("recdet_id_asal")?>
			<?=create_input("price_bruto_item")?>
			<?=create_input("funds_id")?>
			<?=create_input("consignment_status")?>
			<?=create_input("supplier_id")?>
			<?=create_input("recdet_id_start")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_farmasi.receiving_detail').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_receiving_detail").hide();
		$("#form_receiving_detail").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>