<style>
    .ui-autocomplete { z-index:2147483647; }
</style>
<?= form_open("sale/update_data", ["method" => "post", "id" => "form_update_sale"]) ?>
<div class="row">
    <div class="col-md-4">
        <div class="col-md-6">
            <?= form_hidden("sale_id") ?>
            <?= form_hidden("surety_id") ?>
            <?= form_hidden("visit_id") ?>
            <?= form_hidden("service_id") ?>
            <?= form_hidden("unit_id") ?>
            <?= form_hidden("own_id") ?>
            <?= form_hidden("sale_services") ?>
            <?= form_hidden("profit") ?>
            <?= form_hidden("profit_item") ?>
            <?= create_input("sale_num", [
                "readonly"  => true,
            ]) ?>
            <?= create_select([
                "attr"         => ["name" => "tipe_patient=Tipe Penjualan", "id" => "tipe_patient", "class" => "form-control"],
                "option"    => [
                    ["id" => "0", "text" => "APS"], ["id" => "1", "text" => "NON APS"]
                ]
            ]) ?>
            <?= create_inputDate("sale_date", [
                "format"        => "yyyy-mm-dd",
                "autoclose"     => "true",
                "endDate"       => "today"
            ], [
                "readonly"  => true,
                "required"  => true,
                "value"     => date('Y-m-d')
            ]) ?>
            <?= create_input("patient_norm", [
                "required"  => true,
            ]) ?>
            <?= create_input("patient_name", [
                "required"  => true,
            ]) ?>
            
            

            <?= create_select([
                "attr"         => ["name" => "sale_type=Cara Bayar", "id" => "sale_type", "class" => "form-control"],
                "option"    => [
                    ["id" => "0", "text" => "Tunai"], ["id" => "1", "text" => "Kredit"]
                ]
            ]) ?>

        </div>
        <div class="col-md-6">
            <?= create_select2([
                "attr" => [
                    "name" => "doctor_id=Dokter", "id" => "doctor_id", "class" => "form-control"
                ],
                "model" => [
                    "m_sale" => ["get_dokter", ["employee_active" => 't']],
                    "column" => ["employee_id", "nama_dokter"]
                ],
            ]) ?>
            <?= create_select([
                "attr"         => ["name" => "kronis=Kronis", "id" => "kronis", "class" => "form-control"],
                "option"    => [
                    ["id" => "t", "text" => "Ya"], ["id" => "f", "text" => "Tidak"]
                ]
            ]) ?>
            
            <?= create_select2([
                "attr" => [
                    "name" => "unit_id_lay=unit layanan", "id" => "unit_id_lay", "class" => "form-control"
                ],
                "model" => [
                    "m_sale" => ["get_unit_layanan", [
                        "unit_active" => 't'
                    ]],
                    "column" => ["unit_id", "unit_name"]
                ],
            ]) ?>
            <?= create_input("sep= No SEP", [
                "readonly"    => true
            ]) ?>
            <?= create_textarea("alamat=Alamat Lengkap") ?>
        </div>
    </div>
    <div class="col-md-8">
        <div class="list_obat_edited">
        </div>
    </div>
    <div class="col-md-12">
        <div class="box">
            <div class="box-footer">
                <div class="box-tools pull-right">
                    <button class="btn btn-primary" id="btn-save-updated" onclick="$('#form_update_sale').submit()" type="button">Save</button>
                    <button class="btn btn-danger" type="button" id="btn-close-pasien" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>

<script>
    $(document).ready(()=>{
        var dataItemSale = JSON.parse('<?=addslashes(json_encode($item))?>');
        $(".list_obat_edited").inputMultiRow({
            column: ()=>{
                var dataku;
                $.ajax({
                    'async': false,
                    'type': "GET",
                    'dataType': 'json',
                    'url': "sale/show_multiRows/true/<?=$sale_id?>",
                    'success': function (data) {
                        dataku = data;
                    }
                });
                return dataku;
                },
            "data": dataItemSale
        });
    });
    $("body").on("change", ".tb_list_obat_edited", function() {
        $('.tb_list_obat_edited > tbody  > tr').each(function() {
            const jumlah_barang = $(this).find(".sale_qty").val();
            const harga_satuan = $(this).find(".sale_price").val();
            const total_item = jumlah_barang * harga_satuan;
            $(this).find('.price_total').val(total_item);
        });
        $(this).find("input").on('keyup', null, 'ctrl+a', function(e){
            $(".btnplus_list_obat_edited").click();
            $(".autocom_item_id:last").focus();
            e.stopImmediatePropagation();
            return false;
        });
        $(this).find("input").on('keydown', null, 'ctrl+s', function(e){
            $("#btn-save-updated").click();
            e.stopImmediatePropagation();
            return false;
        });
        $(this).find("input:not([class*='autocom_item_id'])").on("keydown",function(e) {
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
            source: "<?php echo site_url('sale/get_item');?>/"+$("#unit_id_depo").val(),
			autoFocus: true,
            select: function (event, ui) {
				$('tr[class*="list_obat"]').each(function(i,a){
					if($(this).find('.item_id').val() == ui.item.item_id ){
						$(this).eq((i)).closest('tr').find('.sale_qty').focus();
						$(this).last().find('.removeItem_list_obat').click();
						return false;
					}
				});
                $(this).closest('tr').find('.item_id').val(ui.item.item_id);
				$(this).closest('tr').find('.stock').val(ui.item.total_stock);
				$(this).closest('tr').find('.sale_price').val(ui.item.harga);
				$(this).closest('tr').find('.sale_qty').focus();
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

    $('#form_update_sale').on("submit",function(){
		$(this).data("validator").settings.submitHandler = function (form) {
            leavePage=false;
            $.ajax({
                'type': "post",
                'data': $(form).serialize(),
                'url': "sale/update_data",
                'dataType' : 'json',
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

    function changeSurety() {

        var sale_type = $("#surety_id").val();
        if (sale_type == 1 || sale_type == 33) {
            $('#sale_type option[value="0"]').attr('selected', true);

        } else {
            $('#sale_type option[value="1"]').attr('selected', true);

        }

    }
	<?=$this->config->item('footerJS')?>
</script>