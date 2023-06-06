<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Respondtime_sale extends MY_Generator
{
	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_daterange();
		$this->load->model('M_respondtime_sale');
	}

	public function index()
	{
		$data['data'] = [];
		$this->theme('laporan_respondtime_sale/v_respond_time_sale',$data);
	}

	public function show_laporan()
	{
		$data = $this->input->post();
		list($tgl1,$tgl2) = explode('/', $data['tanggal']);
		$unit = $data['unit_name'];
		$jns_resep = $data['jns_resep'];
		$jenis_layana = $data['jenis_layanan'];

		$swhere = "";

		if ($unit!=='') {
			$unit_id = $unit;
			$units = $this->db->query("select unit_name from admin.ms_unit where unit_id = $unit_id")->row();
			$unit_name = $units->unit_name;
			$swhere .= "and s.unit_id = '$unit_id'";
		}else{
			$unit_name = "Semua";
		}
		if ($jns_resep !== ''){
			if ($jns_resep == 2){
				$swhere .= "and s.sale_services::numeric <= 0";
			}else{
				$swhere .= "and s.sale_services::numeric > 0";
			}
		}
		if ($jenis_layana !== ''){
			if ($jenis_layana == 1){
				$swhere .= " AND (vs.srv_type in ('RJ','IGD') or s.visit_id is null)";
			}else{
				$swhere .= " AND (vs.srv_type in ('RI'))";
			}
		}

		$tglPeriodikDari = $tgl1;
		$tglPeriodikSampai = $tgl2;
		$swhere .=" AND to_char(s.date_act,'YYYY-MM-DD') between '$tglPeriodikDari' and '$tglPeriodikSampai'";
		$data['unit']=$unit_name;
		$data['judul'] = "Tanggal ".$tgl1." Jam 00:00:00 s/d ".$tgl2." Jam 23:59:59";
		$data['data']          = $this->M_respondtime_sale->get_respondtime_sale($swhere);

		$this->load->view('laporan_respondtime_sale/v_laporan_respondtime_sale',$data);

	}
}
