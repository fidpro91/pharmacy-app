<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0
  </div>
  <strong><?=FOOT_NOTE?>
</footer>
</div>
<!-- ./wrapper -->
</body>

</html>
<script src="<?= base_url() ?>assets/dist/js/loading.js"></script>
<script type="text/javascript">
  $('.breadcrumb').breadcrumbsGenerator({
    sitemaps: '.sidebar-menu',
    index_type: 'index.html'
  });
  
/* setTimeout(function(){
  $(".alert").hide("slow");
},3000); */

function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config):
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
};
  
  $("#checkAll").click(() => {
    if ($("#checkAll").is(':checked')) {
      $("#tb_ms_jabatan input[type='checkbox']").attr("checked", true);
    } else {
      $("#tb_ms_jabatan input[type='checkbox']").attr("checked", false);
    }
  });

  $(document).ready(()=>{
    $('body').on( 'init.dt', function ( e, ctx ) {
      var api = new $.fn.dataTable.Api( ctx );
      $('.dataTables_filter input').unbind();
      $('.dataTables_filter input').bind('keyup', function(e){
          var code = e.keyCode || e.which;
          if (code == 13) { 
              api.search(this.value).draw();
          }
      });
    });
  })
</script>