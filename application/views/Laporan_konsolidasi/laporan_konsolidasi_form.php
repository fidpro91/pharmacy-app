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
          <h3 class="box-title">Form Laporan Konsolidasi</h3>
          <div class="box-tools pull-right">
          </div>
<br>
 </div> 
<?=form_open("laporan_konsolidasi/show_laporan",["method"=>"post","id"=>"formlaporan","target"=>"blank"])?>      
        <div class="box-body" id="konsolidasi">                  
          <div class="col-md-5" >            
          <?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>          
          <?=create_select(["attr"=>["name"=>"unit_name=UNIT","id"=>"unit_name","class"=>"form-control"],
								"model"=>["m_ms_unit" => "get_farmasi_unit","column"=>["unit_id","unit_name"]]
							])?>  
           
         <?=create_select(["attr"=>["name"=>"kepemilikan=KEPEMILIKAN","id"=>"kepemilikan","class"=>"form-control"],
								"model"=>["m_ownership" => "get_ownership","column"=>["own_id","own_name"]]
							])?> 
         <?=create_select(["attr"=>["name"=>"golongan","id"=>"golongan","class"=>"form-control"],
								"model"=>["m_lap_konsolidasi" => "get_golongan","column"=>["gol","gol"]]
							])?>  
         
        <?= create_select([
					"attr" => ["name" => "tampil=Tampilkan", "id" => "tampil", "class" => "form-control", 'required' => true],
					"option" => [["id" => '1', "text" => "Satuan"], ["id" => '2', "text" => "Kemasan"]],
		]) ?>   
       <div align="center">
       <button  class="btn btn-primary" type="button" onclick="$('#formlaporan').submit()">Tampilkan</button>
        </div>
       </div>
    
     
     

<?=form_close()?>

</div>
    </section>
    <!-- /.content -->
  </div>

<script type="text/javascript">
$(document).ready(function() {    
          
});
    
      <?=$this->config->item('footerJS')?>
</script>