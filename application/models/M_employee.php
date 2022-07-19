<?php

class M_employee extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",employee_id as id_key from hr.employee 
				where 0=0 and empcat_id = 12
				$sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",employee_id as id_key from hr.employee 
				where 0=0 and empcat_id = 12
				$sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				// "employee_id",
				"employee_nik",
				"employee_nip",
				"employee_name",
				"employee_sex",
				"employee_ft",
				"employee_bt",
				"employee_active",
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"employee_id" => "trim|integer|required",
					"absen_code" => "trim",
					"employee_nik" => "trim",
					"employee_nip" => "trim",
					"employee_name" => "trim|required",
					"empcat_id" => "trim|integer",
					"employee_active" => "trim|required",
					"employee_ft" => "trim",
					"employee_bt" => "trim",
															"employee_type" => "trim",
					"employee_jabatan" => "trim",
					"employee_pendidikan" => "trim",
					"employee_tmp_tgl_lahir" => "trim",
					"employee_tmt" => "trim",
					"employee_address" => "trim",
					"employee_salary" => "trim",
					"signature" => "trim",
					"kodehfis" => "trim|integer",

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

	public function get_employee($where)
	{
		return $this->db->get_where("hr.employee",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("hr.employee",$where)->row();
	}
}