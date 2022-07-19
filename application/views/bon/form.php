    <div class="col-md-12">
		<?=form_open("bon/save",["method"=>"post","id"=>"fm_bon"],$model)?>
		<?=form_hidden("bon_id")?>
		<div class="col-md-6">
			<div class="form-group">
				<label>Tanggal</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<div class="input-group-addon">
						<i class="fa fa-close"></i>
					</div>
					<input type="text" class="form-control" name="mutation_date" id="mutation_date" />
				</div>

			</div>
			<div class="form-group">
				<label>Nomor</label>
				<input type="text" class="form-control" name="mutation_no" id="mutation_no" readonly/>
			</div>
			<?=create_select2(["attr"=>["name"=>"unit_id=unit peminta ","id"=>"unit_id","class"=>"form-control"],
					"model"=>["m_bon" => "get_unit","column"=>["unit_id","unit_name"]]
			])?>
			<?=create_input("bon_date=Tanggal Permintaan",$attr = ["readonly"=>"readonly"])?>
		</div>
		<div class="col-md-6">
			<?=create_input("bon_no=Nomer Permintaan",$attr = ["readonly"=>"readonly"])?>
			<?=create_select(["attr"=>["name"=>"own_id=Kepemilikan","id"=>"own_id","class"=>"form-control"],
					"model"=>["m_bon" => "get_kepemilikan","column"=>["own_id","own_name"]]
			])?>
			<?=create_input("unit_target")?>
			<div class="form-group">
				<label for="unit_id">Satuan Dipakai </label>
				<select name="satuan_dipakai" id="satuan_dipakai" class="form-control">
					<?php foreach ($setting as $key=>$value){ ?>
						<option value="<?= $key?>"><?=$value?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-md-12">
        	<div class="list_item_permintaan"></div>
    	</div>




<!--			--><?//=create_input("bon_status")?>
<!---->

<!---->
<!--			--><?//=create_input("user_id")?>
<!---->
<!--			--><?//=create_input("item_status")?>
<!--			--><?//=create_input("bon_no_uniq")?>
<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_bon').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$(document).ready(()=>{
		var dataItemSale;
		$(".list_item_permintaan").inputMultiRow({
				column: ()=>{
					var dataku;
					$.ajax({
						'async': false,
						'type': "GET",
						'dataType': 'json',
						'url': "bon/show_multiRows",
						'success': function (data) {
							dataku = data;
							// const sub_total_racikan = $("#sub_total_racikan").text();
							// const sub_total_nonracikan = $("#sub_total_nonracikan").text();
							// const total = Math.round(sub_total_nonracikan)+Math.round(sub_total_racikan);
							// $("#pembulatan_biaya").html(total);
							// $("#grand_total").html(total);
						}
					});
					return dataku;
					},
				"data": dataItemSale
		});
	})

	$("#btn-cancel").click( () => {
		$("#form_bon").hide();
		$("#form_bon").html('');
	});

	$("#mutation_date").datepicker({dateFormat:"yy-mm-dd"});

	$('#mutation_date').val('<?php echo date('Y-m-d')?>');
	$('#bon_date').val('<?php echo date('Y-m-d')?>');
	$.get("<?php echo base_url()?>bon/get_code_permintaan_unit", function(data){
		$("#mutation_no").val(data);
		$("#bon_no").val(data);
	});

  <?=$this->config->item('footerJS')?>

</script>
