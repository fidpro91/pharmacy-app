    <div class="col-md-12">
      			<?=form_open("ownership/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_ownership"],$model)?>
		<?=form_hidden("own_id")?>
			<?=create_input("own_name")?>
			<?=create_input("own_active")?>
			<?=create_input("order_num")?>
			<?=create_input("own_code")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_ownership').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_ownership").hide();
		$("#form_ownership").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>