
<div class="row">
	<div class="col-md-3">
		<?= form_hidden("visit_id") ?>
		<?= form_hidden("service_id") ?>
		<?= form_hidden("px_id") ?>
        <?= form_hidden("surety_id2") ?>
		<?= create_select([
			"attr"         => ["name" => "tipe_patient=Tipe Penjualan", "id" => "tipe_patient", "class" => "form-control"],
			"option"    => [
				["id" => "0", "text" => "APS"], ["id" => "1", "text" => "NON APS"]
			],
			"selected" => "1"
		]) ?>
		<?= create_inputDate("sale_date", [
			"format"        => "yyyy-mm-dd",
			"autoclose"     => "true",
			"endDate"       => "today"
		],[
			"readonly"  => true,
			"required"  => true,
			"value"     => date('Y-m-d')
		]) ?>
		<?= create_input("patient_norm",[
			"required"  => true,]) ?>
		<?= create_input("patient_name",[
			"required"  => true,]) ?>
	</div>
	<div class="col-md-3">
		<?= create_select([
			"attr" => [
                "name"          => "own_id=Kepemilikan", 
                "id"            => "own_id", 
                "class"         => "form-control",
                "required"      => true
            ],
			"model" => [
				"m_surety_ownership" => ["get_kepemilikan", ["0" => '0']],
				"column"  => ["own_id", "own_name"]
			],
			"selected" => "1"
		]) ?>
		<?= create_select2([
			"attr" => ["name" => "surety_id=Penjamin", "id" => "surety_id", "class" => "form-control", "onchange" => "changeSurety()",
			"required"  => true],
			"model" => [
				"m_sale" => ["get_penjamin", ["surety_active" => 't']],
				"column"  => ["surety_id", "surety_name"]
			],
			"selected" => "1"
		]) ?>
		<?= create_select([
			"attr"         => ["name" => "sale_type=Cara Bayar", "id" => "sale_type", "class" => "form-control"],
			"option"    => [
				["id" => "0", "text" => "Tunai"], ["id" => "1", "text" => "Kredit"]
			]
		]) ?>
		<?= create_select([
			"attr"         => ["name" => "resep_prb", "id" => "resep_prb", "class" => "form-control"],
			"option"    => [
				["id" => "t", "text" => "Ya"], ["id" => "f", "text" => "Tidak"]
			],
			"selected" => "f"
		]) ?>
	</div>
	<div class="col-md-3">
		<?= create_select([
			"attr"         => ["name" => "kronis=Kronis", "id" => "kronis", "class" => "form-control","required"  => true],
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
		<?= create_select2([
			"attr" => [
				"name" => "doctor_id=Dokter", "id" => "doctor_id", "class" => "form-control"
			],
			"model" => [
				"m_sale" => ["get_dokter", ["employee_active" => 't']],
				"column" => ["employee_id", "nama_dokter"]
			],
		]) ?>
			<?=create_input("sep= No SEP",[
							"readonly"	=> true
		])?>
	</div>
	<div class="col-md-12">
		<?=create_textarea("alamat=Alamat Lengkap")?>
	</div>
</div>
<?= modal_open("modal_history", "History Pelayanan Pasien", "modal-lg", ["style" => "width:90%"]) ?>
<?= modal_close() ?>
<script>
    var dataPatient;
    $("body").on("focus", "#patient_norm", function() {
        $(this).autocomplete({
            source: "<?php echo site_url('sale/get_no_rm/norm'); ?>/"+$("#tipe_patient").val(),
            autoFocus: true,
            minLength:4,
            maxShowItems: 5,
            select: function(event, ui) {
                dataPatient = ui.item;
                $("#btn-history").attr("disabled",false);
                $('#patient_norm').val(ui.item.px_norm); 
                $('#patient_name').val(ui.item.px_name);
                $('#px_id').val(ui.item.px_id);
                $('#alamat').val(ui.item.px_address);
                if ($("#tipe_patient").val() == 1) {
                    if (ui.item.visit_prb == 't') {
                        $(".info-pasien").text("PASIEN PRB");
                        $("#resep_prb").val(ui.item.visit_prb);
                    }
                    $('#visit_id').val(ui.item.visit_id);
                    $('#doctor_id').val(ui.item.par_id);
                    $('#service_id').val(ui.item.srv_id);
                    $('#doctor_name').val(ui.item.par_name);
                    $('#unit_id_lay').val(ui.item.unit_id);
                    $('#sep').val(ui.item.sep_no);                   
                    if (ui.item.surety_id) {
                        $('#surety_id').val(ui.item.surety_id).trigger("change");
                    }
                    $('#unit_name_lay').val(ui.item.unit_name);
                    if (ui.item.surety_id == 1) {
                        $('#sale_type option[value="0"]').attr('selected', true);
                    } else {
                        $('#sale_type option[value="1"]').attr('selected', true);
                    }
                    $("select[class*='select2']").trigger("change");
                }
                $("#own_id").trigger("change");
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            if ($("#tipe_patient").val() == 1) {
                return $("<li>")
                .append("<div style='color: black;' class='comment-text'><span class=\"username\"><b>" +
                    item.px_norm + "|" + item.px_name +
                    "</b><span class=\"text-muted pull-right\">" + item.unit_name + "</span></span><p>" +
                    "<span>Tanggal Kunjung : <span class=\"text-muted pull-right\">" + (item.srv_date) + "</span></span><br>" +
                    "<span>Status Kunjung : <span class=\"text-muted pull-right\">" + (item.status_kunjungan) + "</span></span><br>" +
                    "<span>Dokter : <span class=\"text-muted pull-right\">" + (item.par_name) + "</span></span>" +
                    "</div>")
                .appendTo(ul);
            }else{
                return $("<li>")
                .append("<div style='color: black' class='comment-text'><span class=\"username\"><b>" +
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
            autoFocus: true,
            minLength:4,
            select: function(event, ui) {
                $("#btn-history").attr("disabled",false);
                $('#patient_norm').val(ui.item.px_norm);
                $('#px_id').val(ui.item.px_id);
                $('#patient_name').val(ui.item.px_name);
                $('#alamat').val(ui.item.px_address);
                $('#visit_id').val(ui.item.visit_id);
                $('#doctor_id').val(ui.item.par_id);
                $('#service_id').val(ui.item.srv_id);
                $('#doctor_name').val(ui.item.par_name);
                $('#unit_id_lay').val(ui.item.unit_id);
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
            $('#sale_type').val(0);
        } else {
            $('#sale_type').val(1);
        }

        $.get("sale/get_own_surety/"+$("#own_id").val()+'/'+$("#surety_id").val(),function(data){
            $("#margin_profit").val(data.percent_profit);
        },'json');
    }

    $('#modal_history').on('show.bs.modal', function () {
        $(this).find(".modal-body").load("sale/show_history_px");
    });

	<?= $this->config->item('footerJS') ?>
</script>