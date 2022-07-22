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
          <h3 class="box-title">Form Laporan Penjualan</h3>
          <div class="box-tools pull-right">
          </div>
<br>
 </div> 
<?=form_open("laporan_penjualan/show_laporan",["method"=>"post","id"=>"formlaporan","target"=>"blank"])?>      
        <div class="box-body" id="penjualan"> 
                 
          <div class="col-md-5" >
          <?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>          
          <?=create_select(["attr"=>["name"=>"unit_name=UNIT","id"=>"unit_name","class"=>"form-control"],
								"model"=>["m_ms_unit" => "get_farmasi_unit","column"=>["unit_id","unit_name"]]
							])?>  
          <?= create_select([
					"attr" => ["name" => "tipe_bayar=PENJUALAN", "id" => "tipe_bayar", "class" => "form-control", 'required' => true],
					"option" => [["id" => '0', "text" => "Tunai"], ["id" => '1', "text" => "Kredit"]],
			]) ?>  
         <?=create_select(["attr"=>["name"=>"kepemilikan=KEPEMILIKAN","id"=>"kepemilikan","class"=>"form-control"],
								"model"=>["m_ownership" => "get_ownership","column"=>["own_id","own_name"]]
							])?>  
         <?=create_select2(["attr"=>["name"=>"surety=PENJAMIN","id"=>"surety","class"=>"form-control"],
								"model"=>["m_surety_ownership" => "get_ms_Surety","column"=>["surety_id","surety_name"]]
							])?>        
       </div>
       <div class="col-md-5">
       <?=create_select2(["attr"=>["name"=>"jenis_px=JENIS PASIEN","id"=>"jenis_px","class"=>"form-control"],
								"model"=>["m_laporan_penjualan" => "get_jenis_layanan","column"=>["catunit_id","nama"]]
							])?> 
        <?= create_select2([
					"attr" => ["name" => "unit_layanan=UNIT LAYANAN PASIEN", "id" => "unit_layanan","class" => "form-control"],
			]) ?>
         <?= create_select([
					"attr" => ["name" => "status_bayar=STATUS BAYAR", "id" => "status_bayar", "class" => "form-control", 'required' => true],
					"option" => [["id" => '1', "text" => "Sudah Bayar"], ["id" => '2', "text" => "Belum Bayar"]],
			]) ?> 
         <?= create_select([
					"attr" => ["name" => "tipe=TIPE LAPORAN", "id" => "tipe", "class" => "form-control", 'required' => true],
					"option" => [["id" => ' ', "text" => "SEMUA"], ["id" => '1', "text" => "Dokter"],
                                 ["id" => '2', "text" => "Summary Customer"],["id" => '3', "text" => "Pasien"],
                                 ["id" => '4', "text" => "Obat"],["id" => '5', "text" => "Rekap Obat"]],
			]) ?> 
      
      <div id="item_name"><?= create_input("item_name=Nama Obat")?></div>
      <div id="nama_px"><?= create_input("nama_px=Nama Pasien")?></div>
       </div>
       
         
     </div>
     <div align="center">
     <button class="btn btn-primary" type="button" onclick="$('#formlaporan').submit()">Tampilkan</button>
     </div>

<?=form_close()?>

      
</div>
    </section>
    <!-- /.content -->
  </div>  

<script type="text/javascript">
      $(document).ready(function() {
    //$('.select2').select2({placeholder: '--Pilih--'});
     $('#nama_px').hide();
		 $('#item_name').hide();
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
  $('#tipe').change(function(){
     if($(this).val() == '2' || $(this).val() == '3') {
			$('#nama_px').show();
			$('#item_name').hide();
		}else if ($(this).val() >= '4'){
			$('#nama_px').hide();
			$('#item_name').show();
		}else{
			$('#nama_px').hide();
			$('#item_name').hide();
		}
	})
          
    });
    
    <?=$this->config->item('footerJS')?>
</script>