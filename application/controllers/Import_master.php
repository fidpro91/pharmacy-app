<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';
class Import_master extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_employee_on_unit');
	}

	public function index()
	{
		$this->theme('import_master/index','',get_class($this));
	}

    public function show_so_awal()
    {
        $this->load->view("import_master/form_so");
    }

    public function show_import_item()
    {
        $this->load->view("import_master/form_master_item");
    }

    public function import_so($value='')
	{
		$arr_file = explode('.', $_FILES['file_import']['name']);
	    $extension = end($arr_file);
	    if('csv' == $extension) {
	        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
	    } else {
	        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	    }
	 
	    $spreadsheet = $reader->load($_FILES['file_import']['tmp_name']);
	     
	    $sheetData = $spreadsheet->getActiveSheet()->toArray();
	    $sukses=$gagal=0;
	    $data=[];
	    $dataGagal="";
		$row=[];
        $this->db->trans_begin();
        $data=[];
	    foreach ($sheetData as $key => $value) {
	    	if ($key>0) {
				$cek_item = $this->db->where("item_code = '".$value[2]."'")->get("admin.ms_item");
                if ($cek_item->num_rows()>0) {
                    $cek_item = $cek_item->row();
                }

				$row = [
					"item_id" 				=> $cek_item->item_id,
					"adj_date" 			    => $this->input->post('import_date'),
					"own_id" 			    => $this->input->post('own_id'),
					"unit_id" 			    => $this->input->post('unit_id'),
					"user_id" 			    => $this->session->user_id,
					"stock_old" 			=> 0,
					"stock_after" 			=> $value[10],
					"different_qty" 		=> $value[10],
					"price_item" 		    => $value[9],
					"expired_date" 		    => (!empty($value[11])?date('Y-m-d',strtotime($value[11])):null),
					"price_total" 		    => $value[12],
					"type" 			        => "plus",
					"is_so" 		        => "t"
				];
	    		$this->form_validation->set_data($row);
	    		if ($this->validation_import()){
                    $this->db->insert('newfarmasi.adjusment_stok',$row);
                    if ($this->db->trans_status() === false) {
                        $dataGagal[] = $value[1]."-".$value[2];
	    			    $gagal++;
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
	    			    $sukses++;
                    }
	    		}else{
	    			$dataGagal[] = $value[1]."-".$value[2];
	    			$gagal++;
	    		}
	    	}
	    }

	    $message = "<b>$sukses</b> berhasil diimport,<b>$gagal</b> gagal diimport<br>";
	    if ($gagal>0) {
	    	$message .= "data gagal import :<br>".implode("||".$dataGagal);
	    }
	   	$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$message.'</div>'); 
	   	
        redirect('import_master');
	}

    public function import_master_item($value='')
	{
		$arr_file = explode('.', $_FILES['file_import']['name']);
	    $extension = end($arr_file);
	    if('csv' == $extension) {
	        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
	    } else {
	        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	    }
	 
	    $spreadsheet = $reader->load($_FILES['file_import']['tmp_name']);
	     
	    $sheetData = $spreadsheet->getActiveSheet()->toArray();
	    $sukses=$gagal=0;
	    $data=[];
	    $dataGagal="";
		$row=[];
        $this->db->trans_begin();
        if ($this->input->post('jns_import') == 't') {
            $this->db->query("truncate admin.ms_item RESTART IDENTITY;");
        }
        /* $data=[];
	    foreach ($sheetData as $key => $value) {
	    	if ($key>0) {
				$cek_item = $this->db->where("item_code = '".$value[2]."'")->get("admin.ms_item");
                if ($cek_item->num_rows()>0) {
                    $cek_item = $cek_item->row();
                }

				$row = [
					"item_id" 				=> $cek_item->item_id,
					"adj_date" 			    => $this->input->post('import_date'),
					"own_id" 			    => $this->input->post('own_id'),
					"unit_id" 			    => $this->input->post('unit_id'),
					"user_id" 			    => $this->session->user_id,
					"stock_old" 			=> 0,
					"stock_after" 			=> $value[10],
					"different_qty" 		=> $value[10],
					"price_item" 		    => $value[9],
					"expired_date" 		    => (!empty($value[11])?date('Y-m-d',strtotime($value[11])):null),
					"price_total" 		    => $value[12],
					"type" 			        => "plus",
					"is_so" 		        => "t"
				];
	    		$this->form_validation->set_data($row);
	    		if ($this->validation_import()){
                    $this->db->insert('newfarmasi.adjusment_stok',$row);
                    if ($this->db->trans_status() === false) {
                        $dataGagal[] = $value[1]."-".$value[2];
	    			    $gagal++;
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
	    			    $sukses++;
                    }
	    		}else{
	    			$dataGagal[] = $value[1]."-".$value[2];
	    			$gagal++;
	    		}
	    	}
	    }

	    $message = "<b>$sukses</b> berhasil diimport,<b>$gagal</b> gagal diimport<br>";
	    if ($gagal>0) {
	    	$message .= "data gagal import :<br>".implode("||".$dataGagal);
	    }
	   	$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$message.'</div>'); 
	   	
        redirect('import_master'); */
	}

    public function validation_import()
	{   
        $data = [
            "item_id" => "trim|required|integer",
            "adj_date" => "trim|required",
            "own_id" => "trim|required|integer",
            "unit_id" => "trim|required|integer",
            "user_id" => "trim|required|integer",
            "stock_after" => "trim|required|integer",
            "price_item" => "trim|required|integer",
            "price_total" => "trim|required|integer"
        ];
		
        foreach ($data as $key => $value) {
			$this->form_validation->set_rules($key,$key,$value);
		}

		return $this->form_validation->run();
	}
}