<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_patuh extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_select2()->lib_daterange();
	    $this->load->model('M_laporan_kepatuhan');
	}

	public function index()
	{
		
	    $data['data'] = [];		
		$this->theme('laporan_kepatuhan/form_laporan_kepatuhan',$data);
	}   

    public function show_laporan()
	{ 	
        if ($this->input->post('submit') == 'excel'){
			header("Content-type: application/vnd-ms-excel");
			header("Content-Disposition: attachment; filename= LAPORAN KEPATUHAN RESEP.xls");
		}
		$tgl = $this->input->post(); 
		list($tgl1,$tgl2) = explode('/', $tgl['tanggal']);
		$unit = $this->input->post('unit_name');        
		$catunit_id          = $this->input->post('jenis_px', true); 
		$unit_layanan        = $this->input->post('unit_layanan',true);  
		if($unit_layanan !=null){
			$all_layanan = "";
			foreach ($unit_layanan as $key => $value) {
				$all_layanan .= $value.",";
			}
			$layanan = rtrim($all_layanan,','); 
		 }
		 $where = "";
		$where = " AND date(r.rcp_date) between '".$tgl1."' and '".$tgl2."'";
		if (!empty($unit)) {
			$where .= " AND r.unit_id = '$unit'";
		} 
		if (!empty($layanan)) {
			
			$where .= " AND r.unit_id_layanan in ($layanan)";
		}
		$data['period1']= $tgl1;
		$data['period2']= $tgl2;
		$data['data']=$this->M_laporan_kepatuhan->get_data($where); 
		$this->load->view("laporan_kepatuhan/v_laporan_kepatuahan",$data); //print_r($data);die;
	}
}