<?php

class M_mutation extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
		SELECT
		".implode(',', $aColumns)." ,mutation_id as id_key
		FROM
			newfarmasi.mutation M
		left join admin.ms_unit u1 on m.unit_require = u1.unit_id
		left join admin.ms_unit u2 on m.unit_sender = u2.unit_id
		left join farmasi.ownership o on m.own_id = o.own_id
		where 0=0 $sWhere order by mutation_date_act desc $sLimit
		")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
		SELECT
		".implode(',', $aColumns)." ,mutation_id as id_key
		FROM
			newfarmasi.mutation M
		left join admin.ms_unit u1 on m.unit_require = u1.unit_id
		left join admin.ms_unit u2 on m.unit_sender = u2.unit_id
		left join farmasi.ownership o on m.own_id = o.own_id
		where 0=0 $sWhere
		")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				//"mutation_id",
				"mutation_date"=>["label"=>"Tgl. Mutasi"],
				//"mutation_date_act",
				//"user_require",				
				"mutation_no"=>["label"=>"No.Mutasi"],
				//"user_sender",
				//"user_receiver",				
				"unit_require" => [
					"label" 	=> "unit minta",
					"initial" 	=> "u1",
					"field" 	=> "unit_name",
				],
				"unit_sender" => [
					"label" 	=> "unit pengirim",
					"initial" 	=> "u2",
					"field" 	=> "unit_name",
				],
				"mutation_status"=>[
					"label" => "Status",
					"custom" => function ($a) {
						if ($a == '1') {
							$condition = ["class" => "label-danger", "text" => "Minta"];
						} else if($a == '2') {
							$condition = ["class" => "label-primary", "text" => "Sedang Diproses"];
						}else if($a == '3'){
							$condition = ["class" => "label-success", "text" => "Terima"];
						}else {
							$condition = ["class" => "label-danger", "text" => "Batal"];
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
				"bon_no"=>
				[
					"label"	=>"Nomor",
					"field"	=> "coalesce(bon_no,mutation_no)"
				],
				//"mutation_id",
				"mutation_date"=>["label"=>"Tgl. Mutasi"],
				//"user_require",
				//"mutation_date_act",
				"mutation_status"=> [
					"label" => "Status",
					"custom" => function ($a) {
						if ($a == '1') {
							$condition = ["class" => "label-primary", "text" => "Request"];
						}else if ($a == '2') {
							$condition = ["class" => "label-danger", "text" => "Sending"];
						}else if($a == '3'){
							$condition = ["class" => "label-success", "text" => "Received"];
						}else {
							$condition = ["class" => "label-danger", "text" => "Batal"];
						}
						return label_status($condition);
					}
				] ,
				// "user_sender",
				// "user_receiver",
				"unit_require" => [
					"label" 	=> "unit minta",
					"initial" 	=> "u1",
					"field" 	=> "unit_name",
				],
				"unit_sender" => [
					"label" 	=> "unit pengirim",
					"initial" 	=> "u2",
					"field" 	=> "unit_name",
				]
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

	public function get_item_autocomplete($where,$own_id,$unit_id)
	{
		return $this->db->query(
			"SELECT  mi.item_id,mi.item_package,mi.item_name as value,mi.item_code,mi.item_unitofitem,
			coalesce(sf.stock_summary,0) as total_stock
			FROM farmasi.v_obat mi
			JOIN newfarmasi.stock sf ON sf.item_id = mi.item_id and sf.own_id = '$own_id' and sf.unit_id = '$unit_id'
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
								   ->join("newfarmasi.mutation m","m.mutation_id=md.mutation_id")
								   ->join("newfarmasi.stock s","s.item_id=md.item_id AND s.unit_id = m.unit_sender AND s.own_id = m.own_id","left")
								   ->order_by('md.item_id','asc')  
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
	public function get_data_m($sLimit, $sWhere, $sOrder, $aColumns)
	{
		// $sql    = "	SELECT 
		// 				distinct  " . implode(',',$aColumns) . "
		// 			FROM 
		// 				farmasi.bon b
		// 			inner join farmasi.bon_detail bd
		// 				on bd.bon_id = b.bon_id
		// 			inner join farmasi.ownership o
		// 				on o.own_id = b.own_id
		// 			inner join admin.ms_unit v
		// 				on v.unit_id = b.unit_id 
		// 			inner join admin.ms_unit vt
		// 				on vt.unit_id = b.unit_target
		// 			WHERE 0=0 
		// 			$sWhere 
		// 			$sOrder $sLimit  ";
		$sql = "SELECT mutation_id, mutation_date,mutation_no,u.unit_name as asal,u2.unit_name as tujuan,o.own_name, m.mutation_status,m.bon_no
FROM newfarmasi.mutation m
INNER JOIN admin.ms_unit u on m.unit_require = u.unit_id 
INNER JOIN admin.ms_unit u2 on m.unit_sender = u2.unit_id
INNER JOIN farmasi.ownership o on m.own_id = o.own_id
WHERE
	0 = 0 $sWhere";
		$result = $this->db->query($sql);
		$result = $result->result();
		return $result;
	}
	public function get_permintaan_detail($id, $unit = null)
	{
		$sql    = "	SELECT i.item_code,i.item_name, item_unitofitem, md.qty_request,md.qty_send
FROM newfarmasi.mutation_detail md
INNER JOIN admin.ms_item i on md.item_id = i.item_id
WHERE md.mutation_id = $id
GROUP BY i.item_id,i.item_code,i.item_name, item_unitofitem, md.qty_request,md.qty_send
ORDER BY i.item_id asc";
		$result = $this->db->query($sql);
		$result = $result->result();
		return $result;
	}
}
