<div class="col-md-12">
	<?= form_open("employee/save", ["method" => "post", "class" => "form-horizontal", "id" => "fm_employee"], $model) ?>
	<?= form_hidden("employee_id") ?>
	<?= create_input("employee_nik") ?>
	<?= create_input("employee_nip") ?>
	<?= create_input("employee_name") ?>
	<?= create_input("employee_ft=gelar depan") ?>
	<?= create_input("employee_bt=gelar belakang") ?>
	<?= create_select([
				"attr" => ["name" => "employee_sex=Jenis Kelamin", "id" => "employee_sex", "class" => "form-control", 'required' => true],
				"option" => [["id" => 'L', "text" => "Laki-laki"], ["id" => 'P', "text" => "Perempuan"]],
		]) ?>
	<?= create_input("user_name",[
		"required" => true
	]) ?>
	
	<?= create_input("user_password",[
		"required" => true
	]) ?>

	<?=create_select2(["attr"=>["name"=>"group_id[]=Group Akses ","required"=>"true","id"=>"group_id","multiple"=>"true","class"=>"form-control"],
		"model"=>["m_ms_group" => "get_ms_group",
		"column"=>["group_id","group_name"]]
	])?>
	
	<?= create_select([
			"attr" => ["name" => "employee_active=Status", "id" => "employee_active", "class" => "form-control", 'required' => true],
			"option" => [["id" => 't', "text" => "Aktif"], ["id" => 'f', "text" => "Non Aktif"]],
	]) ?>
	<?= form_close() ?>
	<div class="box-footer">
		<button class="btn btn-primary" type="button" onclick="$('#fm_employee').submit()">Save</button>
		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
	</div>
</div>
<script type="text/javascript">
	$("#btn-cancel").click(() => {
		$("#form_employee").hide();
		$("#form_employee").html('');
	});

	<?= $this->config->item('footerJS') ?>
</script>