<style>
    .ui-autocomplete { z-index:2147483647; }
</style>
<div class="row">
    <?= form_open("", ["method" => "post", "id" => "form_racikan"]) ?>
    <div class="col-md-4">
        <?= create_input("nama_racikan") ?>
    </div>
    <div class="col-md-3">
        <?= create_input("signa") ?>
    </div>
    <div class="col-md-2">
        <?= create_input("qty_racikan") ?>
    </div>
    <div class="col-md-3">
        <?= create_input("biaya_racikan") ?>
    </div>
    <div class="col-md-12">
        <div class="list_item_racikan"></div>
    </div>
    <div class="col-md-12" style="text-align:center ;">
        <button class="btn btn-primary" id="btn-save-racikan" type="button">Save</button>
        <button class="btn btn-danger" type="button" id="btn-close">Close</button>
    </div>
    <?= form_close() ?>
</div>
<script>
$(document).ready(()=>{
    var dataItemSale;
    $(".list_item_racikan").inputMultiRow({
            column: ()=>{
                var dataku;
                $.ajax({
                    'async': false,
                    'type': "GET",
                    'dataType': 'json',
                    'url': "sale/show_multiRows",
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
});

$("body").on("change", ".tb_list_item_racikan", function() {
	$('.tb_list_item_racikan > tbody  > tr').each(function() {
		const jumlah_barang = $(this).find(".sale_qty").val();
		const harga_satuan = $(this).find(".sale_price").val();
		const total_item = jumlah_barang * harga_satuan;
		$(this).find('.price_total').val(total_item);
	})
});

$("#btn-save-racikan").click(()=>{
    $.ajax({
        'async': false,
        'type': "post",
        'data': $("#form_racikan").serialize(),
        'url': "sale/set_item_racikan",
        'success': function (data) {
            // console.log(data);
            $(".list_obat_racikan").append(data);
            $.get("sale/get_total_racikan", function(data2){
                $("#sub_total_racikan").html(parseInt(data2).toLocaleString());
                const sub_total_nonracikan = $("#sub_total_nonracikan").text();
                const total = Math.round(data2)+Math.round(sub_total_nonracikan);
                $("#pembulatan_biaya").html(parseInt(total).toLocaleString());
                $("#grand_total").html(parseInt(total).toLocaleString());
            });
        }
    });
});

</script>
