<?php

class M_receiving_retur extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rr_id as id_key  from newfarmasi.receiving_retur where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rr_id as id_key  from newfarmasi.receiving_retur where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"rr_id",
				"rr_date",
				"rr_status",
				"user_id",
				"num_retur",
				"rr_type"];
		return $col;
	}

	public function rules()
	{
		$data = [
										"rr_date" => "trim|required",
					"rr_status" => "trim",
					"user_id" => "trim|integer",
					"num_retur" => "trim",
					
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

	public function get_receiving_retur($where)
	{
		return $this->db->get_where("newfarmasi.receiving_retur",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.receiving_retur",$where)->row();
	}
}