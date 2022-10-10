<?php

class M_recipe extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rcp_id as id_key  from newfarmasi.recipe where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rcp_id as id_key  from newfarmasi.recipe where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"rcp_id",
				"rcp_date",
				"services_id",
				"rcp_status",
				"user_id",
				"doctor_id",
				"unit_id",
				"rcp_no",
				"diagnosa_id",
				"verificated",
				"verificator_id",
				"verified_at",
				"racikan_txt",
				"px_id",
				"visit_id"];
		return $col;
	}

	public function rules()
	{
		$data = [
										"rcp_date" => "trim",
					"services_id" => "trim|integer|required",
					"rcp_status" => "trim|integer",
					"user_id" => "trim|integer|required",
					"doctor_id" => "trim|integer",
					"unit_id" => "trim|integer",
					"rcp_no" => "trim",
					"diagnosa_id" => "trim|integer",
					"verificated" => "trim",
					"verificator_id" => "trim|integer",
					"verified_at" => "trim",
					"racikan_txt" => "trim",
					"px_id" => "trim|integer",
					"visit_id" => "trim|integer",

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

	public function get_recipe($where)
	{
		return $this->db->get_where("newfarmasi.recipe",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.recipe",$where)->row();
	}
}