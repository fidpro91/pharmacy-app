<style>
    .ui-autocomplete {
        z-index: 2147483647;
    }
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
                <?= form_hidden("service_id") ?>
                <?= form_hidden("unit_id") ?>
                <?= create_select([
                    "attr"         => ["name" => "tipe_patient=Tipe Penjualan", "id" => "tipe_patient", "class" => "form-control"],
                    "option"    => [
                        ["id" => "0", "text" => "APS"], ["id" => "1", "text" => "NON APS"]
                    ]
                ]) ?>
                <?= create_inputDate("sale_date", [
                    "format" => "yyyy-mm-dd",
                    "autoclose" => "true"
                ]) ?>
                <?= create_input("patient_norm") ?>
                <?= create_input("patient_name") ?>
                <!-- //<?= create_input("surety_id") ?> -->
                <?= create_select2([
                    "attr" => ["name" => "surety_id=Penjamin", "id" => "surety_id", "class" => "form-control", "onchange" => "changeSurety()"],
                    "model" => [
                        "m_sale" => ["get_penjamin", ["0" => '0']],
                        "column"  => ["surety_id", "surety_name"]
                    ]
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
                        "m_sale" => "get_dokter",
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
                    "attr" => ["name" => "own_id=Kepemilikan", "id" => "own_id", "class" => "form-control"],
                    "model" => [
                        "m_surety_ownership" => ["get_kepemilikan", ["0" => '0']],
                        "column"  => ["own_id", "own_name"]
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
                <?=create_textarea("alamat=Alamat Lengkap",["readonly" => true])?>

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
            source: "<?php echo site_url('sale/get_no_rm/norm'); ?>/"+$("#tipe_patient").val(),
            select: function(event, ui) {
                $('#patient_norm').val(ui.item.px_norm); 
                $('#patient_name').val(ui.item.px_name);
                $('#alamat').val(ui.item.px_address);
                if ($("#tipe_patient").val() == 1) {
                    $('#visit_id').val(ui.item.visit_id);
                    $('#doctor_id').val(ui.item.par_id);
                    $('#service_id').val(ui.item.srv_id);
                    $('#doctor_name').val(ui.item.par_name);
                    $('#unit_id_lay').val(ui.item.unit_id);
                    $('#unit_id').val(ui.item.unit_id);                   
                    if (ui.item.surety_id) {
                        $('#surety_id').val(ui.item.surety_id);
                    }
                    $('#unit_name_lay').val(ui.item.unit_name);
                    if (ui.item.surety_id == 1) {
                        $('#sale_type option[value="0"]').attr('selected', true);
                    } else {
                        $('#sale_type option[value="1"]').attr('selected', true);
                    }
                }
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            if ($("#tipe_patient").val() == 1) {
                return $("<li>")
                .append("<div class='comment-text'><span class=\"username\"><b>" +
                    item.px_norm + "|" + item.px_name +
                    "</b><span class=\"text-muted pull-right\">" + item.unit_name + "</span></span><p>" +
                    "<span>Tanggal Kunjung : <span class=\"text-muted pull-right\">" + (item.srv_date) + "</span></span><br>" +
                    "<span>Status Kunjung : <span class=\"text-muted pull-right\">" + (item.status_kunjungan) + "</span></span><br>" +
                    "<span>Dokter : <span class=\"text-muted pull-right\">" + (item.par_name) + "</span></span>" +
                    "</div>")
                .appendTo(ul);
            }else{
                return $("<li>")
                .append("<div class='comment-text'><span class=\"username\"><b>" +
                    item.px_norm + "|" + item.px_name +
                    "</b><span class=\"text-muted pull-right\">Atas permintaan Sendiri(APS)</span></span><p>" +
                    "<span>NO Telp : <span class=\"text-muted pull-right\">" + (item.telepon) + "</span></span><br>" +
                    "<span>Tanggal Lahir : <span class=\"text-muted pull-right\">" + (item.tgl_lahir) + "</span></span><br>" +
                    "<span>Alamat : <span class=\"text-muted pull-right\">" + (item.px_address) + "</span></span>" +
                    "</div>")
                .appendTo(ul);
            }
        };
        changeSurety();
        return false;
    });

    $("body").on("focus", "#patient_name", function() {
        $(this).autocomplete({
            source: "<?php echo site_url('sale/get_no_rm/name'); ?>",
            select: function(event, ui) {
                $('#patient_norm').val(ui.item.px_norm);
                $('#patient_name').val(ui.item.px_name);
                $('#alamat').val(ui.item.px_address);
                $('#visit_id').val(ui.item.visit_id);
                $('#doctor_id').val(ui.item.par_id);
                $('#service_id').val(ui.item.srv_id);
                $('#doctor_name').val(ui.item.par_name);
                $('#unit_id_lay').val(ui.item.unit_id);
                $('#unit_id').val(ui.item.unit_id);
                $('#kronis').val(ui.item.kronis);
                if (ui.item.surety_id) {
                    $('#surety_id').val(ui.item.surety_id);
                }
                $('#unit_name_lay').val(ui.item.unit_name);
                if (ui.item.surety_id == 1) {
                    $('#sale_type option[value="0"]').attr('selected', true);
                } else {
                    $('#sale_type option[value="1"]').attr('selected', true);
                }
            }


        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div class='comment-text'><span class=\"username\"><b>" +
                    item.px_norm + "|" + item.px_name +
                    "</b><span class=\"text-muted pull-right\">" + item.unit_name + "</span></span><p>" +
                    "<span>Tanggal Kunjung : <span class=\"text-muted pull-right\">" + (item.srv_date) + "</span></span><br>" +
                    "<span>Status Kunjung : <span class=\"text-muted pull-right\">" + (item.status_kunjungan) + "</span></span><br>" +
                    "<span>Dokter : <span class=\"text-muted pull-right\">" + (item.par_name) + "</span></span>" +
                    "</div>")
                .appendTo(ul);
        };
        changeSurety();
        return false;
    });

    function changeSurety() {

        var sale_type = $("#surety_id").val();
        if (sale_type == 1 || sale_type == 33) {
            $('#sale_type option[value="0"]').attr('selected', true);

        } else {
            $('#sale_type option[value="1"]').attr('selected', true);

        }

    }
    $("#btn-save-pasien").click(() => {
        $.ajax({
            'async': false,
            'type': "post",
            'data': $("#form_pasien").serialize(),
            'url': "sale/set_data_pasien",
            'dataType' : 'json',
            'success': function(data) {
                if (data.code !== '200') {
                    alert(data.message);
                    return false;
                }
                let profit = data.profit*100;
                $("#labelProfit").append('('+profit+'%)');
                $("#tno_rm").append(data.px_norm);
                $("#tpx_name").append(data.px_name+''); 
                $("#px_alamat").append(data.alamat); 
                $("#dokter_").append(data.dokter); 
                $("#surety_").append(data.surety);                
                $("#margin_profit").val(data.profit);
                $("#tno_rm").html($("#no_rm").val());
                $("#tpx_name").html($("#nama").val());
                $("#modal_pasien").modal('hide');
            }
        });
    }
    );
    <?= $this->config->item('footerJS') ?>
</script>