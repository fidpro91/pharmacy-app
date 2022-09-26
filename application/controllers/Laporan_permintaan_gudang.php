<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';
class Laporan_permintaan_gudang extends MY_Generator
{
    public function __construct()
    {
        parent::__construct();
         $this->datascript->lib_select2()->lib_daterange();
		$this->load->model('m_laporan_gudang');
    }
    public function index()
    {
		$data['data'] = [];
		$this->theme('laporan_gudang/v_penerimaan_gudang', $data);
    }

	public function show_laporan()
	{

		$jenis_laporan = $this->input->post('jenis_laporan');
		if ($jenis_laporan != 3){
			$this->lap_penerimaan($this->input->post());
		}else{
			$data = array();
			$input = $this->input->post();
			$tgl = explode('/', $input['tanggal']);
			$tanggal_awal = $tgl[0];
			$tanggal_akhir = $tgl[1];
			$data['profil'] 	= $this->m_laporan_gudang->get_profil_rs();
			$data['periode']	= "Periode ".$tanggal_awal." s/d ".$tanggal_akhir."";
			if( empty($tanggal_awal) ){
				$tanggal_awal = date("Y-m-d");
			}

			if( empty($tanggal_akhir) ){
				$tanggal_akhir = date("Y-m-d");
			}

			if( empty($input['own_id']) ){
				$own = 1;
			}

			$param = array(
				'jenis'=>$input['jenis_permintaan'],
				'own_id'=>$input['own_id'],
				'tanggal_awal'=>$tanggal_awal,
				'tanggal_akhir'=>$tanggal_akhir,
				'sumber_anggaran'=>$input['sumber_anggaran'],
				'pay_type'=>$input['pembayaran']
			);
			$data['datas']		= $this->m_laporan_gudang->get_data_penerimaan( "0502", $param);
			$this->load->view('laporan_gudang/v_penerimaan_gudang_html', $data);
		}
	}

	public function lap_penerimaan($input)
	{
		$data['profil'] 	= $this->m_laporan_gudang->get_profil_rs();
		$tgl = explode('/', $input['tanggal']);
		$tanggal_awal = $tgl[0];
		$tanggal_akhir = $tgl[1];
		$data['periode']	= "Periode ".$tanggal_awal." s/d ".$tanggal_akhir."";
		if ($input['jenis_laporan'] == 1){

			$where = "AND rec.receiver_date between  '$tanggal_awal' and '$tanggal_akhir' AND rec.own_id = '".$input['own_id']."' AND rec.rec_type = '".$input['jenis_permintaan']."'";
			if ($input['supplier_id']){
				$where .= "AND rec.supplier_id = '".$input['supplier_id']."'";
			}
			if ($input['item_id']){
				$where .= "AND recdet.item_id = '".$input['item_id']."'";
			}
			if ($input['sumber_anggaran']){
				$where .= "AND lower(rec.estimate_resource) = '".strtolower($input['sumber_anggaran'])."'";
			}
			if ($input['jenis_permintaan'] != 1){
				if ($input['pembayaran']){
					$where .= "AND lower(rec.pay_type) = '".strtolower($input['pembayaran'])."'";
				}
			}

			$data['data']		= $this->m_laporan_gudang->get_lap_penerimaan($where);

			$this->load->view('laporan_gudang/v_lap_penerimaan_01',$data);
		}
		elseif ($input['jenis_laporan'] == 5) {
			$where = "AND receiver_date between  '$tanggal_awal' and '$tanggal_akhir' AND own_id = '".$input['own_id']."' AND rec_type = '".$input['jenis_permintaan']."'";

			if ($input['supplier_id']) {
				$where .= "AND supplier_id = '".$input['supplier_id']."'";
			}

			if ($input['item_id']) {
				$where .= "AND item_id = '".$input['item_id']."'";
			}

			if ($input['sumber_anggaran']) {
				$where .= "AND lower(estimate_resource) = '".strtolower($input['sumber_anggaran'])."'";
			}

			if ($input['jenis_permintaan'] != 1){
				if ($input['pembayaran']) {
					$where .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
				}
			}


			$data['data']		= $this->m_laporan_gudang->get_lap_penerimaan_05($where);
			$this->load->view('laporan_gudang/v_lap_penerimaan_05',$data);
		}
		elseif ($input['jenis_laporan'] == 4) {
			$where1 = "AND receiver_date between '$tanggal_awal' and '$tanggal_akhir' AND own_id = '".$input['own_id']."' AND rec_type = '".$input['jenis_permintaan']."'";
			$where2 = str_replace('own_id', 'r.own_id', $where1);

			if ($input['supplier_id']) {
				$where1 .= "AND rc.supplier_id = '".$input['supplier_id']."'";
				$where2 .= "AND r.supplier_id = '".$input['supplier_id']."'";
			}

			if ($input['item_id']) {
				$where2 .= "AND item_id = '".$input['item_id']."'";
			}

			if ($input['sumber_anggaran']) {
				$where1 .= "AND lower(estimate_resource) = '".strtolower($input['sumber_anggaran'])."'";
				$where2 .= "AND lower(estimate_resource) = '".strtolower($input['sumber_anggaran'])."'";
			}

			if ($input['jenis_permintaan'] != 1){
				if ($input['pembayaran']) {
					$where1 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
					$where2 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
				}
			}



			$data['data']		= $this->m_laporan_gudang->get_lap_penerimaan_04($where1,$where2);
			$this->load->view('laporan_gudang/v_lap_penerimaan_04',$data);
		}
		elseif ($input['jenis_laporan'] == 2) {
			$where1 = "AND receiver_date between  '$tanggal_awal' and '$tanggal_akhir' AND own_id = '".$input['own_id']."' AND rec_type = '".$input['jenis_permintaan']."'";
			$where2 = str_replace('own_id', 'r.own_id', $where1);

			if ($input['supplier_id']) {
				$where1 .= "AND rc.supplier_id = '".$input['supplier_id']."'";
				$where2 .= "AND r.supplier_id = '".$input['supplier_id']."'";
			}

			if ($input['item_id']) {
				$where2 .= "AND item_id = '".$input['item_id']."'";
			}

			if ($input['sumber_anggaran']) {
				$where1 .= "AND lower(estimate_resource) = '".strtolower($input['sumber_anggaran'])."'";
				$where2 .= "AND lower(estimate_resource) = '".strtolower($input['sumber_anggaran'])."'";
			}

			if ($input['jenis_permintaan'] != 1){
				if ($input['pembayaran']) {
					$where1 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
					$where2 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
				}
			}


			$data['data']		= $this->m_laporan_gudang->get_lap_penerimaan_02($where1,$where2);
			$this->load->view('laporan_gudang/v_lap_penerimaan_02',$data);
		}
		elseif ($input['jenis_laporan'] == 6) {
			$where1 = "AND receiver_date between  '$tanggal_awal' and '$tanggal_akhir' AND rc.own_id = '".$input['own_id']."' AND rec_type = '".$input['jenis_permintaan']."'";
			$where2 = str_replace('own_id', 'r.own_id', $where1);

			if ($input['supplier_id']) {
				$where1 .= "AND rc.supplier_id = '".$input['supplier_id']."'";
				$where2 .= "AND r.supplier_id = '".$input['supplier_id']."'";
			}

			if ($input['item_id']) {
				$where2 .= "AND item_id = '".$input['item_id']."'";
			}

			if ($input['sumber_anggaran']) {
				$where1 .= "AND lower(estimate_resource) = '".strtolower($input['sumber_anggaran'])."'";
				$where2 .= "AND lower(estimate_resource) = '".strtolower($input['sumber_anggaran'])."'";
			}

			if ($input['jenis_permintaan'] != 1){
				if ($input['pembayaran']) {
					$where1 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
					$where2 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
				}
			}


			$data['data']		= $this->m_laporan_gudang->get_lap_penerimaan_06($where1,$where2);
			$this->load->view('laporan_gudang/v_lap_penerimaan_06',$data);
		}
	}

	public function stok()
	{
		$data['data'] = [];
		$this->theme('laporan_gudang/v_form_stok_perunit', $data);
	}

	public function show_laporan_stok_perunit($unit_id,$act)
	{
		$data = array();

		$data['username'] 	= $this->session->user_name;
		$data['profil'] 	= $this->m_laporan_gudang->get_profil_rs();
		$data['unit']		= $this->m_laporan_gudang->get_unit(
			array( 'unit_id' => $unit_id ),
			1
		);
		$data['datas']		= $this->m_laporan_gudang->get_data_stok( $unit_id );

		$data = array_merge(
			$data
		);
		if ($act == 'p' ){
			$this->load->view('laporan_gudang/v_stok_apotek_html', $data);
		}else{
			$namafile = "Laporan Stok Apotik per Unit.pdf";
			header("Content-type: application/vnd-ms-excel");
			header("Content-Disposition: attachment; filename=" . $namafile . ".xls");
			$this->load->view('laporan_gudang/v_stok_apotek_html', $data);
		}
	}

	public function laporan_po()
	{
		$data['data'] = [];
		$this->theme('laporan_gudang/laporan_po/v_laporan_po_form',$data);
	}

	public function cetak_po()
	{
		$jns_layanan_temp   = $this->input->post('unit_id', true);
		$tanggal   = $this->input->post('tanggal', true);
		$this->m_laporan_gudang->get_laporan_po($jns_layanan_temp,$tanggal);
	}

	public function laporan_obat_exp()
	{
		$data['data'] = [];
		$this->theme('laporan_gudang/obat_exp/v_obat_expired',$data);
	}

	public function show_lap_exp()
	{
		$data['profil'] 	= $this->m_laporan_gudang->get_profil_rs();
		$unit_id   = $this->input->post('unit_id', true);
		$tanggal   = $this->input->post('tanggal', true);
		$data['expired'] = $this->m_laporan_gudang->get_obat_exp($unit_id,$tanggal);
		$data['judul'] = "Tanggal ".$tanggal;
		$data['nama_unit'] = $this->db->query("select unit_name from admin.ms_unit where unit_id = $unit_id")->row();
		$data['username']       = $this->session->user_name;
		$mpdf = new \Mpdf\Mpdf();
		$html = $this->load->view('laporan_gudang/obat_exp/v_laporan_obat_exp',$data,true);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}

	public function form_stok_minimum()
	{
		$data['data'] = [];
		$this->theme('laporan_gudang/stok_minimum/form_stok_minimum',$data);
	}

	public function detil_stok_minimum()
	{
		$data['profil'] = $this->m_laporan_gudang->get_profil_rs();
		$data['unit']	= $this->m_laporan_gudang->get_unit_stok_min( $this->input->post('unit_id') );
		$data['datas'] =$this->m_laporan_gudang->get_detail( $this->input->post('unit_id') );
		$this->load->view('laporan_gudang/stok_minimum/v_detail_stok_minimum', $data);
	}
	public function laporan_slowfast_moving()
	{
		$data['data'] = [];
		$this->theme('laporan_gudang/laporan_slowfast_moving/v_slowfast_moving',$data);
	}
	public function show_laporan_slowfast_moving()
	{
		$tanggal = $this->input->post('tanggal',true);
		$unit_id = $this->input->post('unit_id', true);
		$tgl = explode('/', $tanggal);
		$tanggal_awal = $tgl[0];
		$tanggal_akhir = $tgl[1];
		$data['waktu'] = $tanggal_awal."sd".$tanggal_akhir;
		$data['username'] = $this->session->user_name;
		$data['rs'] = $this->m_laporan_gudang->get_profil_rs();
		$data['nama_unit'] = $this->db->query("select unit_name from admin.ms_unit where unit_id = $unit_id")->row();
		$data['slowfast'] = $this->m_laporan_gudang->get_slowfast_bulanan($unit_id, $tanggal);
		$data['judul'] = "Tanggal ".$tanggal_awal." - ".$tanggal_akhir;
		$mpdf = new \Mpdf\Mpdf();
		$html = $this->load->view('laporan_gudang/laporan_slowfast_moving/v_laporan_slowfast_moving', $data,true);
		$mpdf->WriteHTML($html);
		$mpdf->Output();

	}

	public function laporan_stok_opname()
	{
		$data['data'] = [];
		$this->theme('laporan_gudang/laporan_stok_opname/v_laporan_stok_opname',$data);
	}

	public function show_stok_opname($unit_id,$tanggal_awal,$tanggal_akhir,$act)
	{
		if($unit_id == 0){
			$data['unit_name']="SEMUA";
		}
		else{
			$data['unit_name']=$unit_id;
		}
		$data['rs'] = $this->m_laporan_gudang->get_profil_rs();
		$data['username']   = $this->session->user_name;
		$where = "";
		if ($tanggal_awal && $tanggal_akhir) {
			$where .= "and (date(opname_date) between '".date("Y-m-d", strtotime($tanggal_awal))."' and '".date("Y-m-d", strtotime($tanggal_akhir))."')";
		}

		if ($unit_id && $unit_id != 0) {
			$where .= "and unit_id = '$unit_id'";
		}

		$data['stok']         = $this->m_laporan_gudang->stok_opname($where);
		$data['judul']      = "PERIODE ".$tanggal_awal." s/d ".$tanggal_akhir;
		if ($act == 'excel'){
			$namafile = "Laporan Stok Opname";
			header("Content-type: application/vnd-ms-excel");
			header("Content-Disposition: attachment; filename=" . $namafile . ".xls");
			$this->load->view('laporan_gudang/laporan_stok_opname/v_laporan_stok_opname_all', $data);
		}else{
			$this->load->view('laporan_gudang/laporan_stok_opname/v_laporan_stok_opname_all', $data);
		}
	}

	public function retur_penerimaan_supplier()
	{
		$data['data'] = [];
		$this->theme('laporan_gudang/retur_penerimaan_supplier/v_lap_retur_supplier',$data);
	}

	public function show_retur()
	{
		$tanggal       = $this->input->post('tanggal',true);
		$unit_id                = $this->input->post('unit_id',true);
		$supplier			 = $this->input->post('supplier_id',true);
		$tgl = explode('/', $tanggal);
		$tanggal_awal = $tgl[0];
		$tanggal_akhir = $tgl[1];
		if ($unit_id==0) {
			$query_unit="";
			$data['unit']="SEMUA";
		}
		else{
			$data['unit']   =   $unit_id;
		}
		$where = " AND rr.unit_id = '$unit_id'";
		$data['waktu'] = $tanggal_awal."sd".$tanggal_akhir;
		$data['username']       = $this->session->user_name;
		$data['judul'] = "Tanggal ".$tanggal_awal." Jam 00:00:00 s/d ".$tanggal_akhir." Jam 23:59:59";
		$where .= " AND to_char(rr.rr_date,'YYYY-MM-DD') between '$tanggal_awal' AND '$tanggal_akhir'";
		$item_id = $this->input->post('item_id',true);
		if ($item_id) {
			$where .= " AND rrd.item_id = '$item_id'";
		}
		if ($supplier) {
			$where .= " And rrd.supplier_id = '$supplier'";
		}
		$data['profil']            = $this->m_laporan_gudang->get_profil_rs();
		$data['data']          = $this->m_laporan_gudang->get_data_retur($where);
		$this->load->view('laporan_gudang/retur_penerimaan_supplier/v_cetak_retur_supplier',$data);

	}

	public function stok_konsolidasoi()
	{
		$data['data'] = [];
		$this->theme('laporan_gudang/stok_konsolidasi/v_laporan_mutasi_form',$data);
	}

	public function show_laporan_stok_konsolidasi($tgl_awal,$tgl_akhir,$unit_id,$own_id,$golongan,$tampilan,$act)
	{
		$data['tampilan']       = $tampilan;
		$data['tampil']         = $tampilan;

		$where = "";
		if($unit_id !=null){
			$where .= " AND sp.unit_id = $unit_id";
		}
		if($own_id !=null){
			$where .= " AND sp.own_id = $own_id";
		}
		$unit = $this->db->query("select own_name from farmasi.ownership where own_id = $own_id")->row();
		$data['own_name'] = $unit->own_name;
//		if ($golongan=="0") {
//			$gol= " ";
//		}else{
//			$where .= " and mi.gol='$golongan'";
//		}
		$data['golongan'] = $golongan;
		$where2="";
			$where .= " AND (date(sp.date_trans) between '$tgl_awal' AND '$tgl_akhir')";
			$where2 .= " AND date(sp.date_trans) < '$tgl_awal'";
			$data['waktu'] = $tgl_awal."sd".$tgl_akhir;
//		}
		$data['username'] = $this->session->user_name;
		$data['rs']      = $this->m_laporan_gudang->get_profil_rs();
		if ($unit_id==0) {
			$data['unit_name'] = "";
		}else{
			$unit = $this->db->query("select unit_name from admin.ms_unit where unit_id = $unit_id")->row();
			$data['unit_name'] = $unit->unit_name;
		}
		$data['data'] = $this->m_laporan_gudang->get_new_konsolidasi($where,$where2,$unit_id,$own_id);
//		var_dump($data['data']);
		$data['tombol'] = $act;
		$this->load->view("laporan_gudang/stok_konsolidasi/v_laporan_mutasi2",$data);

	}

	public function laporan_pengeluaran_obat()
	{
		$data['data'] = [];
		$this->theme('laporan_gudang/laporan_pengeluaran_obat/v_pengeluaran_obat',$data);
	}

	public function show_pengeluaran_obat($unit_id,$unit_peminta,$own_id,$jns_laporan,$tgl_awal,$tgl_akhir,$act)
	{
//		var_dump($unit_id,$unit_peminta,$own_id,$jns_laporan,$tgl_awal,$tgl_akhir,$act);die();
		$data = array();

		$data['periode']	= $tgl_awal." s/d ".$tgl_akhir;

		if ($jns_laporan == 2) {
			$data['datas']		= $this->m_laporan_gudang->get_data_pobat($unit_id, $unit_peminta, $tgl_awal, $tgl_akhir, $own_id);
		}else{
			$data['datas']		= $this->m_laporan_gudang->get_data_byItem($unit_id, $unit_peminta, $tgl_awal, $tgl_akhir, $own_id);
		}

		$data['username'] 	= $this->session->user_name;
		$data['rs'] 	= $this->m_laporan_gudang->get_profil_rs();

		$data['unit_asal']	= $this->m_laporan_gudang->get_unit(
			array( 'unit_id' => $unit_id ),
			1
		);
		$data = array_merge(
			$data, $data['datas']
		);

		if ($jns_laporan == 1) {
			if ($act == 2){
				header("Content-type: application/vnd-ms-excel");
				header("Content-Disposition: attachment; filename=" . $namafile . ".xls");
			}
			$this->load->view('laporan_gudang/laporan_pengeluaran_obat/v_pengeluaran_obat_html_byItem', $data);
		}elseif($jns_laporan == 2){
			if ($act == 2){
				header("Content-type: application/vnd-ms-excel");
				header("Content-Disposition: attachment; filename=" . $namafile . ".xls");
			}
			$this->load->view('laporan_gudang/laporan_pengeluaran_obat/v_pengeluaran_obat_html', $data);
		}else{
			if ($act == 2){
				header("Content-type: application/vnd-ms-excel");
				header("Content-Disposition: attachment; filename=" . $namafile . ".xls");
			}
			$data['data'] = $this->m_laporan_gudang->get_rekapData($unit_id,$unit_peminta,$own_id,$jns_laporan,$tgl_awal,$tgl_akhir);
			$this->load->view('laporan_gudang/laporan_pengeluaran_obat/v_lap_rekap_mutasi', $data);
		}
	}

}
