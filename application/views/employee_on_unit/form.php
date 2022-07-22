<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Setting unit kerja pegawai')?>
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
      <div class="row">
        <div class="col-md-5">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Daftar Pegawai</h3>
                </div>
                <div class="box-body" id="data_ms_pegawai">
                  <?=create_table("tb_pegawai",[
                      "model" => "m_employee",
                      "col"   => "get_column2"
                  ],["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
                </div>
                <!-- /.box-footer-->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-2" id="dataUnit">
            <?=create_select(["attr"=>["name"=>"unit_id=Unit Kerja ","id"=>"unit_id","class"=>"form-control"],
                "model"=>["m_ms_unit" => "get_ms_unit",
				"column"=>["unit_id","unit_name"]]
            ])?>
            <div class="form-group" align="center">
              <button class="btn btn-primary" id="btn-left"><i class="fa fa-backward"></i></button>
              <button class="btn btn-primary" id="btn-right"><i class="fa fa-forward"></i></button>
            </div>
        </div>
        <div class="col-md-5">
            <div class="box box-primary">
                <div class="box-header with-border">
                <h3 class="box-title">Pegawai Di Unit Kerja</h3>
                </div>
                <div class="box-body" id="data_unit_pegawai">
                <?=create_table("tb_employee_on_unit","M_employee_on_unit",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
                </div>
                <!-- /.box-footer-->
            </div>
            <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script type="text/javascript">
    var table1,table2;
    $(document).ready(function() {
        table1 = $('#tb_pegawai').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('employee/get_data3')?>",
                "type": "POST",
                "data": function(data){
                  data.unit_id      = $("#unit_id").val();
              }
            },
            'columnDefs': [
            {
              'targets': [0,1],
               'searchable': false,
               'orderable': false,
             },
              {
                "targets": [-1],
                "visible": false
              },
              {
               'targets': 0,
               'className': 'dt-body-center',
               'render': function (data, type, full, meta){
                   return '<input type="checkbox" name="id_emp[]" value="' + $('<div/>').text(data).html() + '">';
               }
            }], 
        });

        table2 = $('#tb_employee_on_unit').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('employee_on_unit/get_data')?>",
                "type": "POST",
                "data": function(data){
					data.unit_id      = $("#unit_id").val();
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
    });

    $('#data_ms_pegawai').find('#checkAll').click(function(){
      if ($(this).is(':checked')) {
          $("#tb_pegawai input[type='checkbox']").attr("checked",true);
      }else{
          $("#tb_pegawai input[type='checkbox']").attr("checked",false);
      }
    });

    $('#data_unit_pegawai').find('#checkAll').click(function(){
      if ($(this).is(':checked')) {
          $("#tb_proporsi input[type='checkbox']").attr("checked",true);
      }else{
          $("#tb_proporsi input[type='checkbox']").attr("checked",false);
      }
    });

    $("#btn-right").click(()=>{
      $.ajax({
        type: 'POST',
        url: 'employee_on_unit/insert_right',
        dataType : 'json',
        data: $('#data_ms_pegawai :input[type="checkbox"]').serialize()+'&'+$('#dataUnit :input').serialize(),
        success: function (resp) {
          alert(resp.message);
          table1.ajax.reload();
          table2.ajax.reload();
        }
      })
    });

    $("#unit_id").change(()=>{
      table2.draw();
      table1.draw();
    });
    
    $("#btn-left").click(()=>{
      $.ajax({
        type: 'POST',
        url: 'employee_on_unit/insert_left',
        dataType : 'json',
        data: $('#data_unit_pegawai :input[type="checkbox"]').serialize(),
        success: function (resp) {
          alert(resp.message);
          table1.ajax.reload();
          table2.ajax.reload();
        }
      })
    }); 
    <?=$this->config->item('footerJS')?>
</script>