<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recipe extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
						->lib_inputmulti()
						->lib_select2()
						->lib_inputmask();
		$this->load->model('m_recipe');
	}

	public function index()
	{
		$this->theme('recipe/index','',get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_recipe->validation()) {
			$input = [];
			foreach ($this->m_recipe->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['rcp_id']) {
				$this->db->where('rcp_id',$data['rcp_id'])->update('newfarmasi.recipe',$input);
			}else{
				$this->db->insert('newfarmasi.recipe',$input);
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
		redirect('recipe');

	}

	public function show_form($id)
	{
		$data["item"]  = $this->db->query("
			SELECT sd.*,sd.sale_price::numeric as sale_price,mi.item_name as label_item_id,st.stock_summary as stock,
			(sd.sale_qty*sd.sale_price)::numeric as price_total FROM farmasi.sale_detail sd
			JOIN admin.ms_item mi ON sd.item_id = mi.item_id
			JOIN farmasi.sale s on sd.sale_id = s.sale_id
			JOIN newfarmasi.stock st ON st.item_id = sd.item_id and st.own_id = sd.own_id and st.unit_id = s.unit_id
			WHERE sd.sale_id = '$id'
		")->result();
		$data['recipe_id'] = $id;
		$data['model'] 	 = $this->m_recipe->rules();
		$this->load->view("recipe/form", $data);
	}

	public function show_multiRows($rcp_id = 0)
	{
		$this->load->model("m_recipe_detail");
		$data = $this->m_recipe_detail->get_column_multiple();
		$colauto = ["item_id" => "Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '30%',
				];
			} elseif ($value == "sale_price" || $value == "stock") {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => '10%',
					"attr" => [
						"readonly" => 'readonly',
						"data-inputmask" => "'alias': 'IDR'"
					]
				];
			} elseif ($value == "price_total") {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => "30%",
					"attr" => [
						"readonly" => "readonly",
						"data-inputmask" => "'alias': 'IDR'"
					]
				];
			} elseif ($value == "racikan_id") {
				$racikan = $this->db->query(
					"select distinct coalesce(racikan_id,'') as id,racikan_id as text from newfarmasi.recipe_detail where rcp_id = '$rcp_id'"
				)->result();
				$row[] = [
					"id" => $value,
					"label" => "Racikan",
					"type" => 'select',
					"width" => '15%',
					"data" => $racikan
				];
			} else {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => '10%',
				];
			}
		}
		echo json_encode($row);
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_recipe->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_recipe',$attr);
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
		$data = $this->db->where('rcp_id',$id)->get("newfarmasi.recipe")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('rcp_id',$id)->delete("newfarmasi.recipe");
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
			$this->db->where('rcp_id',$value)->delete("newfarmasi.recipe");
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
}
