<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= ucwords('Receiving') ?>
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
        <h3 class="box-title">Form Receiving</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-plus"></i> Add
            <span class="fa fa-caret-down"></span></button>
          <ul class="dropdown-menu">
            <li><a href="#" id="btn-add-pembelian">Add Pembelian</a></li>
            <li><a href="#" id="btn-add-hibah">Add Hibah</a></li>
          </ul>
        </div>
      </div>
      <div class="box-body" id="form_receiving" style="display: none;">
      </div>
      <div class="box-body" id="data_receiving">
        <div class="col-md-3">
            <?=create_inputDate("filter_bulan=bulan penerimaan",[
                "format"		=>"mm-yyyy",
                "viewMode"		=> "year",
                "minViewMode"	=> "year",
                "autoclose"		=>true],[
                  "value"     => date('m-Y'),
                  "readonly"  => true
                ])
            ?>
        </div>
        <div class="col-md-3">
           <?= create_select([
              "attr" => ["name" => "kepemilikan_id=Kepemilikan", "id" => "kepemilikan_id", "class" => "form-control"],
              "model"=>["m_ms_unit" => ["get_ownership",[0]],
              "column"  => ["own_id","own_name"]
            ],
            ]) ?>
        </div>
        <div class="col-md-12">
          <?= create_table("tb_receiving", "M_receiving", ["class" => "table table-bordered", "style" => "width:100% !important;"]) ?>
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
<?= modal_open("modal_edit", "Update Header") ?>
<?= modal_close([
  form_button([
      "name"      => "save-update",
      "id"        => "save-update",
      "class"     => "btn btn-primary",
      "type"      => "button",
      "content"   => "Simpan"
  ]),
  form_button([
    "name"      => "close-update",
    "id"        => "close-update",
    "class"     => "btn btn-danger",
    "data-dismiss"     => "modal",
    "type"      => "button",
    "content"   => "Cancel"
])
]) ?>
<script type="text/javascript">
  var table;
  var dataHibah=null;
  $(document).ready(function() {
    table = $('#tb_receiving').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "scrollX": true,
      "ajax": {
        "url": "<?php echo site_url('receiving/get_data') ?>",
        "type": "POST",
        "data": function(f) {        
            f.bulan = $("#filter_bulan").val();
            f.own_id = $("#kepemilikan_id").val();
        }
      },
      'columnDefs': [{
          'targets': [0, 1, -1],
          'searchable': false,
          'orderable': false,
        },
        {
          'targets': 0,
          'className': 'dt-body-center',
          'render': function(data, type, full, meta) {
            return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
          }
        }
      ],
    });

    $("#filter_bulan, #kepemilikan_id").change(() => {
			table.draw();
		});
  });

  $("#btn-add-pembelian").click(function() {
    $("#form_receiving").show();
    $("#form_receiving").load("receiving/show_form");
  });

  $("#btn-add-hibah").click(function() {
    $("#form_receiving").show();
    $("#form_receiving").load("receiving/show_form_hibah");
  });

  function set_val(id) {
    $("#form_receiving").show();
    $.get('receiving/find_one/' + id+"/update", (data) => {
      if (typeof(data.message) != "undefined" && data.message !== null) {
          alert(data.message);
          return false;
      }
      if (data.rec_type == '0') {
        $("#form_receiving").load("receiving/show_form", () => {
          $.each(data, (ind, obj) => {
            $("#" + ind).val(obj);
          });
          $("select[class*='select2']").trigger("change");
          $.get("receiving/find_receiving_detail/html/" + id, function(resp) {
            $("#list_item").html(resp);
          }, "html");
        });
      }else{
        $("#form_receiving").load("receiving/show_form_hibah", () => {
          $.each(data, (ind, obj) => {
            $("#" + ind).val(obj);
          });
          $("select[class*='select2']").trigger("change");
          $.ajax({
              'async': false,
              'type': "GET",
              'dataType': 'json',
              'url': "receiving/find_receiving_detail/json/" + id,
              'success': function (data) {
                dataHibah = data;
              }
          });
          console.log(dataHibah);
        });
      }
    }, 'json');
  }

  function update_header(id) {
    $("#modal_edit").modal('show');
    $("#modal_edit").find(".modal-body").load("receiving/show_form_update/"+id,(a)=>{
      $.get("receiving/find_one/" + id, function(resp) {
        $.each(resp, (ind, obj) => {
            $("#modal_edit").find("#" + ind).val(obj);
        });
        $(".select2").trigger("change");
      }, "json");
    });
  }

  function deleteRow(id) {
    if (confirm("Anda yakin akan menghapus data ini?")) {
      $.get('receiving/delete_row/' + id, (data) => {
        alert(data.message);
        location.reload();
      }, 'json');
    }
  }

  $("#checkAll").click(() => {
    if ($("#checkAll").is(':checked')) {
      $("#tb_receiving input[type='checkbox']").attr("checked", true);
    } else {
      $("#tb_receiving input[type='checkbox']").attr("checked", false);
    }
  });

  $("#btn-deleteChecked").click(function(event) {
    event.preventDefault();
    var searchIDs = $("#tb_receiving input:checkbox:checked").map(function() {
      return $(this).val();
    }).toArray();
    if (searchIDs.length == 0) {
      alert("Mohon cek list data yang akan dihapus");
      return false;
    }
    if (confirm("Anda yakin akan menghapus data ini?")) {
      $.post('receiving/delete_multi', {
        data: searchIDs
      }, (resp) => {
        alert(resp.message);
        location.reload();
      }, 'json');
    }
  });
  <?=$this->config->item('footerJS')?>
</script>