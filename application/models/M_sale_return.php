<?php

class M_sale_return extends CI_Model
{

	public function get_data($sLimit, $sWhere, $sOrder, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",sr_id as id_key  from farmasi.sale_return where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",sr_id as id_key  from farmasi.sale_return where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
			"sr_id",
			"sr_num",
			"sr_date",
			"date_act",
			"sr_status",
			"sr_shift",
			"user_id",
			"unit_id",
			"own_id",
			"sale_id",
			"sale_type",
			"visit_id",
			"patient_name",
			"service_id",
			"surety_id",
			"sr_embalase",
			"sr_services",
			"doctor_id",
			"doctor_name",
			"rcp_id",
			"cash_id",
			"verificated",
			"verificator_id",
			"verified_at",
			"rec_id",
			"cashretur_id",
			"sr_total",
			"discount",
			"sr_total_before_discount"
		];
		return $col;
	}

	public function rules()
	{
		$data = [
			"sr_num" => "trim|required",
			"sr_date" => "trim",
			"date_act" => "trim",
			"sr_status" => "trim|integer",
			"sr_shift" => "trim|integer",
			"user_id" => "trim|integer",
			"unit_id" => "trim|integer",
			"own_id" => "trim|integer",
			"sale_id" => "trim|integer|required",
			"visit_id" => "trim|integer",
			"patient_name" => "trim",
			"service_id" => "trim|integer",
			"surety_id" => "trim|integer",
			"doctor_id" => "trim|integer",
			"doctor_name" => "trim",
			"rcp_id" => "trim|integer",
			"cash_id" => "trim|integer",
			"verificated" => "trim",
			"verificator_id" => "trim|integer",
			"verified_at" => "trim",
			"rec_id" => "trim|integer",
			"cashretur_id" => "trim|integer",

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

	public function get_sale_return($where)
	{
		return $this->db->get_where("farmasi.sale_return", $where)->result();
	}

	public function get_saleDetail($where)
	{
		return $this->db->query("
			SELECT mi.item_code,mi.item_name,sd.sale_price::numeric as harga,sd.* FROM farmasi.sale_detail sd
			JOIN admin.ms_item mi ON sd.item_id = mi.item_id
			LIMIT 10
		")->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.sale_return", $where)->row();
	}
}
