    <div class="col-md-12">
	<?=form_open("receiving/save",["method"=>"post","id"=>"fm_receiving"],$model)?>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Data PO</h3>
				</div>
				<div class="box-body">
					<div class="col-md-6">
						<?=form_hidden("rec_id")?>
						<?=create_inputDate("receiver_date=tgl Penerimaan",[
							"format"=>"yyyy-mm-dd",
							"autoclose"=>"true"
						],[
							"value" 	=> date('Y-m-d'),
							"readonly"	=> true
						])?>
						<?=create_input("receiver_num=No Penerimaan",[
							"value" 	=> $norec,
							"readonly"	=> true,
						])?>
						<?=create_input("rec_num=No Faktur")?>
						<?=create_inputDate("rec_date=Tgl Faktur",[
							"format"=>"yyyy-mm-dd",
							"autoclose"=>"true"
						],[
							"readonly" => "true"
						])?>
						<?=create_select2([
							"attr" =>["name"=>"estimate_resource=sumber anggaran","id"=>"estimate_resource","class"=>"form-control"],
							"model"=>["m_receiving" => ["get_estimate_resource",["0"=>'0']],
											"column"  => ["estimate_resource","estimate_resource"]
										],
							"select2" => ["tags"=>true]
						])?>
					</div>
					<div class="col-md-6">
						<?=create_select2([
							"attr" =>["name"=>"receiver_unit=Unit penerima","id"=>"receiver_unit","class"=>"form-control"],
							"model"=>["m_ms_unit" => ["get_ms_unit",			["employee_id"=>$this->session->employee_id]],
											"column"  => ["unit_id","unit_name"]
										]
						])?>
						<?=create_select2([
							"attr" =>[
								"name"		=>"po_id=nomor po",
								"id"		=>"po_id",
								"class"		=>"form-control",
								// "onchange"	=>"hitunggrandTotal()"
							],"model"=>["m_po" => ["get_po",["po.po_status is null"=>null]],
											"column"  => ["po_id","detail_po"]
										],
						])?>
					
						<?=create_select([
							"attr"=>["name"=>"pay_type=Tipe pembayaran","id"=>"pay_type","class"=>"form-control"],
							"option"=> [["id"=>'1',"text"=>"Tunai"],["id"=>'2',"text"=>"Kredit"]],
						])?>
						<?=create_select2([
							"attr" =>["name"=>"ppn=PPN","id"=>"ppn","class"=>"form-control"],
							"model"=>["m_setting_app" => ["get_setting_app",["setting_type"=>'1']],
											"column"  => ["setting_value","setting_name"]
										],
						])?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">List Item</h3>
				</div>
				<div class="box-body" id="list_item">
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<?=create_inputmask("rec_taxes=PPN","IDR",["readonly"=>true])?>
			<?=create_inputmask("discount_total","IDR",["readonly"=>true])?>
			<?=create_inputmask("grand_total","IDR",["readonly"=>true])?>
		</div>
	</div>
	<?=form_close()?>
      <div class="box-footer">
      		<button class="btn btn-primary" type="button" onclick="$('#fm_receiving').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
      </div>
	
    </div>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_receiving").hide();
		$("#form_receiving").html('');
	});
	$(document).ready(()=>{
		$("#po_id").change(function(){
			$.get("receiving/find_po_detail/"+$(this).val(),function(resp){
				$("#list_item").html(resp);
			},"html");
		});
	});
	$('#fm_receiving').on("submit",function(){
		$(this).data("validator").settings.submitHandler = function (form) {
			if (confirm("Simpan data penerimaan?")) { 
				$.blockUI();
				$.ajax({
					'type': "post",
					'data'	: $(form).serialize(),
					'dataType': 'json',
					'url': "receiving/save",
					'success': function (data) {
						$.unblockUI();
						alert(data.message);
						if (data.code == '200') {
							location.reload(true);
						}
					}
				});
			}
			return false;
		};
	});
  <?=$this->config->item('footerJS')?>
</script>