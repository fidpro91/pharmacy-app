<?php

class M_opname_header extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",opname_header_id as id_key  from newfarmasi.opname_header oh
				join farmasi.ownership o on oh.own_id = o.own_id where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",opname_header_id as id_key  from newfarmasi.opname_header oh
				join farmasi.ownership o on oh.own_id = o.own_id where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				//"opname_header_id",
				"opname_no",
				"opname_date",				
				"own_name"=>["label"=>"Kepemilikan"],				
				"opname_note"=>["label"=>"Keterangan"]
				
			];
		return $col;
	}

	public function rules()
	{
		$data = [
										"opname_no" => "trim|required",
					"opname_date" => "trim|required",
					"unit_id" => "trim|integer|required",
					"own_id" => "trim|integer|required",
					"user_id" => "trim|integer",
					"opname_note" => "trim",
					"is_approved" => "trim",

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

	public function get_opname_header($where)
	{
		return $this->db->get_where("newfarmasi.opname_header",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.opname_header",$where)->row();
	}
}