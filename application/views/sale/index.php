<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= ucwords('Sale') ?>
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
      <div class="col-md-12" id="form_sale" id="form_sale" style="display: none;"></div>
    </div>
    <div class="box" id="data_sale">
      <?= $this->session->flashdata('message') ?>
      <div class="box-header with-border">
        <div class="box-tools pull-left">
          <?= form_dropdown("unit_id_depo", $unit, '', 'class="form-control select2" id="unit_id_depo"') ?>
        </div>
        <div class="box-tools pull-right">
          <button type="button" id="btn-add" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add[F1]</button>
        </div>
      </div>
      <div class="box-body">
        <div class="col-md-3">
          <?= create_inputDate("filter_bulan=bulan penjualan", [
            "format"    => "mm-yyyy",
            "viewMode"    => "year",
            "minViewMode"  => "year",
            "autoclose"    => true
          ], [
            "value"     => date('m-Y'),
            "readonly"  => true
          ])
          ?>
        </div>
        <div class="col-md-3">
          <?= create_select([
            "attr"         => ["name" => "filter_pembayaran=Cara Bayar", "id" => "filter_pembayaran", "class" => "form-control"],
            "option"    => [
              ["id" => "0", "text" => "Tunai"], ["id" => "1", "text" => "Kredit"]
            ]
          ]) ?>
        </div>
        <div>
          <div class="col-md-12">
            <?= create_table("tb_sale", "M_sale", ["class" => "table table-bordered", "style" => "width:100% !important;"]) ?>
          </div>
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
<?= modal_open("modal_pasien", "Biodata pasien", "modal-lg") ?>
<?= modal_close() ?>
<?= modal_open("modal_update", "Form Update", "modal-lg",[
  "style" => "width:90%"
]) ?>
<?= modal_close() ?>
<script src="<?= base_url("assets/plugins/jquery.hotkeys-master") ?>/jquery.hotkeys.js"></script>
<script type="text/javascript">
  var table;
  $(document).ready(function() {
    // $("#form_sale").load("sale/show_form");
    $(document).bind('keydown', 'f1', function assets() {
      $("#btn-add").click();
      return false;
    });
    table = $('#tb_sale').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [[3, 'desc']],
      "scrollX": true,
      "ajax": {
        "url": "<?php echo site_url('sale/get_data') ?>",
        "type": "POST",
        "data": function(f) {
          f.unit_id = $("#unit_id_depo").val();
          f.bulan = $("#filter_bulan").val();
          f.sale_type = $("#filter_pembayaran").val();
        }
      },
      'columnDefs': [{
          'targets': [0, 1, -1],
          'searchable': false,
          'orderable': false,
        },
        { "width": "10%", "targets": -1 },
        {
          'targets': 0,
          'className': 'dt-body-center',
          'render': function(data, type, full, meta) {
            var mati = "";
            if (full[10] === '') {
              mati = "disabled";
            }
            return '<input ' + mati + ' type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
          }
        }
      ],
    });
  });

  $("#unit_id_depo, #sale_type, #filter_pembayaran").change(() => {
    table.draw();
  });

  $("#btn-add").click(function() {
    $("#form_sale").show();
    $("#data_sale").hide();
    $("#form_sale").load("sale/show_form/"+$("#unit_id_depo").val());
  });

  function set_val(id) {
    $("#modal_update").modal('show');
    $("#modal_update").find('.modal-body').load('sale/show_form_update/'+id,function(){
      $.get('sale/find_one/'+id,(data)=>{
        $.each(data,(ind,obj)=>{
            $('.modal-body').find("#"+ind).val(obj);
        });
        $("select[class*='select2']").trigger("change");
      },'json');
    });
  }

  function deleteRow(id) {
    if (confirm("Anda yakin akan menghapus data ini?")) {
      $.get('sale/delete_row/' + id, (data) => {
        alert(data.message);
        location.reload();
      }, 'json');
    }
  }

  $("#checkAll").click(() => {
    if ($("#checkAll").is(':checked')) {
      $("#tb_sale input[type='checkbox']").attr("checked", true);
    } else {
      $("#tb_sale input[type='checkbox']").attr("checked", false);
    }
  });

  $("#btn-deleteChecked").click(function(event) {
    event.preventDefault();
    var searchIDs = $("#tb_sale input:checkbox:checked").map(function() {
      return $(this).val();
    }).toArray();
    if (searchIDs.length == 0) {
      alert("Mohon cek list data yang akan dihapus");
      return false;
    }
    if (confirm("Anda yakin akan menghapus data ini?")) {
      $.post('sale/delete_multi', {
        data: searchIDs
      }, (resp) => {
        alert(resp.message);
        location.reload();
      }, 'json');
    }
  });

  function cetak_resep(id, type) {
    var url = "<?php echo base_url() ?>sale/strukapotikresep/" + id + '/' + $('#unit_id_depo').val() + '/' + type;
    var left = ($(window).width() / 2) - (1200 / 2);
    var top = ($(window).height() / 2) - (800 / 2);
    window.open(url, "Struk Pembayaran Apotik", "width=1200, height=800, top=" + top + ", left=" + left);
  }

  function cetak_etiket(id) {
    var url = "<?php echo base_url() ?>sale/struketiket/" + id;
    var left = ($(window).width() / 2) - (600 / 2);
    var top = ($(window).height() / 2) - (400 / 2);
    window.open(url, "Struk E-tiket", "width=600, height=400, top=" + top + ", left=" + left + ",scrollbars=yes");
  }

  <?= $this->config->item('footerJS') ?>
</script>
