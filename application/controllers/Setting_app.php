<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_app extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_setting_app');
	}

	public function index()
	{
		$this->theme('setting_app/index','',get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_setting_app->validation()) {
			$input = [];
			foreach ($this->m_setting_app->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['setting_id']) {
				$this->db->where('setting_id',$data['setting_id'])->update('newfarmasi.setting_app',$input);
			}else{
				$this->db->insert('newfarmasi.setting_app',$input);
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
		redirect('setting_app');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_setting_app->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_setting_app',$attr);
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
		$data = $this->db->where('setting_id',$id)->get("newfarmasi.setting_app")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('setting_id',$id)->delete("newfarmasi.setting_app");
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
			$this->db->where('setting_id',$value)->delete("newfarmasi.setting_app");
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
		$data['model'] = $this->m_setting_app->rules();
		$this->load->view("setting_app/form",$data);
	}
}
