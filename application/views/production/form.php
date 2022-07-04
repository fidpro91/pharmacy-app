    <div class="col-md-12">
      			<?=form_open("production/save",["method"=>"post","id"=>"fm_production"],$model)?>
		<?=form_hidden("production_id")?>
		<div class="row">
<?= form_fieldset(''); ?>
		<div class = "col-md-6">			
		<?= create_input("production_no",[
							"value"=>$norec,
							"readonly"=>true]) ?>

			<?= create_inputDate("production_date=Tgl Produksi", [
			"format" => "yyyy-mm-dd",
			"autoclose" => "true"
				]) ?>		
			<?=create_select2([
                                "attr" =>["name"=>"unit_id=Unit Farmasi","id"=>"unit_id","class"=>"form-control"],
                                "model"=>["m_ms_unit" => ["get_ms_unit",["0"=>'0']],
                                                "column"  => ["unit_id","unit_name"]
                                            ]
                            ])?>			
		</div>
			<div class = "col-md-6">
			<?=create_select([
                                "attr" =>["name"=>"own_id=Kepemilikan","id"=>"own_id","class"=>"form-control"],
                                "model"=>["m_ownership" => ["get_ownership",["0"=>'0']],
                                                "column"  => ["own_id","own_name"]
                                            ]
                            ])?>
			<?=create_textarea("production_note=Keterangan")?>					
			<?=create_select2([
                                "attr" =>["name"=>"rec_unit_pro=Unit Tujuan","id"=>"rec_unit_pro","class"=>"form-control"],
                                "model"=>["m_ms_unit" => ["get_ms_unit",["0"=>'0']],
                                                "column"  => ["unit_id","unit_name"]
                                            ]
                            ])?>
		</div>
<?= form_fieldset_close(); ?>
<?= form_fieldset(''); ?>
		<div class="col-md-6">
		<div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar Bahan Produksi Obat</h3>
                    <div class="box-tools pull-right">
                        <div class="form-group">                                           
                        </div>
                    </div>
                </div>
                <div class="item_bahan box-body" id="item_bahan">
                </div>
            </div>
	</div>
	
	<div class="col-md-6">
	<div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar Hasil Produksi Obat</h3>
                    <div class="box-tools pull-right">
                        <div class="form-group">                         
                        </div>
                    </div>
                </div>
                <div class="item_hasil box-body" id="item_hasil">
                </div>
            </div>
	</div>
	<?= form_fieldset(''); ?>
		
</div>

<?=form_close()?>
      <div class="box-footer" align="center">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_production').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_production").hide();
		$("#form_production").html('');
	});
	$(document).ready(()=>{
        $(".item_bahan").inputMultiRow({
	            column: ()=>{
					var dataku; 
					$.ajax({
						'async': false,
						'type': "GET",
						'dataType': 'json',
						'url': "Production/show_multiRows_produksi",
						'success': function (data) {
							dataku = data;
						}
					});
					return dataku;
	                },   "data" : item_produk           
	    });        
	});

	$(document).ready(()=>{
		//var item_hasil ;
		//console.log(item_hasil);
        $(".item_hasil").inputMultiRow({
	            column: ()=>{
					var dataku; 
					$.ajax({
						'async': false,
						'type': "GET",
						'dataType': 'json',
						'url': "Production/show_multiRows_hasil",
						'success': function (data) {
							dataku = data;
						}
					});
					return dataku;
	                },   
					"data" : item_hasil            
	    });        
	});

	$("body").on("focus", ".autocom_item_id", function() {
	    $(this).autocomplete({
            source: "<?php echo site_url('Production/get_item');?>/" + $("#own_id").val() + "/" + $("#unit_id").val(),
            select: function (event, ui) {
                $(this).closest('tr').find('.item_id').val(ui.item.item_id);    
				
				$(this).closest('tr').find('.item_price').val(ui.item.harga);			
               
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) { 
            return $("<li>")
                .append('<a>'
                    + '<table class="table"><tr>'
                    + '<td style="width:150px">' + item.value + '</td>'
					+ '<td style="width:40px">' + item.total_stock + '</td>'                    
                    + '</tr></table></a>')
                .appendTo(ul);
        };
	});
	

  <?=$this->config->item('footerJS')?>
</script>