    <div class="col-md-12">
      			<?=form_open("/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_"],$model)?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_").hide();
		$("#form_").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>