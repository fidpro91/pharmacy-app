<?php

class M_dose extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",dose_id as id_key  from farmasi.dose where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",dose_id as id_key  from farmasi.dose where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				//"dose_id",
				"dose_code",
				"dose_name",
				"dose_active"=>["label"=>"status",
				"custom"=> function($a){
					if($a == 't'){
						$condition =["class"=>"label-primary","text"=>"Aktif"];
					}else{
						$condition =["class"=>"label-danger","text"=>"Non aktif"];
					}
					return label_status($condition);
				}]
			];
		return $col;
	}

	public function rules()
	{
		$data = [
										"dose_code" => "trim",
					"dose_name" => "trim",
					"dose_active" => "trim",

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

	public function get_dose($where)
	{
		return $this->db->get_where("farmasi.dose",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.dose",$where)->row();
	}
}