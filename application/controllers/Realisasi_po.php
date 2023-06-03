<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realisasi_po extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_select2()->lib_daterange();
	    $this->load->model('M_realisasi_po');
	}

	public function index()
	{
		
	    $data['data'] = [];		
		$this->theme('Realisasi_po/realisasi_po_form',$data);
	}   

    public function show_laporan()
	{ 	
        $tgl = $this->input->post(); 
		list($tgl1,$tgl2) = explode('/', $tgl['tanggal']);
        $data = array();
	
		$supplier 	= $this->input->post('supplier');
		$own_id 	= $this->input->post('own'); //print_r($own_id);die;
		
				
		$dataRealisasi 		= $this->M_realisasi_po->get_data_realisasi( $supplier, $own_id, $tgl1, $tgl2 );
		foreach( $dataRealisasi as $index=>$row ){
			if($row->po_id)
				$row->detail 			= $this->M_realisasi_po->get_data_detail( $row->po_id );
			
			$data['realisasi'][$index] = $row;  
		} 
		$this->load->view('Realisasi_po/v_realisasi', $data);
	}
}