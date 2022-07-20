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