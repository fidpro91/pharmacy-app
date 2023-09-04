<div class="col-md-12">
	<?= form_open("sale2/save", ["method" => "post", "id" => "fm_sale"], $model) ?>
	<?= form_hidden("sale_id") ?>
	<?= form_hidden("profit") ?>
	<?= form_hidden("margin_profit") ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<div class="box-tools pull-right">
				<button type="button" id="btn-history" disabled class="btn" data-toggle="modal" href="#modal_history"><i class="fa fa-clock-o"></i> History Pelayanan</button>
				<button type="button" title="Informasi Stock Unit" id="btn-info-stock" class="btn btn-info">
				<i class="fa fa-info"></i> Informasi Stok</button>
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		<div class="box-body">
			<?php
				$this->load->view("sale2/patient");
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<!-- Tab headers -->
				<ul class="nav nav-tabs nav-justified">
					<li class="active"><a href="#tab1" data-toggle="tab">OBAT NON RACIKAN</a></li>
					<li><a href="#tab2" data-toggle="tab">OBAT RACIKAN</a></li>
				</ul>
				<!-- Tab content -->
				<div class="tab-content">
					<div class="tab-pane active" id="tab1">
						<?php
							$this->load->view("sale2/nonracikan");
						?>
					</div>
					<div class="tab-pane" id="tab2">
						<?php
							$this->load->view("sale2/form_racikan");
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?= form_close() ?>
	<div class="row">
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
	<div class="box-footer text-center bg-primary">
		<button class="btn btn-primary btn-save" type="button">Save</button>
		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
	</div>
</div>
<?= modal_open("modal_info_stock", "[Ctrl+i]Informasi Stock", "modal-lg") ?>
<?= modal_close() ?>
<script type="text/javascript">
	$("body").on("focus", ".autocom_item_id", function() {
		$(this).autocomplete({
			source: "<?php echo site_url('sale2/get_item'); ?>/" + $("#unit_id_depo").val() +"/"+ + $("#own_id").val(),
			autoFocus: true,
			minLength: 3,
			select: function(event, ui) {
				$('tr[class*="list_obat"]').each(function(i, a) {
					if ($(this).find('.item_id').val() == ui.item.item_id) {
						$(this).eq((i)).closest('tr').find('.sale_qty').focus();
						$(this).last().find('.removeItem_list_obat').click();
						return false;
					}
				});
				$(this).closest('tr').find('.item_id').val(ui.item.item_id);
				$(this).closest('tr').find('.stock').val(ui.item.total_stock);
				var	profit 	= valid_numeric($("#margin_profit").val());
				var harga 	= valid_numeric(ui.item.harga);
				harga 		= ((harga*profit)+harga);
				$(this).closest('tr').find('.sale_price').val(harga);
				$(this).closest('tr').find('.sale_qty').focus();
				$("#profit").val(ui.item.profit);
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
	
	function valid_numeric(a) {
		return parseFloat($.isNumeric(a) ? a : 0);
	}

	function formatMoney(val = 0) {
		return new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR',
		}).format(val);
	}

	function grandTotal() {
		let totalNonRacikan = valid_numeric($('#sub_total_nonracikan').attr('isi'));
		let totalRacikan = valid_numeric($('#sub_total_racikan').attr('isi'));
		let BiayaRacikan = valid_numeric($('#total_biaya_racikan').attr('isi'));
		let BiayaNonRacikan = valid_numeric($('#total_biaya_nonracikan').attr('isi'));
		let totalAll = totalNonRacikan + totalRacikan + BiayaRacikan + BiayaNonRacikan;
		let embalase = 0;
		if (totalAll>0) {
			embalase = totalAll / 100;
			embalase = (parseFloat(Math.abs(Math.ceil(embalase)-embalase)).toFixed(2))*100;
			totalAll = Math.ceil(totalAll+embalase);
		}
		$("#pembulatan_biaya").text(formatMoney(embalase));
		$("#pembulatan_biaya").attr("isi",embalase);
		$("#grand_total").attr("isi",totalAll);
		$("#grand_total").text(formatMoney(totalAll));
	}

	$(document).ready(()=>{
		$("#btn-info-stock").click(() => {
			$("#modal_info_stock").modal('show');
			$("#modal_info_stock").find(".modal-body").load("stock_all_unit/show_stock");
		});

		$('.btn-save').on('click', function() {
			hitungNonRacikan(function(){
				$('#fm_sale').submit();
			});
		});

		$("#btn-cancel").click(() => {
			$("#data_sale").show();
			$("#form_sale").html('');
			$("#form_sale").hide();
			table.draw();
		});

		$('#fm_sale').on("submit", function(event) {
			event.preventDefault();
			var form = this;
			if (confirm("Simpan data?")) {
				$.blockUI();
				$.ajax({
					'type': "post",
					'data': $(form).serialize() + "&unit_id=" + $("#unit_id_depo").val() + "&embalase_item=" + $("#total_biaya_nonracikan").attr('isi')+ "&grand_total=" + $("#grand_total").attr('isi')+ "&pembulatan=" + $("#pembulatan_biaya").attr('isi')+ "&biaya_racikan=" + $("#total_biaya_racikan").attr('isi')+ "&doctor_name=" + $("#doctor_id").find('option:selected').text(),
					'dataType': 'json',
					'url': "sale2/save2",
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
			/* $(form).data("validator").settings.submitHandler = function(form) {
				alert('x');
				// return false;
			} */
		});
	})
</script>