<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Riwayat Harga Item')?>
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
            <?= form_dropdown("item_id", $item, '', 'class="form-control select2" id="item_id"') ?>
          </div>
        </div>
        <div class="box-body">
          <?=create_table("tb_harga",
          [
            "model" => "m_receiving",
            "col"   => "get_column_harga"
          ],["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
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
        $(".select2").select2();
        table = $('#tb_harga').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('riwayat_harga/get_data')?>",
                "type": "POST",
                "data" : function (f) {
                    f.item_id = $("#item_id").val();
                }
            },
            'columnDefs': [
            {
              'targets': [0,1,-1],
               'searchable': false,
               'orderable': false,
             },
            {
               'targets': [0,-1],
               "visible" : false
            }], 
        });
    });
    $("#item_id").change(()=>{
        table.draw();
    });
</script>