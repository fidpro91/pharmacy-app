<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
//			var_dump($data);
//
//
//			$this->load->library('../controllers/farmasi/laporan/penerimaan_gudang');
//
//			$data['username'] 	= $this->userData['username'];
//			$data['profil'] 	= $this->get_data_profil();
//			$data = array_merge(
//				$data, $this->penerimaan_gudang->get_data_penerimaan_gudang( $this->input->post() )
//			);
//
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
			if ($input['pembayaran']){
				$where .= "AND lower(rec.pay_type) = '".strtolower($input['pembayaran'])."'";
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

			if ($input['pembayaran']) {
				$where .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
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

			if ($input['pembayaran']) {
				$where1 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
				$where2 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
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

			if ($input['pembayaran']) {
				$where1 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
				$where2 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
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

			if ($input['pembayaran']) {
				$where1 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
				$where2 .= "AND lower(pay_type) = '".strtolower($input['pembayaran'])."'";
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

	public function show_laporan_stok_perunit()
	{
		
	}
}
