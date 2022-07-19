<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_daterange();
		$this->load->model('m_stock');
	}

	public function index()
	{
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit() as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$this->load->model("m_ownership");
		foreach ($this->m_ownership->get_ownership() as $key => $value) {
			$own[$value->own_id] = $value->own_name;
		}
		$data['unit'] = $kat;
		$data['own'] = $own;
		$this->theme('stock/index',$data);
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_stock->validation()) {
			$input = [];
			foreach ($this->m_stock->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['id']) {
				$this->db->where('id',$data['id'])->update('newfarmasi.stock',$input);
			}else{
				$this->db->insert('newfarmasi.stock',$input);
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
		redirect('stock');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_stock->get_column();
		$filter['s.unit_id'] = $attr['unit_id'];
		$filter['s.own_id'] = $attr['own_id'];
		$data 	= $this->datatable->get_data($fields,$filter,'m_stock',$attr);
		$records["aaData"] = array();
		$no   	= 1 + $attr['start']; 
        foreach ($data['dataku'] as $index=>$row) { 
            $obj = array($row['id_key'],$no);
            foreach ($fields as $key => $value) {
            	if (is_array($value)) {
            		if (isset($value['custom'])){
            			$obj[] = call_user_func($value['custom'],$row);
            		}else{
            			$obj[] = $row[$key];
            		}
            	}else{
            		$obj[] = $row[$value];
            	}
            }
            $obj[] = create_btnAction(["update","delete",[
				"btn-act" => "cek_stok(".$attr['own_id'].','. $attr['unit_id']. ','.$row['item_id'].")",
				"btn-icon" => "fa fa-eye",
				"btn-class" => "btn-warning",
			] ],$row['id_key']);
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}

	public function find_one($id)
	{
		$data = $this->db->where('id',$id)->get("newfarmasi.stock")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('id',$id)->delete("newfarmasi.stock");
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
			$this->db->where('id',$value)->delete("newfarmasi.stock");
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
		$data['model'] = $this->m_stock->rules();
		$this->load->view("stock/form",$data);
	}
}
