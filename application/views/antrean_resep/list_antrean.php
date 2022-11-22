<style>
    .tampilan {
        height: 150vh !important;
    }
    table td,th {
        font-size: 3vh !important;
    }
</style>
<script src="<?=base_url()."assets/"?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()."assets/"?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<div class="col-md-4">
    <div class="box box-success tampilan">
        <div class="box-header with-border">
            <h3 class="box-title">ORDER RESEP ONLINE</h3>
        </div>
        <div class="box-body">
            <?= create_report_custom([
                "ext"         => ['class' => 'table table-hover'],
                "name"         => "tabel_order",
                "column"     => [
                    "rcp_no" => [
                        "label" => "NOMOR RESEP"
                    ]
                ],
                "data"     => $orderRcp,
            ]) ?>
        </div>
        <!-- /.box-body -->
    </div>
</div>
<div class="col-md-8">
    <div class="box box-success tampilan">
        <div class="box-header with-border">
            <h3 class="box-title">ANTREAN RESEP APOTEK</h3>
        </div>
        <div class="box-body">
            <?= create_report_custom([
                "ext"         => ['class' => 'table table-hover'],
                "name"         => "tabel_antrean",
                "column"     => [
                    "sale_num" => [
                        "label" => "KODE ANTREAN"
                    ],
                    "patient_norm" => [
                        "label" => "NORM"
                    ],
                    "patient_name" => [
                        "label" => "NAMA PASIEN"
                    ],
                    "order_rcp" => [
                        "label" => "ORDER"
                    ],
                    "entri_rcp" => [
                        "label" => "ENTRI"
                    ],
                    "finish_rcp" => [
                        "label" => "FINISH"
                    ],
                ],
                "data"     => $antreanRcp,
            ]) ?>
        </div>
        <!-- /.box-body -->
    </div>
</div>
<script>
    $(document).ready(()=>{
        $("table").DataTable({
            "pageLength": 10,
            "lengthChange": false,
            "filter"    : false
        });
    })
</script>