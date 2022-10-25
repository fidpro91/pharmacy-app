<?= form_open("recipe/save", ["method" => "post", "id" => "fm_recipe"], $model) ?>
<div class="row">
	<div class="col-md-2">
		<?= form_hidden("rcp_id") ?>
		<?= form_hidden("px_id") ?>
		<?= form_hidden("visit_id") ?>
		<?= form_hidden("surety_id") ?>
		<?= form_hidden("services_id") ?>
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
			]
		]) ?>
	</div>
	<div class="col-md-10">
		<div class="list_recipe">
		</div>
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
	});
	$("#btn-cancel").click(() => {
		$("#form_recipe").hide();
		$("#form_recipe").html('');
	});

	$("body").on("change", ".tb_list_recipe", function() {
		$('.tb_list_recipe > tbody  > tr').each(function() {
			const jumlah_barang = $(this).find(".sale_qty").val();
			const harga_satuan = $(this).find(".sale_price").val();
			const total_item = jumlah_barang * harga_satuan;
			$(this).find('.price_total').val(total_item);
		});
		$(this).find("input").on('keyup', null, 'ctrl+a', function(e) {
			$(".btnplus_list_recipe").click();
			$(".autocom_item_id:last").focus();
			e.stopImmediatePropagation();
			return false;
		});
		$(this).find("input").on('keydown', null, 'ctrl+s', function(e) {
			$("#btn-save-updated").click();
			e.stopImmediatePropagation();
			return false;
		});
		$(this).find("input:not([class*='autocom_item_id'])").on("keydown", function(e) {
			if (e.which == 13) {
				$(".btnplus_list_obat_edited").click();
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
						$(this).eq((i)).closest('tr').find('.sale_qty').focus();
						$(this).last().remove();
						return false;
					}
				});
				$(this).closest('tr').find('.item_id').val(ui.item.item_id);
				$(this).closest('tr').find('.stock').val(ui.item.total_stock);
				$(this).closest('tr').find('.sale_price').val(ui.item.harga);
				$(this).closest('tr').find('.sale_qty').focus();
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
			leavePage = false;
			$.ajax({
				'type': "post",
				'data': $(form).serialize(),
				'url': "recipe/save",
				'dataType': 'json',
				'success': function(data) {
					$.unblockUI();
					alert(data.message);
					if (data.code !== '200') {
						return false;
					}
					location.reload(true);
				}
			});
		}
	});

	$("#own_id").change(function(){
		$.post("recipe/get_recipe_detail",{
			"unit_id" : +$("#unit_id_depo").val(),
			"rcp_id" : $("#rcp_id").val(),
			"own_id" : $(this).val(),
			"surety_id" : $("#surety_id").val(),
		},function(resp){
			$(".list_recipe").inputMultiRow({
				column: () => {
					return dataku;
				},
				"data": resp
			});
		},'json').then(function(){
			$('.tb_list_recipe > tbody  > tr').each(function() {
				const jumlah_barang = $(this).find(".qty").val();				
				const harga_satuan = $(this).find(".sale_price").val();
				const total_item = jumlah_barang * harga_satuan;
				console.log(jumlah_barang+"-"+harga_satuan);
				$(this).find('.price_total').val(total_item);
				// $(this).find('.price_total').inputmask("IDR");
        	});
		});
	});

	<?= $this->config->item('footerJS') ?>
</script>