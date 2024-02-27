<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_item extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_inputmulti()
						 ->lib_datatableExt()
						 ->lib_select2();
		$this->load->model('m_ms_item');
	}

	public function index()
	{
		$this->theme('ms_item/index','',get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();

		if ($this->m_ms_item->validation()) {
			$input = [];
			foreach ($this->m_ms_item->rules() as $key => $value) {
				$input[$key] = (!empty($data[$key]))?$data[$key]:null;
			}
			
			if ($data['item_id']) {
				$this->db->where('item_id',$data['item_id'])->update('admin.ms_item',$input);
			}else{
				$this->db->insert('admin.ms_item',$input);
				$insertId = $this->db->query("select currval('admin.item_id_seq')")->row();
				$data['item_id'] = $insertId->currval;
			}

			//insert price
			if ($data['list_item']) {
				$this->db->where("item_id",$data['item_id'])->delete("farmasi.price");
				for ($i=0;$i<count($data['list_item']);$i++){
					$dtown = [
						"item_id"=>$data['item_id'],
						"own_id"=>$data['list_item'][$i]['own_id'],
						"price_buy"=>$data['list_item'][$i]['price_buy'],
						"price_sell"=>$data['list_item'][$i]['price_sell'],
					];
					$this->db->insert('farmasi.price',$dtown);
				};
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
		redirect('ms_item');

	}

	public function show_multiRows()
	{
		$this->load->model("m_ms_item");
		$data = $this->m_ms_item->get_column_multiple();
		$colauto=["own_id"=>"Kepemilikan"];
		$labelprice=["price_buy"=>"Harga Beli"];
		$labelsell=["price_sell"=>"Harga Jual"];
		foreach ($data as $key => $value) { 
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'select',
					"width" => '40%',
					"data" => $this->m_ms_item->get_own("own_id as id,own_name as text")
				];
			}else if(array_key_exists($value, $labelprice)){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $labelprice[$value])),
					"type" => 'text'
				];
			}else if(array_key_exists($value, $labelsell)){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $labelsell[$value])),
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
		$fields = $this->m_ms_item->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_ms_item',$attr);
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
		$data = $this->db->where('item_id',$id)->get("admin.ms_item")->row();
		
		echo json_encode($data);
	}

	public function show_kode_kfa($id){
		$data['nama'] = $this->db->query("select nama_kfa from admin.ms_item i 
		join admin.ms_kfa a on i.kode_satusehat = a.code_kfa 
		where item_id = $id ")->row();	
		echo json_encode($data['nama']);
	}

	public function delete_row($id)
	{
		$id_price = $this->db->query("SELECT price_id as id_price FROM admin.ms_item i
		LEFT JOIN farmasi.price p on i.item_id = p.item_id
		left JOIN farmasi.ownership o on p.own_id = o.own_id
		WHERE i.item_id =$id")->result();
		if (!empty($id_price)){
			foreach ($id_price as $row){
				$this->db->where('price_id',$row->id_price)->delete("farmasi.price");
			}
		}

		$this->db->where('item_id',$id)->delete("admin.ms_item");
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
			$this->db->where('item_id',$value)->delete("admin.ms_item");
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
		$data['model'] = $this->m_ms_item->rules();
		$data['own'] = $this->db->query("select own_id, own_name from farmasi.ownership where own_active = 't'")->result();		
//		$respond= $this->m_item->get_item_by_id($id);
		$this->load->view("ms_item/form",$data);
	}

	public function package()
	{
		$term = $this->input->get('term');
		$respond= $this->m_ms_item->get_package($term);
		echo json_encode($respond);
	}

	public function get_nama_generic()
	{
		$this->load->library("curls");
		$term = $this->input->get('term');
		$respond= $this->curls->api_sregep("GET","get_obat_generic/$term");
		$item=[];
		if ($respond["metaData"]["code"] == 200) {
			foreach ($respond["response"]["list"] as $key => $value) {
				$item[] = [
					"code"	=> $value["kode"],
					"name"	=> $value["nama"],
				];
			}
		}
		echo json_encode($item);
	}

	public function get_kode_kfa()
	{		
		$term = $this->input->get('term');
		$respond= $this->m_ms_item->get_item_kfa($term);
		$item=[];		
			foreach ($respond as $key => $value) {
				$item[] = [
					"code"	=> $value->code_kfa,
					"name"	=> $value->nama_kfa,
				];
			}
		
		echo json_encode($item);
	}

	public function get_satuan()
	{
		$term 	= $this->input->get('term', true);
		$respond= $this->m_ms_item->get_satuan($term);

		echo json_encode($respond);
	}

	public function find_price($type,$id)
	{
		$data['data'] = $this->m_ms_item->get_price_detail($id);
		echo json_encode($data['data']);
	}

}
