<?php

class M_employee_on_unit extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",concat(eo.unit_id,'|',e.employee_id) as id_key from hr.employee_on_unit eo
				join admin.ms_unit mu on mu.unit_id = eo.unit_id
				join hr.employee e on eo.employee_id = e.employee_id where unit_type in (34,32) $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",concat(eo.unit_id,'|',e.employee_id) as id_key from hr.employee_on_unit eo
				join admin.ms_unit mu on mu.unit_id = eo.unit_id
				join hr.employee e on eo.employee_id = e.employee_id 
				where unit_type in (34,32) $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"employee_id"=>[
					"initial" => "e"
				],
				"employee_nip",
				"employee_name",
				"employee_bt",
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"employee_id" => "trim|integer|required",
					"unit_id" => "trim|integer|required",
					"set_on" => "trim",
					"set_by" => "trim|integer",

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

	public function get_employee_on_unit($where)
	{
		return $this->db->get_where("hr.employee_on_unit",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("hr.employee_on_unit",$where)->row();
	}
}