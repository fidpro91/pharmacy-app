<?php

class M_ms_group extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",group_id as id_key  from admin.ms_group where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",group_id as id_key  from admin.ms_group where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"group_id",
				"group_code",
				"group_name",
				"group_active",
				"group_desc"];
		return $col;
	}

	public function rules()
	{
		$data = [
					"group_code" => "trim|required",
					"group_name" => "trim|required",
					"group_active" => "trim",
					"group_desc" => "trim",

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

	public function get_ms_group($where=null)
	{
		return $this->db->where("group_desc","6")->get_where("admin.ms_group",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("admin.ms_group",$where)->row();
	}
}