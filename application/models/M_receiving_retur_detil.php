<?php

class M_receiving_retur_detil extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rrd_id as id_key  from newfarmasi.receiving_retur_detil where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rrd_id as id_key  from newfarmasi.receiving_retur_detil where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"rrd_id",
				"item_id",
				"rec_id",
				"rrd_qty",
				"rrd_price",
				"rrd_type",
				"rrd_paid",
				"rrd_paid_date",
				"rr_id",
				"recdet_id",
				"rrd_note",
				"supplier_id"];
		return $col;
	}

	public function get_column_multiple()
	{
		$col = [
				"item_id",
				"rrd_qty",
				"rrd_note",
				"rrd_price",
				"qty_terima",
				"stock_saldo",
				"supplier",
				"id_penerimaan",
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"item_id" => "trim|integer",
					"rec_id" => "trim|integer",
					"rrd_qty" => "trim|integer",
					"rrd_price" => "trim",
					"rrd_type" => "trim|integer",
					"rrd_paid" => "trim",
					"rrd_paid_date" => "trim",
					"rr_id" => "trim|integer",
					"recdet_id" => "trim|integer",
					"rrd_note" => "trim",
					"supplier_id" => "trim|integer",
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

	public function get_receiving_retur_detil($where)
	{
		return $this->db->get_where("newfarmasi.receiving_retur_detil",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.receiving_retur_detil",$where)->row();
	}
}