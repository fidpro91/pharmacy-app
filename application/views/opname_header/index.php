<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Opname Header')?>
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
          <div class="box-tools pull-left">
            <?= form_dropdown("unit_id_depo", $unit, '', 'class="form-control select2" id="unit_id_depo"') ?>
          </div>
          <div class="box-tools pull-right">
            <button type="button" id="btn-add" class="btn btn-primary">
              <i class="fa fa-plus"></i> Add</button>
          </div>
        </div>
        <div class="box-body" id="form_opname_header" style="display: none;">
        </div>
        <div class="box-body" id="data_opname_header">
          <?=create_table("tb_opname_header","M_opname_header",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
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
    var dataItemOpname;
    $(document).ready(function() {
        table = $('#tb_opname_header').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('opname_header/get_data')?>",
                "type": "POST",
                "data" : function (f) {
                    f.unit_id = $("#unit_id_depo").val();
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
        $("#unit_id_depo").change(() => {
          table.draw();
        });
    });

    $("#btn-add").click(function() {
      $("#form_opname_header").show();
      $("#form_opname_header").load("opname_header/show_form/"+$("#unit_id_depo").val());
    });
    function set_val(id) {
      $("#form_opname_header").show();
      $.get('opname_header/find_one/'+id,(data)=>{
          $("#form_opname_header").load("opname_header/show_form",()=>{
            $.each(data,(ind,obj)=>{
                $("#"+ind).val(obj);
            });
            if(data.detail){
              dataItemOpname = data.detail;
            }
          });
      },'json');
    }

    function deleteRow(id) {
      if (confirm("Anda yakin akan menghapus data ini?")) {
          $.get('opname_header/delete_row/'+id,(data)=>{
            alert(data.message);
            location.reload();
        },'json');
      }
    }

    $("#checkAll").click(()=>{
      if ($("#checkAll").is(':checked')) {
          $("#tb_opname_header input[type='checkbox']").attr("checked",true);
      }else{
          $("#tb_opname_header input[type='checkbox']").attr("checked",false);
      }
    });

    $("#btn-deleteChecked").click(function(event){
        event.preventDefault();
        var searchIDs = $("#tb_opname_header input:checkbox:checked").map(function(){
              return $(this).val();
          }).toArray();
        if (searchIDs.length == 0) {
          alert("Mohon cek list data yang akan dihapus");
          return false;
        }
        if (confirm("Anda yakin akan menghapus data ini?")) {
          $.post('opname_header/delete_multi',{data:searchIDs},(resp)=>{
            alert(resp.message);
            location.reload();
          },'json');
        }
    });
</script>