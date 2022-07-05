    <div class="col-md-12">
      			<?=form_open("dose/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_dose"],$model)?>
		<?=form_hidden("dose_id")?>
			<?=create_input("dose_code")?>
			<?=create_input("dose_name")?>			
			<?= create_select([
					"attr" => ["name" => "dose_active=status", "id" => "dose_active", "class" => "form-control"],
					"option" => [["id" => 't', "text" => "Aktif"], ["id" => 'f', "text" => "Non Aktif"]],
			]) ?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_dose').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_dose").hide();
		$("#form_dose").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>