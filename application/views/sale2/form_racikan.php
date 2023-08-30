<div class="row" id="form_racikan">
    <div class="col-md-6">
        <div class="col-md-6">
            <?= create_input("nama_racikan") ?>
            <?= create_input("signa") ?>
            <?= create_input("qty_racikan") ?>
        </div>
        <div class="col-md-6">
            <?= create_input("ed_obat=BUD", ["readonly" => true]) ?>
            <?php
            $biayaRacik = $this->db->get_where("newfarmasi.setting_app", [
                "setting_id"   => 3
            ])->row("setting_value");
            ?>
            <?= create_inputMask("biaya_racikan", "IDR", ["value" => $biayaRacik]) ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Obat Racikan</h3>
            </div>
            <div class="box-body">
                <div class="list_obat_racikan"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="list_item_racikan"></div>
    </div>
    <div class="col-md-12">
        <div class="pull-right">
            <button class="btn btn-success" id="btn-save-racikan" type="button">
                <i class="fa fa-bitbucket"></i>Tambah Resep Racikan
            </button>
        </div>
    </div>
</div>
<script>
    $(document).ready(() => {
        var dataItemSale;
        $(".list_item_racikan").inputMultiRow({
            column: () => {
                var dataku;
                $.ajax({
                    'async': false,
                    'type': "GET",
                    'dataType': 'json',
                    'url': "sale/show_multiRowsRacikan",
                    'success': function(data) {
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

        $("#btn-save-racikan").click(()=>{
            $.ajax({
                'async': false,
                'type': "post",
                'data': $("#fm_sale").serialize(),
                'url': "sale/set_item_racikan",
                'dataType':'json',
                'success': function (data) {
                    $("#form_racikan").find("input[name!='biaya_racikan']").val('');
                    $(".tb_list_item_racikan > tbody").find('tr').remove();
                    $("#nama_racikan").focus();
                    $(".list_obat_racikan").append(data.html);
                    let total = valid_numeric($('#sub_total_racikan').attr('isi'));
                    let totalBiayaRacik = valid_numeric($('#total_biaya_racikan').attr('isi'));
                    total = total+data.total;
                    totalBiayaRacik = (totalBiayaRacik + valid_numeric(data.biaya_racik));
                    $("#total_biaya_racikan").text(formatMoney(totalBiayaRacik));
                    $("#total_biaya_racikan").attr("isi",totalBiayaRacik);
                    $("#sub_total_racikan").text(formatMoney(total));
                    $("#sub_total_racikan").attr("isi",total);
                    grandTotal();
                }
            });
        });
    });
    $("body").on("change", ".tb_list_item_racikan", function() {
        $('.tb_list_item_racikan > tbody  > tr').each(function() {
            const jumlah_barang = $(this).find(".sale_qty").val();
            const harga_satuan = $(this).find(".sale_price").val();
            const total_item = jumlah_barang * harga_satuan;
            $(this).find('.price_total').val(total_item);
        });
        $(this).find("input").on('keyup', null, 'ctrl+a', function(e) {
            $(".btnplus_list_item_racikan").click();
            $(".autocom_item_id:last").focus();
            e.stopImmediatePropagation();
            return false;
        });
        $(this).find("input").on('keydown', null, 'ctrl+s', function(e) {
            $("#btn-save-racikan").click();
            e.stopImmediatePropagation();
            return false;
        });
        $(this).find("input:not([class*='autocom_item_id'])").on("keydown", function(e) {
            if (e.which == 13) {
                $(".btnplus_list_item_racikan").click();
                $(".autocom_item_id:last").focus();
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                e.stopImmediatePropagation();
                return false;
            }
        });
    });
    $("#ed_obat").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
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

    <?= $this->config->item('footerJS') ?>
</script>