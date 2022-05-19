    <div class="col-md-12">
      	<?=form_open("opname_header/save",["method"=>"post","class"=>"form-horizontal","id"=>"fm_opname_header"],$model)?>
			<div class="col-md-3">
				<?=form_hidden("opname_header_id")?>
				<?=create_inputDate("opname_date",[
					"format"=>"yyyy-mm-dd",
					"autoclose"=>"true"
				])?>
				<?=create_input("opname_no")?>
				<?=create_select2([
					"attr" =>["name"=>"unit_id=Unit","id"=>"unit_id","class"=>"form-control"],
					"model"=>["m_ms_unit" => ["get_ms_unit",["0"=>'0']],
									"column"  => ["unit_id","unit_name"]
								]
				])?>
				<?=create_select([
					"attr" =>["name"=>"own_id=Kepemilikan","id"=>"own_id","class"=>"form-control"],
					"model"=>["m_ms_reff" => ["get_ms_reff",["refcat_id"=>'37']],
									"column"  => ["reff_id","reff_name"]
								],
				])?>
				<?=create_textarea("opname_note")?>
			</div>
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List Item</h3>
					</div>
					<div class="list_item box-body" id="list_item">
					</div>
				</div>
			</div>
    </div>
	<div class="box-footer">
		<div class="box-tools pull-right">
			<button class="btn btn-primary" type="button" onclick="$('#fm_opname_header').submit()">Save</button>
      		<button class="btn btn-warning" type="button" id="btn-cancel">Cancel</button>
		</div>
    </div>
	<?=form_close()?>
<script type="text/javascript">
	$("#btn-cancel").click( () => {
		$("#form_opname_header").hide();
		$("#form_opname_header").html('');
	});
	$(document).ready(()=>{
        $(".list_item").inputMultiRow({
			column: ()=>{
				var dataku;
				$.ajax({
					'async': false,
					'type': "GET",
					'dataType': 'json',
					'url': "opname_header/show_multiRows",
					'success': function (data) {
						dataku = data;
					}
				});
				return dataku;
				},
			"data":dataItemOpname,
	    });
	});
	/* jQuery.widget('custom.mcautocomplete', jQuery.ui.autocomplete, {
		_resizeMenu: function() {
			var ul = this.menu.element;
			ul.outerWidth( Math.max(
				ul.width( this.options.width ).outerWidth() + 1,
				this.element.outerWidth()
				) );
		},
		_renderMenu: function(ul, items) {
			var self = this, thead;
			if (this.options.showHeader) {
				table = jQuery('<div class="ui-widget-header" style="padding:0 2px; width:100%; border-bottom:1px solid #848484;"></div>');
				jQuery.each(this.options.columns, function(index, item) {
					table.append('<span style="margin-top:2px; margin-bottom:2px; padding:0 4px; font-size:11px; ' + 
						'float:left; text-align:' + item.align + '; width:' + item.width + ';">' + 
						item.name + 
						'</span>');
				});
				table.append('<div style="clear: both;"></div>');
				ul.append(table);
			}
			jQuery.each(items, function(index, item) {
				self._renderItem(ul, item);
			});
		},
		_renderItem: function(ul, item) {
			var t = '',
			result = '';

			jQuery.each(this.options.columns, function(index, column) {
				t += '<span style="margin-top:3px; padding:0 4px; float:left; font-size:11px; ' + 
				'width:' + column.width + '; text-align:' + column.align + '; color: #000;">' + 
				item[column.valueField ? column.valueField : index] + 
				'</span>'
			});
			result = jQuery('<li></li>').addClass('li-autocomplete-border')
			.data('ui-autocomplete-item', item)
			.append('<a class="ui-corner-all">' + t + '<div style="clear: both;"></div></a>')
			.appendTo(ul);
			return result;
		}
	});

	width 	= 650;
	columns = [
	{ name: 'Kode Obat',width: '100px',  align: 'left', valueField: 'item_code' },
	{ name: 'Nama Obat',width: '250px', align: 'left', valueField: 'item_name' },
	{ name: 'Kategori',width: '200px',  align: 'center', valueField: 'harga' },
	{ name: 'Kemasan',width: '80px',  align: 'left', valueField: 'total_stock' },
	];

	$('body').on("focus", ".autocom_item_id", function() {
		$(this).mcautocomplete({
			showHeader: true,
			minLength:3,
			delay:800,
			width: width,
			autoFocus: true,
			columns: columns,
			source:function(request, response) {
					$.getJSON(
						"<?php echo site_url('opname_header/get_item');?>", 
						{ 	
							term : request.term
						},  
						response
					);
				},
			select:function(event, ui){
				
			}
		});
	}) */
	$("body").on("focus", ".autocom_item_id", function() {
	    $(this).autocomplete({
            source: "<?php echo site_url('opname_header/get_item');?>",
            select: function (event, ui) {
                $(this).closest('tr').find('.item_id').val(ui.item.item_id);
                $(this).closest('tr').find('.qty_data').val(ui.item.total_stock);
                $(this).closest('tr').find('.item_price').val(ui.item.harga);
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div class='comment-text'><span class=\"username\"><b>"+
					item.item_name+
				"</b><span class=\"text-muted pull-right\">"+formatNumeric(item.harga)+"</span></span><p>"+
				"<span>Stok terakhir : <span class=\"text-muted pull-right\">"+formatNumeric(item.total_stock)+"</span></span>"+
				"</div>")
                .appendTo(ul);
        };
	});
  <?=$this->config->item('footerJS')?>