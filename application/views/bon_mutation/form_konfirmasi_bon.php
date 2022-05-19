<div class="col-md-12">
    <?= form_open("bon_mutation/konfirmasi_distribusi", ["method" => "post", "class" => "form-horizontal", "id" => "fm_konfirmasi_bon"], $model) ?>
    <?= form_hidden("mutation_id",$dataBon['header']->mutation_id) ?>
    <div class="list_item">
        <table class="table">
            <tr>
                <th>NO</th>
                <th>NAMA ITEM</th>
                <th>SATUAN</th>
                <th>JML MINTA</th>
                <th>JML KIRIM</th>
            </tr>
            <?php
                foreach ($dataBon['detail'] as $key => $value) {
                    echo "
                        <tr>
                            <td>".($key+1)."</td>
                            <td>$value->item_name</td>
                            <td>$value->item_package</td>
                            <td>$value->qty_request</td>
                            <td>$value->qty_send</td>
                        </tr>
                    ";
                }
            ?>
        </table>
    </div>
    <div class="box-footer" align="center">
        <button class="btn btn-primary" name="button-konfirm" value="1" type="submit">Terima</button>
        <button class="btn btn-danger" name="button-konfirm" value="2" type="submit">Batal Terima</button>
    </div>
    <?= form_close() ?>
</div>
<script type="text/javascript">
    $("#btn-cancel").click(() => {
        $("#modal_konfirmasi").modal("hide");
    });

    $("#fm_konfirmasi_bon").on("submit",()=>{
        if(!confirm("Konfirmasi distribusi item?")){
            return false;
        };
    });
    <?= $this->config->item('footerJS') ?>
</script>