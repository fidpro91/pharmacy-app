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
                    }
                });
                return dataku;
                },
            "data": dataItemSale
    });
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
        }
    });
});
</script>