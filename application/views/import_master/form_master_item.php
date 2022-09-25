<div class="row">
    <?= form_open("import_master/import_master_item", ["method" => "post", "id" => "form_import", "enctype" => "multipart/form-data"]) ?>
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
            "attr"      => ["name" => "jns_import", "id" => "jns_import", "class" => "form-control"],
            "option"    => [
                ["id" => "t", "text" => "Replace"], ["id" => "f", "text" => "Insert"]
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
        if (confirm("Import Data Item?")) {
            return true;
        }
        return false;
    });
    <?= $this->config->item('footerJS') ?>
</script>