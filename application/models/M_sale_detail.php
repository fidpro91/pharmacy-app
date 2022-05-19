<?php

class M_sale_detail extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",saledetail_id as id_key  from farmasi.sale_detail where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",saledetail_id as id_key  from farmasi.sale_detail where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"saledetail_id",
				"sale_id",
				"item_id",
				"recdet_id",
				"sale_qty",
				"sale_price",
				"dosis",
				"racikan",
				"kronis",
				"sale_status",
				"sale_return",
				"racikan_qty",
				"racikan_id",
				"racikan_dosis",
				"verificated",
				"verificator_id",
				"verified_at",
				"racikan_desc",
				"own_id",
				"usage_date",
				"package_price",
				"subtotal_package_price",
				"detail_dosis",
				"drug_per_day"];
		return $col;
	}

	public function rules()
	{
		$data = [
										"sale_id" => "trim|integer|required",
					"item_id" => "trim|integer|required",
					"recdet_id" => "trim|integer|required",
															"dosis" => "trim",
																									"racikan_qty" => "trim|integer",
					"racikan_id" => "trim",
					"racikan_dosis" => "trim",
					"verificated" => "trim",
					"verificator_id" => "trim|integer",
					"verified_at" => "trim",
					"racikan_desc" => "trim",
					"own_id" => "trim|integer",
					"usage_date" => "trim",
					"package_price" => "trim",
					"subtotal_package_price" => "trim",
					"detail_dosis" => "trim",
					"drug_per_day" => "trim",

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

	public function get_sale_detail($where)
	{
		return $this->db->get_where("farmasi.sale_detail",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.sale_detail",$where)->row();
	}
}