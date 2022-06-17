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
                <?= form_hidden("unit_id_lay") ?>
                <?= form_hidden("unit_id") ?>
                <?= create_input("no_rm") ?>
                <?= create_input("nama") ?>
                <!-- //<?= create_input("surety_id") ?> -->
                <?= create_select2(["attr" => ["name" => "surety_id=Penjamin", "id" => "surety_id", "class" => "form-control", "onchange"=>"changeSurety()"],
			"model" => ["m_sale" => ["get_penjamin", ["0" => '0']],
				"column"  => ["surety_id", "surety_name"]
			]
		]) ?>

                <?=create_select([
								"attr" 		=> ["name"=>"sale_type=Tipe Penjualan","id"=>"sale_type","class"=>"form-control"],
								"option"	=>[
												["id"=>"0","text"=>"Tunai"],["id"=>"1","text"=>"Kredit"]
											  ]
							])?>

            </div>
            <div class="col-md-6">
                <?= create_input("doctor_name") ?>
                <?=create_select([
								"attr" 		=> ["name"=>"kronis=Kronis","id"=>"kronis","class"=>"form-control"],
								"option"	=>[
												["id"=>"t","text"=>"Ya"],["id"=>"f","text"=>"Tidak"]
											  ]
							])?>
            <?= create_select2(["attr" => ["name" => "own_id=Kepemilikan", "id" => "own_id", "class" => "form-control"],
			"model" => ["m_surety_ownership" => ["get_kepemilikan", ["0" => '0']],
				"column"  => ["own_id", "own_name"]
			]
		]) ?>
                <?= create_input("unit_name_lay=Unit Layanan") ?>

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
$("body").on("focus", "#no_rm", function() {
    $(this).autocomplete({
        source: "<?php echo site_url('sale/get_no_rm/norm');?>",
        select: function (event, ui) {
			$('#no_rm').val(ui.item.px_norm);
            $('#nama').val(ui.item.px_name);
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
            if(ui.item.surety_id==1){
				$('#sale_type option[value="0"]').attr('selected', true);
			}
			else{
				$('#sale_type option[value="1"]').attr('selected', true);
			}
        }


    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<div class='comment-text'><span class=\"username\"><b>"+
                item.px_norm+"|"+item.px_name+
            "</b><span class=\"text-muted pull-right\">"+item.unit_name+"</span></span><p>"+
            "<span>Tanggal Kunjung : <span class=\"text-muted pull-right\">"+(item.srv_date)+"</span></span><br>"+
            "<span>Status Kunjung : <span class=\"text-muted pull-right\">"+(item.status_kunjungan)+"</span></span><br>"+
            "<span>Dokter : <span class=\"text-muted pull-right\">"+(item.par_name)+"</span></span>"+
            "</div>")
            .appendTo(ul);
    };
    changeSurety();
			return false;
});

$("body").on("focus", "#nama", function() {
    $(this).autocomplete({
        source: "<?php echo site_url('sale/get_no_rm/name');?>",
        select: function (event, ui) {
			$('#no_rm').val(ui.item.px_norm);
            $('#nama').val(ui.item.px_name);
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
            if(ui.item.surety_id==1){
				$('#sale_type option[value="0"]').attr('selected', true);
			}
			else{
				$('#sale_type option[value="1"]').attr('selected', true);
			}
        }


    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<div class='comment-text'><span class=\"username\"><b>"+
                item.px_norm+"|"+item.px_name+
            "</b><span class=\"text-muted pull-right\">"+item.unit_name+"</span></span><p>"+
            "<span>Tanggal Kunjung : <span class=\"text-muted pull-right\">"+(item.srv_date)+"</span></span><br>"+
            "<span>Status Kunjung : <span class=\"text-muted pull-right\">"+(item.status_kunjungan)+"</span></span><br>"+
            "<span>Dokter : <span class=\"text-muted pull-right\">"+(item.par_name)+"</span></span>"+
            "</div>")
            .appendTo(ul);
    };
    changeSurety();
			return false;
});

function changeSurety(){

	var sale_type = $("#surety_id").val(); 
	if(sale_type == 1 || sale_type == 33){
		$('#sale_type option[value="0"]').val('selected', true);      
	}
	else{
		$('#sale_type option[value="1"]').attr('selected', true);
        
	}
}
$("#btn-save-pasien").click(()=>{
    $.ajax({
        'async': false,
        'type': "post",
        'data': $("#form_pasien").serialize(),
        'url': "sale/set_data_pasien",
        'success': function (data) {
           if(data=="sukses"){
            $("#modal_pasien").modal('hide');
			$("#tno_rm").html($("#no_rm").val());
			$("#tpx_name").html($("#nama").val());
           }
        }
    });
});

</script>
