<?php

class M_stock_fifo extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", as id_key  from newfarmasi.stock_fifo where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", as id_key  from newfarmasi.stock_fifo where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"item_id",
				"own_id",
				"unit_id",
				"stock_in",
				"total_price",
				"update_date",
				"stock_saldo",
				"stock_id",
				"recdet_id"];
		return $col;
	}

	public function rules()
	{
		$data = [
					"item_id" => "trim|integer|required",
					"own_id" => "trim|integer|required",
					"unit_id" => "trim|integer|required",
															"update_date" => "trim",
					"stock_saldo" => "trim|integer",
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

	public function get_stock_fifo($where)
	{
		return $this->db->get_where("newfarmasi.stock_fifo",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.stock_fifo",$where)->row();
	}
}