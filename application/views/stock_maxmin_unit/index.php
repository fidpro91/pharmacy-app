<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Stock Maxmin Unit')?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Layout</a></li>
        <li class="active">Fixed</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="box">
        <?=$this->session->flashdata('message')?>
        <div class="box-header with-border">
          <h3 class="box-title">Form Stock Maxmin Unit</h3>
          <div class="box-tools pull-right">
            <button type="button" id="btn-add" class="btn btn-primary">
              <i class="fa fa-plus"></i> Add</button>
          </div>
        </div>
        <div class="box-body" id="form_stock_maxmin_unit" style="display: none;">
        </div>
        <div class="box-body" id="data_stock_maxmin_unit">
        <div class="col-md-3">
           <?= create_select([
              "attr" => ["name" => "own_id=Kepemilikan", "id" => "own_id", "class" => "form-control"],
              "model"=>["m_ownership" => ["get_ownership",[0]],
              "column"  => ["own_id","own_name"]
            ],
            ]) ?>
        </div>
        <div class="col-md-3">
           <?= create_select([
              "attr" => ["name" => "unit=UNIT", "id" => "unit", "class" => "form-control"],
              "model"=>["m_ms_unit" =>"get_farmasi_unit",
              "column"  => ["unit_id","unit_name"]
            ],
            ]) ?>
        </div>
        <div class="col-md-12">
          <?=create_table("tb_stock_maxmin_unit","M_stock_maxmin_unit",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
          </div>
        </div>
        <div class="box-footer">
          <button class="btn btn-danger" id="btn-deleteChecked"><i class="fa fa-trash"></i> Delete</button>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#tb_stock_maxmin_unit').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('stock_maxmin_unit/get_data')?>",
                "type": "POST",
                "data": function(f) {        
                       f.own = $("#own_id").val();
                       f.unit_id = $("#unit").val();
                }
            },
            'columnDefs': [
            {
              'targets': [0,1,-1],
               'searchable': false,
               'orderable': false,
             },
            {
               'targets': 0,
               'className': 'dt-body-center',
               'render': function (data, type, full, meta){
                   return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
               }
            }], 
        });
        $("#own_id, #unit").change(() => {
			table.draw();
		});
    });
    $("#btn-add").click(function() {
      $("#form_stock_maxmin_unit").show();
      $("#form_stock_maxmin_unit").load("stock_maxmin_unit/show_form");
    });
    function set_val(id) {
      $("#form_stock_maxmin_unit").show();
      $.get('stock_maxmin_unit/find_one/'+id,(data)=>{
          $("#form_stock_maxmin_unit").load("stock_maxmin_unit/show_form",()=>{
            $.each(data,(ind,obj)=>{
                $("#"+ind).val(obj);
            });
          });
      },'json');
    }

    function deleteRow(id) {
      if (confirm("Anda yakin akan menghapus data ini?")) {
          $.get('stock_maxmin_unit/delete_row/'+id,(data)=>{
            alert(data.message);
            location.reload();
        },'json');
      }
    }

    $("#checkAll").click(()=>{
      if ($("#checkAll").is(':checked')) {
          $("#tb_stock_maxmin_unit input[type='checkbox']").attr("checked",true);
      }else{
          $("#tb_stock_maxmin_unit input[type='checkbox']").attr("checked",false);
      }
    });

    $("#btn-deleteChecked").click(function(event){
        event.preventDefault();
        var searchIDs = $("#tb_stock_maxmin_unit input:checkbox:checked").map(function(){
              return $(this).val();
          }).toArray();
        if (searchIDs.length == 0) {
          alert("Mohon cek list data yang akan dihapus");
          return false;
        }
        if (confirm("Anda yakin akan menghapus data ini?")) {
          $.post('stock_maxmin_unit/delete_multi',{data:searchIDs},(resp)=>{
            alert(resp.message);
            location.reload();
          },'json');
        }
    });
</script>