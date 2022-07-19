<div class="row">
    <?= form_open("", ["method" => "post", "id" => "form_import"]) ?>
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
                "m_ms_unit" => "get_ms_unit",
                "column" => ["unit_id", "unit_name"]
            ]
        ]) ?>
        <div class="form-group">
            <label>File Excel</label>
            <input type="file" name="file_import" id="file_import">
        </div>
    </div>
    <div class="col-md-12" style="text-align:center ;">
        <button class="btn btn-primary" id="btn-save" type="button">Save</button>
        <button class="btn btn-danger" type="button" id="btn-close" data-dismiss="modal" aria-label="Close">Close</button>
    </div>
    <?= form_close() ?>
</div>