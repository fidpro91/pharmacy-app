<?= form_open("sale/update_data", ["method" => "post", "id" => "form_update_sale"]) ?>
<div class="row">
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
            <table class="table table-bordered">
                <tr>
                    <th>NO</th>
                    <th>ITEM</th>
                    <th>RACIKAN</th>
                    <th>STOK</th>
                    <th>HARGA</th>
                    <th>QTY</th>
                    <th>#</th>
                </tr>
                <?php
                    foreach ($item as $key => $value) {
                        echo "<tr>
                            <td>".($key+1)."</td>
                            <td>$value->item_name</td>
                            <td>".
                            form_input([
                                "name"      => "detail[$key][racikan_id]",
                                "class"     => "form-control sale_qty",
                                "value"     => $value->racikan_id,
                                "readonly"  => true
                            ])
                            ."</td>
                            <td>$value->stok</td>
                            <td>".
                            form_input([
                                "name"      => "detail[$key][sale_price]",
                                "class"     => "form-control sale_qty",
                                "value"     => $value->harga,
                                "readonly"  => true
                            ])."</td>
                            <td>".
                            form_hidden("detail[$key][item_id]",$value->item_id).
                            form_hidden("detail[$key][racikan]",$value->racikan).
                            form_hidden("detail[$key][racikan_dosis]",$value->racikan_dosis).
                            form_hidden("detail[$key][racikan_qty]",$value->racikan_qty).
                            form_input([
                                "name"      => "detail[$key][sale_qty]",
                                "class"     => "form-control sale_qty",
                                "value"     => $value->sale_qty
                            ])
                            ."</td>
                            <td>".
                            form_button([
                                "type"      => "button",
                                "class"     => "btn btn-xs btn-danger",
                                "content"   => "<i class='fa fa-trash'></i>",
                                "onclick"   => "removeTr(this)"
                            ])
                            ."</td>
                        </tr>";
                    }
                ?>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box">
            <div class="box-footer">
                <div class="box-tools pull-right">
                    <button class="btn btn-primary" id="btn-save-pasien" onclick="$('#form_update_sale').submit()" type="button">Save</button>
                    <button class="btn btn-danger" type="button" id="btn-close-pasien" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>
<script>
	function removeTr(a) {
        $(a).closest('tr').remove();
    }
	<?=$this->config->item('footerJS')?>
</script>