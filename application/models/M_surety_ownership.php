<?php

class M_surety_ownership extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",id_key from( select
					surety_name,own_name,priority,percent_profit,sw.own_id as id_key
					 from farmasi.surety_ownership sw 
				left join  yanmed.ms_surety s on sw.surety_id = s.surety_id
				left join farmasi.ownership o on sw.own_id = o.own_id where 0=0 $sWhere $sOrder $sLimit)x
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
		select ".implode(',', $aColumns).",id_key from( select
			surety_name,own_name,priority,percent_profit,sw.own_id as id_key
			 from farmasi.surety_ownership sw 
		left join  yanmed.ms_surety s on sw.surety_id = s.surety_id
		left join farmasi.ownership o on sw.own_id = o.own_id where 0=0 $sWhere)x
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"surety_name",
				"own_name",
				"priority",
				"percent_profit"];
		return $col;
	}

	public function rules()
	{
		$data = [
					"surety_id" => "trim|integer|required",
					"own_id" => "trim|integer|required",
					"priority" => "trim|integer",
					"percent_profit" => "trim|numeric",

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

	public function get_surety_ownership($where)
	{
		return $this->db->get_where("farmasi.surety_ownership",$where)->result();
	}

	public function get_kepemilikan($where)
	{
		return $this->db->get_where("farmasi.ownership",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.surety_ownership",$where)->row();
	}
}