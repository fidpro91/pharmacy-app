<?php

class M_production extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",production_id as id_key  from farmasi.production p
				left join farmasi.ownership o on p.own_id = o.own_id
				 where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",production_id as id_key  from farmasi.production  p
				left join farmasi.ownership o on p.own_id = o.own_id
				 where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [				
				"production_no",
				"production_date",				
				"own_name"=>["label"=>"kepemilikan"],
				];
		return $col;
	}

	public function rules()
	{
		$data = [
					"production_no" => "trim",
					"production_date" => "trim",
					"unit_id" => "trim|integer",
					"own_id" => "trim|integer",
					"production_note" => "trim",
					"production_status" => "trim|integer",
					"user_id" => "trim|integer",
					"rec_unit_pro" => "trim|integer",

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

	public function get_production($where)
	{
		return $this->db->get_where("farmasi.production",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.production",$where)->row();
	}
}