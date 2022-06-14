<?= form_open("", ["method" => "post", "id" => "form_non_racikan"]) ?>
<div class="row">
	<div class="col-md-12">
		<div class="list_obat_nonracikan"></div>
	</div>
	<div class="col-md-12" style="text-align:center ;">
        <button class="btn btn-primary" id="btn-save-non_racikan" type="button">Save</button>
        <button class="btn btn-danger" type="button" id="btn-close">Close</button>
	</div>
</div>

<?= form_close() ?>
<script>
	$(document).ready(()=>{
		var dataItemSale;
        $(".list_obat_nonracikan").inputMultiRow({
	            column: ()=>{
					var dataku;
					$.ajax({
						'async': false,
						'type': "GET",
						'dataType': 'json',
						'url': "sale/show_multiRows",
						'success': function (data) {
							dataku = data;
						}
					});
					return dataku;
	                },
                "data": dataItemSale
	    });

		$("body").on("change", ".tb_list_obat_nonracikan", function() {
			$('.tb_list_obat_nonracikan > tbody  > tr').each(function() {
				const jumlah_barang = $(this).find(".sale_qty").val();
				const harga_satuan = $(this).find(".sale_price").val();
				const total_item = jumlah_barang * harga_satuan;
				$(this).find('.price_total').val(total_item);
        	})
		});

		$("#btn-save-non_racikan").click(()=>{
			$.ajax({
				'async': false,
				'type': "post",
				'data': $("#form_non_racikan").serialize(),
				'url': "sale/set_item_nonracikan",
				'success': function (data) {
					$(".list_obat_nonracikan2").append(data);
					$.get("sale/get_total_nonracikan", function(data2){
						$("#sub_total_nonracikan").html(data2);
						const sub_total_racikan =$("#sub_total_racikan").text();
						const total = Math.round(data2)+Math.round(sub_total_racikan);
						$("#pembulatan_biaya").html(total);
						$("#grand_total").html(total);
					});
					$("#modal_nonracikan").modal('hide');
				}
			});
		});

	});
</script>
