<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dose extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_dose');
	}

	public function index()
	{
		$this->theme('dose/index');
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_dose->validation()) {
			$input = [];
			foreach ($this->m_dose->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['dose_id']) {
				$this->db->where('dose_id',$data['dose_id'])->update('farmasi.dose',$input);
			}else{
				$this->db->insert('farmasi.dose',$input);
			}
			$err = $this->db->error();
			if ($err['message']) {
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
			}else{
				$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil disimpan</div>');
			}
		}else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		}
		redirect('dose');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_dose->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_dose',$attr);
		$records["aaData"] = array();
		$no   	= 1 + $attr['start']; 
        foreach ($data['dataku'] as $index=>$row) { 
            $obj = array($row['id_key'],$no);
            foreach ($fields as $key => $value) {
            	if (is_array($value)) {
            		if (isset($value['custom'])){
            			$obj[] = call_user_func($value['custom'],$row[$key]);
            		}else{
            			$obj[] = $row[$key];
            		}
            	}else{
            		$obj[] = $row[$value];
            	}
            }
            $obj[] = create_btnAction(["update","delete"],$row['id_key']);
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}

	public function find_one($id)
	{
		$data = $this->db->where('dose_id',$id)->get("farmasi.dose")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('dose_id',$id)->delete("farmasi.dose");
		$resp = array();
		if ($this->db->affected_rows()) {
			$resp['message'] = 'Data berhasil dihapus';
		}else{
			$err = $this->db->error();
			$resp['message'] = $err['message'];
		}
		echo json_encode($resp);
	}

	public function delete_multi()
	{
		$resp = array();
		foreach ($this->input->post('data') as $key => $value) {
			$this->db->where('dose_id',$value)->delete("farmasi.dose");
			$err = $this->db->error();
			if ($err['message']) {
				$resp['message'] .= $err['message']."\n";
			}
		}
		if (empty($resp['message'])) {
			$resp['message'] = 'Data berhasil dihapus';
		}
		echo json_encode($resp);
	}

	public function show_form()
	{
		$data['model'] = $this->m_dose->rules();
		$this->load->view("dose/form",$data);
	}
}
