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
    public function get_item()
    {
        $term = $this->input->post('term');

        $data = $this->m_laporan_penjualan->get_item($term);

        echo json_encode($data); 
    }

    public function get_patient()
	{
        $tgl = $this->input->post(); 
		list($tgl1,$tgl2) = explode('/', $tgl['tanggal']);        
        $term       = $this->input->post('term');
        $karakteristik = $this->input->post('tipe',true);
        $param  = array(
                        
                        'tgl_awal'      => $tgl1,
                        'tgl_akhir'     => $tgl2,                        
                        'term'          => $term
                    );

        $data = $this->m_laporan_penjualan->get_patient($param,$karakteristik);

        echo json_encode($data);
	}

    public function show_laporan()
	{ 	var_dump($this->input->post('unit_name',true));die;
        $tgl = $this->input->post(); 
		list($tgl1,$tgl2) = explode('/', $tgl['tanggal']);		
        $kepemilikan         = $this->input->post('kepemilikan',true);
        $catunit_id          = $this->input->post('catunit_id', true); 
        $unit_penjualan      = $this->input->post('unit_name',true);
        $karakteristik       = $this->input->post('tipe',true);
//		if (empty($karakteristik)){
//			$karakteristik = "0";
//		}
        $sale_type           = $this->input->post('tipe_bayar',true);
        $unit_layanan        = $this->input->post('unit_layanan',true);           
        $surety              = $this->input->post('surety',true);
        $bayar               = $this->input->post('status_bayar',true);
        $visit_id            = $this->input->post('visit_id',true);
        $date = " AND date(sale_date) between '".$tgl1."' and '".$tgl2."'";
        if($karakteristik == 1)
        {
            $data['data']=$this->m_laporan_penjualan->get_sale_by_doctor($unit_penjualan,$surety,$sale_type,$unit_layanan,$date); 
            $this->load->view("laporan_penjualan/v_lap_penjualan_byDokter",$data);
        }else if($karakteristik == 2) {
            $data['data']=$this->m_laporan_penjualan->get_sale_by_visit($unit_penjualan,$surety,$sale_type,$unit_layanan,$date,$visit_id); 
            $this->load->view("laporan_penjualan/v_lap_penjualan_sum_customer",$data);
        }else if($karakteristik == 3){
            $pasien = $this->input->post('px_norm');           
            $datasale = $this->m_laporan_penjualan->get_sale_by_patient($unit_penjualan,$kepemilikan,$surety,$sale_type,$unit_layanan,$pasien,$date);
            $array_data = array();
            foreach ($datasale as $rs) {
                 $array_data[] = array(
                                    'tgl_sale'      => $rs->tgl_sale,
                                    'nomor_resep'   => $rs->sale_num,
                                    'px_name'       => $rs->patient_name,
                                    'par_name'      => $rs->doctor_name,
                                    'sale_total'    => $rs->grand_total,
                                    'embalase_item_sale'    => $rs->embalase_item_sale,
                                    'sale_service'          => $rs->biaya_racik,
                                    'sr_total'          => $rs->sr_total,
                                    'detail_sale'           => $this->m_laporan_penjualan->get_sale_by_visit_patient_detail($rs->sale_id)
                                );
             } 
            $data['data']          = $array_data;
            $this->load->view('laporan_penjualan/v_lap_penjualan_byVisit',$data);
        }else if($karakteristik == 4){
            $item_id = $this->input->post('item_id',true); 
            $data['data']=$this->m_laporan_penjualan->get_sale_by_item($kepemilikan,$unit_penjualan,$surety,$sale_type,$catunit_id,$unit_layanan,$date,$item_id); 
            $this->load->view("laporan_penjualan/v_lap_penjualan_byObat",$data);
        }
}
}
