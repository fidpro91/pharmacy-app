<style>
  .comment-text {
    color: black !important;
  }
</style>
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
        <div class="col-md-4">
            <?=create_inputDaterange("filter_tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>
        </div>
        <div class="col-md-3">
          <?= create_select([
            "attr"         => ["name" => "filter_pembayaran=Cara Bayar", "id" => "filter_pembayaran", "class" => "form-control"],
            "option"    => [
              ["id" => "", "text" => "Semua"], ["id" => "0", "text" => "Tunai"], ["id" => "1", "text" => "Kredit"]
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
        <button class="btn btn-success" id="btn-checkout"><i class="fa fa-sign-out"></i> Checkout Resep</button>
      </div>
      <!-- /.box-footer-->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= modal_open("modal_update", "Form Update", "modal-lg", [
  "style" => "width:90%"
]) ?>
<?= modal_close() ?>
<?= modal_open("modal_checkout", "Checkout Resep",null,null,false) ?>
<?= create_select2([
    "attr" => [
        "name" => "asal_resep=unit layanan", "id" => "asal_resep", "class" => "form-control"
    ],
    "model" => [
        "m_sale" => ["get_unit_layanan", [
            "unit_active" => 't'
        ]],
        "column" => ["unit_id", "unit_name"]
    ],
]) ?>
<div class="input-group input-group-sm">
  <input id="nomor_resep_co" name="nomor_resep_co" required="true" class="form-control input-sm" type="text" placeholder="isikan nomor rekam medis pasien / scan barcode">
  <span class="input-group-btn">
    <button class="btn btn-danger" type="button" onclick="checkout_pasien($('#nomor_resep_co').val())" id="go-checkout" tabindex="-1">
      Checkout Pasien
    </button>
  </span>
</div>
<div class="overlay loading-checkout" style="display: none;">
  <i class="fa fa-refresh fa-spin"></i>
</div>
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
      "order": [
        [4, 'desc'],
        [3, 'desc']
      ],
      "scrollX": true,
      "ajax": {
        "url": "<?php echo site_url('sale/get_data') ?>",
        "type": "POST",
        "data": function(f) {
          f.unit_id = $("#unit_id_depo").val();
          f.tanggal = $("#filter_tanggal").val();
          f.sale_type = $("#filter_pembayaran").val();
        }
      },
      'columnDefs': [{
          'targets': [0,1,2,-1],
          'searchable': false,
          'orderable': false,
        },
        {
          "width": "10%",
          "targets": -1
        },
        {
          "visible": false,
          "targets": [8,3]
        },
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

    $("#nomor_resep_co").bind('keyup', function(e) {
      if (e.keyCode == 13) {
        checkout_pasien(this.value);
        // e.stopImmediatePropagation();
      }
      e.preventDefault();
    });
  });

  $("#unit_id_depo, #sale_type, #filter_pembayaran, #filter_tanggal").change(() => {
      table.draw();
  });

  $("#btn-add").click(function() {
    $("#form_sale").show();
    $("#data_sale").hide();
    $("#form_sale").load("sale/show_form/" + $("#unit_id_depo").val());
  });

  function panggil_antrian(id){
    $.get('sale/panggil_antrian/' + id);
  }

  function set_val(id) {
    $("#modal_update").modal('show');
    $("#modal_update").find('.modal-body').load('sale/show_form_update/' + id, function() {
      $.get('sale/find_one/' + id, (data) => {
        $.each(data, (ind, obj) => {
          $('.modal-body').find("#" + ind).val(obj);
        });
        if (data.visit_id) {
          $('.modal-body').find("#tipe_patient").val(1);
        }
        $("select[class*='select2']").trigger("change");
      }, 'json');
    });
  }

  function checkout_pasien(noresep) {
    $(".loading-checkout").show();
    $.post('<?php echo base_url() ?>sale/checkout_pasien', {
      noresep: noresep,
      unit_id: $("#unit_id_depo").val(),
      asal_resep: $("#asal_resep").val(),
    }, function(data) {
      alert(data.message);
      $(".loading-checkout").hide();
      $('#nomor_resep_co').focus();
      $('#nomor_resep_co').val('');
      $('#nomor_resep_co').focus();
      table.draw();
      return false;
    }, 'json');
  }

  function deleteRow(id,rcp_id) {
    if (confirm("Anda yakin akan menghapus data ini?")) {
      $.get('sale/delete_row/' + id+'/'+ rcp_id, (data) => {
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

  $("#btn-checkout").click(function(event) {
    $("#modal_checkout").modal("show");
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

  function cetak_prb(id) {
    var url = "<?php echo base_url() ?>sale/cetak_prb/" + id;
    var left = ($(window).width() / 2) - (800 / 2);
    var top = ($(window).height() / 2) - (400 / 2);
    window.open(url, "SURAT RUJUK BALIK", "width=800, height=400, top=" + top + ", left=" + left);
  }

  function faktur_pdf(id,type) {
  
    var url = "<?php echo base_url() ?>sale/strukapotikresep/" +  id + '/' + $('#unit_id_depo').val() + '/' + type;
    var left = ($(window).width() / 2) - (800 / 2);
    var top = ($(window).height() / 2) - (400 / 2);
    window.open(url, "FAKTUR OBAT", "width=800, height=400, top=" + top + ", left=" + left);
  }



  <?= $this->config->item('footerJS') ?>
</script>