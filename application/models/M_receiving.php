<?php

class M_receiving extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rec_id as id_key from (
					select r.*,mr.own_name as own_name,supplier_name from newfarmasi.receiving r 
					LEFT JOIN farmasi.ownership mr ON r.own_id = mr.own_id 
					left join admin.ms_supplier sp on r.supplier_id = sp.supplier_id
					where 0=0 $sWhere $sOrder $sLimit
				)x
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select rec_id as id_key  from newfarmasi.receiving r 
				left join admin.ms_supplier sp on r.supplier_id = sp.supplier_id
				where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"receiver_date"=>["label"=>"Tgl.Penerimaan"],
				"rec_num"=>["label"=>"No.Faktur"],
				"receiver_num"=>["label"=>"No.Penerimaan"],
				"own_name"=>["label"=>"Kepemilikan"],
				"rec_type"=>[
					"label" => "Jenis Penerimaan",
					"custom" => function ($a) {
						if ($a == '0') {
							$condition = ["class" => "label-primary", "text" => "Penerimaan Po"];
						}else if($a == '1'){
							$condition = ["class" => "label-success", "text" => "Hibah"];
						}else{
							$condition = ["class" => "label-danger", "text" => "Konsinyasi"];
						}
						return label_status($condition);
					}
				],
				"supplier_name"=>["label"=>"Supplier"],
				"pay_type"=>["label" => "Tipe Pembayaran","custom"=>function($x){
					if($x==1){
						return 'Tunai';
					}else {
						return 'Kredit';
					}
				}],
				//"estimate_resource",
				"grand_total" => [
					"custom" => function($a){
						return convert_currency($a);
					}
				],
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"rec_num" => "trim",
					"rec_date" => "trim",
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
					"supplier_id" => "trim|integer",
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