    <div class="col-md-12">
	<?=form_open("receiving/save",["method"=>"post","id"=>"fm_receiving"],$model)?>
	<div class="row">
		<div class="col-md-2">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Data PO</h3>
				</div>
				<div class="box-body">
					<?=form_hidden("rec_id")?>
					<?=create_input("receiver_num")?>
					<?=create_input("rec_num")?>
					<?=create_inputDate("rec_date",[
						"format"=>"yyyy-mm-dd",
						"autoclose"=>"true"
					])?>
					<?=create_inputDate("receiver_date",[
						"format"=>"yyyy-mm-dd",
						"autoclose"=>"true"
					])?>
					<?=create_select2([
						"attr" =>["name"=>"receiver_unit=Unit penerima","id"=>"receiver_unit","class"=>"form-control"],
						"model"=>["m_ms_unit" => ["get_ms_unit",["0"=>'0']],
										"column"  => ["unit_id","unit_name"]
									]
					])?>
					<?=create_select2([
						"attr" =>["name"=>"estimate_resource=sumber anggaran","id"=>"estimate_resource","class"=>"form-control"],
						"model"=>["m_receiving" => ["get_estimate_resource",["0"=>'0']],
										"column"  => ["estimate_resource","estimate_resource"]
									],
						"select2" => ["tags"=>true]
					])?>
				</div>
			</div>
		</div>
		<div class="col-md-10">
			<div class="col-sm-6">
				<?=create_select2([
					"attr" =>["name"=>"po_id=nomor po","id"=>"po_id","class"=>"form-control"],
					"model"=>["m_po" => ["get_po",["0"=>'0']],
									"column"  => ["po_id","detail_po"]
								],
				])?>
				<?=create_select([
					"attr"=>["name"=>"pay_type=Tipe pembayaran","id"=>"pay_type","class"=>"form-control"],
					"option"=> [["id"=>'1',"text"=>"Tunai"],["id"=>'2',"text"=>"Kredit"]],
				])?>
				<?=create_select([
					"attr"=>["name"=>"ppn","id"=>"ppn","class"=>"form-control"],
					"option"=> [["id"=>'0',"text"=>"Harga Include PPN"],["id"=>'10',"text"=>"PPN 10%"]],
				])?>
			</div>
			<div class="col-sm-6">
				<?=create_input("rec_taxes=PPN",["readonly"=>true])?>
				<?=create_input("discount_total",["readonly"=>true])?>
				<?=create_input("grand_total",["readonly"=>true])?>
			</div>
			<DIV class="col-sm-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List Item</h3>
					</div>
					<div class="box-body" id="list_item">
					</div>
				</div>
			</DIV>
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
  <?=$this->config->item('footerJS')?>
</script>