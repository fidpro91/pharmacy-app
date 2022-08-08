    <div class="col-md-12">
		<?=form_open("ms_comodity/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_ms_comodity"],$model)?>
		<?=form_hidden("comodity_id")?>
		<?=create_input("comodity_code=Code")?>
		<?=create_input("comodity_name=Nama")?>
		<?=create_select2(["attr"=>["name"=>"comodity_active=Status","id"=>"comodity_active","class"=>"form-control"],
				"option" => [
						["id" => 't', "text" => "Aktif"],
						["id" => 'f', "text" => "Tidak Aktif"]
				],
		])?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_ms_comodity').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_ms_comodity").hide();
		$("#form_ms_comodity").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>
