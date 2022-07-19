<?php

class M_mutation_detail extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",mutation_detil_id as id_key  from newfarmasi.mutation_detail where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",mutation_detil_id as id_key  from newfarmasi.mutation_detail where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"mutation_detil_id",
				"mutation_id",
				"item_id",
				"qty_request",
				"qty_pack",
				"qty_send",
				"is_approved",
				"is_usage"];
		return $col;
	}

	public function get_column_multiple()
	{
		$col = [
				"item_id",
				"qty_send",
				"stock_unit",
				"unit_pack",
				"item_unit"
			];
		return $col;
	}

	
	public function get_column_multiple_permintaan()
	{
		$col = [
				"item_id",
				"stock_unit",
				"qty_request",
				"qty_pack"
			];
		return $col;
	}



	public function get_column_multiple_bon()
	{
		$col = [
				"item_id",
				"qty_send",
				"stock_unit",
				"unit_pack",
				"item_unit",
				"qty_request",
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"mutation_id" => "trim|integer|required",
					"item_id" => "trim|integer|required",
					"qty_send" => "trim|integer",
					"qty_send" => "trim|integer",
					"qty_request" => "trim|integer",
					"stock_unit" => "trim|integer",
					"is_approved" => "trim",
					"is_usage" => "trim",
					"expired_date" => "trim",

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

	public function get_mutation_detail($where)
	{
		return $this->db->get_where("newfarmasi.mutation_detail",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.mutation_detail",$where)->row();
	}
}