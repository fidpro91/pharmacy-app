<div class="row">
<?= form_open("sale/save", ["method" => "post", "id" => "fm_sale_h"], $model) ?>
	<?= form_hidden("sale_id") ?>
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
	</div>
	<div class="col-md-4">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Obat NonRacikan</h3>
				<div class="box-tools pull-right">
					<button type="button" id="btn-nonracikan" class="btn btn-info">
					<i class="fa fa-plus"></i> Tambah</button>
				</div>
			</div>
			<div class="box-body">
				<div class="list_obat_nonracikan2"></div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="box">
			<div class="box-body">
				<table class="table1">
					<tr>
						<th colspan="2" style="text-align: center;">INVOICE NO :</th>
					</tr>
					<tr>
						<th colspan="2" style="text-align: center;" id="tno_invoice"><?=$sale_num?></th>
					</tr>
					<tr>
						<td>NORM</td>
						<td class="pull-right" id="tno_rm"></td>
					</tr>
					<tr>
						<td>NAMA PASIEN</td>
						<td class="pull-right" id="tpx_name"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<li class="list-group-item">
				<b>Biaya Racikan</b> <a class="pull-right" id="total_biaya_racikan" isi="0">0</a>
			</li>
			<li class="list-group-item">
				<b>Sub Total Racikan</b> <a class="pull-right" id="sub_total_racikan" isi="0">0</a>
			</li>
			<li class="list-group-item">
				<b>Sub Total Non Racikan</b> <a class="pull-right" id="sub_total_nonracikan" isi="0">0</a>
			</li>
		</div>
		<div class="col-md-6">
			<li class="list-group-item">
				<?=form_hidden('margin_profit')?>
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
	<?= form_close() ?>
	<div class="col-md-12">
		<div class="box-footer" align="center">
			<button class="btn btn-primary" type="button" onclick="$('#fm_sale_h').submit()">Save</button>
			<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
		</div>
	</div>
<?= modal_open("modal_racikan", "List Item Racikan","modal-lg") ?>
<?= modal_close() ?>
<?= modal_open("modal_nonracikan", "List Item Non Racikan","modal-lg") ?>
<?= modal_close() ?>
</div>
<script type="text/javascript">
	$("body").on("focus", ".autocom_item_id", function() {
	    $(this).autocomplete({
            source: "<?php echo site_url('sale/get_item');?>",
            select: function (event, ui) {
                $(this).closest('tr').find('.item_id').val(ui.item.item_id);
                $(this).closest('tr').find('.sale_price').val(ui.item.harga);
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div class='comment-text'><span class=\"username\"><b>"+
					item.item_name+"|"+item.item_code+
				"</b><span class=\"text-muted pull-right\">"+formatNumeric(item.harga)+"</span></span><p>"+
				"<span>Kategori Item : <span class=\"text-muted pull-right\">"+(item.classification_name)+"</span></span><br>"+
				"<span>Stok terakhir : <span class=\"text-muted pull-right\">"+formatNumeric(item.total_stock)+"</span></span>"+
				"</div>")
                .appendTo(ul);
        };
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
		$("#form_sale").hide();
		$("#form_sale").html('');
	});
	function save_sale() {
		alert("simpan")
	}

	function formatMoney(val=0){
        return new Intl.NumberFormat('id-ID',  {
                style: 'currency',
                currency: 'IDR',
        }).format(val);
    }

	function grandTotal() {
		let totalNonRacikan = parseFloat($.isNumeric($('#sub_total_nonracikan').attr('isi'))?$('#sub_total_nonracikan').attr('isi'):0);
		let totalRacikan = parseFloat($.isNumeric($('#sub_total_racikan').attr('isi'))?$('#sub_total_racikan').attr('isi'):0);
		let totalAll = totalNonRacikan+totalRacikan;
		let embalase = totalAll/100;
		embalase = Math.abs(Math.ceil(embalase)-embalase)*100;
		totalAll = totalAll+embalase;
		$("#pembulatan_biaya").text(formatMoney(embalase));
		$("#grand_total").text(formatMoney(totalAll));
	}

	
	<?= $this->config->item('footerJS') ?>
</script>
