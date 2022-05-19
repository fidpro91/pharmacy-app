<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receiving_retur extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_receiving_retur');
	}

	public function index()
	{
		$this->theme('receiving_retur/index');
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_receiving_retur->validation()) {
			$input = [];
			foreach ($this->m_receiving_retur->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['rr_id']) {
				$this->db->where('rr_id',$data['rr_id'])->update('newfarmasi.receiving_retur',$input);
			}else{
				$this->db->insert('newfarmasi.receiving_retur',$input);
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
		redirect('receiving_retur');

	}

	public function show_multiRows()
	{
		$this->load->model("m_receiving_retur_detil");
		$data = $this->m_mutation_detail->get_column_multiple();
		$colauto = ["item_id"=>"Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '40%',
				];
			}else{
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text'
				];
			}
		}
		echo json_encode($row);
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_receiving_retur->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_receiving_retur',$attr);
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
		$data = $this->db->where('rr_id',$id)->get("newfarmasi.receiving_retur")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('rr_id',$id)->delete("newfarmasi.receiving_retur");
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
			$this->db->where('rr_id',$value)->delete("newfarmasi.receiving_retur");
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
		$data['model'] = $this->m_receiving_retur->rules();
		$this->load->view("receiving_retur/form",$data);
	}
}
