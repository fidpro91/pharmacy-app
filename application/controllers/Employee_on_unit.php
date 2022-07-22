<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_on_unit extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_employee_on_unit');
	}

	public function index()
	{
		$this->theme('employee_on_unit/form','',get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_employee_on_unit->validation()) {
			$input = [];
			foreach ($this->m_employee_on_unit->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['employee_id']) {
				$this->db->where('employee_id',$data['employee_id'])->update('hr.employee_on_unit',$input);
			}else{
				$this->db->insert('hr.employee_on_unit',$input);
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
		redirect('employee_on_unit');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_employee_on_unit->get_column();
		$filter=[];
		if ($attr['unit_id']>0) {
			$filter["eo.unit_id"] = $attr['unit_id'];
		}
		$data 	= $this->datatable->get_data($fields,$filter,'m_employee_on_unit',$attr);
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
            $obj[] = "";
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}

	public function find_one($id)
	{
		$data = $this->db->where('employee_id',$id)->get("hr.employee_on_unit")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('employee_id',$id)->delete("hr.employee_on_unit");
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
			$this->db->where('employee_id',$value)->delete("hr.employee_on_unit");
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
		$data['model'] = $this->m_employee_on_unit->rules();
		$this->load->view("employee_on_unit/form",$data);
	}

	public function insert_right()
	{
		$this->db->trans_begin();
		foreach ($this->input->post('id_emp') as $key => $value) {
			$data[$key] = [
				"employee_id" 		=> $value,
				"unit_id" 			=> $this->input->post('unit_id'),
				"set_by"			=> $this->session->user_id
			];
		}
		$this->db->insert_batch('hr.employee_on_unit', $data);
		$err = $this->db->error();
		if ($err['message']) {
			$this->db->trans_rollback();
			$resp['message'] = $err['message'];
			$resp['code'] = $err['01'];
		} else {
			$this->db->trans_commit();
			$resp['message'] 	= "Data berhasil disimpan";
			$resp['code'] 		= "00";
		}
		echo json_encode($resp);
	}

	public function insert_left()
	{
		$this->db->trans_begin();
		foreach ($this->input->post('id') as $key => $value) {
			list($unit_id,$employee_id) = explode('|',$value);
			$this->db->where([
				"unit_id"		=> $unit_id,
				"employee_id"	=> $employee_id,
			])->delete("hr.employee_on_unit");
			$err = $this->db->error();
		}
		if ($err['message']) {
			$this->db->trans_rollback();
			$resp['message'] = $err['message'];
			$resp['code'] = $err['01'];
		} else {
			$this->db->trans_commit();
			$resp['message'] 	= "Data berhasil disimpan";
			$resp['code'] 		= "00";
		}
		echo json_encode($resp);
	}
}
