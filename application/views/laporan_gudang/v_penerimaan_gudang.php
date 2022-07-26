<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= ucwords('FORM LAPORAN PERMINTAAN GUDANG') ?>
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
                <h3 class="box-title">Form Laporan Permintaan Gudang</h3>
                <div class="box-tools pull-right">
                </div>
                <br>
            </div>
            <?= form_open("laporan_permintaan_gudang/show_laporan", ["method" => "post", "id" => "formlaporan", "target"=>"_blank"]) ?>
                <div class="box-body">
					<div class="col-md-6">
						<?=create_select2(["attr"=>["name"=>"jenis_laporan","id"=>"jenis_laporan","class"=>"form-control"],
								"option" => [
										["id" => '1', "text" => "Journal Detail"],
										["id" => '2', "text" => "Journal Detail Supplier"],
										["id" => '3', "text" => "Journal"],
										["id" => '4', "text" => "Journal Supplier"],
										["id" => '5', "text" => "Journal Item"],
										["id" => '6', "text" => "Versi PPTK"]
								],
						])?>
						<?=create_select2(["attr"=>["name"=>"jenis_permintaan","id"=>"jenis_permintaan","class"=>"form-control"],
								"option" => [
										["id" => '0', "text" => "Pembelian PBF"],
										["id" => '5', "text" => "hibah"]
								],
						])?>
						<?=create_select2(["attr"=>["name"=>"supplier_id=nama supplier","id"=>"supplier_id","class"=>"form-control"],
								"model"=>[
										"m_ms_supplier" =>["get_ms_supplier",["0"=>"0"]],
										"column"=>["supplier_id","supplier_name"]
								]
						])?>

						<?=create_select2(["attr"=>["name"=>"item_id=nama item","id"=>"item_id","class"=>"form-control"],
								"model"=>[
										"m_ms_item" =>["get_ms_item",["item_active"=>"t"]],
										"column"=>["item_id","item_name"]
								]
						])?>
					</div>
					<div class="col-md-6">
						<?=create_select2(["attr"=>["name"=>"own_id","id"=>"own_id","class"=>"form-control"],
								"model"=>[
										"m_ownership" =>"get_ownership",
										"column"=>["own_id","own_name"]
								]
						])?>
						<?=create_select2(["attr"=>["name"=>"sumber_anggaran","id"=>"sumber_anggaran","class"=>"form-control"],
								"model"=>[
										"m_laporan_gudang" =>"get_data_sumber",
										"column"=>["estimate_resource","estimate_resource"]
								]
						])?>
						<?=create_select2(["attr"=>["name"=>"pembayaran","id"=>"pembayaran","class"=>"form-control"],
								"option" => [
										["id" => '1', "text" => "tunai"],
										["id" => '2', "text" => "kredit"]
								],
						])?>
						<?=create_inputDaterange("tanggal",["locale"=>["format"=>"YYYY-MM-DD","separator"=>"/"]])?>
					</div>
				</div>
                <?= form_close() ?>
			<div class="box-footer">
				<button class="btn btn-primary" type="button" onclick="$('#formlaporan').submit()">Tampilkan</button>
			</div>
            </div>
    </section>
</div>
<script>
	<?=$this->config->item('footerJS')?>
</script>
