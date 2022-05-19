<?php

class M_mutation extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",mutation_id as id_key  from newfarmasi.mutation where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",mutation_id as id_key  from newfarmasi.mutation where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"mutation_id",
				"mutation_date",
				"mutation_date_act",
				"user_require",
				"mutation_status",
				"mutation_no",
				"user_sender",
				"user_receiver",
				"unit_require",
				"unit_sender"];
		return $col;
	}

	public function get_column_bon()
	{
		$col = [
				"bon_no",
				"mutation_id",
				"mutation_date",
				"user_require",
				"mutation_status",
				"user_sender",
				"user_receiver",
				"unit_require",
				"unit_sender"
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
					"own_id" => "trim|integer",
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

	public function get_item_autocomplete($select=null,$where)
	{
		return $this->db->query(
			"SELECT $select mi.item_id,mi.item_package,mi.item_name,mi.item_code,mi.item_unitofitem,
			sum(sf.stock_saldo)as total_stock
			FROM newfarmasi.stock_fifo sf
			JOIN admin.ms_item mi ON sf.item_id = mi.item_id
			where 0=0 $where
			GROUP BY mi.item_id,mi.item_name,mi.item_code,mi.item_unitofitem,mi.item_package"
		)->result();
	}

	public function get_databon($where)
	{
		$data["header"] = $this->db->join("admin.ms_unit mu","mu.unit_id=m.unit_require")
								   ->join("admin.ms_user mus","mus.user_id=m.user_require","left")
								   ->join("admin.ms_reff mr","mr.reff_id=m.own_id","left")
								   ->get_where("newfarmasi.mutation m",$where)->row();
		
		$data["detail"] = $this->db->join("admin.ms_item mi","mi.item_id=md.item_id")
								   ->get_where("newfarmasi.mutation_detail md",$where)
								   ->result();
		
		return $data;
	}
}