<div class="row">
    <?= form_open("", ["method" => "post", "id" => "form_racikan"]) ?>
    <div class="col-md-4">
        <?= create_input("nama_racikan",[
            "required" => true
        ]) ?>
    </div>
    <div class="col-md-3">
        <?= create_input("signa") ?>
    </div>
    <div class="col-md-2">
        <?= create_input("qty_racikan") ?>
    </div>
    <div class="col-md-3">
        <?php
            $biayaRacik = $this->db->get_where("newfarmasi.setting_app",[
                "setting_id"   => 3
            ])->row("setting_value");
        ?>
        <?=create_inputMask("biaya_racikan","IDR",["value"=>$biayaRacik])?>
    </div>
    <div class="col-md-12">
        <div class="list_item_racikan"></div>
    </div>
    <div class="col-md-12" style="text-align:center ;">
        <button class="btn btn-primary" id="btn-save-racikan" type="button">Save</button>
        <button class="btn btn-danger" type="button" id="btn-close" data-dismiss="modal" aria-label="Close">Close</button>
    </div>
    <?= form_close() ?>
</div>
<script>
$(document).ready(()=>{
    setHotkey();
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

function setHotkey() {
    $('input').unbind('keydown', 'ctrl+a');
    $('input').bind('keydown', 'ctrl+a', addItemRacikan);
}

$("body").on("change", ".tb_list_item_racikan", function() {
	$('.tb_list_item_racikan > tbody  > tr').each(function() {
		const jumlah_barang = $(this).find(".sale_qty").val();
		const harga_satuan = $(this).find(".sale_price").val();
		const total_item = jumlah_barang * harga_satuan;
		$(this).find('.price_total').val(total_item);
	});
    $(this).find("input").on('keyup', null, 'ctrl+a', function(e){
        $(".btnplus_list_item_racikan").click();
        $(".autocom_item_id:last").focus();
        e.stopImmediatePropagation();
        return false;
    });
    $(this).find("input").on('keydown', null, 'ctrl+s', function(e){
        $("#btn-save-racikan").click();
        e.stopImmediatePropagation();
        return false;
    });
    $(this).find("input:not([class*='autocom_item_id'])").on("keydown",function(e) {
        if (e.which == 13) {
            $(".btnplus_list_item_racikan").click();
            $(".autocom_item_id:last").focus();
            e.stopImmediatePropagation();
            return false;
        }
    });
});

$("#btn-save-racikan").click(()=>{
    $("#form_racikan").submit();
});

$("#form_racikan").on("submit",()=>{
    $("#form_racikan").data("validator").settings.submitHandler = function (form) {
        $.ajax({
            'async': false,
            'type': "post",
            'data': $(form).serialize(),
            'url': "sale/set_item_racikan",
            'dataType':'json',
            'success': function (data) {
                $(".list_obat_racikan").append(data.html);
                let total = parseFloat($.isNumeric($('#sub_total_racikan').attr('isi'))?$('#sub_total_racikan').attr('isi'):0);
                let totalBiayaRacik = parseFloat($.isNumeric($('#total_biaya_racikan').attr('isi'))?$('#total_biaya_racikan').attr('isi'):0);
                total = total+data.total;
                totalBiayaRacik = totalBiayaRacik+data.biaya_racik;
                $("#total_biaya_racikan").text(formatMoney(totalBiayaRacik));
                $("#total_biaya_racikan").attr("isi",totalBiayaRacik);
                $("#sub_total_racikan").text(formatMoney(total));
                $("#sub_total_racikan").attr("isi",total);
                grandTotal();
                $("#modal_racikan").modal('hide');
            }
        });
        return false;
    }
})
<?= $this->config->item('footerJS') ?>
</script>
