<?= form_open("", ["method" => "post", "id" => "form_non_racikan"]) ?>
<div class="row">
	<div class="col-md-12">
		<div class="list_obat_nonracikan"></div>
	</div>
	<div class="col-md-12" style="text-align:center ;">
        <button class="btn btn-primary" id="btn-save-non_racikan" type="button">Save</button>
        <button class="btn btn-danger" type="button" id="btn-close" data-dismiss="modal" aria-label="Close">Close</button>
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
				$(this).find('.price_total').inputmask("IDR");
        	});

			$(this).find("input").on('keyup', null, 'ctrl+a', function(e){
				$(".btnplus_list_obat_nonracikan").click();
				$(".autocom_item_id:last").focus();
				e.stopImmediatePropagation();
				return false;
			}); 
			$(this).find("input").on('keydown', null, 'ctrl+s', function(e){
				$("#btn-save-non_racikan").click();
				e.stopImmediatePropagation();
				return false;
			});
			$(this).find("input:not([class*='autocom_item_id'])").on("keydown",function(e) {
				if (e.which == 13) {
					$(".btnplus_list_obat_nonracikan").click();
					$(".autocom_item_id:last").focus();
					e.stopImmediatePropagation();
					return false;
				}
			});
		});

		$("#btn-save-non_racikan").click(()=>{
			$.ajax({
				'async': false,
				'type': "post",
				'data': $("#form_non_racikan").serialize()+"&embalase_item="+$("#labelEmbalase").text(),
				'url': "sale/set_item_nonracikan",
				'dataType':'json',
				'success': function (data) {
					$(".list_obat_nonracikan2").append(data.html);
					let total = parseFloat($.isNumeric($('#sub_total_nonracikan').attr('isi'))?$('#sub_total_nonracikan').attr('isi'):0);
					let biayaNonracik = parseFloat($.isNumeric($('#total_biaya_nonracikan').attr('isi'))?$('#total_biaya_nonracikan').attr('isi'):0);
            		total = total+data.total;
            		biayaNonracik = biayaNonracik+data.embalase;
					$("#total_biaya_nonracikan").text(formatMoney(biayaNonracik));
            		$("#total_biaya_nonracikan").attr("isi",biayaNonracik);
					$("#sub_total_nonracikan").text(formatMoney(total));
            		$("#sub_total_nonracikan").attr("isi",total);
					grandTotal();
					$("#modal_nonracikan").modal('hide');
				}
			});
		});

	});
	
	<?=$this->config->item('footerJS')?>
</script>
