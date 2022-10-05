<div class="col-md-12">
	<?= form_open("adjusment_stok/save", ["method" => "post", "class" => "form-horizontal", "id" => "fm_adjusment_stok"], $model) ?>
	<?= form_hidden("adj_id") ?>
	<?= form_hidden("item_id") ?>
	<?= form_hidden("own_id") ?>
	<?= form_hidden("unit_id") ?>
	<?= create_inputDate("adj_date", [
		"format" => "yyyy-mm-dd",
		"autoclose" => "true"
	],[
		"readonly"  => true,
		"required"  => true,
		"value"     => date('Y-m-d')
	]) ?>
	<?= create_input("stock_old",[
		"onchange"	=> "hitung_selisih()",
		"value"		=> $item->stock_after,
		"readonly"	=> true
	]) ?>
	<?= create_input("stock_after",[
		"onchange"	=> "hitung_selisih()"
	]) ?>
	<?= create_input("different_qty",[
		"readonly"	=> true
	]) ?>
	<?= create_inputMask("price_item","IDR",[
		"onchange"	=> "hitung_total()"
	]) ?>
	<?= create_inputMask("price_total","IDR",[
		"value" => $item->item_price
	]) ?>
	<?= form_close() ?>
	<div class="box-footer">
		<button class="btn btn-primary" type="button" onclick="$('#fm_adjusment_stok').submit()">Save</button>
		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
	</div>
</div>
<script type="text/javascript">
	$("#btn-cancel").click(() => {
		$("#modal_penyesuaian").modal("hide");
	});

	function hitung_selisih() {
		let stok1 = parseFloat($.isNumeric($('#stock_old').val())?$('#stock_old').val():0);
		let stok2 = parseFloat($.isNumeric($('#stock_after').val())?$('#stock_after').val():0);
		let adj = Math.abs(stok1-stok2);
		console.log(stok1+"-"+stok2+"-"+adj);
		$("#different_qty").val(adj);
	}

	function hitung_total() {
		let bil1 = parseFloat($.isNumeric($('#stock_after').val())?$('#stock_after').val():0);
		let bil2 = parseFloat($.isNumeric($('#price_item').val())?$('#price_item').val():0);
		let total = bil1*bil2;
		$("#price_total").val(total);
	}
	<?= $this->config->item('footerJS') ?>
</script>