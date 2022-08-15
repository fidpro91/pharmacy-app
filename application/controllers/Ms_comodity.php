<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_comodity extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_ms_comodity');
	}

	public function index()
	{
		$this->theme('ms_comodity/index','',get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_ms_comodity->validation()) {
			$input = [];
			foreach ($this->m_ms_comodity->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['comodity_id']) {
				$this->db->where('comodity_id',$data['comodity_id'])->update('admin.ms_comodity',$input);
			}else{
				$this->db->insert('admin.ms_comodity',$input);
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
		redirect('ms_comodity');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_ms_comodity->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_ms_comodity',$attr);
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
		$data = $this->db->where('comodity_id',$id)->get("admin.ms_comodity")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('comodity_id',$id)->delete("admin.ms_comodity");
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
			$this->db->where('$pkey',$value)->delete("admin.ms_comodity");
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
		$data['model'] = $this->m_ms_comodity->rules();
		$this->load->view("ms_comodity/form",$data);
	}
}
