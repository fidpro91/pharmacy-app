<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjusment_stok extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_adjusment_stok');
	}

	public function index()
	{
		$this->theme('adjusment_stok/index','',get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();
		$data["user_id"] = $this->session->user_id;
		if ($data["stock_old"]>$data["stock_after"]) {
			$data["type"] = "minus";
		}else{
			$data["type"] = "plus";
		}
		$this->form_validation->set_data($data);
		if ($this->m_adjusment_stok->validation()) {
			$input = [];
			foreach ($this->m_adjusment_stok->rules() as $key => $value) {
				$input[$key] = (!empty($data[$key])?$data[$key]:null);
			}
			if ($data['adj_id']) {
				$this->db->where('adj_id',$data['adj_id'])->update('newfarmasi.adjusment_stok',$input);
			}else{
				$this->db->insert('newfarmasi.adjusment_stok',$input);
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
		$fields = $this->m_adjusment_stok->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_adjusment_stok',$attr);
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
		$data = $this->db->where('adj_id',$id)->get("newfarmasi.adjusment_stok")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('adj_id',$id)->delete("newfarmasi.adjusment_stok");
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
			$this->db->where('adj_id',$value)->delete("newfarmasi.adjusment_stok");
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

	public function show_form($item_id,$unit_id,$own_id)
	{
		$data['model'] = $this->m_adjusment_stok->rules();
		$data['item']  = $this->db->order_by("stockprocess_id","desc")->get_where("newfarmasi.stock_process",[
							"item_id"	=> $item_id,
							"unit_id"	=> $unit_id,
							"own_id"	=> $own_id,
						])->row(); //print_r($data['item']);die;
		$this->load->view("adjusment_stok/form",$data);
	}
}
