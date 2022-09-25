<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Stock Item All Unit')?>
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
           <div class="box-tools pull-left" >
                <?= form_dropdown("kepemilikan", $own, '', 'class="form-control select2" id="kepemilikan_id"') ?>
            </div>
            <h3 class="box-title pull-right">Informasi Stok Seluruh Unit Farmasi</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table id="table_stock" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>ITEM</th>
                            <?PHP
                                foreach ($unit as $key => $value) {
                                    echo "<th>$value->unit_name</th>";
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
    $(document).ready(()=>{
        $("#kepemilikan_id").trigger("change");
    });
    $("#kepemilikan_id").change(function(){
        $("#table_stock").DataTable().destroy();
        $("#table_stock > tbody").load("stock_all_unit/get_data/"+$(this).val(),function(){
            $("#table_stock").DataTable({ 
                dom: 'Bfrtip',
                "pageLength":100,
                buttons: [
                    {
                    "extend": 'pdf',
                    "text": '<i class="fa fa-file-pdf-o" style="color: green;"></i> PDF',
                    "titleAttr": 'PDF',
                    "orientation" : 'landscape',
                    "pageSize" : 'LEGAL',
                    "download": 'open'
                    },
                    {
                    "extend": 'excel',
                    "text": '<i class="fa fa-file-excel-o" style="color: green;"></i> EXCEL',
                    "titleAttr": 'Excel'
                    },
                    {
                    "extend": 'print',
                    "text": '<i class="fa fa-print" style="color: green;"></i> CETAK',
                    "titleAttr": 'Print'
                    }
                ]
            });
        });
    });
  </script>