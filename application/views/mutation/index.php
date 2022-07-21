<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Mutation')?>
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
          <h3 class="box-title">Form Mutation</h3>
          <div class="box-tools pull-right">
            <button type="button" id="btn-add" class="btn btn-primary">
              <i class="fa fa-plus"></i> Add</button>
          </div>
        </div>
        <div class="box-body" id="form_mutation" style="display: none;">
        </div>
        <div class="box-body" id="data_mutation">
          <div class="col-md-3">
              <?=create_inputDate("filter_bulan=bulan mutasi",[
                  "format"		=>"mm-yyyy",
                  "viewMode"		=> "year",
                  "minViewMode"	=> "year",
                  "autoclose"		=>true],[
                    "value"     => date('m-Y'),
                    "readonly"  => true
                  ])
              ?>
          </div>
          <div class="col-md-3">
            <?= create_select2([
                "attr" => ["name" => "filter_unit=Unit Minta", "id" => "filter_unit", "class" => "form-control"],
                "model"=>["m_ms_unit" => ["get_ms_unit_all",[0]],
                "column"  => ["unit_id","unit_name"]
              ],
              ]) ?>
          </div>
          <div class="col-md-12">
            <?=create_table("tb_mutation","M_mutation",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
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
    var mutationDetail;
    $(document).ready(function() {
        table = $('#tb_mutation').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('mutation/get_data')?>",
                "type": "POST",
                "data": function(f) {        
                    f.unit = $("#filter_unit").val();
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
              'targets': [-2],
               'visible': false,
             },
            {
               'targets': 0,
               'className': 'dt-body-center',
               'render': function (data, type, full, meta){
                    var mati = "";
                    if (full[7] == '3') {
                        mati = "disabled";
                    }
                   return '<input '+mati+' type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
               }
            }], 
        });

        $("#filter_bulan, #filter_unit").change(() => {
          table.draw();
        });
    });
    $("#btn-add").click(function() {
      $("#form_mutation").show();
      $("#form_mutation").load("mutation/show_form");
    });
    function set_val(id) {
      $("#form_mutation").show();
      $.ajax({
        'async': false,
        'type': "GET",
        'dataType': 'json',
        'url': 'mutation/find_one/'+id,
        'success': function(data) {
          $("#form_mutation").load("mutation/show_form",()=>{
            $.each(data,(ind,obj)=>{
                $("#"+ind).val(obj);               
            }); $("select[class*='select2']").trigger('change');
          });
          mutationDetail = data.detail; 
        }
      });
    }

    function deleteRow(id) {
      if (confirm("Anda yakin akan menghapus data ini?")) {
          $.get('mutation/delete_row/'+id,(data)=>{
            alert(data.message);
            location.reload();
        },'json');
      }
    }

    $("#checkAll").click(()=>{
      if ($("#checkAll").is(':checked')) {
          $("#tb_mutation input[type='checkbox']").attr("checked",true);
      }else{
          $("#tb_mutation input[type='checkbox']").attr("checked",false);
      }
    });

    $("#btn-deleteChecked").click(function(event){
        event.preventDefault();
        var searchIDs = $("#tb_mutation input:checkbox:checked").map(function(){
              return $(this).val();
          }).toArray();
        if (searchIDs.length == 0) {
          alert("Mohon cek list data yang akan dihapus");
          return false;
        }
        if (confirm("Anda yakin akan menghapus data ini?")) {
          $.post('mutation/delete_multi',{data:searchIDs},(resp)=>{
            alert(resp.message);
            location.reload();
          },'json');
        }
    });
    <?= $this->config->item('footerJS') ?>
</script>