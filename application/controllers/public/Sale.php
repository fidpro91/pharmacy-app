<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';
class Sale extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library("curls");
		$this->load->model('m_sale');
		$this->load->model('m_sale_detail');
	}

	public function index(){
		var_dump("tes");die;
	}
	

	public function strukapotikresep($sale_id, $unit_id, $type)
	{
		$detailrs 				= $this->m_sale->rumah_sakit();
		$detailpasien 			= $this->m_sale->get_detail_patient($sale_id);
		$data['detailrs'] 		= $detailrs;
		$data['detailcetak'] 	= $detailpasien;
		$data['listresep'] 		= $this->m_sale->resep_dijual2($sale_id);
		$data['pencetak'] 		=  $this->m_sale->get_employee($this->session->employee_id);
		
		if ($type == 1) {
			$mpdf = new \Mpdf\Mpdf([
				'format' => 'A4', // Atur format halaman, bisa 'A4', 'Letter', dll.
                'orientation' => 'P',
            ]);
			$name = $detailpasien['namapasien'].".PDF";
			$html = $this->load->view('sale/v_cetakanresep3', $data, true);		
			$mpdf->WriteHTML($html);
			$mpdf->Output($name,'D');
		} else {
			$this->load->view('sale/v_cetakanresep2', $data);
		}
	}
	


}
