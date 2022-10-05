<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class C_laporan_imut extends MY_Generator
{
	public function __construct()
	{
		parent::__construct();
		$this->datascript
			->lib_select2()
			->lib_daterange();
		$this->load->model('M_laporan_imut');
	}
	public function index()
	{
		$idEmpl=$this->session->user_id;
		$data['data'] = [];
		$this->theme('laporan_imutu/v_laporan_imut',$data);
	}

	public function show_laporan()
	{
		$tanggal			= $this->input->post('tanggal', true);
		$unit_penjualan     = $this->input->post('unit_name',true);
		$tipe_patient       = $this->input->post('tipe_patient',true);
		$kepemilikan      	= $this->input->post('kepemilikan',true);
		$surety       		= $this->input->post('surety',true);
		$gol_laporan        = $this->input->post('gol_laporan',true);
		$unit_id            = "";
		$where 				= "";

		if($unit_penjualan !=null){
			$unit_id = implode(',',$unit_penjualan);
			$where .= " AND s.unit_id in ($unit_id)";
		}
		if($surety != null){
			$where .= " AND s.surety_id = $surety";
		}
		$myArray = explode('/',$tanggal);
		$tglPeriodikDari       = date("Y-m-d H:i:s", strtotime($myArray[0]));
		$tglPeriodikSampai       = date("Y-m-d H:i:s", strtotime($myArray[1]));
		$data['waktu'] = $tglPeriodikDari."sd".$tglPeriodikSampai;
		$data['username']       = $this->userData['username'];
		$data['judul'] = "Tanggal ".$myArray[0]." Jam 00:00:00 s/d ".$myArray[1]." Jam 23:59:59";
		$where .= " AND (to_char(s.date_act,'YYYY-MM-DD HH24:MI:SS') between '$tglPeriodikDari' AND '$tglPeriodikSampai')";

		$data['rs']     = $this->m_laporan_imut->get_data_rs($where);

		if ($kepemilikan) {
			$where .= "and s.own_id = $kepemilikan";
		}
		if ($tipe_patient == '0') {
			$where .= " AND v.visit_id is null";
		}elseif ($tipe_patient == 1) {
			$where .= " AND v.visit_id is not null";
		}
		if ($gol_laporan == '0') {
			$data['data'] = $this->m_laporan_imut->get_data_lap($where);
			$this->load->view('farmasi/laporan/v_lap_farmasi_imut',$data);
		}elseif($gol_laporan == 1){
			$data['data'] = $this->m_laporan_imut->get_rekap_dt($where);
			$this->load->view('farmasi/laporan/v_lap_rekap_imut',$data);
		}elseif($gol_laporan == 2){
			$data['data'] = $this->m_laporan_imut->get_data_by_dokter($where);
			$this->load->view('farmasi/laporan/v_lap_imut_bydokter',$data);
		}elseif($gol_laporan == 3){
			$data['data'] = $this->m_laporan_imut->get_data_by_pasien($where);
			$this->load->view('farmasi/laporan/v_laporan_terapi_obat',$data);
		}
	}
}
