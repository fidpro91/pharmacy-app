    <div class="col-md-12">
      			<?=form_open("tabel_srb/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_tabel_srb"],$model)?>
		<?=form_hidden("srb_id")?>
			<?=create_input("saran")?>
			<?=create_input("no_sep")?>
			<?=create_input("visit_id")?>
			<?=create_input("created_at")?>
			<?=create_input("tgl_srb")?>
			<?=create_input("obat")?>
			<?=create_input("srb_status")?>
			<?=create_input("program_prb")?>
			<?=create_input("no_srb")?>
			<?=create_input("user_id")?>
			<?=create_input("srv_id")?>
			<?=create_input("keterangan")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_tabel_srb').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_tabel_srb").hide();
		$("#form_tabel_srb").html('');
	});

  <?=$this->config->item('footerJS')?>
</script>