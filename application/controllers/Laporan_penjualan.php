<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_penjualan extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_select2()->lib_daterange();
	    $this->load->model('m_laporan_penjualan');
	}

	public function index()
	{
		
	    $data['data'] = [];		
		$this->theme('laporan_penjualan/form_laporan_penjualan',$data);
	} 

    public function get_unit_layanan(){
        $catunit_id = $this->input->post('catunit_id', true); 
       
        $result = $this->m_laporan_penjualan->get_unit_layanan($catunit_id);
        echo json_encode($result);
    } 

    public function show_laporan()
	{ 	
        $tgl = $this->input->post(); 
		list($tgl1,$tgl2) = explode('/', $tgl['tanggal']);		
        $kepemilikan         = $this->input->post('kepemilikan',true);
        $catunit_id          = $this->input->post('catunit_id', true); 
        $unit_penjualan      = $this->input->post('unit_name',true);
        $karakteristik       = $this->input->post('tipe',true);  
        $sale_type           = $this->input->post('tipe_bayar',true);       
        $unit_layanan        = $this->input->post('unit_layanan',true);           
        $surety              = $this->input->post('surety',true);
        $bayar               = $this->input->post('status_bayar',true);
        $date = " AND date(sale_date) between '".$tgl1."' and '".$tgl2."'";
        if($karakteristik == 1)
        {
            $data['data']=$this->m_laporan_penjualan->get_sale_by_doctor($unit_penjualan,$surety,$sale_type,$unit_layanan,$date); 
            $this->load->view("laporan_penjualan/v_lap_penjualan_byDokter",$data);
        }else if($karakteristik == 2) {       
            $data['data']=$this->m_laporan_penjualan->get_sale_by_visit($unit_penjualan,$surety,$sale_type,$unit_layanan,$date); 
            $this->load->view("laporan_penjualan/v_lap_penjualan_sum_customer",$data);
        }else if($karakteristik == 4){
            $data['data']=$this->m_laporan_penjualan->get_sale_by_item($kepemilikan,$unit_penjualan,$surety,$sale_type,$catunit_id,$unit_layanan,$date); 
            $this->load->view("laporan_penjualan/v_lap_penjualan_byObat",$data);
        }
		 //print_r($data);die;
	}
}