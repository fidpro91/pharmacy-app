<?=form_open("receiving/save_update",["method"=>"post","id"=>"fm_receiving_update"],$model)?>
<?= form_hidden("rec_id") ?>
<?= create_inputDate("receiver_date=tgl Penerimaan", [
    "format" => "yyyy-mm-dd",
    "autoclose" => "true"
], [
    "value"     => date('Y-m-d'),
    "readonly"    => true
]) ?>
<?= create_input("receiver_num=No Penerimaan", [
    "readonly"    => true,
]) ?>
<?= create_input("rec_num=No Faktur") ?>
<?= create_inputDate("rec_date=Tgl Faktur", [
    "format" => "yyyy-mm-dd",
    "autoclose" => "true"
], [
    "readonly" => "true"
]) ?>
<?= create_select([
    "attr" => ["name" => "pay_type=Tipe pembayaran", "id" => "pay_type", "class" => "form-control"],
    "option" => [["id" => '1', "text" => "Tunai"], ["id" => '2', "text" => "Kredit"]],
]) ?>
<?=form_close()?>
<script>
$(document).ready(()=>{
    $("body").on("click","#save-update",()=>{
        $("#fm_receiving_update").submit();
    });
})
<?=$this->config->item('footerJS')?>
</script>