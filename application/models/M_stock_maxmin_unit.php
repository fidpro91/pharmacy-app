<?php

class M_stock_maxmin_unit extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",id as id_key  from farmasi.stock_maxmin_unit where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",id as id_key  from farmasi.stock_maxmin_unit where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"id",
				"unit_id",
				"own_id",
				"item_id",
				"stock_max",
				"stock_min"];
		return $col;
	}

	public function rules()
	{
		$data = [
										"unit_id" => "trim|integer",
					"own_id" => "trim|integer|required",
					"item_id" => "trim|integer|required",
					"stock_max" => "trim",
					"stock_min" => "trim",

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

	public function get_stock_maxmin_unit($where)
	{
		return $this->db->get_where("farmasi.stock_maxmin_unit",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.stock_maxmin_unit",$where)->row();
	}
}