<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Stock Item Expired')?>
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
          <div class="box-tools pull-left col-md-3">
            <?= form_dropdown("unit_id_depo", $unit, '', 'class="form-control select2" id="unit_id_depo"') ?>
          </div>
          <div class="box-tools pull-left col-md-3" >
            <?= form_dropdown("kepemilikan", $own, '', 'class="form-control select2" id="kepemilikan_id"') ?>
          </div>
        </div>
        <div class="box-body" id="data_stock">
            <div class="col-md-3">     
            <?=create_inputDaterange("tanggal=tanggal expired",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>
          </div>
          <div class="col-md-12">
            <?=create_table("tb_stock",[
                "model" => "m_ms_item",
                "col"   => "get_column_exp"
            ],["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
          </div>
        </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?= modal_open("modal_penyesuaian", "Penyesuaian_stok","modal-lg") ?>
<?= modal_close() ?>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#tb_stock').DataTable({ 
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
            "ajax": {
                "url": "<?php echo site_url('item_expired/get_data')?>",
                "type": "POST",
                "data" : function (f) {
                    f.unit_id = $("#unit_id_depo").val();
                    f.tanggal = $("#tanggal").val();
                    f.own_id = $("#kepemilikan_id").val();
                  }
            },
            'columnDefs': [
            {
              'targets': [0,1,-1],
               'searchable': false,
               'orderable': false,
             },
             { "width": "8%", "targets": -1 },
            {
               'targets': 0,
               'className': 'dt-body-center',
               "visible" : false
            }], 
        });
    });
    
    $("#unit_id_depo,#kepemilikan_id,#tanggal").change(() => {
		table.draw();
	});
    <?= $this->config->item('footerJS') ?>
</script>