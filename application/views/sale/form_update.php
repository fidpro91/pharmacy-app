<div class="col-md-4">
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
        <?= create_select2([
            "attr" => [
                "name" => "surety_id=Penjamin", "id" => "surety_id", "class" => "form-control", "onchange" => "changeSurety()",
                "required"  => true
            ],
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
        <?= create_select([
            "attr" => ["name" => "own_id=Kepemilikan", "id" => "own_id", "class" => "form-control"],
            "model" => [
                "m_surety_ownership" => ["get_kepemilikan", ["0" => '0']],
                "column"  => ["own_id", "own_name"]
            ],
            "selected" => "1"
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
<script>
	$(document).ready(()=>{
		var dataItemSale=<?=$itemDetail?>;
        $(".list_obat_edited").inputMultiRow({
	            column: ()=>{
					var dataku;
					$.ajax({
						'async': false,
						'type': "GET",
						'dataType': 'json',
						'url': "sale/show_multiRows_update/true",
						'success': function (data) {
							dataku = data;
						}
					});
					return dataku;
	                },
                "data": dataItemSale
	    });
	});
	<?=$this->config->item('footerJS')?>
</script>