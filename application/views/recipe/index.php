<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= ucwords('Recipe') ?>
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
      <?= $this->session->flashdata('message') ?>
      <div class="box-header with-border">
        <div class="box-tools pull-left">
          <?= form_dropdown("unit_id_depo", $unit, '', 'class="form-control select2" id="unit_id_depo"') ?>
        </div>
        <div class="box-tools pull-right">
        </div>
      </div>
      <div class="box-body" id="form_recipe" style="display: none;">
      </div>
      <div class="box-body" id="data_recipe">
        <div class="col-md-3">
          <?= create_inputDate("filter_tanggal=tanggal", [
            "format"    => "dd-mm-yyyy",
            "autoclose"    => true
          ], [
            "value"     => date('d-m-Y'),
            "readonly"  => true
          ])
          ?>
        </div>
        <div class="col-md-3">
          <?= create_select2([
            "attr" => ["name" => "unit_id_layanan=Unit Layanan", "id" => "unit_id_layanan", "class" => "form-control"],
            "model" => [
              "m_ms_unit" => ["get_ms_unit_all", ["0" => '0']],
              "column"  => ["unit_id", "unit_name"]
            ]
          ]) ?>
        </div>
        <div class="col-md-3">
          <?= create_select2([
            "attr" => [
              "name" => "surety_id_filter=Penjamin", "id" => "surety_id_filter", "class" => "form-control",
              "required"  => true
            ],
            "model" => [
              "m_sale" => ["get_penjamin", ["surety_active" => 't']],
              "column"  => ["surety_id", "surety_name"]
            ]
          ]) ?>
        </div>
        <div class="col-md-3">
          <?= create_select([
            "attr"         => ["name" => "rcp_status=Status", "id" => "rcp_status", "class" => "form-control"],
            "option"    => [
              ["id" => "0", "text" => "Request"],
              ["id" => "1", "text" => "Dilayani Penuh"],
              ["id" => "2", "text" => "Dilayani Sebagian"],
              ["id" => "3", "text" => "Sudah diprint"]
            ]
          ]) ?>
        </div>
        <div class="col-md-12">
          <?= create_table("tb_recipe", "M_recipe", ["class" => "table table-bordered", "style" => "width:100% !important;"]) ?>
        </div>
      </div>
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= modal_open("modal_recipe", "Form Recipe Online", "modal-lg", [
  "style" => "width:90%"
]) ?>
<?= modal_close() ?>
<?= modal_open("modal_delete", "Hapus pelayanan resep", "modal-lg") ?>
<?= modal_close() ?>
<?= modal_open("modal_telaah", "Form Recipe Online", "modal-lg", [
  "style" => "width:90%"
]) ?>
<?= modal_close() ?>
<script type="text/javascript">
  var table;
  var audio = document.createElement('audio');
  var audioActive = 'true';
  var lastNotice = 0;
  $(document).ready(function() {
    audio.src = "<?php echo base_url() ?>assets/audio/e-resep.mp3";
    table = $('#tb_recipe').DataTable({
      "processing": true,
      "serverSide": true,
      "pageLength": 100,
      "order": [],
      "scrollX": true,
      "ajax": {
        "url": "<?php echo site_url('recipe/get_data') ?>",
        "type": "POST",
        "data": function(f) {
          f.unit_id = $("#unit_id_depo").val();
          f.tanggal = $("#filter_tanggal").val();
          f.unit_layanan = $("#unit_id_layanan").val();
          f.surety_id = $("#surety_id_filter").val();
          f.rcp_status = $("#rcp_status").val();
        }

      },
      "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        if (aData[10].includes("Request") && lastNotice < aData[2]) {
          audio.play();
          lastNotice = aData[2];
        }
      },
      'columnDefs': [{
          'targets': [0, 1, -1],
          'searchable': false,
          'orderable': false,
        },
        {
          'targets': [0],
          'visible': false
        }
      ],
    });

    $("#unit_id_depo, #filter_tanggal, #unit_id_layanan, #surety_id_filter, #rcp_status").change(() => {
      table.draw();
    })
  });

  setInterval(() => {
    table.draw();
  }, 30000);

  function set_val(id) {
    $("#modal_recipe").modal('show');
    $("#modal_recipe").find('.modal-body').load('recipe/show_form/' + id, function() {
      $.get('recipe/find_one/' + id, (data) => {
        $.each(data, (ind, obj) => {
          $('.modal-body').find("#" + ind).val(obj);
        });
             
        $('.modal-body').find(".px_name").text(data.px_name);
        $('.modal-body').find(".px_norm").text(data.px_norm);
        $('.modal-body').find(".unit_layanan").text(data.unit_name);
        $('.modal-body').find(".cara_bayar").text(data.surety_name);
        $('.modal-body').find(".noka").text(data.pxsurety_no);
        $('.modal-body').find(".nosep").text(data.sep_no);
        if(data.par_id != data.user_dokter || data.par_id == null){
          $('.modal-body').find(".dpjp_layan").text(data.person_name);
        }else{
          $('.modal-body').find(".dpjp_layan").text(data.employee_ft + data.employee_name + data.employee_bt);
        }
        
        $("#modal_recipe").find("select[class*='select2']").trigger("change");
      }, 'json');
    });
  }

  function deleteRow(id) {
    $("#modal_delete").modal("show");
    $("#modal_delete").find('.modal-body').load('recipe/show_form_delete/' + id, function() {

    });
  }

  $("#checkAll").click(() => {
    if ($("#checkAll").is(':checked')) {
      $("#tb_recipe input[type='checkbox']").attr("checked", true);
    } else {
      $("#tb_recipe input[type='checkbox']").attr("checked", false);
    }
  });

  $("#btn-deleteChecked").click(function(event) {
    event.preventDefault();
    var searchIDs = $("#tb_recipe input:checkbox:checked").map(function() {
      return $(this).val();
    }).toArray();
    if (searchIDs.length == 0) {
      alert("Mohon cek list data yang akan dihapus");
      return false;
    }
    if (confirm("Anda yakin akan menghapus data ini?")) {
      $.post('recipe/delete_multi', {
        data: searchIDs
      }, (resp) => {
        alert(resp.message);
        location.reload();
      }, 'json');
    }
  });

  function cetak_eresep(id) {
    var url = "<?php echo base_url() ?>recipe/cetak_eresep/" + id;
    var left = ($(window).width() / 2) - (1200 / 2);
    var top = ($(window).height() / 2) - (800 / 2);
    window.open(url, "Struk Pembayaran Apotik", "width=1200, height=800, top=" + top + ", left=" + left);
  }

  function preview(id) {
    var url = "<?php echo base_url() ?>recipe/preview/" + id;
    var left = ($(window).width() / 2) - (1200 / 2);
    var top = ($(window).height() / 2) - (800 / 2);
    window.open(url, "Struk Pembayaran Apotik", "width=1200, height=800, top=" + top + ", left=" + left);
  }
  <?= $this->config->item('footerJS') ?>
</script>