<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= ucwords('Receiving Retur') ?>
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
        <h3 class="box-title">Form Receiving Retur</h3>
        <div class="box-tools pull-right">
          <button type="button" id="btn-add" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add</button>
        </div>
      </div>
      <div class="box-body" id="form_receiving_retur" style="display: none;">
      </div>
      <div class="box-body" id="data_receiving_retur">
      <div class="col-md-3">
            <?=create_inputDate("filter_bulan=Filter Retur",[
                "format"		=>"mm-yyyy",
                "viewMode"		=> "year",
                "minViewMode"	=> "year",
                "autoclose"		=>true],[
                  "value"     => date('m-Y'),
                  "readonly"  => true
                ])
            ?>
        </div>
        <div class = "col-md-12">
        <?= create_table("tb_receiving_retur", "M_receiving_retur", ["class" => "table table-bordered", "style" => "width:100% !important;"]) ?>
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
<script type="text/javascript">
  var table;
  var dataRetur;
  $(document).ready(function() {
    table = $('#tb_receiving_retur').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "scrollX": true,
      "ajax": {
        "url": "<?php echo site_url('receiving_retur/get_data') ?>",
        "type": "POST",
        "data": function(f) {        
            f.bulan = $("#filter_bulan").val();           
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
    $("#filter_bulan").change(() => {
			table.draw();
		});
  });
  $("#btn-add").click(function() {
    dataRetur = null;
    $("#form_receiving_retur").show();
    $("#form_receiving_retur").load("receiving_retur/show_form");
  });

  function set_val(id) {
    $("#form_receiving_retur").show();
    $.get('receiving_retur/find_one/' + id, (data) => {
      $.ajax({
        'async': false,
        'type': "GET",
        'dataType': 'json',
        'url': "receiving_retur/find_rr_detail/" + id,
        'success': function(data) {
          dataRetur = data;
        }
      });
      $("#form_receiving_retur").load("receiving_retur/show_form", () => {
        $.each(data, (ind, obj) => {
          $("#" + ind).val(obj);
        });
      });
    }, 'json');
  }

  function deleteRow(id) {
    if (confirm("Anda yakin akan menghapus data ini?")) {
      $.get('receiving_retur/delete_row/' + id, (data) => {
        alert(data.message);
        location.reload();
      }, 'json');
    }
  }

  $("#checkAll").click(() => {
    if ($("#checkAll").is(':checked')) {
      $("#tb_receiving_retur input[type='checkbox']").attr("checked", true);
    } else {
      $("#tb_receiving_retur input[type='checkbox']").attr("checked", false);
    }
  });

  $("#btn-deleteChecked").click(function(event) {
    event.preventDefault();
    var searchIDs = $("#tb_receiving_retur input:checkbox:checked").map(function() {
      return $(this).val();
    }).toArray();
    if (searchIDs.length == 0) {
      alert("Mohon cek list data yang akan dihapus");
      return false;
    }
    if (confirm("Anda yakin akan menghapus data ini?")) {
      $.post('receiving_retur/delete_multi', {
        data: searchIDs
      }, (resp) => {
        alert(resp.message);
        location.reload();
      }, 'json');
    }
  });

  function cetak(id, type) {
    var myWindow = window.open('<?php echo base_url() ?>receiving_retur/cetak/' + id + '/' + type, '', 'width=1000,height=500');
    myWindow.focus();
    myWindow.print();
  }

  // function cetak(id, type) {
  //   var url = "<?php echo base_url() ?>sale/receiving_retur/cetak/" + id + "/" + type;
  //   var left = ($(window).width() / 2) - (600 / 2);
  //   var top = ($(window).height() / 2) - (400 / 2);
  //   window.open(url, "", "width=1000,height=500,scrollbars=yes");
  // }
  <?=$this->config->item('footerJS')?>
</script>