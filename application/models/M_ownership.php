<?php

class M_ownership extends CI_Model
{

	public function get_data($sLimit, $sWhere, $sOrder, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",own_id as id_key  from farmasi.ownership where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",own_id as id_key  from farmasi.ownership where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
			"own_code",
			"own_name",
			"own_active" => [
				"label" => "status",
				"custom" => function ($a) {
					if ($a == 't') {
						$condition = ["class" => "label-primary", "text" => "Aktif"];
					} else {
						$condition = ["class" => "label-danger", "text" => "Non Aktif"];
					}
					return label_status($condition);
				}
			],
			"profit_item"=>[
				"custom" => function($a){
					return convert_currency($a);
				}
			],
			"own_active" => [
				"label" => "status",
				"custom" => function ($a) {
					if ($a == 't') {
						$condition = ["class" => "label-primary", "text" => "Aktif"];
					} else {
						$condition = ["class" => "label-danger", "text" => "Non Aktif"];
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
			"own_name" => "trim",
			"own_active" => "trim",
			"profit_item" => "trim|integer",
			"own_code" => "trim",
		];
		return $data;
	}

	public function validation()
	{
		foreach ($this->rules() as $key => $value) {
			$this->form_validation->set_rules($key, $key, $value);
		}

		return $this->form_validation->run();
	}

	public function get_ownership($where = [0 => 0])
	{
		return $this->db->order_by("own_id","asc")
						->get_where("farmasi.ownership", $where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.ownership", $where)->row();
	}
}
