<style>
    .info-box {
        cursor: pointer !important;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= ucwords('Import Data Master') ?>
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
                <h3 class="box-title">Form Import Data</h3>
            </div>
            <div class="box-body" id="data_surety_ownership">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12" id="stok_awal">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Stok Awal</span>
                                <span class="info-box-number">1,410</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Master Item</span>
                                <span class="info-box-number">410</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Uploads</span>
                                <span class="info-box-number">13,648</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Likes</span>
                                <span class="info-box-number">93,139</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= modal_open("modal_import", "Import Data") ?>
<?= modal_close() ?>
<script type="text/javascript">
$("#stok_awal").click(()=>{
    $("#modal_import").modal("show");
    $("#modal_import").find(".modal-body").load("import_master/show_so_awal");
})
</script>