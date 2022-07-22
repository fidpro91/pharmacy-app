<div class="row">
    <?= form_open("import_master/import_so", ["method" => "post", "id" => "form_import", "enctype" => "multipart/form-data"]) ?>
    <div class="col-md-12">
        <?= create_inputDate("import_date", [
            "format" => "yyyy-mm-dd",
            "autoclose" => "true"
        ], [
            "readonly"  => true,
            "required"  => true,
            "value"     => date('Y-m-d')
        ]) ?>
        <?= create_select([
            "attr" => ["name" => "own_id=Kepemilikan", "id" => "own_id", "class" => "form-control"],
            "model" => [
                "m_surety_ownership" => ["get_kepemilikan", ["0" => '0']],
                "column"  => ["own_id", "own_name"]
            ]
        ]) ?>
        <?= create_select([
            "attr" => ["name" => "unit_id=Unit Farmasi ", "id" => "unit_id", "class" => "form-control"],
            "model" => [
                "m_ms_unit" => ["get_ms_unit",["employee_id"=>$this->session->employee_id]],
                "column" => ["unit_id", "unit_name"]
            ]
        ]) ?>
        <div class="form-group">
            <label>File Excel</label>
            <input type="file" name="file_import" id="file_import">
        </div>
    </div>
    <div class="col-md-12" style="text-align:center ;">
        <button class="btn btn-primary" id="btn-save" type="submit">Save</button>
        <button class="btn btn-danger" type="button" id="btn-close" data-dismiss="modal" aria-label="Close">Close</button>
    </div>
    <?= form_close() ?>
</div>
<script>
    $("#form_import").submit(()=>{
        if (confirm("Data stok akan direset dan digantikan dengan stok yang baru, Anda yakin?")) {
            return true;
        }
        return false;
    });
    <?= $this->config->item('footerJS') ?>
</script>