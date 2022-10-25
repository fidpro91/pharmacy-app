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
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit(["employee_id" => $this->session->employee_id]) as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$data['unit'] = $kat;
		$this->theme('recipe/index',$data,get_class($this));
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

	public function get_recipe_detail()
	{
		$post = $this->input->post();
		$data = $this->db->query("SELECT rd.*,mi.item_name as label_item_id,racikan_id,racikan_desc,qty,s.stock_summary as stock,
		(p.price_sell::numeric+(p.price_sell::numeric*so.percent_profit)+ow.profit_item)sale_price
		FROM newfarmasi.recipe_detail rd
		JOIN admin.ms_item mi ON mi.item_id = rd.item_id
		LEFT JOIN newfarmasi.stock s ON s.item_id = rd.item_id AND s.unit_id = ".$post["unit_id"]." AND s.own_id = ".$post["own_id"]."
		LEFT JOIN farmasi.ownership ow ON ow.own_id = s.own_id
		LEFT JOIN farmasi.price p ON s.item_id = p.item_id AND s.own_id = p.own_id
		LEFT JOIN farmasi.surety_ownership so ON so.own_id = s.own_id AND so.surety_id = ".$post["surety_id"]."
		where rd.rcp_id = ".$post["rcp_id"]."
		")->result();
		echo json_encode($data);
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
<<<<<<< HEAD
					"width" => "30%",
=======
					"width" => "20%",
>>>>>>> 997196f8206048588b68615b4879684ae20d8be4
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
<<<<<<< HEAD
					"width" => '15%',
					"data" => $racikan
				];
			} else {
=======
					"width" => '10%',
					"data" => $racikan
				];
			}elseif ($value == "kronis") {
				$row[] = [
					"id" => $value,
					"label" => "Jns Obat",
					"type" => 'select',
					"width" => '10%',
					"data" => get_type_kronis()
				];
			} elseif ($value == "price_total"||$value == "dosis") {
>>>>>>> 997196f8206048588b68615b4879684ae20d8be4
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
<<<<<<< HEAD
					"width" => '10%',
=======
					"width" => '15%',
				];
			}else {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => '5%',
>>>>>>> 997196f8206048588b68615b4879684ae20d8be4
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
		$data = $this->db->where('rcp_id',$id)
						 ->join("yanmed.visit v", "v.visit_id=r.visit_id")
						 ->get("newfarmasi.recipe r")->row();

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
