    <div class="col-md-12">
      			<?=form_open("sale/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_farmasi.sale"],$model)?>
		<?=form_hidden("sale_id")?>
			<?=create_input("sale_num")?>
			<?=create_input("sale_date")?>
			<?=create_input("unit_id")?>
			<?=create_input("visit_id")?>
			<?=create_input("patient_name")?>
			<?=create_input("user_id")?>
			<?=create_input("date_act")?>
			<?=create_input("sale_status")?>
			<?=create_input("sale_shift")?>
			<?=create_input("sale_type")?>
			<?=create_input("service_id")?>
			<?=create_input("surety_id")?>
			<?=create_input("sale_embalase")?>
			<?=create_input("sale_services")?>
			<?=create_input("doctor_id")?>
			<?=create_input("own_id")?>
			<?=create_input("rcp_id")?>
			<?=create_input("cash_id")?>
			<?=create_input("finish_user_id")?>
			<?=create_input("finish_time")?>
			<?=create_input("doctor_name")?>
			<?=create_input("verificated")?>
			<?=create_input("verificator_id")?>
			<?=create_input("verified_at")?>
			<?=create_input("sale_total")?>
			<?=create_input("sale_cover")?>
			<?=create_input("patient_norm")?>
			<?=create_input("kronis")?>
			<?=create_input("pay_act")?>
			<?=create_input("embalase_item_sale")?>
			<?=create_input("sale_total_returned")?>
			<?=create_input("sale_total_ppn")?>
			<?=create_input("sale_total_payment")?>
			<?=create_input("sale_is_paid")?>
			<?=create_input("sale_total_surety")?>
			<?=create_input("sale_total_beforediscount")?>
			<?=create_input("sale_discount_percent")?>
			<?=create_input("sale_discount_nominal")?>
			<?=create_input("kronis_drug_usage")?>
			<?=create_input("apoteker_service_item")?>
			<?=create_input("apoteker_service_total")?>
			<?=create_input("apoteker_service_status")?>
			<?=create_input("start_time")?>
			<?=create_input("usage_date")?>
			<?=create_input("total_price_package")?>
			<?=create_input("sale_no")?>
			<?=create_input("unit_id_lay")?>
			<?=create_input("unit_name_lay")?>
			<?=create_input("transfer_date")?>
			<?=create_input("transfer_by")?>
			<?=create_input("no_transaksi")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_farmasi.sale').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_sale").hide();
		$("#form_sale").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>