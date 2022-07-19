<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Sale Return')?>
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
              <i class="fa fa-edit "></i> New</button>
          </div>
        </div>
        <div class="box-body" id="form_sale_return" style="display: none;">
        </div>
        <div class="box-body" id="data_sale_return">
          <div class="col-md-3">
              <?=create_inputDate("filter_bulan=bulan retur",[
                  "format"		=>"mm-yyyy",
                  "viewMode"		=> "year",
                  "minViewMode"	=> "year",
                  "autoclose"		=>true],[
                    "value"     => date('m-Y'),
                    "readonly"  => true
                  ])
              ?>
          </div>
          <div class="col-md-12">
          <?=create_table("tb_sale_return","M_sale_return",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
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
      $("#form_sale_return").load("sale_return/show_form");
        table = $('#tb_sale_return').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('sale_return/get_data')?>",
                "type": "POST",
                "data" : function (f) {
                    f.unit_id = $("#unit_id_depo").val();
                    f.bulan = $("#filter_bulan").val();
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

        $("#unit_id_depo,#filter_bulan").change(() => {
          table.draw();
        });
    });
    $("#btn-add").click(function() {
      $("#form_sale_return").show();
      $("#form_sale_return").load("sale_return/show_form");
    });
    function set_val(id) {
      $("#form_sale_return").show();
      $.get('sale_return/find_one/'+id,(data)=>{
          $("#form_sale_return").load("sale_return/show_form",()=>{
            $.each(data,(ind,obj)=>{
                $("#"+ind).val(obj);
            });
          });
      },'json');
    }

    function deleteRow(id) {
      if (confirm("Anda yakin akan menghapus data ini?")) {
          $.get('sale_return/delete_row/'+id,(data)=>{
            alert(data.message);
            location.reload();
        },'json');
      }
    }

    $("#checkAll").click(()=>{
      if ($("#checkAll").is(':checked')) {
          $("#tb_sale_return input[type='checkbox']").attr("checked",true);
      }else{
          $("#tb_sale_return input[type='checkbox']").attr("checked",false);
      }
    });

    $("#btn-deleteChecked").click(function(event){
        event.preventDefault();
        var searchIDs = $("#tb_sale_return input:checkbox:checked").map(function(){
              return $(this).val();
          }).toArray();
        if (searchIDs.length == 0) {
          alert("Mohon cek list data yang akan dihapus");
          return false;
        }
        if (confirm("Anda yakin akan menghapus data ini?")) {
          $.post('sale_return/delete_multi',{data:searchIDs},(resp)=>{
            alert(resp.message);
            location.reload();
          },'json');
        }
    });
    <?= $this->config->item('footerJS') ?>
</script>