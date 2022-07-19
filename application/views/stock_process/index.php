<div class="col-md-3">
  <?=form_hidden("item_id")?>
  <?=create_inputDaterange("tgl_transaksi",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]],"required")?>
  <?=form_button([
    "name"      => "btn-back",
    "id"        => "btn-back",
    "type"      => "button",
    "class"     => "btn btn-warning",
    "style"     => "width:100% !important",
    'content'   => '<i class="fa fa-arrow-circle-left "></i> Kembali'
  ])?>
</div>
<div class="col-md-9">
<?=create_table("tb_stock_process","M_stock_process",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
</div>

<script type="text/javascript">
    var tableStockProsess;
    $(document).ready(function() {
      tableStockProsess  = $('#tb_stock_process').DataTable({ 
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
                f.unit = $("#unit_id_depo").val();
                f.own_id = $("#kempilikan_id").val();
                f.item_id = $("#item_id").val();
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

        $("#filter_kelas,#tgl_transaksi,#kempilikan_id").change((e) => {
          tableStockProsess.draw();
          e.preventDefault();
        });

    });

    $("#btn-back").click(function() {
      /* $("#data_stock").show();
      $("#kartu_Stok").html("");
      $("#kartu_Stok").hide(); */
      location.reload();
    });
    <?= $this->config->item('footerJS') ?>
</script>