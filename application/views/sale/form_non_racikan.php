<div class="list_obat_nonracikan"></div>
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
	});
</script>