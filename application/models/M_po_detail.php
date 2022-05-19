<?php

class M_po_detail extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",podet_id as id_key  from farmasi.po_detail where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",podet_id as id_key  from farmasi.po_detail where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"po_id",
				"item_id",
				"po_pack",
				"po_qtypack",
				"po_unititem",
				"po_qtyunit",
				"po_pricepack",
				"po_pricediscount",
				"po_extradiscount",
				"po_qtyreceived",
				"po_discount",
				"podet_id"];
		return $col;
	}

	public function rules()
	{
		$data = [
					"po_id" => "trim|integer|required",
					"item_id" => "trim|integer|required",
					"po_pack" => "trim",
					"po_qtypack" => "trim|integer",
					"po_unititem" => "trim",
					"po_qtyunit" => "trim|numeric",
					"po_pricepack" => "trim",
					"po_pricediscount" => "trim",
					"po_extradiscount" => "trim",
					"po_qtyreceived" => "trim|numeric",
					"po_discount" => "trim|numeric",
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

	public function get_po_detail($where)
	{
		return $this->db
					->join("admin.ms_item mi"," pd.item_id = mi.item_id")
					->select("*,(pd.po_pricepack/pd.po_qtyunit)price_item")
					->get_where("farmasi.po_detail pd",$where)
					->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.po_detail",$where)->row();
	}
}