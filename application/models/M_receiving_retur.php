<?php

class M_receiving_retur extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rr_id as id_key  from newfarmasi.receiving_retur where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rr_id as id_key  from newfarmasi.receiving_retur where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"rr_date",
				"num_retur",
				// "rr_id",
				"rr_status",
				"rr_type",
				"user_id",
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"rr_date" => "trim|required",
					"rr_type" => "trim|required",
					"rr_status" => "trim",
					"user_id" => "trim|integer",
					"num_retur" => "trim",
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

	public function get_receiving_retur($where)
	{
		return $this->db->get_where("newfarmasi.receiving_retur",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.receiving_retur",$where)->row();
	}

	public function get_item($where="")
	{
		return $this->db->query("
			SELECT r.rec_num,rd.rec_id,rd.recdet_id,rd.item_id,item_code,item_name,ms.supplier_id,ms.supplier_name,rd.qty_unit,rd.price_item,item_name as label FROM farmasi.receiving_detail rd
			JOIN farmasi.receiving r ON rd.rec_id = r.rec_id
			JOIN admin.ms_item mi ON mi.item_id = rd.item_id
			JOIN admin.ms_supplier ms ON r.supplier_id = ms.supplier_id
			where r.po_id is not null $where
			order by r.receiver_date desc
		")->result();
	}
}