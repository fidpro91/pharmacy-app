<?php

class M_stock_process extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", as id_key  from newfarmasi.stock_process where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", as id_key  from newfarmasi.stock_process where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"item_id",
				"own_id",
				"unit_id",
				"date_trans",
				"date_act",
				"trans_num",
				"trans_type",
				"stock_before",
				"debet",
				"kredit",
				"stock_after",
				"item_price",
				"total_price",
				"description",
				"type_act",
				"stockprocess_id"];
		return $col;
	}

	public function rules()
	{
		$data = [
					"item_id" => "trim|integer|required",
					"own_id" => "trim|integer|required",
					"unit_id" => "trim|integer|required",
					"date_trans" => "trim",
					"date_act" => "trim",
					"trans_num" => "trim",
					"trans_type" => "trim|integer",
					"stock_before" => "trim|integer",
																														"description" => "trim",
					"type_act" => "trim|integer",
					
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

	public function get_stock_process($where)
	{
		return $this->db->get_where("newfarmasi.stock_process",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.stock_process",$where)->row();
	}
}