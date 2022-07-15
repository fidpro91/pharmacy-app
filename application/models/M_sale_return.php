<?php

class M_sale_return extends CI_Model
{

	public function get_data($sLimit, $sWhere, $sOrder, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",sr_id as id_key  from farmasi.sale_return st
				left join admin.ms_unit u on st.unit_id = u.unit_id
				left join farmasi.ownership o on st.own_id = o.own_id
				left join yanmed.ms_surety s on st.surety_id = s.surety_id
				where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",sr_id as id_key  from farmasi.sale_return st
				left join admin.ms_unit u on st.unit_id = u.unit_id
				left join farmasi.ownership o on st.own_id = o.own_id
				left join yanmed.ms_surety s on st.surety_id = s.surety_id				
				where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
			"sr_num"=>["label"=>"No Retur"],
			"sr_date"=>["label"=>"Tgl Retur"],
			"patient_name"=>["label"=>"Nama"],	
			"unit_name"=>["label"=>"Depo"],
			"own_name"=>["label"=>"Kepemilikan"],	
			"sale_type"=>[
				"label" => "Tipe Penjualan",
				"custom" => function ($a) {
					if ($a == '0') {
						$condition = ["class" => "label-primary", "text" => "Tunai"];
					}else{
						$condition = ["class" => "label-success", "text" => "Kredit"];
					}
					return label_status($condition);
				}
			],	
						
			"surety_name"=>["label"=>"Penjamin"],	
			"doctor_name"=>["label"=>"Dpjp"],	
			"sr_total"=>["label"=>"Total"]
		];
		return $col;
	}

	public function rules()
	{
		$data = [
			"sr_num" => "trim|required",
			"sr_date" => "trim",
			"user_id" => "trim|integer",
			"unit_id" => "trim|integer",
			"visit_id" => "trim|integer",
			"patient_name" => "trim",
			"service_id" => "trim|integer",
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
			JOIN farmasi.sale s on sd.sale_id = s.sale_id
			JOIN admin.ms_item mi ON sd.item_id = mi.item_id
			WHERE s.service_id = '$where'
		")->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.sale_return", $where)->row();
	}
}
