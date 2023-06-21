<?php

class M_adjusment_stok extends CI_Model
{

	public function get_data($sLimit, $sWhere, $sOrder, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",adj_id as id_key  from newfarmasi.adjusment_stok where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",adj_id as id_key  from newfarmasi.adjusment_stok where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
			"adj_id",
			"adj_date",
			"user_id",
			"item_id",
			"own_id",
			"unit_id",
			"stock_old",
			"stock_after",
			"different_qty",
			"price_item",
			"price_total"
		];
		return $col;
	}

	public function rules()
	{
		$data = [
			"adj_date" => "trim|required",
			"user_id" => "trim|integer|required",
			"item_id" => "trim|integer|required",
			"own_id" => "trim|integer|required",
			"unit_id" => "trim|integer|required",
			"stock_old" => "trim|integer",
			"stock_after" => "trim|integer",
			"different_qty" => "trim|integer",
			"price_item" => "trim",
			"price_total" => "trim",
			"type" => "trim",

		];
		return $data;
	}

	public function validation()
	{
		foreach ($this->rules() as $key => $value) {
			$this->form_validation->set_rules($key, $key, $value);
		}

		return $this->form_validation->run();
	}

	public function get_adjusment_stok($where)
	{
		return $this->db->get_where("newfarmasi.adjusment_stok", $where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.adjusment_stok", $where)->row();
	}
}
