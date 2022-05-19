    <div class="col-md-12">
      			<?=form_open("po/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_po"],$model)?>
			<?=create_input("po_date")?>
			<?=create_input("po_no")?>
			<?=create_input("supplier_id")?>
			<?=create_input("rfq_id")?>
			<?=create_input("po_status")?>
			<?=create_input("po_expired")?>
			<?=create_input("po_days")?>
		<?=form_hidden("po_id")?>
			<?=create_input("po_ppn")?>
			<?=create_input("own_id")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_po').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_po").hide();
		$("#form_po").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>