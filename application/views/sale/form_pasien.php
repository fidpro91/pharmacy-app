<style>
    .ui-autocomplete { z-index:2147483647; }
</style>
<div class="row">
    <?= form_open("", ["method" => "post", "id" => "form_pasien"]) ?>
    <div class="box" class="info-medis-pasien" style="display:none ;">
    </div>
    <div class="box" class="data-pasien">
        <div class="box-header with-border">
            <h3 class="box-title">Data Pasien</h3>
            <div class="box-tools pull-right">
                <button type="button" id="btn-history" class="btn btn-default">
                    <i class="fa fa-clock-o"></i> History Pelayanan</button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <?= form_hidden("visit_id") ?>
                <?= form_hidden("doctor_id") ?>
                <?= form_hidden("service_id") ?>
                <?= create_input("patient_norm") ?>
                <?= create_input("patient_name") ?>
                <?= create_input("surety_id") ?>
                <?= create_input("sale_type") ?>
                <?= create_input("own_id") ?>
            </div>
            <div class="col-md-6">
                <?= create_input("doctor_name") ?>
                <?= create_input("kronis") ?>
                <?= create_input("unit_id_lay") ?>
            </div>
            <div class="col-md-12" style="text-align:center ;">
                <button class="btn btn-primary" id="btn-save-pasien" type="button">Save</button>
                <button class="btn btn-danger" type="button" id="btn-close-pasien">Close</button>
            </div>
        </div>
        <!-- /.box-footer-->
    </div>
    <?= form_close() ?>
</div>
<script>
$("body").on("focus", "#patient_norm", function() {
    $(this).autocomplete({
        source: "<?php echo site_url('sale/get_data_pasien');?>",
        select: function (event, ui) {
            $('#px_id').val(ui.item.item_id);
            $('#px_norm').val(ui.item.harga);
            $('#px_name').val(ui.item.harga);
            $('#visit_id').val(ui.item.harga);
            $('#doctor_id').val(ui.item.harga);
            $('#service_id').val(ui.item.harga);
            $('#doctor_name').val(ui.item.harga);
            $('#unit_id_lay').val(ui.item.harga);
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<div class='comment-text'><span class=\"username\"><b>"+
                item.px_norm+"|"+item.px_name+
            "</b><span class=\"text-muted pull-right\">"+item.unit_kunjungan+"</span></span><p>"+
            "<span>Tanggal Kunjung : <span class=\"text-muted pull-right\">"+(item.tanggal_kunjung)+"</span></span><br>"+
            "<span>Status Kunjung : <span class=\"text-muted pull-right\">"+(item.status_kunjung)+"</span></span>"+
            "</div>")
            .appendTo(ul);
    };
});
$("#btn-save-racikan").click(()=>{
    $.ajax({
        'async': false,
        'type': "post",
        'data': $("#form_racikan").serialize(),
        'url': "sale/set_data_pasien",
        'success': function (data) {
            // $(".list_obat_racikan").append(data);
        }
    });
});
</script>