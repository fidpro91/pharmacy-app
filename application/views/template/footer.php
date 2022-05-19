<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0
  </div>
  <strong>Copyright &copy; 2021 <a href="www.hosterweb.co.id">Hosterweb</a>.</strong> All rights
  reserved.
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
  function formatNumeric(val = 0) {
    return new Intl.NumberFormat({
      style: 'currency',
      currency: 'IDR',
    }).format(val);
  }
  $("#checkAll").click(() => {
    if ($("#checkAll").is(':checked')) {
      $("#tb_ms_jabatan input[type='checkbox']").attr("checked", true);
    } else {
      $("#tb_ms_jabatan input[type='checkbox']").attr("checked", false);
    }
  });
</script>