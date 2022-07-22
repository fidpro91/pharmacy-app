<?php

class M_setting_app extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",setting_id as id_key  from newfarmasi.setting_app where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",setting_id as id_key  from newfarmasi.setting_app where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"setting_id",
				"setting_name",
				"setting_value",
				"is_active",
				"setting_type"];
		return $col;
	}

	public function rules()
	{
		$data = [
										"setting_name" => "trim",
					"setting_value" => "trim",
										"setting_type" => "trim|integer",

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

	public function get_setting_app($where)
	{
		return $this->db->get_where("newfarmasi.setting_app",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.setting_app",$where)->row();
	}
}