<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tabel_srb extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_tabel_srb');
		$this->load->library("curls");
	}

	public function index()
	{
		$this->theme('tabel_srb/index','',get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_tabel_srb->validation()) {
			$input = [];
			foreach ($this->m_tabel_srb->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['srb_id']) {
				$this->db->where('srb_id',$data['srb_id'])->update('yanmed.tabel_srb',$input);
			}else{
				$this->db->insert('yanmed.tabel_srb',$input);
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
		redirect('tabel_srb');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_tabel_srb->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_tabel_srb',$attr);
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
		$data = $this->db->where('srb_id',$id)->get("yanmed.tabel_srb")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$srb = $this->db->get_Where("yanmed.tabel_srb",[
			'srb_id' => $id
		])->row();
		if (!empty($srb->no_srb)) {
			$deletePrb=$this->cursl->api_sregep("GET","delete_prb/$srb->no_srb/$srb->no_sep");
			if ($deletePrb["metaData"]["code"] != "200") {
				echo json_encode([
					"message"	=> $deletePrb["metaData"]["message"]
				]);
				exit();
			}
		}
		$this->db->where('srb_id',$id)->delete("yanmed.tabel_srb");
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
			$this->db->where('srb_id',$value)->delete("yanmed.tabel_srb");
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
		$data['model'] = $this->m_tabel_srb->rules();
		$this->load->view("tabel_srb/form",$data);
	}
}
