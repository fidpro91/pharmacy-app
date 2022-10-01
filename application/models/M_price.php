<?php

class M_price extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
		select ".implode(',', $aColumns).", id_key from (SELECT
		item_name,own_name,price_buy :: numeric,price_sell :: numeric,profit,stock_min,stock_max,price_id AS id_key 
		FROM
		farmasi.price
		P LEFT JOIN ADMIN.ms_item i ON P.item_id = i.item_id
		LEFT JOIN farmasi.ownership o ON P.own_id = o.own_id 
		WHERE
		0 = 0 $sWhere $sOrder $sLimit) x
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
		select ".implode(',', $aColumns).", id_key from (SELECT
		item_name,own_name,price_buy :: numeric,price_sell :: numeric,profit,stock_min,stock_max,price_id AS id_key 
		FROM
		farmasi.price
		P LEFT JOIN ADMIN.ms_item i ON P.item_id = i.item_id
		LEFT JOIN farmasi.ownership o ON P.own_id = o.own_id 
		WHERE
		0 = 0 $sWhere) x
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"item_name"=>["label"=>"Obat"],
				"own_name"=>["label"=>"Kepemilikan"],
				"price_buy"=>["label"=>"Harga Beli",
					"custom"=> function($a) {
						return convert_currency($a);
					}
				],
				"price_sell"=>["label"=>"Harga Jual",
					"custom"=> function($a) {
						return convert_currency($a);
					}
				],
				"profit",
				"stock_min",
				"stock_max",
				//"price_lock",
				//"update_at",
				//"user_id",
				//"user_ip",
				//"price_id"
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"item_id" => "trim|integer|required",
					"own_id" => "trim|integer|required",																																			"update_at" => "trim",
					"user_id" => "trim|integer",
					"user_ip" => "trim",
					
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

	public function get_price($where)
	{
		return $this->db->get_where("farmasi.price",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.price",$where)->row();
	}
}