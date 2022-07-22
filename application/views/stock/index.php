<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('Stock')?>
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
        <div class="box-body" id="kartu_Stok" style="display: none;">
        </div>
        <div class="box-body" id="data_stock">
          <?=create_table("tb_stock","M_stock",["class"=>"table table-bordered" ,"style" => "width:100% !important;"])?>
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
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "scrollX": true,
            "ajax": {
                "url": "<?php echo site_url('stock/get_data')?>",
                "type": "POST",
                "data" : function (f) {
                    f.unit_id = $("#unit_id_depo").val();
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
    
    $("#unit_id_depo,#kepemilikan_id").change(() => {
			table.draw();
		});

    $("#btn-add").click(function() {
      $("#form_stock").show();
      $("#form_stock").load("stock/show_form");
    });
    function set_val(id) {
      $("#form_stock").show();
      $.get('stock/find_one/'+id,(data)=>{
          $("#form_stock").load("stock/show_form",()=>{
            $.each(data,(ind,obj)=>{
                $("#"+ind).val(obj);
            });
          });
      },'json');
    }

    function deleteRow(id) {
      if (confirm("Anda yakin akan menghapus data ini?")) {
          $.get('stock/delete_row/'+id,(data)=>{
            alert(data.message);
            location.reload();
        },'json');
      }
    }

    $("#checkAll").click(()=>{
      if ($("#checkAll").is(':checked')) {
          $("#tb_stock input[type='checkbox']").attr("checked",true);
      }else{
          $("#tb_stock input[type='checkbox']").attr("checked",false);
      }
    });

    $("#btn-deleteChecked").click(function(event){
        event.preventDefault();
        var searchIDs = $("#tb_stock input:checkbox:checked").map(function(){
              return $(this).val();
          }).toArray();
        if (searchIDs.length == 0) {
          alert("Mohon cek list data yang akan dihapus");
          return false;
        }
        if (confirm("Anda yakin akan menghapus data ini?")) {
          $.post('stock/delete_multi',{data:searchIDs},(resp)=>{
            alert(resp.message);
            location.reload();
          },'json');
        }
    });

    function cek_stok(own_id,unit_id,item_id) {
      $("#kartu_Stok").show();
      $("#kartu_Stok").load("stock_process/index"+'/'+own_id,'/'+unit_id+'/'+item_id,function(){
        $("#kartu_Stok").find('#item_id').val(item_id);
      });
      $("#data_stock").hide();
    }

    function penyesuaian_stok(a,item_id) {
      $("#modal_penyesuaian").modal('show');
      $("#modal_penyesuaian").find(".modal-body").load("adjusment_stok/show_form/"+item_id+"/"+$("#unit_id_depo").val()+"/"+$("#kepemilikan_id").val(),function(){
        let stock_1 = parseInt($(a).closest('tr').find("td:eq(6)").text());
        let stock_2 = parseInt($(a).closest('tr').find("td:eq(7)").text());
        let stock_3 = stock_1-stock_2;
        $(".modal-title").text("Penyesuaian Stok#"+$(a).closest('tr').find("td:eq(3)").text());
        $("#modal_penyesuaian").find("#stock_old").val(stock_2);
        $("#modal_penyesuaian").find("#stock_after").val(stock_1);
        $("#modal_penyesuaian").find("#different_qty").val(stock_3);
        $("#modal_penyesuaian").find("#item_id").val(item_id);
        $("#modal_penyesuaian").find("#unit_id").val($("#unit_id_depo").val());
        $("#modal_penyesuaian").find("#own_id").val($("#kepemilikan_id").val());
      });
    }
</script>