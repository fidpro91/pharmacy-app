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
<?=form_open("laporan_telaah/show_dataku",["method"=>"post","id"=>"formlaporan","target"=>"blank"])?>      
        <div class="box-body" id="produksi">        
          <div class="col-md-6" >
          <?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>          
          <?=create_select2(["attr"=>["name"=>"unit_name=DEPO","id"=>"unit_name","class"=>"form-control"],
								"model"=>["m_ms_unit" => "get_farmasi_unit","column"=>["unit_id","unit_name"]]
							])?>  
           <?=create_select2(["attr"=>["name"=>"jenis_px=KATEGORI UNIT","id"=>"jenis_px","class"=>"form-control"],
								"model"=>["m_laporan_penjualan" => "get_jenis_layanan","column"=>["catunit_id","nama"]]
							])?> 
           <?= create_select2([
					"attr" => ["name" => "unit_layanan[]=UNIT LAYANAN PASIEN", "id" => "unit_layanan","class" => "form-control","multiple"=>true],
			      ]) ?>           
        <br><br>
       <button class="btn btn-primary" type="submit" onclick="$('#formlaporan').submit()">Tampilkan</button>
       <input class="btn btn-success" id="excel" type="submit" name="submit" value="excel" onclick="$('#formlaporan').submit()">
       </div>
     </div>

<?=form_close()?>

      
</div>
    </section>
    <!-- /.content -->
  </div>  

<script type="text/javascript">
      $(document).ready(function() {
    $('#jenis_px').change(function(){
		$('#unit_layanan option[value != 0]').remove();
     
		$('#unit_layanan').val(null).trigger('change'); 
			$.post('laporan_penjualan/get_unit_layanan',{catunit_id : $(this).val() }, 
				function(data){
					$.each(data,function(index,obj){                                             
						$('#unit_layanan').append($('<option>', {value: obj.unit_id,text: obj.unit_name}));
					}); 
			},'json');
	});    
    });
    <?=$this->config->item('footerJS')?>
</script>