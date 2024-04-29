<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= ucwords('Distribusi BON Unit') ?>
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
        <h3 class="box-title">Form Distribusi BON Unit</h3>
      </div>
      <div class="col-md-3">
        <?= create_select([
          "attr" => ["name" => "filter_unit=Filter Unit", "id" => "filter_unit", "class" => "form-control"],
          "model" => [
            "m_ms_unit" => ["get_ms_unit", ["employee_id" => $this->session->employee_id]],
            "column"  => ["unit_id", "unit_name"]
          ],
        ]) ?>
      </div>
      <div class="col-md-3">
        <?= create_inputDate("tanggal=bulan mutasi", [
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
          "attr" => ["name" => "status=status mutasi", "id" => "status", "class" => "form-control"],
          "option" => [["id" => ' ', "text" => 'Pilih'], ["id" => '1', "text" => "Meminta"], ["id" => '2', "text" => "diproses"], ["id" => '3', "text" => "terima"]]
        ]) ?>
      </div>
      <div class="col-md-2">
        <?= create_select([
          "attr" => ["name" => "is_print=status cetak", "id" => "is_print", "class" => "form-control"],
          "option" => [["id" => ' ', "text" => "BELUM"], ["id" => 't', "text" => "SUDAH"]]
        ]) ?>
      </div>
      <div class="box-body" id="form_mutation" style="display: none;">
      </div>

      <div class="box-body" id="data_mutation">
        <?= create_table(
          "tb_mutation",
          [
            "model" => "M_mutation",
            "col"   => "get_column_bon"
          ],
          ["class" => "table table-bordered", "style" => "width:100% !important;"]
        ) ?>
      </div>
      <!-- /.box-footer-->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= modal_open("modal_distribusi", "Distribusi Permintaan", "modal-lg") ?>
<?= modal_close() ?>
<script type="text/javascript">
  var table;
  var mutationDetail;
  $(document).ready(function() {
    table = $('#tb_mutation').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [[3,'desc']],
      "scrollX": true,
      "ajax": {
        "url": "<?php echo site_url('Distribusi_bon/get_data') ?>",
        "type": "POST",
        "data": function(f) {
          f.unit = $("#filter_unit").val();
          f.tgl = $("#tanggal").val();
          f.sts = $("#status").val();
          f.print = $("#is_print").val();
        }
      },
      'columnDefs': [{
          'targets': [0, 1, -1],
          'searchable': false,
          'orderable': false,
        },
        {
          "width": "10%",
          "targets": -1
        },
        {
          'targets': 0,
          'className': 'dt-body-center',
          'visible': false
        }
      ],
    });
    $("#filter_unit,#tanggal,#status,#is_print").change(() => {
      table.draw();
    });
  });
  $("#btn-add").click(function() {
    $("#form_mutation").show();
    $("#form_mutation").load("Distribusi_bon/show_form");
  });

  function konfirm_distribusi(id) {
    $("#modal_distribusi").modal("show");
    $("#modal_distribusi").find(".modal-body").load("distribusi_bon/show_form/" + id);
  }

  function batal_mutasi(id) {
    if (confirm("Batalkan pengiriman item?")) {
      $.get('Distribusi_bon/batal_mutation/' + id, (data) => {
        alert(data.message);
        location.reload();
      }, 'json');
    }
  }

  $("#checkAll").click(() => {
    if ($("#checkAll").is(':checked')) {
      $("#tb_mutation input[type='checkbox']").attr("checked", true);
    } else {
      $("#tb_mutation input[type='checkbox']").attr("checked", false);
    }
  });

  $("#btn-deleteChecked").click(function(event) {
    event.preventDefault();
    var searchIDs = $("#tb_mutation input:checkbox:checked").map(function() {
      return $(this).val();
    }).toArray();
    if (searchIDs.length == 0) {
      alert("Mohon cek list data yang akan dihapus");
      return false;
    }
    if (confirm("Anda yakin akan menghapus data ini?")) {
      $.post('Distribusi_bon/delete_multi', {
        data: searchIDs
      }, (resp) => {
        alert(resp.message);
        location.reload();
      }, 'json');
    }
  });

  function cetak_struk(id) {
    if (confirm("cetak struk bon?")) {
      $.get('Distribusi_bon/update_print/' + id, (data) => {
        console.log(data);
      });
    }

    var printdata = window.open("<?php echo base_url() ?>distribusi_bon/cetak_struk/" + id, "", "width=500, height=300");
   
  }
  <?= $this->config->item('footerJS') ?>
</script>
