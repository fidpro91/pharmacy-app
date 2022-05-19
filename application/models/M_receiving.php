<?php

class M_receiving extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rec_id as id_key from (
					select r.*,mr.reff_name as own_name from newfarmasi.receiving r 
					left join admin.ms_reff mr on r.own_id = mr.reff_id
					where 0=0 $sWhere $sOrder $sLimit
				)x
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select rec_id as id_key  from newfarmasi.receiving where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"receiver_date",
				"rec_num",
				"receiver_num",
				"own_name",
				"rec_type",
				"status",
				"pay_type"=>["custom"=>function($x){
					if($x==1){
						return 'Tunai';
					}else {
						return 'Kredit';
					}
				}],
				"estimate_resource",
				"grand_total",
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"rec_num" => "trim|required",
					"rec_date" => "trim|required",
					"po_id" => "trim|integer",
					"own_id" => "trim|integer",
					"receiver_unit" => "trim|integer",
					"sender_unit" => "trim|integer",
					"sender_num" => "trim",
					"sender_date" => "trim",
					"receiver_num" => "trim",
					"receiver_date" => "trim|required",
					"rec_type" => "trim|integer|required",
					"rec_taxes" => "trim|numeric",
					"discount_total" => "trim|numeric",
					"mutation_id" => "trim|integer",
					"opname_id" => "trim|integer",
					"sender_name" => "trim",
					"recfromret_id" => "trim|integer",
					"hibah_cat" => "trim|integer",
					"pay_type" => "trim|required",
					"estimate_resource" => "trim|required",
					"supplier_id" => "trim|integer|required",
					"user_id" => "trim|integer",
					"item_status" => "trim|integer",
					"transfer_date" => "trim",
					"transfer_by" => "trim|integer",
					"no_transaksi" => "trim",
					"production_id" => "trim|integer",
					"grand_total" => "trim|numeric",
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

	public function get_receiving($where)
	{
		return $this->db->get_where("newfarmasi.receiving",$where)->result();
	}

	public function get_owner($where)
	{
		return $this->db->get_where("farmasi.ownership",$where)->result();
	}

	public function get_hibah($where)
	{
		return $this->db->get_where("admin.ms_reff",$where)->result();
	}

	public function get_estimate_resource($where)
	{
		return $this->db
					->select("estimate_resource")
					->distinct()
					->get_where("newfarmasi.receiving",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.receiving",$where)->row();
	}
}