<?php

class M_ms_unit extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",unit_id as id_key from admin.ms_unit where unit_type in (34,32) $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",unit_id as id_key from admin.ms_unit where unit_type in (34,32) $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"unit_id",
				"unit_code",
				"unit_name",
				"unit_nickname",
				"unit_level",
				"unit_islast",
				"unit_active",
				"unit_type",
				"unit_inpatient_status",
				"unit_support_status",
				"unit_id_parent",
				"ut_id",
				"kodeaskes",
				"inap",
				"is_vip",
				"no_retrib",
				"group_finder",
				"show_pm"];
		return $col;
	}

	public function rules()
	{
		$data = [
										"unit_code" => "trim",
					"unit_name" => "trim|required",
					"unit_nickname" => "trim",
																																								"ut_id" => "trim|integer",
					"kodeaskes" => "trim",
					"inap" => "trim|integer",
															"group_finder" => "trim",
					"show_pm" => "trim",

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

	public function get_ms_unit($where=[0=>0])
	{
		return $this->db->where(["unit_active"=>'t'])
						->where("unit_type in (34,32)")
						->join("hr.employee_on_unit eo","eo.unit_id=mu.unit_id")
						->get_where("admin.ms_unit mu",$where)->result();
	}

	public function get_ms_unit_all($where=[0=>0])
	{
		return $this->db->where(["unit_active"=>'t'])
						->get_where("admin.ms_unit",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("admin.ms_unit",$where)->row();
	}
}