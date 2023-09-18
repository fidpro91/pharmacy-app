<div class="col-md-3">
<?= create_inputDaterange("tanggal_pelayanan", ["locale" => ["format" => "YYYY-MM-DD", "separator" => "/"]]) ?>
</div>
<div class="col-md-12">
    <table class="table table-bordered table-hover" id="tableHistory">
        <thead>
            <tr>
                <th>NO</th>
                <th>TGL/JAM</th>
                <th>UNIT LAYANAN</th>
                <th>DIAGNOSA</th>
                <th>TINDAKAN</th>
                <th>TERAPI/OBAT</th>
                <th>LABORATORIUM</th>
                <th>RADIOLOGI</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>         
</div>
<script>

    $(document).ready(()=>{
        getHistory();
    })

    $("#tanggal_pelayanan").change(() => {
      getHistory();
    });

    function getHistory() {
        $("#tableHistory").find("tbody").empty();
        $.post("sale/get_history_px/",{
            px_id : $('#px_id').val(),
            tanggal  : $("#tanggal_pelayanan").val(),
        },function(resp){
            $("#tableHistory").find("tbody").html(resp);
            $("#tableHistory").DataTable();
        });
    }
    <?= $this->config->item('footerJS') ?>
</script>