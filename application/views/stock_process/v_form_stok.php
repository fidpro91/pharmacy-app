<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Stock Process')?>
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
          <h3 class="box-title">Form Stock Process</h3>
          
        </div>

        <div class="panel-body" id="data_ms_siswa">
      <div class="row">
      <div class="col-md-2" id="unit">
          <?= create_select2([
                  "attr" => ["name" => "filter_kelas=filter unit", "id" => "filter_kelas", "class" => "form-control", 'required' => true],
                  "model" => [
                          "m_ms_unit" => "get_ms_unit",
                          "column" => ["unit_id", "unit_name"]
                  ],
          ]) ?>
       </div>
       <div class="col-md-2" id="tgl">
       <?=create_inputDaterange("tgl_transaksi",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]],"required")?>
       </div>
                </div>
              </div>
        <div class="box-body" id="form_stock_process" style="display: none;">
        </div>       
        <div class="box-body" id="data_stock_process">
          <?=create_table("tb_stock_process","M_stock_process",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
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
        table = $('#tb_stock_process').DataTable({ 
          dom: 'Bfrtip',
            buttons: [
                  {
                  "extend": 'pdf',
                  "text": '<i class="fa fa-file-pdf-o" style="color: green;"></i> PDF',
                  "titleAttr": 'PDF',                               
                  "action": newexportaction,
                  "orientation" : 'landscape',
                  "pageSize" : 'LEGAL',
                  "download": 'open'
                },
                {
                  "extend": 'excel',
                  "text": '<i class="fa fa-file-excel-o" style="color: green;"></i> EXCEL',
                  "titleAttr": 'Excel',                               
                  "action": newexportaction
                },
                {
                  "extend": 'print',
                  "text": '<i class="fa fa-print" style="color: green;"></i> CETAK',
                  "titleAttr": 'Print',                                
                  "action": newexportaction
                }
            ], 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "pageLength": 100,
            "ajax": {
                "url": "<?php echo site_url('stock_process/get_data')?>",
                "type": "POST",
                "data": function(f) {        
                f.unit = $("#filter_kelas").val();
                f.tgl = $("#tgl_transaksi").val();
            }
            },
            'columnDefs': [
            {
              'targets': [0,1,-1],
               'searchable': false,
               'orderable': false,
             },{
              'targets': [0,-1],
               'visible': false,
             },
            {
               'targets': 0,
               'className': 'dt-body-center',
               'render': function (data, type, full, meta){
                   return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
               }
            }], 
        });
        $("#filter_kelas,#tgl_transaksi").change(() => {
      table.draw();
    });
    });
    $("#btn-add").click(function() {
      $("#form_stock_process").show();
      $("#form_stock_process").load("stock_process/show_form");
    });
    function set_val(id) {
      $("#form_stock_process").show();
      $.get('stock_process/find_one/'+id,(data)=>{
          $("#form_stock_process").load("stock_process/show_form",()=>{
            $.each(data,(ind,obj)=>{
                $("#"+ind).val(obj);
            });
          });
      },'json');
    }

    function deleteRow(id) {
      if (confirm("Anda yakin akan menghapus data ini?")) {
          $.get('stock_process/delete_row/'+id,(data)=>{
            alert(data.message);
            location.reload();
        },'json');
      }
    }

    $("#checkAll").click(()=>{
      if ($("#checkAll").is(':checked')) {
          $("#tb_stock_process input[type='checkbox']").attr("checked",true);
      }else{
          $("#tb_stock_process input[type='checkbox']").attr("checked",false);
      }
    });

    $("#btn-deleteChecked").click(function(event){
        event.preventDefault();
        var searchIDs = $("#tb_stock_process input:checkbox:checked").map(function(){
              return $(this).val();
          }).toArray();
        if (searchIDs.length == 0) {
          alert("Mohon cek list data yang akan dihapus");
          return false;
        }
        if (confirm("Anda yakin akan menghapus data ini?")) {
          $.post('stock_process/delete_multi',{data:searchIDs},(resp)=>{
            alert(resp.message);
            location.reload();
          },'json');
        }
    });
    <?= $this->config->item('footerJS') ?>
</script>