<?php

class M_production_indetail extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",production_indetail_id as id_key  from farmasi.production_indetail where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",production_indetail_id as id_key  from farmasi.production_indetail where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [				
				"item_id",
				"qty_item",
				"item_price",
				"recdet_id"];
		return $col;
	}

	public function get_column_multi()
	{
		$col = [				
				"item_id",	
				"stok",		
				"item_price",
				"qty_item"
				];
		return $col;
	}

	public function rules()
	{
		$data = [
					"production_id" => "trim|integer",
					"item_id" => "trim|integer",
					"qty_item" => "trim|integer",
					"item_price" => "trim",
					"recdet_id" => "trim|integer",

				];
		return $data;
	}

	public function validation()
	{
		foreach ($this->rules() as $key => $value) {
			$this->form_validation->set_rules($key,$key,$value);
		}

		return $this->form_validation->run();
	}

	public function get_production_indetail($where)
	{
		return $this->db->get_where("farmasi.production_indetail",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.production_indetail",$where)->row();
	}
}