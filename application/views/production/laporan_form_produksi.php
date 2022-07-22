<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=ucwords('FORM LAPORAN')?>
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
          <h3 class="box-title">Form Laporan</h3>
          <div class="box-tools pull-right">
          </div>
<br>
 </div> 
<?=form_open("laporan_produksi/show_laporan",["method"=>"post","id"=>"formlaporan","target"=>"blank"])?>      
        <div class="box-body" id="jaspel">        
          <div class="col-md-6" >
          <?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>          
          <?=create_select2(["attr"=>["name"=>"unit_name=UNIT","id"=>"unit_name","class"=>"form-control"],
								"model"=>["m_ms_unit" => "get_farmasi_unit","column"=>["unit_id","unit_name"]]
							])?>          
        <br><br>
       <button class="btn btn-primary" type="button" onclick="$('#formlaporan').submit()">Tampilkan</button>
       </div>
     </div>

<?=form_close()?>

      
</div>
    </section>
    <!-- /.content -->
  </div>  

<script type="text/javascript">
      $(document).ready(function() {
     // $('.select2').select2();     
    });
    <?=$this->config->item('footerJS')?>
</script>