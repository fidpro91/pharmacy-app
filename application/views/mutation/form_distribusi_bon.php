<div class="col-md-12">
    <?= form_open("distribusi_bon/save_distribusi", ["method" => "post", "class" => "form-horizontal", "id" => "fm_mutation"], $model) ?>
    <?= form_hidden("mutation_id",$dataBon['header']->mutation_id) ?>
    <?= form_hidden("own_id",$dataBon['header']->own_id) ?>
    <?= create_select([
        "attr" => [
                    "name" => "unit_sender=Unit Pengirim", 
                    "id" => "unit_sender", 
                    "class" => "form-control",
                    "style" => "width:100%;"
                ],
                "model"=>["m_mutation" => ["get_user_in_unit",[0,"u.user_id"=>$this->session->user_id]],
                "column"  => ["unit_id","unit_name"]],
                "selected" => $dataBon['header']->unit_sender
            ])
    ?>
    <?= create_inputDate("mutation_date=tanggal kirim", [
        "format" => "yyyy-mm-dd",
        "autoclose" => "true"
    ],[
        "readonly"  => true,
        "value"     => date('Y-m-d')
    ]) ?>
    <div>
		<table class="table">
			<tr>
				<th>NOMOR</th>
				<th>:</th>
				<th><?=$dataBon['header']->bon_no?></th>
				<th>TANGGAL</th>
				<th>:</th>
				<th><?=($dataBon['header']->mutation_date)?></th>
			</tr>
			<tr>
				<th>UNIT MINTA</th>
				<th>:</th>
				<th><?=$dataBon['header']->unit_name?></th>
				<th>KEPEMILKAN</th>
				<th>:</th>
				<th><?=$dataBon['header']->own_name?></th>
			</tr>
			<tr>
				<th>USER MINTA</th>
				<th>:</th>
				<th><?=$dataBon['header']->person_name?></th>
			</tr>
		</table>
	</div>
    <div class="list_item">
        <table class="table">
            <tr>
                <th>NO</th>
                <th>NAMA ITEM</th>
                <th>SATUAN</th>
                <th>JML MINTA</th>
                <th>JML STOK</th>
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
                            <td>".(!empty($value->stock_summary)?$value->stock_summary:"0")."</td>
                            <td>
                                ".form_hidden("list_item[$key][mutation_detil_id]",$value->mutation_detil_id.'|'.$value->item_id.'|'.$value->unit_require).form_input([
                                    "type"  => "text",
                                    "name"  => "list_item[$key][qty_send]",
                                    "class" => "form-control",
                                    "value" => (isset($value->qty_send)?$value->qty_send:$value->qty_request)
                                ])."
                            </td>
                        </tr>
                    ";
                }
            ?>
        </table>
    </div>
    <?= form_close() ?>
    <div class="box-footer">
        <button class="btn btn-primary" type="button" onclick="$('#fm_mutation').submit()">Save</button>
        <button class="btn btn-warning" type="button" id="btn-cancel" data-dismiss="modal">Cancel</button>
    </div>
</div>
<script type="text/javascript">
    $("#btn-cancel").click(() => {
        $("#form_mutation").hide();
        $("#form_mutation").html('');
    });
    $("#fm_mutation").on("submit",function(){
        if (confirm("Simpan data permintaan?")) {
            $.ajax({
                'type': "post",
                'data': $("#fm_mutation").serialize(),
                'url': "distribusi_bon/save_distribusi",
                'dataType' : 'json',
                'success': function(data) {
                    alert(data.message);
                    if (data.code == '200') {
                        table.draw();
                        $("#modal_distribusi").modal('hide');
                    }
                }
            });
        }
        return false;
    });
    <?= $this->config->item('footerJS') ?>
</script>