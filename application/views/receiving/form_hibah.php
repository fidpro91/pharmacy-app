<div class="col-md-12">
	<?=form_open("receiving/save_non_po",["method"=>"post","id"=>"fm_receiving"],$model)?>
	<div class="row">
		<div class="col-md-4">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Data Hibah</h3>
				</div>
				<div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?=form_hidden("rec_id")?>
                            <?=create_inputDate("receiver_date",[
                                "format"=>"yyyy-mm-dd",
                                "autoclose"=>"true"
                            ],[
                                "value"     => date("Y-m-d"),
                                "readonly" => true
                            ])?>
                            <?=create_input("receiver_num",[
                                "value"     => $norec,
                                "readonly"  => true
                            ])?>
                            <?=create_input("rec_num")?>
                            <?=create_select2([
                                "attr" =>[
                                    "name"      =>"receiver_unit=Unit penerima",
                                    "id"        =>"receiver_unit",
                                    "class"     =>"form-control",
                                    "required"  =>"true",
                                ],
                                "model"=>["m_ms_unit" => ["get_ms_unit",["employee_id"=>$this->session->employee_id]],
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
                        <div class="col-md-6">
                            <?=create_select([
                                "attr" =>[
                                    "name"=>"own_id=Kepemilikan",
                                    "id"=>"own_id",
                                    "class"=>"form-control",
                                    "required"  =>"true",
                                ],
                                "model"=>["m_receiving" => ["get_owner",["0"=>'0']],
                                                "column"  => ["own_id","own_name"]
                                            ],
                            ])?>
                            <?=create_input("hibah_name=Dari")?>
                            <?=create_select([
                                "attr" =>["name"=>"hibah_cat=Kategori Hibah","id"=>"hibah_cat","class"=>"form-control"],
                                "model"=>["m_receiving" => ["get_hibah",["refcat_id"=>'21']],
                                                "column"  => ["reff_id","reff_name"]
                                            ],
                            ])?>
                            <?=create_select([
                                "attr"=>["name"=>"rec_type=Tipe Penerimaan","id"=>"rec_type","class"=>"form-control"],
                                "option"=> [["id"=>'1',"text"=>"Penerimaan Po"],["id"=>'1',"text"=>"Penerimaan Hibah"],["id"=>'2',"text"=>"Penerimaan Konsinyasi"]],
                            ])?>
                        </div>
                    </div>
				</div>
			</div>
		</div>
        <DIV class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">List Item</h3>
                    <div class="box-tools pull-right">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Grand Total</label>
                            <div class="col-sm-8">
                                <input type="text" name="grand_total" id="grand_total" class="form-control uang" style="text-align: right;" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list_item box-body" id="list_item">
                </div>
            </div>
        </DIV>
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
        $(".list_item").inputMultiRow({
	            column: ()=>{
					var dataku; 
					$.ajax({
						'async': false,
						'type': "GET",
						'dataType': 'json',
						'url': "receiving/show_multiRows",
						'success': function (data) {
							dataku = data;
						}
					});
					return dataku;
	                },
                "data": dataHibah 
	    }); 
        $(".uang").inputmask("IDR"); 
	});

    $("body").on("focus", ".autocom_item_id", function() {
	    $(this).autocomplete({
            source: "<?php echo site_url('receiving/get_item');?>",
            autoFocus: true,
			minLength:3,
            select: function (event, ui) {
                $(this).closest('tr').find('.item_id').val(ui.item.item_id);
                $(this).closest('tr').find('.item_pack').val(ui.item.item_package);
                $(this).closest('tr').find('.item_unit').val(ui.item.item_unitofitem);
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div class='comment-text'><span class=\"username\"><b>"+
					item.value+"|"+item.item_code+
				"</b><span class=\"text-muted pull-right\">"+formatNumeric(item.kemasan)+"</span></span><p>"+
				"<span>Kategori Item : <span class=\"text-muted pull-right\">"+(item.classification_name)+"</span></span><br>"+
				"</div>")
                .appendTo(ul);
        };
	});

    $("body").on("focus", ".expired_date", function() {
		$(this).inputmask("99-99-9999",{ "placeholder": "dd-mm-yyyy" });
	});

    $("body").on("focus", ".price_item, .price_total", function() {
		$(this).inputmask("IDR");
	});

    $("body").on("keyup", ".qty_pack, .price_item, .unit_per_pack", function() {
		hitungTotal($(this));
	});

    function hitungTotal(row) {
		let qtyPack = parseFloat($.isNumeric(row.closest('tr').find('.qty_pack').val())?row.closest('tr').find('.qty_pack').val():0);
		let qty = parseFloat($.isNumeric(row.closest('tr').find('.unit_per_pack').val())?row.closest('tr').find('.unit_per_pack').val():0);
		let harga = parseFloat($.isNumeric(row.closest('tr').find('.price_item').val())?row.closest('tr').find('.price_item').val():0);
		let total = (qtyPack*qty)*harga;
		row.closest('tr').find('.price_total').val(total);
        hitunggrandTotal();
	}

    function hitunggrandTotal(){
		let grandtotal=0;
		$(".price_total").each(function(){
			let total = parseFloat($.isNumeric($(this).val())?$(this).val():0);
			grandtotal += total;
		});
		$('#grand_total').val(grandtotal);
	}

    $('#fm_receiving').on("submit",function(){
        $(this).data("validator").settings.submitHandler = function (form) { 
            if (confirm("Simpan data hibah?")) {
                $.blockUI();
                $.ajax({
                    'type': "post",
                    'data'	: $(form).serialize(),
                    'dataType': 'json',
                    'url': "receiving/save_non_po",
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