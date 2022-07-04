<?php

class M_stock_fifo extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", as id_key  from farmasi.stock_fifo where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", as id_key  from farmasi.stock_fifo where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"item_id",
				"own_id",
				"unit_id",
				"stock_in",
				"total_price",
				"update_date",
				"stock_saldo",
				"stock_id",
				"recdet_id"];
		return $col;
	}

	public function rules()
	{
		$data = [
					"item_id" => "trim|integer|required",
					"own_id" => "trim|integer|required",
					"unit_id" => "trim|integer|required",
															"update_date" => "trim",
					"stock_saldo" => "trim|integer",
										"recdet_id" => "trim|integer",

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

	public function get_stock_fifo($where)
	{
		return $this->db->get_where("farmasi.stock_fifo",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.stock_fifo",$where)->row();
	}

	public function get_stock_item($where)
	{
		$data = $this->db->query("SELECT mi.item_code,mi.item_name as value,mc.classification_name,mi.item_id,mi.item_name,COALESCE(p.price_sell::numeric,0) as harga,sum(COALESCE(sf.stock_saldo,0))total_stock FROM farmasi.v_obat_alkes mi
		LEFT JOIN farmasi.price p ON mi.item_id = p.item_id
		LEFT JOIN admin.ms_classification mc ON mi.classification_id = mc.classification_id
		LEFT JOIN newfarmasi.stock_fifo sf ON mi.item_id = sf.item_id
		where 0=0 $where
		GROUP BY mi.item_id,mi.item_name,p.price_sell,mi.item_code,mc.classification_name")->result();

		return $data;
	}


}
