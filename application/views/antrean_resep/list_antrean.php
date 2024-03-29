<style>
    .tampilan {
        height: 150vh !important;
    }
    table td,th {
        font-size: 3vh !important;
    }
</style>
<div class="col-md-12">
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
                    "patient_name" => [
                        "label" => "NAMA PASIEN"
                    ],
                    "status_resep" => [
                        "label"     => "STATUS"
                    ]
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
            "pageLength": 20,
            "lengthChange": false,
            "filter"    : false,
			'scroller':       true,
			'scrollY':        220,
			'scrollCollapse': true,
			'paging'      : false,
			'searching'   : false,
			'info'        : true,
        });
    })
</script>
