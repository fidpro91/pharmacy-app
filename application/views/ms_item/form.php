    <div class="col-md-12">
      			<?=form_open("ms_item/save",["method"=>"post","class"=>"form-vertical","id"=>"fm_ms_item"],$model)?>
		<?=form_hidden("item_id")?>
		<div class="row">
			<div class="col-md-3">
				<?=create_input("item_code=Kode Item")?>
				<?=create_input("item_name=Nama Paten")?>
				<?=create_input("item_name_generic=Nama Generik")?>
				<?=create_input("item_desc=diskripsi")?>
				<?= create_select([
						"attr" => ["name" => "comodity_id=Komoditi", "id" => "comodity_id", "class" => "form-control", 'required' => true],
						"model" => [
								"m_ms_comodity" => ["get_ms_comodity", ["comodity_active" => 't']],
								"column" => ["comodity_id", "comodity_name"]
						],
				]) ?>
				<?= create_select([
						"attr" => ["name" => "classification_id=Klasifikasi", "id" => "classification_id", "class" => "form-control", 'required' => true],
						"model" => [
								"m_ms_classification" => ["get_ms_classification", ["classification_active" => 't']],
								"column" => ["classification_id", "classification_name"]
						],
				]) ?>
				<?=create_input("item_package=Kemasan")?>
				<?=create_input("item_unitofitem=Satuan")?>
			</div>
			<div class="col-md-3">

				<?=create_input("qty_packtounit=satuan pack ke unit")?>
			<?= create_select([
					"attr" => ["name" => "is_generic=Jenis Obat", "id" => "is_generic", "class" => "form-control", 'required' => true],
					"option" => [["id" => 't', "text" => "Generik"], ["id" => 'f', "text" => "Paten(Nama Dagang)"]],
			]) ?>
			<?= create_select([
						"attr" => ["name" => "item_form=Bentuk Sediaan", "id" => "item_form", "class" => "form-control", 'required' => true],
						"model" => [
								"m_ms_item" => ["get_data_bentuk", ["0" => '0']],
								"column" => ["reff_id", "reff_name"]
						],
				]) ?>
			<?=create_input("item_strength=Kekuatan")?>
			<?= create_select([
					"attr" => ["name" => "is_formularium=Golongan Obat", "id" => "is_formularium", "class" => "form-control", 'required' => true, "onchange" => "getVal(this)"],
					"option" => [["id" => 'f', "text" => "Non Formularium"], ["id" => 't', "text" => "Formularium"]],
			]) ?>
			<div id="jenis_formilarium">
			<?= create_select2([
					"attr" => ["name" => "type_formularium=Jenis Formularium", "id" => "type_formularium", "class" => "form-control", 'required' => true],
					"model" => [
							"m_ms_item" => ["get_data_formularium", ["0" => '0']],
							"column" => ["reff_id", "reff_name"]
					],
			]) ?>
			</div>
			<?= create_select([
					"attr" => ["name" => "item_active=Status", "id" => "item_active", "class" => "form-control", 'required' => true],
					"option" => [["id" => 't', "text" => "Aktif"], ["id" => 'f', "text" => "Non Aktif"]],
			]) ?>
			</div>
			<div class="col-md-6">
				<div class="list_item">
				</div>
			</div>
		</div>



<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_ms_item').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_ms_item").hide();
		$("#form_ms_item").html('');
	});

function getVal(sel) {
	if(sel.value=='t'){$("#jenis_formilarium").show();}else{$("#jenis_formilarium").hide();}
}
$(document).ready(function () {
	$("#jenis_formilarium").hide();
	$("#item_package").autocomplete({
		source: function( request, response ) {
			$.ajax( {
                    url: "ms_item/package/",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        response($.map(data, function (item) {
                            return {
                                label: item.package_name,
                                value: item.package_name
                            };
                        }));



                    }
               })
		}
	})
	$("#item_unitofitem").autocomplete({
		source: function( request, response ) {
			$.ajax( {
                    url: "ms_item/get_satuan/",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        response($.map(data, function (item) {
                            return {
                                label: item.unitofitem_name,
                                value: item.unitofitem_name
                            };
                        }));

                    }
               })
		}
	})

	// console.log(mutationDetail);
	$(".list_item").inputMultiRow({
		column: () => {
			var dataku;
			$.ajax({
				'async': false,
				'type': "GET",
				'dataType': 'json',
				'url': "ms_item/show_multiRows",
				'success': function(data) {
					dataku = data;
				}
			});
			return dataku;
		},
		"data" : dataprice
	});

})

  <?=$this->config->item('footerJS')?>
</script>
