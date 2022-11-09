<style>
	.ui-autocomplete {
		z-index: 2147483647;
	}
</style>
<script src="<?= base_url("assets/plugins/autocompletescroll/jquery.ui.autocomplete.scroll.min.js") ?>"></script>
<div class="row">
	<?= form_open("sale/save", ["method" => "post", "id" => "fm_sale_h"], $model) ?>
	<?= form_hidden("sale_id") ?>
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<i class="box-title">[F3]Add Racikan | [F4]Add Non Racikan | [Ctrl+a]Add Item | [Ctrl+s] Save Transaction | [Ctrl+e] Edit Patient</i>
				<div class="box-tools pull-right">
					<button type="button" title="Informasi Stock Unit" id="btn-info-stock" class="btn btn-success">
						<i class="fa fa-info"></i></button>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Obat NonRacikan</h3>
				<div class="box-tools pull-right">
					<button type="button" id="btn-nonracikan" class="btn btn-info">
						<i class="fa fa-plus"></i> Tambah</button>
				</div>
			</div>
			<div class="box-body" style="min-height: 300px !important;">
				<div class="list_obat_nonracikan2"></div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Obat Racikan</h3>
				<div class="box-tools pull-right">
					<button type="button" id="btn-racikan" class="btn btn-info">
						<i class="fa fa-plus"></i> Tambah</button>
				</div>
			</div>
			<div class="box-body">
				<div class="list_obat_racikan"></div>
			</div>
		</div>

		<div class="box">
			<div class="box-header with-border">
				<a href="#" class="text-muted" onclick="edit_px()"><i class="fa fa-gear"></i></a>
			</div>
			<div class="box-body">
				<table class="table1">
					<tr>
						<th colspan="3" style="text-align: center;">INVOICE NO :</th>
					</tr>
					<tr>
						<th colspan="3" style="text-align: center;" id="tno_invoice"><?= $sale_num ?></th>
					</tr>
					<tr>
						<td style="width:40%;">NORM</td>
						<td style="width:5%;">:</td>
						<td class="pull-right" style="width:100%;" id="tno_rm"></td>
					</tr>
					<tr>
						<td style="width:30%;">NAMA PASIEN</td>
						<td style="width:5%;">:</td>
						<td class="pull-right" style="width:100%;" id="tpx_name"></td>
					</tr>
					<tr>
						<td style="width:30%;">ALAMAT</td>
						<td style="width:5%;">:</td>
						<td class="pull-right" style="width:100%;" id="px_alamat"></td>
					</tr>
					<tr>
						<td style="width:30%;">DOKTER</td>
						<td style="width:5%;">:</td>
						<td class="pull-right" style="width:100%;" id="dokter_"></td>
					</tr>
					<tr>
						<td style="width:30%;">PENJAMIN</td>
						<td style="width:5%;">:</td>
						<td class="pull-right" style="width:100%;" id="surety_"></td>
					</tr>
					<tr>
						<td style="width:30%;">MARGIN OBAT</td>
						<td style="width:5%;">:</td>
						<td class="pull-right" style="width:100%;" id="labelProfit"></td>
					</tr>
					<tr>
						<td style="width:30%;">Embalase Item (Non Racikan)</td>
						<td style="width:5%;">:</td>
						<td class="pull-right" style="width:100%;" id="labelEmbalase"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="col-xs-6 footer-kiri">
				<li class="list-group-item">
					<b>Biaya Racikan</b> <a class="pull-right" id="total_biaya_racikan" isi="0">0</a>
				</li>
				<li class="list-group-item">
					<b>Sub Total Racikan</b> <a class="pull-right" id="sub_total_racikan" isi="0">0</a>
				</li>
				<li class="list-group-item">
					<b>Biaya Non Racikan</b> <a class="pull-right" id="total_biaya_nonracikan" isi="0">0</a>
				</li>
				<li class="list-group-item">
					<b>Sub Total Non Racikan</b> <a class="pull-right" id="sub_total_nonracikan" isi="0">0</a>
				</li>
			</div>
			<div class="col-xs-6 footer-kanan">
				<li class="list-group-item">
					<?= form_hidden('margin_profit') ?>
					<b id="labelProfit">Profit </b> <a class="pull-right" id="profit_rp">0</a>
				</li>
				<li class="list-group-item">
					<b>Pembulatan Biaya</b> <a class="pull-right" id="pembulatan_biaya">0</a>
				</li>
				<li class="list-group-item">
					<b>Grand Total</b> <a class="pull-right" id="grand_total">0</a>
				</li>
			</div>
		</div>
	</div>
	<?= form_close() ?>
	<div class="col-md-12">
		<div class="box-footer" align="center">
			<button class="btn btn-primary" type="button" onclick="$('#fm_sale_h').submit()">Save</button>
			<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
		</div>
	</div>
	<style>
		.modal-dialog {
			overflow-y: initial !important
		}

		.modal-body {
			height: 80vh;
			overflow-y: auto;
		}
	</style>
	<?= modal_open("modal_pasien", "Biodata pasien", "modal-lg") ?>
	<?= modal_close() ?>
	<?= modal_open("modal_racikan", "List Item Racikan | [Ctrl+a]Add Item | [Ctrl+s]Save", "modal-lg") ?>
	<?= modal_close() ?>
	<?= modal_open("modal_nonracikan", "List Item Non Racikan | [Ctrl+a]Add Item | [Ctrl+s]Save", "modal-lg") ?>
	<?= modal_close() ?>
	<?= modal_open("modal_info_stock", "List Item Non Racikan | [Ctrl+i]Informasi Stock", "modal-lg") ?>
	<?= modal_close() ?>
	<?= modal_open("modal_history", "History Pelayanan Pasien", "modal-lg", ["style" => "width:90%"]) ?>
	<?= modal_close() ?>
</div>
<script type="text/javascript">
	var leavePage = true;
	$(document).ready(() => {
		$(document).bind('keydown', 'f3', function assets() {
			$("#btn-racikan").click();
			return false;
		});

		$(document).bind('keydown', 'f4', function assets() {
			$("#btn-nonracikan").click();
			return false;
		});

		$(document).bind('keydown', 'f5', function assets() {
			$('#fm_sale_h').submit();
			return false;
		});

		$(document).bind('keydown', 'Ctrl+e', function assets() {
			edit_px();
			return false;
		});

		$(document).bind('keydown', 'Ctrl+i', function assets() {
			$("#btn-info-stock").click();
			return false;
		});
		edit_px();
	});

	$("#btn-cancel").click(() => {
		$("#data_sale").show();
		$("#form_sale").html('');
		$("#form_sale").hide();
		table.draw();
	});

	$("#btn-info-stock").click(() => {
		$("#modal_info_stock").modal('show');
		$("#modal_info_stock").find(".modal-body").load("stock_all_unit/show_stock");
	});

	function hitungTotal_terima(row) {
		let stockunit = parseInt($(row).closest('tr').find(".stock").val());
		let jml_terima = parseInt(($.isNumeric($(row).val()) ? $(row).val() : 0));
		if (jml_terima > stockunit) {
			alert("Jumlah item melebihi stock");
			$(row).val(0);
			return false;
		}
	}

	$("body").on("focus", ".autocom_item_id", function() {
		$(this).autocomplete({
			source: "<?php echo site_url('sale/get_item'); ?>/" + $("#unit_id_depo").val(),
			autoFocus: true,
			minLength: 3,
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

	function removeRacikan(a, b, biaya, total) {
		$.ajax({
			'type': "get",
			'url': "sale/remove_item_racikan/" + b + "/" + biaya + "/" + total,
			'dataType': 'json',
			'success': function(data) {
				$(a).closest('div').remove();
				$("#sub_total_racikan").text(formatNumeric(data.total));
				$("#sub_total_racikan").attr("isi", (data.total));
				$("#total_biaya_racikan").attr("isi", (data.biaya_racik));
				$("#total_biaya_racikan").text(formatNumeric(data.biaya_racik));
				grandTotal();
			}
		});
	}

	function removeNonRacikan(a, b, biaya) {
		$.ajax({
			'type': "get",
			'url': "sale/remove_item_nonracikan/" + b + "/" + biaya,
			'dataType': 'json',
			'success': function(data) {
				$(a).closest('div').remove();
				$("#sub_total_nonracikan").text(formatNumeric(data.total));
				$("#sub_total_nonracikan").attr("isi", (data.total));
				$("#total_biaya_nonracikan").text(formatNumeric(data.embalase));
				$("#total_biaya_nonracikan").attr("isi", (data.embalase));
				grandTotal();
			}
		});
	}

	$(document).bind('keydown', 'Ctrl+a', addItemRacikan);

	function addItemRacikan() {
		$(".btnplus_list_item_racikan").click();
		$(".autocom_item_id:last").focus();
		return false;
	}

	$(document).bind('keydown', 'Ctrl+a', addItemNonRacikan);

	function addItemNonRacikan(params) {
		$(".btnplus_list_obat_nonracikan").click();
		$(".autocom_item_id:last").focus();
		return false;
	}

	$(document).bind('keydown', 'Ctrl+Shift+s', function() {
		$("#btn-save-racikan").click();
		$("#btn-save-non_racikan").click();
		return false;
	});

	$(document).bind('keydown', 'Ctrl+s', function(e) {
		$('#fm_sale_h').submit();
		e.stopImmediatePropagation();
		return false;
	});

	$('#fm_sale_h').on("submit", function() {
		if (confirm("Simpan data?")) {
			$.blockUI();
			$.ajax({
				'type': "post",
				'data': $(this).serialize() + "&unit_id=" + $("#unit_id_depo").val() + "&embalase_item=" + $("#total_biaya_nonracikan").attr('isi'),
				'dataType': 'json',
				'url': "sale/save",
				'success': function(data) {
					$.unblockUI();
					leavePage = false;
					alert(data.message);
					if (data.code == '200') {
						cetak_resep(data.sale_id, 0, 0);
						location.reload(true);
					}
				},
				timeout: 5000,
				error: function(jqXHR, textStatus, errorThrown) {
					$.unblockUI();
					if (errorThrown == 'timeout') {
						alert("Data berhasil disimpan");
						leavePage = false;
						location.reload(true);
					} else {
						alert(errorThrown);
					}
				}
			});
		}
		return false;
	});

	$("#btn-racikan").click(() => {
		$("#modal_racikan").modal('show');
		$("#modal_racikan").find(".modal-body").load("sale/show_form_racikan");
	});
	$("#btn-nonracikan").click(() => {
		$("#modal_nonracikan").modal('show');
		$("#modal_nonracikan").find(".modal-body").load("sale/show_form_nonracikan");
	});
	$("#btn-cancel").click(() => {
		leavePage = false;
		$("#form_sale").hide();
		$("#form_sale").html('');
	});

	function formatMoney(val = 0) {
		return new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR',
		}).format(val);
	}

	function grandTotal() {
		let totalNonRacikan = parseFloat($.isNumeric($('#sub_total_nonracikan').attr('isi')) ? $('#sub_total_nonracikan').attr('isi') : 0);
		let totalRacikan = parseFloat($.isNumeric($('#sub_total_racikan').attr('isi')) ? $('#sub_total_racikan').attr('isi') : 0);
		let BiayaRacikan = parseFloat($.isNumeric($('#total_biaya_racikan').attr('isi')) ? $('#total_biaya_racikan').attr('isi') : 0);
		let BiayaNonRacikan = parseFloat($.isNumeric($('#total_biaya_nonracikan').attr('isi')) ? $('#total_biaya_nonracikan').attr('isi') : 0);
		let totalAll = totalNonRacikan + totalRacikan + BiayaRacikan + BiayaNonRacikan;
		let embalase = 0;
		if (totalAll>0) {
			embalase = totalAll / 100;
			embalase = (parseFloat(Math.abs(Math.ceil(embalase)-embalase)).toFixed(2))*100;
			totalAll = Math.ceil(totalAll+embalase);
		}
		$("#pembulatan_biaya").text(formatMoney(embalase));
		$("#grand_total").text(formatMoney(totalAll));
	}

	function edit_px() {
		$("#modal_pasien").modal({
			backdrop: "static"
		});
		$("#modal_pasien").find(".modal-body").load("sale/show_form_pasien");
	}

	$(window).bind('beforeunload', function() {
		if (leavePage) {
			return 'Are you sure you want to leave?';
		}
	});

	<?= $this->config->item('footerJS') ?>
</script>