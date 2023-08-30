<style>
    .comment-text {
        color: black !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="list_obat_nonracikan"></div>
    </div>
</div>
<script>
    $(document).ready(() => {
        var dataItemSale;
        $(".btnplus_list_obat_nonracikan").click(function() {
            alert('tes');
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        });

        $(".list_obat_nonracikan").inputMultiRow({
            column: () => {
                var dataku;
                $.ajax({
                    'async': false,
                    'type': "GET",
                    'dataType': 'json',
                    'url': "sale/show_multiRows",
                    'success': function(data) {
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
            // $("body").on("focus", ".ed_obat", function() {
            // $(this).inputmask("9999-99-99",{ "placeholder": "yyyy-mm-dd" });
            // });
            $('.ed_obat').datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });

            $(this).find("input").on('keyup', null, 'ctrl+a', function(e) {
                $(".btnplus_list_obat_nonracikan").click();
                $(".autocom_item_id:last").focus();
                e.stopImmediatePropagation();
                return false;
            });
            
            $(this).find("input:not([class*='autocom_item_id'])").on("keydown", function(e) {
                if (e.which == 13) {
                    $(".btnplus_list_obat_nonracikan").click();
                    $(".autocom_item_id:last").focus();
                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                    hitungNonRacikan();
                    e.stopImmediatePropagation();
                    return false;
                }
            });
            // $("#modal_nonracikan").find(".modal-body").animate({ scrollTop: 300}, 1000);
        });
    });

    function hitungNonRacikan(callback) {
        let totalAll = 0;
        let totalProfit = 0;
        $('.tb_list_obat_nonracikan > tbody  > tr').each(function() {
            const subTotal = valid_numeric($(this).find(".price_total").val());
            totalAll += subTotal;
            totalProfit = totalProfit + valid_numeric($("#profit").val());
        });
        $("#total_biaya_nonracikan").attr("isi",totalProfit);
        $("#total_biaya_nonracikan").text(formatMoney(totalProfit));
        $("#sub_total_nonracikan").attr('isi',totalAll);
        $("#sub_total_nonracikan").text(formatMoney(totalAll));
        grandTotal();
        console.log(callback);
        if (typeof callback === "function") {
            callback();
        }
    }

    $("body").on("click", ".removeItem_list_obat_nonracikan", function() {
        setTimeout(() => {
            hitungNonRacikan();
        }, '500');
    });
    <?= $this->config->item('footerJS') ?>
</script>