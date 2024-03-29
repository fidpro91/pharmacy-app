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
          <h3 class="box-title">Form Laporan Retur Penjualan</h3>
          <div class="box-tools pull-right">
          </div>
<br>
 </div> 
<?=form_open("laporan_retur_penjualan/show_laporan",["method"=>"post","id"=>"formlaporan","target"=>"blank"])?>      
        <div class="box-body" id="penjualan"> 
                 
          <div class="col-md-5" >
            <?=form_hidden("item_id")?>
            <?=form_hidden("px_id")?>
            <?=form_hidden("visit_id")?>
            <?=form_hidden("srv_id")?>
            <?=form_hidden("px_norm")?>
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
                
       </div>
       <div class="col-md-5">       
       <?= create_select([
					"attr" => ["name" => "jenis_px=JENIS PASIEN", "id" => "jenis_px", "class" => "form-control"],
					"option" => [["id" => '', "text" => "Semua"],["id" => 'RJ', "text" => "Pelayanan Rawat Jalan"], ["id" => 'RI', "text" => " Pelayanan Rawat Inap"],
                        ["id" => 'IGD', "text" => " Pelayanan IGD"]],
		  	]) ?>       
         
         <?= create_select([
					"attr" => ["name" => "tipe=TIPE LAPORAN", "id" => "tipe", "class" => "form-control", 'required' => true],
					"option" => [["id" => ' ', "text" => "SEMUA"], ["id" => '1', "text" => "Detail"],
                                 ["id" => '2', "text" => "Pasien"],["id" => '3', "text" => "Obat"]
                                 ],
			]) ?>       
      <div id="item_name"><?= create_input("item")?></div>
      <div id="nama_px"><?= create_input("patient=Nama Pasien")?></div>
      </div>        
     </div>
     <div align="center">
     <button class="btn btn-primary" type="button" onclick="$('#formlaporan').submit()">
     <i class="fa fa-eye" aria-hidden="true"></i>Tampilkan</button>
     <button class="btn btn-warning" type="reset" id="form_reset">
      <i class="fa fa-paint-brush" aria-hidden="true"></i>Reset</button>
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
	
  $('#tipe').change(function(){
     if($(this).val() == '2') {
			$('#nama_px').show();
			$('#item_name').hide();
		}else if ($(this).val() >= '3'){
			$('#nama_px').hide();
			$('#item_name').show();
		}else{
			$('#nama_px').hide();
			$('#item_name').hide();
		}
	})
          
    });

    $('body').on("keyup", "#patient", function() {
		$(this).autocomplete({
			source: function(request, response) {
			    $.post(
			    	"<?php echo base_url();?>laporan_penjualan/get_patient/",
			    	$('#formlaporan').serialize()+'&term='+request.term,  
			    	response, 'json'
			    );
			},
			minLength:2,
			select: function( event, ui ) {
				$("#px_id").val( ui.item.px_id);
				$("#visit_id").val( ui.item.visit_id);
			}
	    }) 
	    .autocomplete().data("uiAutocomplete")._renderItem =  function( ul, item ){
			return $( "<li>" ) 
	        .append( "<a style='font-size:12px'>" +item.value+" ||( "+item.unit_layanan+" )|| "+item.tgl_visit+"</a>" )
	        .appendTo( ul );
		}; 
	});



    $(function() {
    //Membuat Fungsi Tanda (,)
    function split( val ) {
    return val.split( /,\s*/ );
    }
    function extractLast( term ) {
    return split( term ).pop();
    }

    $("#item" )
    // don’t navigate away from the field on tab when selecting an item
    .bind( "keydown", function( event ) {
    if ( event.keyCode === $.ui.keyCode.TAB &&
    $( this ).autocomplete( "instance" ).menu.active ) {
    event.preventDefault();
    }
    })
    .autocomplete({
    minLength: 3,autoFocus: true,
    source: function(request, response) {
	    $.post(
	    	"<?php echo base_url();?>laporan_penjualan/get_item/",
	    	'term='+extractLast( request.term ),  
	    	response, 'json'
	    );
	},
       focus: function() {
                  // Membatasi Nilai Fokus
                  return false;
                  },
                  select: function( event, ui ) {
                  var terms = split( this.value );
                  var terms2 = split( $('#item_id').val());
                  // Menghapus Inputan yang ada
                  terms.pop();
                  terms2.pop();
                  // Menambahkan data yang di Select
                  terms.push( ui.item.value );
                  terms2.push( ui.item.item_id );
                  // menambahkan  placeholder Untuk Medapatkan koma
                  terms.push( "" );
                  terms2.push( "" );
                  this.value = terms.join( "" );
                  $('#item_id').val(terms2.join( "" ));
                  return false;
                  }
                  });
        });
   
        $("#formlaporan").on("submit",()=>{
        if ($("#unit_name").val() === '' ) {
          alert("Mohon di isikan Depo");
          return false;       
        }else if($("#tipe").val() === ' '){
        alert("Mohon di isikan Tipe Laporan");
        return false;
         }
        }); 
        $('#form_reset').click(function(){
      	$('#formlaporan').find("input[type='hidden']").val('');
        })
    <?=$this->config->item('footerJS')?>
</script>
