<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class laporan_produksi extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_select2()->lib_daterange();
	    $this->load->model('m_production');
	}

	public function index()
	{
		
	    $data['data'] = [];		
		$this->theme('production/laporan_form_produksi',$data);
	}   

    public function show_laporan()
	{ 	
        $tgl = $this->input->post(); 
		list($tgl1,$tgl2) = explode('/', $tgl['tanggal']);
		$unit = $this->input->post('unit_name');
        $where = " AND date(p.production_date) between '".$tgl1."' and '".$tgl2."'";
		if (!empty($unit)) {
			$where .= " AND p.unit_id = '$unit'";
		}        
		$data['data']=$this->m_production->get_laporan($where); 
		$this->load->view("production/v_laporan_produksi",$data); //print_r($data);die;
	}
}