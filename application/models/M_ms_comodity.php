<?php

class M_ms_comodity extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",comodity_id as id_key  from admin.ms_comodity where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", comodity_id as id_key  from admin.ms_comodity where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
//				"comodity_id",
				"comodity_code",
				"comodity_name",
				"comodity_active"=>[
					"label"=>'status',
					"custom"=>function($a){
						if ($a == 't'){
							$condition = ["class" => "label-success", "text" => "Aktif"];
						}else{
							$condition = ["class" => "label-danger", "text" => "Tidak Aktif"];
						}
						return label_status($condition);
					}
				]
		];
		return $col;
	}

	public function rules()
	{
		$data = [
			"comodity_code" => "trim|required",
			"comodity_name" => "trim|required",
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

	public function get_ms_comodity($where)
	{
		return $this->db->get_where("admin.ms_comodity",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("admin.ms_comodity",$where)->row();
	}
}
