<?php

class M_ms_item extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",item_id as id_key  from admin.ms_item where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",item_id as id_key  from admin.ms_item where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"item_id",
				"item_code",
				"item_name",
				"item_desc",
				"item_active",
				"comodity_id",
				"classification_id",
				"item_unitofitem",
				"item_package",
				"is_formularium",
				"is_generic",
				"gol",
				"jns",
				"item_name_generic",
				"qty_packtounit",
				"type_formularium",
				"atc_ood",
				"item_dosis",
				"item_strength",
				"item_form"];
		return $col;
	}

	public function rules()
	{
		$data = [
					"item_code" => "trim|required",
					"item_name" => "trim|required",
					"item_desc" => "trim",
					"comodity_id" => "trim|integer",
					"classification_id" => "trim|integer",
					"item_unitofitem" => "trim",
					"item_package" => "trim",
					"gol" => "trim",
					"jns" => "trim",
					"item_name_generic" => "trim",
					"qty_packtounit" => "trim|numeric",
					"type_formularium" => "trim|integer",
					"atc_ood" => "trim",
					"item_dosis" => "trim",
					"item_form" => "trim|integer",
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

	public function get_ms_item($where)
	{
		return $this->db->get_where("admin.ms_item",$where)->result();
	}

	public function get_item_autocomplete($where)
	{
		$data = $this->db->query(
			"SELECT mi.item_id,mi.item_code,mi.item_name as value,mi.item_package,mi.item_unitofitem,ow.own_name,p.price_buy,p.price_sell FROM admin.ms_item mi
			LEFT JOIN farmasi.price p ON mi.item_id = p.item_id
			LEFT JOIN farmasi.ownership ow ON p.own_id = ow.own_id
			where lower(mi.item_name) like lower('%$where%')"
			)->result();
		return $data;
	}

	public function find_one($where)
	{
		return $this->db->get_where("admin.ms_item",$where)->row();
	}
}