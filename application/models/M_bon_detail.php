<?php

class M_bon_detail extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",bondetail_id as id_key  from farmasi.bon_detail where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",bondetail_id as id_key  from farmasi.bon_detail where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"bondetail_id",
				"bon_id",
				"item_id",
				"bon_qty",
				"bon_saldo",
				"bon_unitofitem",
				"bon_qty_pack",
				"bon_qty_perpack",
				"item_package"];
		return $col;
	}

	public function get_column_multiple()
	{
		$col = [
			"item_id",
			"satuan",
			"Qty_Permintaan",
			"Qty_Proses",
			"Stock_Terkini",
			"Qty",
		];
		return $col;
	}

	public function rules()
	{
		$data = [
										"bon_id" => "trim|integer|required",
					"item_id" => "trim|integer|required",
															"bon_unitofitem" => "trim|required",
															"item_package" => "trim",

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

	public function get_bon_detail($where)
	{
		return $this->db->get_where("farmasi.bon_detail",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.bon_detail",$where)->row();
	}

}