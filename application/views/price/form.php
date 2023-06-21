    <div class="col-md-12">
			<?=form_open("price/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_price"],$model)?>
		<?=form_hidden("item_id")?>
		<?=form_hidden("price_id")?>
		<?=create_select2([
				"attr" =>["name"=>"own_id=Unit penerima","id"=>"own_id","class"=>"form-control"],
				"model"=>["m_ownership" => ["get_ownership",[0=>0]],
						"column"  => ["own_id","own_name"]
				]
		])?>
		<?=create_input("nama_obat")?>
		<?=create_input("price_buy")?>
		<?=create_input("price_sell")?>
		<?=create_input("profit")?>

			
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

	$('body').on("keyup", "#nama_obat", function() {
		$(this).autocomplete({
			source: function(request, response) {
				$.post(
					"<?php echo base_url();?>price/get_item/",
					'&term='+request.term,
					response, 'json'
				);
			},
			minLength:2,
			autofocus:true,
			select: function( event, ui ) {
				$("#item_id").val( ui.item.item_id);
				$("#nama_obat").val( ui.item.nama_obat);
			}
		})
			.autocomplete().data("uiAutocomplete")._renderItem =  function( ul, item ){
			return $( "<li>" )
				.append( "<a style='font-size:12px'>" +item.value+"</a>" )
				.appendTo( ul );
		};
	});

  <?=$this->config->item('footerJS')?>
</script>
