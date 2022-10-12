<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_retur_penjualan extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_select2()->lib_daterange();
	    $this->load->model('M_laporan_retur_penjualan');
	}

	public function index()
	{
		
	    $data['data'] = [];		
		$this->theme('laporan_penjualan/form_lap_retur_penjualan',$data);
	} 

	public function get_patient()
    {
        
		$tgl = $this->input->post(); 
		list($tgl1,$tgl2) = explode('/', $tgl['tanggal']);        
        $term       = $this->input->post('term');        
        $param  = array(
                        
                        'tgl_awal'      => $tgl1,
                        'tgl_akhir'     => $tgl2,                        
                        'term'          => $term
                    );

        $data = $this->M_laporan_retur_penjualan->get_patient($param);
        echo json_encode($data);
    }
	public function show_laporan()
	{ 	
        $tgl = $this->input->post(); //print_r($srv_type);die;
		list($tgl1,$tgl2) = explode('/', $tgl['tanggal']);		
        $kepemilikan         = $this->input->post('kepemilikan',true);
        $srv_type            = $this->input->post('jenis_px', true); 
        $unit_penjualan      = $this->input->post('unit_name',true);
        $karakteristik       = $this->input->post('tipe',true);  
        $sale_type           = $this->input->post('tipe_bayar',true);      
        $visit_id            = $this->input->post('visit_id',true);

		$where = "";
		$where = " and to_char(sr.sr_date,'YYYY-MM-DD') between '".$tgl1."' and '".$tgl2."'";
		if ($unit_penjualan) {
			$where .= " AND sr.unit_id in ($unit_penjualan)";
		}
		if(!empty($srv_type)){           
            $where .= " AND vs.srv_type = '$srv_type'";
        }
        if($sale_type){           
            $where .= " and sr.sale_type='$sale_type'";
        }

		//print_r($where);die;
        if($karakteristik == 1)
        {
            $data['data']=$this->M_laporan_retur_penjualan->get_retur_detail($where); 
            $this->load->view("laporan_penjualan/v_lap_retur_detail",$data);
        }elseif($karakteristik == 2) {
			$px_id = $this->input->post('px_id');
            // $data['pasien']        = $this->m_lap_retur_depo->get_pasien_by_id($this->input->post('px_id'));
			if ($this->input->post('px_id')){
				$where .= " and vs.px_id = $px_id";
			}
            $data['data']          = $this->M_laporan_retur_penjualan->get_retur_by_patient($where);
            $this->load->view('laporan_penjualan/v_lap_retur_penjualan_byPasien',$data);
        }elseif($karakteristik == 3) {
			$item_id = $this->input->post('item_id');
			if ($item_id){
				$where .= " and vo.item_id = $item_id";
			}

            $data['data']          = $this->M_laporan_retur_penjualan->get_retur_by_item($where);
            $this->load->view('laporan_penjualan/v_lap_retur_penjualan_by_item',$data); //print_r($data);die;
        }
	}
   
}
