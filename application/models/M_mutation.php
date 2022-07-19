<?php

class M_mutation extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
		select ".implode(',', $aColumns)." ,
		id_key from (SELECT
		mutation_date,
		mutation_no,
		mutation_status,
		u1.unit_name AS unit_minta,
		u2.unit_name AS unit_tujuan,
		own_name ,bon_no,
		mutation_id as id_key
	FROM
		newfarmasi.mutation M
	left join admin.ms_unit u1 on m.unit_require = u1.unit_id
	left join admin.ms_unit u2 on m.unit_sender = u2.unit_id
	left join farmasi.ownership o on m.own_id = o.own_id
	 where 0=0  $sWhere $sOrder $sLimit ) x
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
		select ".implode(',', $aColumns)." ,
		id_key from (SELECT
		mutation_date,
		mutation_no,
		mutation_status,
		u1.unit_name AS unit_minta,
		u2.unit_name AS unit_tujuan,
		own_name ,bon_no,
		mutation_id as id_key
	FROM
		newfarmasi.mutation M
	left join admin.ms_unit u1 on m.unit_require = u1.unit_id
	left join admin.ms_unit u2 on m.unit_sender = u2.unit_id
	left join farmasi.ownership o on m.own_id = o.own_id
	 where 0=0 $sWhere) x
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				//"mutation_id",
				"mutation_date"=>["label"=>"Tgl.Mutasi"],
				//"mutation_date_act",
				//"user_require",				
				"mutation_no"=>["label"=>"No.Mutasi"],
				//"user_sender",
				//"user_receiver",				
				"unit_minta",
				"unit_tujuan",
				"mutation_status"=>[
					"label" => "Status",
					"custom" => function ($a) {
						if ($a == '1') {
							$condition = ["class" => "label-danger", "text" => "Minta"];
						} else if($a == '2') {
							$condition = ["class" => "label-primary", "text" => "Sedang Diproses"];
						}else {
							$condition = ["class" => "label-success", "text" => "Terima"];
						}
						return label_status($condition);
					}
				],
				"mutation_status",
			];
		return $col;
	}

	public function get_column_bon()
	{
		$col = [
				"bon_no"=>["label"=>"Nomor"],
				//"mutation_id",
				"mutation_date"=>["label"=>"Tgl. Mutasi"],
				//"user_require",
				"mutation_status"=> [
					"label" => "Status",
					"custom" => function ($a) {
						if ($a == '1') {
							$condition = ["class" => "label-primary", "text" => "Meminta"];
						}else if ($a == '2') {
							$condition = ["class" => "label-danger", "text" => "diproses"];
						}else {
							$condition = ["class" => "label-success", "text" => "terima"];
						}
						return label_status($condition);
					}
				] ,
				// "user_sender",
				// "user_receiver",
				"unit_minta",
				"unit_tujuan"
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"mutation_date" => "trim|required",
					"user_require" => "trim|integer",
					"mutation_status" => "trim|integer|required",
					"mutation_no" => "trim",
					"bon_no" => "trim",
					"own_id" => "trim|integer|required",
					"user_sender" => "trim|integer",
					"user_receiver" => "trim|integer",
					"unit_require" => "trim|integer|required",
					"unit_sender" => "trim|integer|required",

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

	public function get_mutation($where)
	{
		return $this->db->get_where("newfarmasi.mutation",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.mutation",$where)->row();
	}

	public function get_item_autocomplete($where)
	{
		return $this->db->query(
			"SELECT  mi.item_id,mi.item_package,mi.item_name as value,mi.item_code,mi.item_unitofitem,
			(sf.stock_summary) as total_stock
			FROM newfarmasi.stock sf
			JOIN admin.ms_item mi ON sf.item_id = mi.item_id
			where 0=0 $where"
		)->result();
	}

	public function get_databon($where)
	{
		$data["header"] = $this->db->join("admin.ms_unit mu","mu.unit_id=m.unit_require")
								   ->join("admin.ms_user mus","mus.user_id=m.user_require","left")
								   ->join("farmasi.ownership mr","mr.own_id=m.own_id","left")
								   ->get_where("newfarmasi.mutation m",$where)->row();
		
		$data["detail"] = $this->db->join("admin.ms_item mi","mi.item_id=md.item_id")
								   ->get_where("newfarmasi.mutation_detail md",$where)
								   ->result();
		
		return $data;
	}

	public function get_user_in_unit($where){
		return $this->db->join("hr.employee e","e.employee_id = u.employee_id")
						->join("hr.employee_on_unit eu","eu.employee_id = e.employee_id")
						->join("farmasi.v_unit_farmasi vf","vf.unit_id = eu.unit_id")
						->where("cat_unit_code in ('0502','0503','0504')")
						->where($where)
						->get("admin.ms_user u")->result();
	}
}