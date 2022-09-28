<?php

class M_opname extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",opname_id as id_key  from newfarmasi.opname where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",opname_id as id_key  from newfarmasi.opname where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"opname_id",
				"opname_header_id",
				"item_id",
				"qty_data",
				"qty_opname",
				"item_price",
				"qty_adj",
				"user_id"];
		return $col;
	}

	public function get_column_multiple()
	{
		$col = [
					"item_id",
					"qty_data",
					"item_price",
					"qty_opname"
				];
		return $col;
	}

	public function rules()
	{
		$data = [
					"opname_header_id" => "trim|integer",
					"item_id" => "trim|integer",
					"qty_data" => "trim|integer",
					"qty_opname" => "trim|integer",
					"item_price" => "trim",
					"qty_adj" => "trim|integer",
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

	public function get_opname($where)
	{
		return $this->db->get_where("newfarmasi.opname",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.opname",$where)->row();
	}

	public function get_stock_item($where,$unit_id=null,$own_id=null)
	{
		$xWhere ="";
		if ($unit_id) {
			$xWhere .= " AND sf.unit_id = '$unit_id'";
		}

		if ($own_id) {
			$xWhere .= " AND sf.own_id = '$own_id'";
		}

		$data = $this->db->query("
		SELECT mi.item_code,mi.item_name as value,mc.classification_name,mi.item_id,mi.item_name,COALESCE(p.price_sell::numeric,0) as harga,sum(COALESCE(sf.stock_saldo,0))total_stock FROM farmasi.v_obat_alkes mi
		LEFT JOIN admin.ms_classification mc ON mi.classification_id = mc.classification_id
		LEFT JOIN newfarmasi.stock_fifo sf ON mi.item_id = sf.item_id $xWhere
		LEFT JOIN farmasi.price P ON mi.item_id = P.item_id AND p.own_id = '$own_id'
		where 0=0 $where
		GROUP BY mi.item_id,mi.item_name,p.price_sell,mi.item_code,mc.classification_name
		")->result();
		return $data;
	}
}