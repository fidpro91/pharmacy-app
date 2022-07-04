<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Distribusi BON Unit')?>
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
          <h3 class="box-title">Form Distribusi BON Unit</h3>        
        </div>  
        <div class="col-md-3">     
          <?= create_select([
              "attr" => ["name" => "filter_unit=Filter Unit", "id" => "filter_unit", "class" => "form-control"],
              "model"=>["m_ms_unit" => "get_ms_unit",
              "column"  => ["unit_id","unit_name"]
            ],
            ]) ?>  
          </div>  
          <div class="col-md-3">     
          <?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>
          </div>  
          <div class="col-md-3">     
          <?= create_select([
              "attr" => ["name" => "status=status mutasi","id" => "status","class" => "form-control"],
              "option" => [["id" => ' ', "text" => 'Pilih'], ["id" => '1', "text" => "Meminta"], ["id" => '2', "text" => "diproses"],["id" => '3', "text" => "terima"]]
            ]) ?>
            </div>
        <div class="box-body" id="form_mutation" style="display: none;">        
        </div>
        
        <div class="box-body" id="data_mutation">        
          <?=create_table("tb_mutation",["M_mutation"=>"get_column_bon"],["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
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
<?= modal_open("modal_distribusi", "Distribusi Permintaan","modal-lg") ?>
<?= modal_close() ?>
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
                "url": "<?php echo site_url('Distribusi_bon/get_data')?>",
                "type": "POST",
                "data":function(f){
                  f.unit=$("#filter_unit").val();
                  f.tgl=$("#tanggal").val();
                  f.sts=$("#status").val();
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
        $("#filter_unit,#tanggal,#status").change(()=>{
            table.draw();
            });
    });
    $("#btn-add").click(function() {
      $("#form_mutation").show();
      $("#form_mutation").load("Distribusi_bon/show_form");
    });

    function konfirm_distribusi(id) {
      $("#modal_distribusi").modal("show");
      $("#modal_distribusi").find(".modal-body").load("distribusi_bon/show_form/"+id);
    }

    function deleteRow(id) {
      if (confirm("Anda yakin akan menghapus data ini?")) {
          $.get('Distribusi_bon/delete_row/'+id,(data)=>{
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
          $.post('Distribusi_bon/delete_multi',{data:searchIDs},(resp)=>{
            alert(resp.message);
            location.reload();
          },'json');
        }
    });
    <?=$this->config->item('footerJS')?>
</script>