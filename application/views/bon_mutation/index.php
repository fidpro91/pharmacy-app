<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('BON PERMINTAAN')?>
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
          <h3 class="box-title">Form BON PERMINTAAN</h3>         
          <div class="box-tools pull-right">         
            <button type="button" id="btn-add" class="btn btn-primary">
              <i class="fa fa-plus"></i> Add</button>              
          </div>
        </div>
        <div id="unit" class="col-md-4">
        <?= create_select([
              "attr" => ["name" => "filter_unit=Filter Unit", "id" => "filter_unit", "class" => "form-control"],
              "model"=>["m_mutation" => ["get_user_in_unit",[0,"u.user_id"=>$this->session->user_id]],
              "column"  => ["unit_id","unit_name"]
            ],
            ]) ?>
            </div>
            <div id="filstatus" class="col-md-4">
        <?= create_select([
              "attr" => ["name" => "mutation_status=status mutasi", "id" => "mutation_status", "class" => "form-control"],
              "option" => [["id" => ' ', "text" => 'Pilih'], ["id" => '1', "text" => "Meminta"], ["id" => '2', "text" => "diproses"],["id" => '3', "text" => "terima"],["id" => '4', "text" => "Batal"]]
            ]) ?>
            </div>
           
        <div class="box-body" id="form_mutation" style="display: none;">        
        </div>        
        <div class="box-body" id="data_mutation">
          <?=create_table("tb_mutation",["M_mutation"=>"get_column_bon"],["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
        </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?= modal_open("modal_konfirmasi", "Konfirmasi Penerimaan","modal-lg") ?>
<?= modal_close() ?>
<script type="text/javascript">
    var table;
    var mutationDetail;
    $(document).ready(function() {
      ('#unit')
        table = $('#tb_mutation').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('bon_mutation/get_data')?>",
                "type": "POST",
                "data": function(a){
                  a.unit = $("#filter_unit").val();
                  a.status = $("#mutation_status").val();
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
               'visible': false,
            }], 
        });
        $("#filter_unit,#mutation_status").change(() => {
      table.draw();
    });
    });
    $("#btn-add").click(function() {
      $("#form_mutation").show();
      $("#unit").hide();
      $("#filstatus").hide();
      $("#form_mutation").load("bon_mutation/show_form");
    });
    function set_val(id) {
      $("#form_mutation").show();
      $.ajax({
        'async': false,
        'type': "GET",
        'dataType': 'json',
        'url': 'bon_mutation/find_one/'+id,
        'success': function(data) {
          $("#form_mutation").load("bon_mutation/show_form",()=>{
            $.each(data,(ind,obj)=>{
                $("#"+ind).val(obj);
            });
          });
          mutationDetail = data.detail;
        }
      });
    }

    function konfirm_penerimaan(id) {
      $("#modal_konfirmasi").modal("show");
      $("#modal_konfirmasi").find(".modal-body").load("bon_mutation/show_form_konfirmasi/"+id);
    }

    function deleteRow(id) {
      if (confirm("Anda yakin akan menghapus data ini?")) {
          $.get('bon_mutation/delete_row/'+id,(data)=>{
            alert(data.message);
            location.reload();
        },'json');
      }
    }
    <?=$this->config->item('footerJS')?>
</script>