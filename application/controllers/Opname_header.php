<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opname_header extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
						 ->lib_inputmulti()
						 ->lib_select2()
						 ->lib_inputmask();
		$this->load->model('m_opname_header');
	}

	public function index()
	{
		$this->theme('opname_header/index');
	}

	public function show_multiRows()
	{
		$this->load->model("m_opname");
		$data = $this->m_opname->get_column_multiple();
		$colauto = ["item_id"=>"Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '50%',
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

	public function get_item()
	{
		$term = $this->input->get('term');
		$this->load->model('m_opname');
		echo json_encode($this->m_opname->get_stock_item($term));
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_opname_header->validation()) {
			$input = [];
			$this->db->trans_begin();
			foreach ($this->m_opname_header->rules() as $key => $value) {
				$input[$key] = (isset($data[$key])?$data[$key]:null);
			}
			if ($data['opname_header_id']) {
				$this->db->where('opname_header_id',$data['opname_header_id'])->update('newfarmasi.opname_header',$input);
				$this->db->where('opname_header_id',$data['opname_header_id'])->delete("newfarmasi.opname");
			}else{
				$this->db->insert('newfarmasi.opname_header',$input);
				$data['opname_header_id'] = $this->db->insert_id();
			}
			$respon = $this->save_stock_opname($data);
			$err = $this->db->error();
			if ($respon['kode'] == '01') {
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
			}else{
				$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil disimpan</div>');
			}
		}else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		}
		redirect('opname_header');

	}

	public function save_stock_opname($data)
	{
		$stockBaru = [];
		$input = [];
		$this->load->model("m_opname");
		$message = "sukses";
		foreach ($data['list_item'] as $x => $value) {
			foreach ($this->m_opname->rules() as $key => $v) {
				$input[$key] = (isset($value[$key])?$value[$key]:"");
			}
			$input["opname_header_id"] = $data["opname_header_id"];
			$input["qty_adj"] 	= ($value["qty_opname"]-$value["qty_data"]);
			$this->db->insert("newfarmasi.opname",$input);
			$opname_id = $this->db->query("SELECT currval('newfarmasi.opname_id_seq')id")->row()->id;
			$this->db->where([
						"unit_id"=>$data["unit_id"],
						"item_id"=>$value["item_id"],
						"own_id"=>$data["own_id"],
					])->update("newfarmasi.stock_fifo",[
						"stock_saldo" => 0
					]);
			$stockBaru[$x] = [
				"unit_id" => $data['unit_id'],
				"item_id" => $value['item_id'],
				"own_id" => $data['own_id'],
				"opname_id" => $opname_id,
				"stock_in"		=> $value["qty_opname"],
				"stock_saldo"	=> $value["qty_opname"],
				"total_price"	=> ($value["qty_opname"]*$value["item_price"])
			];
			$this->db->insert("newfarmasi.stock_fifo",$stockBaru[$x]);
			$err = $this->db->error();
			$message .= $err['message'];
		}
		$resp = [];
		if ($message != 'sukses') {
			$this->db->trans_rollback();
			$resp =[
				"kode" => '01',
				"message" => $err['message']
			];
		}else{
			$resp =[
				"kode" => '00',
				"message" => "Data berhasil disimpan"
			];
			$this->db->trans_commit();
		}
		return $resp;
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_opname_header->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_opname_header',$attr);
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
		$data = $this->db->where('opname_header_id',$id)->get("newfarmasi.opname_header")->row();
		$data->detail = $this->db
							 ->join("admin.ms_item mi","mi.item_id=op.item_id")
							 ->select("mi.item_name as label_item_id,mi.item_id,op.*")
							 ->get_where("newfarmasi.opname op")->result();
		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->trans_begin();
		$this->db->where('opname_header_id',$id)->delete("newfarmasi.opname");
		$this->db->where('opname_header_id',$id)->delete("newfarmasi.opname_header");
		$resp = array();
		if ($this->db->affected_rows()) {
			$this->db->trans_commit();
			$resp['message'] = 'Data berhasil dihapus';
		}else{
			$this->db->trans_rollback();
			$err = $this->db->error();
			$resp['message'] = $err['message'];
		}
		echo json_encode($resp);
	}

	public function delete_multi()
	{
		$resp = array();
		$this->db->trans_begin();
		foreach ($this->input->post('data') as $key => $value) {
			$this->db->where('opname_header_id',$value)->delete("newfarmasi.opname");
			$this->db->where('opname_header_id',$value)->delete("newfarmasi.opname_header");
			$err = $this->db->error();
			if ($err['message']) {
				$resp['message'] .= $err['message']."\n";
			}
		}
		if (empty($resp['message'])) {
			$this->db->trans_commit();
			$resp['message'] = 'Data berhasil dihapus';
		}else{
			$this->db->trans_rollback();
		}
		echo json_encode($resp);
	}

	public function show_form()
	{
		$data['model'] = $this->m_opname_header->rules();
		$this->load->view("opname_header/form",$data);
	}
}
