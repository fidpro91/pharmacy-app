<?php

class M_sale_return_detail extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",srd_id as id_key  from farmasi.sale_return_detail where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",srd_id as id_key  from farmasi.sale_return_detail where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"srd_id",
				"sr_id",
				"saledetail_id",
				"item_id",
				"qty_return",
				"sale_price",
				"total_return",
				"cost_return",
				"verificated",
				"verificator_id",
				"verified_at",
				"recdet_id"];
		return $col;
	}

	public function rules()
	{
		$data = [
										"sr_id" => "trim|integer",
					"saledetail_id" => "trim|integer",
					"item_id" => "trim|integer",
					"qty_return" => "trim|integer",
					"sale_price" => "trim",
					"total_return" => "trim",
					"cost_return" => "trim",
					"verificated" => "trim",
					"verificator_id" => "trim|integer",
					"verified_at" => "trim",
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

	public function get_sale_return_detail($where)
	{
		return $this->db->get_where("farmasi.sale_return_detail",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.sale_return_detail",$where)->row();
	}
}