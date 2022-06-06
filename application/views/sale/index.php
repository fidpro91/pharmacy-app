<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Sale')?>
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
        <div class="col-md-12" id="form_sale"></div>
      </div>
      <div class="box" id="data_sale"  style="display: none;">
        <?=$this->session->flashdata('message')?>
        <div class="box-header with-border">
          <h3 class="box-title">Form Sale</h3>
          <div class="box-tools pull-right">
            <button type="button" id="btn-add" class="btn btn-primary">
              <i class="fa fa-plus"></i> Add</button>
          </div>
        </div>
		  <div class="box-body" id="form_sale" style="display: none;">
		  </div>
		  <div class="box-body" id="data_sale">
          <?=create_table("tb_sale","M_sale",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
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
<?= modal_open("modal_pasien", "Biodata pasien","modal-lg") ?>
<?= modal_close() ?>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
      $("#modal_pasien").modal('show');
      $("#modal_pasien").find(".modal-body").load("sale/show_form_pasien");
      $("#form_sale").load("sale/show_form");
        table = $('#tb_sale').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('sale/get_data')?>",
                "type": "POST"
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
    $("#btn-add").click(function() {
      $("#form_sale").show();
      $("#data_sale").hide();
      $("#form_sale").load("sale/show_form");
    });
    function set_val(id) {
      $("#form_sale").show();
      $.get('sale/find_one/'+id,(data)=>{
          $("#form_sale").load("sale/show_form",()=>{
            $.each(data,(ind,obj)=>{
                $("#"+ind).val(obj);
            });
          });
      },'json');
    }

    function deleteRow(id) {
      if (confirm("Anda yakin akan menghapus data ini?")) {
          $.get('sale/delete_row/'+id,(data)=>{
            alert(data.message);
            location.reload();
        },'json');
      }
    }

    $("#checkAll").click(()=>{
      if ($("#checkAll").is(':checked')) {
          $("#tb_sale input[type='checkbox']").attr("checked",true);
      }else{
          $("#tb_sale input[type='checkbox']").attr("checked",false);
      }
    });

    $("#btn-deleteChecked").click(function(event){
        event.preventDefault();
        var searchIDs = $("#tb_sale input:checkbox:checked").map(function(){
              return $(this).val();
          }).toArray();
        if (searchIDs.length == 0) {
          alert("Mohon cek list data yang akan dihapus");
          return false;
        }
        if (confirm("Anda yakin akan menghapus data ini?")) {
          $.post('sale/delete_multi',{data:searchIDs},(resp)=>{
            alert(resp.message);
            location.reload();
          },'json');
        }
    });
</script>
