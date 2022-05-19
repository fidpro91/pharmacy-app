    <div class="col-md-12">
      	<?=form_open("receiving_retur/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_receiving_retur"],$model)?>
		<?=form_hidden("rr_id")?>
		<?=create_input("num_retur")?>
		<?=create_input("rr_date")?>
		<?=create_input("rr_type")?>
		<div class="box box-primary">
			<div class="box-header">
				List Item retur
			</div>
			<div class="box-body">
				<div class="list_item"></div>
			</div>
		</div>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_receiving_retur').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_receiving_retur").hide();
		$("#form_receiving_retur").html('');
	});
	$(document).ready(() => {
		$(".list_item").inputMultiRow({
			column: () => {
				var dataku;
				$.ajax({
					'async': false,
					'type': "GET",
					'dataType': 'json',
					'url': "receiving_retur/show_multiRows",
					'success': function(data) {
						dataku = data;
					}
				});
				return dataku;
			},
			"data" : dataRetur
		});
	});
  <?=$this->config->item('footerJS')?>
</script>