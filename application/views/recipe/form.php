<style>
	.ui-autocomplete {
		z-index: 2147483647;
	}
	.comment-text{
		color: black !important;
	}
</style>
<?= form_open("recipe/save", ["method" => "post", "id" => "fm_recipe"], $model) ?>
<div class="row">
	<div class="col-md-2">
		<?= form_hidden("rcp_id") ?>
		<?= form_hidden("px_id") ?>
		<?= form_hidden("visit_id") ?>
		<?= form_hidden("par_id") ?>
		<?= form_hidden("surety_id") ?>
		<?= form_hidden("unit_id_lay") ?>
		<?= form_hidden("services_id") ?>
		<?= form_hidden("percent_profit") ?>
		<?= create_inputDate("rcp_date", [
			"format"        => "yyyy-mm-dd",
			"autoclose"     => "true",
			"endDate"       => "today"
		], [
			"readonly"  => true,
			"required"  => true,
			"value"     => date('Y-m-d')
		]) ?>
		<?= create_input("rcp_no", [
			"readonly" => true
		]) ?>
		<?= create_select([
			"attr" => ["name" => "jns_resep=Pelayanan Resep", "id" => "jns_resep", "class" => "form-control"],
			"option" => [["id" => '1', "text" => "Resep Penuh"], ["id" => '2', "text" => "Resep Partial"]],
		]) ?>
		<?= create_select([
			"attr" => ["name" => "own_id=Kepemilikan", "id" => "own_id", "class" => "form-control", "required"  => true],
			"model" => [
				"m_surety_ownership" => ["get_kepemilikan", ["0" => '0']],
				"column"  => ["own_id", "own_name"]
			],
			"selected" => 1
		]) ?>
		
	</div>
	
	<div class="col-md-3">
	
		<div class="box box-primary">
			<div class="box-header">
				TELAAH RESEP
			</div>
			<div class="box-body">
				<div style="border:1px solid #000;">
					<table class="table">
						<?php
							/* $col = 4;
							$brs = count($kelengkapan) / $col;
							$clear = [];
							for ($i = 0; $i < $brs; $i++) {
								echo "<tr>";
								for ($j = 0; $j < $col; $j++) {
									$ii = ($j * $brs) + $i;
									if (!in_array($kelengkapan[$ii]['reff_name'], $clear)) {
										$checked = "";
										if (isset($kelengkapan[$ii]['revrcp_id'])) {
											$checked = "checked";
										}
										echo "<td> <label><input $checked type=\"checkbox\" name=\"cek_kelengkapan[]\" value=\"" . $kelengkapan[$ii]['reff_id'] . "\"/> " . $kelengkapan[$ii]['reff_name'] . "</label></td>\n";
									}
									$clear[] = $kelengkapan[$ii]['reff_name'];
								}
								echo "</tr>";
							} */
							$row="";
							foreach ($kelengkapan as $key => $value) {
								$checked = "";
								if (isset($value['revrcp_id'])) {
									$checked = "checked";
								}
								$row .= "<tr><td> <label><input $checked type=\"checkbox\" name=\"cek_kelengkapan[]\" value=\"" . $value["reff_id"] . "\"/> " . $value['reff_name'] . "</label></td>
								</tr>\n";
								
							}
							echo $row;
							
						?>
						
					</table>
				</div>
			</div>
		</div>
		<?=create_textarea("note_recipe=catatan telaah")?>
	</div>
	
	<div class="col-md-7">
		<div class="box box-widget widget-user-2">
				<div class="widget-user-header bg-aqua-active">
					<div class="pull-right">
						<h3 class="widget-user-username">
							<i class="fa fa-stethoscope"></i>
							<span class="unit_layanan"></span>
						</h3>
						<h3 class="widget-user-username">
							<i class="fa fa-user-md"></i>
							<span class="dpjp_layan"></span>
						</h3>
					</div>
				<div class="widget-user-image">
					<img class="img-circle" src="https://via.placeholder.com/128" alt="User Avatar">
				</div>
				<h3 class="widget-user-username px_norm">John Doe</h3>
				<h4 class="widget-user-username px_name">John Doe</h4>
				</div>
				<div class="box-footer">
				<div class="row">
					<div class="col-sm-4 border-right">
					<div class="description-block">
						<h5 class="description-header">Cara Bayar</h5>
						<span class="description-text cara_bayar">215</span>
					</div>
					</div>
					<div class="col-sm-4 border-right">
					<div class="description-block">
						<h5 class="description-header">No Kartu</h5>
						<span class="description-text noka">150</span>
					</div>
					</div>
					<div class="col-sm-4">
					<div class="description-block">
						<h5 class="description-header">No SEP</h5>
						<span class="description-text nosep">34</span>
					</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	<div class="col-md-12">
		<div class="list_recipe"></div>
	</div>
	<?= form_close() ?>
	<div class="col-md-12">
		<button class="btn btn-primary" type="button" onclick="$('#fm_recipe').submit()">Save</button>
		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
	</div>
</div>
<script type="text/javascript">
	var dataItemRecipe = null;
	var dataku;
	$(document).ready(() => {
		$(".list_recipe").inputMultiRow({
			column: () => {
				$.ajax({
					'async': false,
					'type': "GET",
					'dataType': 'json',
					'url': "recipe/show_multiRows/<?= $recipe_id ?>",
					'success': function(data) {
						dataku = data;
					}
				});
				return dataku;
			},
			"data": dataItemRecipe
		});
		setTimeout(() => {
			$("#own_id").trigger("change");
		}, '500');
	});
	$("#btn-cancel").click(() => {
		$("#form_recipe").hide();
		$("#form_recipe").html('');
	});

	$("body").on("change", ".tb_list_recipe", function() {
		$('.tb_list_recipe > tbody  > tr').each(function() {
			const jumlah_barang = $(this).find(".qty").val();
			const harga_satuan = (valid_numeric($(this).find(".sale_price").val()) + (valid_numeric($(this).find(".sale_price").val()) * valid_numeric($("#percent_profit").val())));
			const total_item = jumlah_barang * harga_satuan;
			$(this).find('.price_total').val(total_item);
		});
		/* $(this).find("input").on('keyup', null, 'ctrl+a', function(e) {
			$(".btnplus_list_recipe").click();
			$(".autocom_item_id:last").focus();
			e.stopImmediatePropagation();
			return false;
		});
		$(this).find("input").on('keydown', null, 'ctrl+s', function(e) {
			$("#btn-save-updated").click();
			e.stopImmediatePropagation();
			return false;
		}); */
		$(this).find("input:not([class*='autocom_item_id'])").on("keydown", function(e) {
			if (e.which == 13) {
				$(".btnplus_list_recipe").click();
				$(".autocom_item_id:last").focus();
				e.stopImmediatePropagation();
				return false;
			}
		});
	});

	$("body").on("focus", ".autocom_item_id", function() {
		$(this).autocomplete({
			source: "<?php echo site_url('sale/get_item'); ?>/" + $("#unit_id_depo").val(),
			autoFocus: true,
			select: function(event, ui) {
				$('tr[class*="list_obat"]').each(function(i, a) {
					if ($(this).find('.item_id').val() == ui.item.item_id) {
						$(this).eq((i)).closest('tr').find('.qty').focus();
						$(this).last().find('.removeItem_list_obat').click();
						return false;
					}
				});
				$(this).closest('tr').find('.item_id').val(ui.item.item_id);
				$(this).closest('tr').find('.stock').val(ui.item.total_stock);
				$(this).closest('tr').find('.sale_price').val(ui.item.harga);
				$(this).closest('tr').find('.qty').focus();
			}
		}).data("ui-autocomplete")._renderItem = function(ul, item) {
			return $("<li>")
				.append("<div class='comment-text'><span class=\"username\"><b>" +
					item.item_name + "|" + item.item_code +
					"</b><span class=\"text-muted pull-right\">" + formatNumeric(item.harga) + "</span></span><p>" +
					"<span>Kategori Item : <span class=\"text-muted pull-right\">" + (item.classification_name) + "</span></span><br>" +
					"<span>Stok terakhir : <span class=\"text-muted pull-right\">" + formatNumeric(item.total_stock) + "</span></span>" +
					"</div>")
				.appendTo(ul);
		};
	});

	$('#fm_recipe').on("submit", function() {
		$(this).data("validator").settings.submitHandler = function(form) {
			$('#modal_recipe').find('.modal-body').block({
				message: "<h1>Processing</h1>"
			})
			$.ajax({
				'type': "post",
				'data': $(form).serialize() + "&unit_id=" + $("#unit_id_depo").val(),
				'url': "recipe/save",
				'dataType': 'json',
				'success': function(data) {
					$('#modal_recipe').find('.modal-body').unblock();
					alert(data.message);
					if (data.code !== '200') {
						return false;
					}
					location.reload(true);
				},
				timeout: 5000,
				error: function(jqXHR, textStatus, errorThrown) {
					$('#modal_recipe').unblock();
					if (errorThrown == 'timeout') {
						alert("Data berhasil disimpan");
						leavePage = false;
						location.reload(true);
					} else {
						$('#modal_recipe').find('.modal-body').unblock();
					}
				}
			});
		}
	});

	$("#own_id").change(function() {
		$.post("recipe/get_recipe_detail", {
			"unit_id": +$("#unit_id_depo").val(),
			"rcp_id": $("#rcp_id").val(),
			"own_id": $(this).val(),
			"surety_id": $("#surety_id").val(),
		}, function(resp) {
			$(".list_recipe").inputMultiRow({
				column: () => {
					return dataku;
				},
				"data": resp
			});
		}, 'json').then(function() {
			$('.tb_list_recipe > tbody  > tr').each(function() {
				const jumlah_barang = $(this).find(".qty").val();
				const harga_satuan = valid_numeric($(this).find(".sale_price").val()) + (valid_numeric($(this).find(".sale_price").val()) * valid_numeric($("#percent_profit").val()));
				const total_item = jumlah_barang * harga_satuan;
				$(this).find('.price_total').val(total_item);
				// $(this).find('.price_total').inputmask("IDR");
			});
		});
	});

	function valid_numeric(a) {
		return parseFloat($.isNumeric(a) ? a : 0);
	}

	<?= $this->config->item('footerJS') ?>
</script>