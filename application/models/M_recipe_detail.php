<?php

class M_recipe_detail extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rcpdet_id as id_key  from newfarmasi.recipe_detail where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rcpdet_id as id_key  from newfarmasi.recipe_detail where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"rcpdet_id",
				"rcp_id",
				"item_id",
				"qty",
				"dosis",
				"racikan",
				"racikan_desc",
				"kronis",
				"racikan_qty",
				"racikan_id",
				"racikan_dosis"];
		return $col;
	}

	public function get_column_multiple()
	{
		$col = [
				"item_id",
				"racikan_id",
				"qty",
				"racikan_qty",
				"stock",			
				"sale_price",
				"dosis",
				"kronis",
				"price_total"
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"rcp_id" => "trim|integer|required",
					"item_id" => "trim|integer",
					"qty" => "trim|integer|required",
					"dosis" => "trim",
					"racikan" => "trim",
					"racikan_desc" => "trim",
					"kronis" => "trim",
					"racikan_qty" => "trim|integer",
					"racikan_id" => "trim",
					"racikan_dosis" => "trim",

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

	public function get_recipe_detail($where)
	{
		return $this->db->get_where("newfarmasi.recipe_detail",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.recipe_detail",$where)->row();
	}
}