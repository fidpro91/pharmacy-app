<div class="portlet box green">
	<div class="portlet-title">
		<div class="caption">
			<small> <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Laporan Pengeluaran Obat </small>
		</div>
	</div> 
	<div class="portlet-body form" id="form_container" >
		<form role="form" class="form-horizontal" id="form_vendor" method="post" target="_blank" action="<?php echo base_url()?>farmasi/laporan_gudang/pengeluaran_obat_html" >
			<div class="form-body">
				<br />
				<div class="form-group form-group-sm">
					<label class="col-sm-4 control-label fontMamel" for="inputSuccess">Unit</label>
					<div class="col-sm-3">
						<select id="unit_asal" class="select2_category form-control" required="true" name="unit_asal" aria-required="true">
							<?php foreach($unit_asal as $dataUnit):?>
								<option value="<?php echo $dataUnit->unit_id?>"> <?php echo $dataUnit->unit_name?> </option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group form-group-sm">
					<label class="col-sm-4 control-label fontMamel" for="inputSuccess">Unit Minta</label>
					<div class="col-sm-3">
						<select id="unit" class="select2_category form-control"  name="unit" aria-required="true">
							<option value="">SEMUA</option>
							<?php foreach($unit as $data):?>
								<option value="<?php echo $data->unit_id?>"> <?php echo $data->unit_name?> </option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group form-group-sm">
					<label class="col-sm-4 control-label fontMamel" for="inputSuccess">Kepemilikan</label>
					<div class="col-sm-3">
						<select id="kepemilikan" class="select2_category form-control"  name="kepemilikan" aria-required="true">
							<option value="">SEMUA</option>
							<?php foreach($kepemilikan as $data):?>
								<option value="<?php echo $data->own_id?>"> <?php echo $data->own_name?> </option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group form-group-sm">
					<label class="col-sm-4 control-label fontMamel" for="inputSuccess">Jenis Laporan</label>
					<div class="col-sm-3">
						<select id="jns_laporan" class="select2_category form-control" name="jns_laporan" aria-required="true">
							<option value="1">Laporan By Item</option>
							<option value="2">Laporan Detail Mutasi</option>
							<option value="3">Laporan Rekap Mutasi</option>
						</select>
					</div>
				</div>
				<div class="form-group form-group-sm">
					<label class="col-sm-4 control-label fontMamel" for="inputSuccess">Periode</label>
					<div class="col-sm-2"  id="tglAwal">
						<div class="input-group input-group-sm date">
							<input id="tgl_awal" name="tgl_awal" required="true" readonly="true" value="<?php echo date("d-m-Y");?>" class="form-control input-sm" type="text">
							<span class="input-group-btn">
								<button class="btn btn-info btn-datepicker" type="button" tabindex="-1">
									<span class="glyphicon glyphicon-calendar"></span>
								</button>
							</span>
						</div>
					</div>
					<div class="col-sm-2"  id="tglAkhir">
						<div class="input-group input-group-sm date">
							<input id="tgl_akhir" name="tgl_akhir" required="true" readonly="true" value="<?php echo date("d-m-Y");?>" class="form-control input-sm" type="text">
							<span class="input-group-btn">
								<button class="btn btn-info btn-datepicker" type="button" tabindex="-1">
									<span class="glyphicon glyphicon-calendar"></span>
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-actions text-center">
				<button id="cari" type="submit" class="btn  btn-sm blue" name="button" value="cari"> 
					<i class="fa fa-search" aria-hidden="true"></i> search 
				</button>
				&nbsp; &nbsp;
				<button id="cetak" type="submit" class="btn  btn-sm green" name="button" value="excel"> 
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> excel
				</button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#cari').click(function(){
		$('#form_vendor').submit();
	});
	$('#cetak').click(function(){
		$('#form_vendor').submit();
	});

	$('#unit').select2();

	$('#tglAwal .input-group.date').datepicker({
		format: "dd-mm-yyyy",
		autoclose : true
	});

	$('#tglAkhir .input-group.date').datepicker({
		format: "dd-mm-yyyy",
		autoclose : true
	});

	/*$('#cetak').click(function(){
		var data = $('#form_vendor').serialize();
		window.open("<?php echo base_url()?>farmasi/laporan_gudang/penerimaan_gudang_xls?"+data);
	});*/
});
</script>