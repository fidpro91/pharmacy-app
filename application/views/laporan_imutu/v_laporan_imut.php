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
				<h3 class="box-title">Form Laporan Penjualan Unit</h3>
				<div class="box-tools pull-right">
				</div>
				<br>
			</div>
			<?=form_open("c_laporan_imut/show_laporan",["method"=>"post","id"=>"formlaporan","target"=>"blank"])?>
			<div class="box-body" id="penjualan">

				<div class="col-md-5" >
					<?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>
					<?=create_select2(["attr"=>["name"=>"unit_name[]=UNIT","id"=>"unit_name","class"=>"form-control","multiple"=>true],
						"model"=>["m_ms_unit" => "get_farmasi_unit","column"=>["unit_id","unit_name"]]
					])?>
					<?= create_select([
							"attr"         => ["name" => "tipe_patient=Tipe Penjualan", "id" => "tipe_patient", "class" => "form-control"],
							"option"    => [
									["id" => "0", "text" => "APS"], ["id" => "1", "text" => "NON APS"]
							],
							"selected" => "1"
					]) ?>
					<?=create_select(["attr"=>["name"=>"kepemilikan=KEPEMILIKAN","id"=>"kepemilikan","class"=>"form-control"],
						"model"=>["m_ownership" => "get_ownership","column"=>["own_id","own_name"]]
					])?>
					<?=create_select2(["attr"=>["name"=>"surety=PENJAMIN","id"=>"surety","class"=>"form-control"],
						"model"=>["m_surety_ownership" => "get_ms_Surety","column"=>["surety_id","surety_name"]]
					])?>

					<?= create_select([
						"attr" => ["name" => "gol_laporan=GOL. LAPORAN", "id" => "gol_laporan", "class" => "form-control", 'required' => true],
						"option" => [
								["id" => '0', "text" => "Laporan Detail"],
								["id" => '1', "text" => "Rekap Data"],
								["id" => '2', "text" => "Laporan By Dokter"],
								["id" => '3', "text" => "Laporan By Terapi Obat"],
						],
					]) ?>

				</div>



			</div>
			<div align="center">
				<button class="btn btn-primary" type="button" onclick="$('#formlaporan').submit()">Tampilkan</button>
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

	$('body').on("keyup", "#patient", function() {
		if ($('#tipe').val() == 3) {
			$(this).autocomplete({
				source: function(request, response) {
					$.post(
						"<?php echo base_url();?>laporan_penjualan/get_patient/",
						$('#formlaporan').serialize()+'&term='+request.term,
						response, 'json'
					);
				},
				minLength:2,
				autofocus:true,
				select: function( event, ui ) {
					// $("#px_id").val( ui.item.px_id);
					$("#px_norm").val( ui.item.value);
				}
			})
				.autocomplete().data("uiAutocomplete")._renderItem =  function( ul, item ){
				return $( "<li>" )
					.append( "<a style='font-size:12px'>" +item.value+"</a>" )
					.appendTo( ul );
			};
		}else{
			$(this).autocomplete({
				source: function(request, response) {
					$.post(
						"<?php echo base_url();?>laporan_penjualan/get_patient/",
						$('#formlaporan').serialize()+'&term='+request.term,
						response, 'json'
					);
				},
				minLength:2,
				autofocus:true,
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
		}
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
					this.value = terms.join( ", " );
					$('#item_id').val(terms2.join( ", " ));
					return false;
				}
			});
	});

	$("#formlaporan").on("submit",()=>{
		if ($("#unit_name").val() === '' ) {
			alert("Mohon di isikan Depo");
			return false;
		}else if($("#tanggal").val() === ''){
			alert("Mohon di isikan tanggal");
			return false;
		}
		else if ($('#tipe').val() == 3) {
			if ($('#patient').val() == '') {
				alert('Nama pasien tidak ditemukan');
				return false;
			}

		}
	});

	$('#form_reset').click(function(){
		$('#formlaporan').find("input[type='hidden']").val('');
	})



	<?=$this->config->item('footerJS')?>
</script>
