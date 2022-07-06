<?php

class M_stock_process extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", stockprocess_id as id_key  from newfarmasi.stock_process sp 
				INNER JOIN ADMIN.ms_item mi ON sp.item_id = mi.item_id	
				left join farmasi.ownership sw on sp.own_id = sw.own_id
				left join admin.ms_unit u on sp.unit_id = u.unit_id
				where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", stockprocess_id as id_key  from newfarmasi.stock_process sp 
				INNER JOIN ADMIN.ms_item mi ON sp.item_id = mi.item_id	
				left join farmasi.ownership sw on sp.own_id = sw.own_id
				left join admin.ms_unit u on sp.unit_id = u.unit_id
				where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"item_name"=>['label'=>'nama obat'],
				"own_name"=>['label'=>'Kepemilikan'],
				"unit_name",
				"date_trans"=>['label'=>'Tgl Transaksi'],
				"trans_num"=>['label'=>'No Transaksi'],
				"stock_before"=>['label'=>'Stock Awal'],				
				"debet"=>['label'=>'Masuk'],
				"kredit"=>['label'=>'Keluar'],
				"stock_after"=>['label'=>'Stock Sisa'],
				"item_price"=>['label'=>'Harga satuan'],
				"total_price"=>['label'=>'Total Harga'],
				"description"=>['label'=>'Keterangan']
				];
		return $col;
	}

	



	public function rules()
	{
		$data = [
					"item_id" => "trim|integer|required",
					"own_id" => "trim|integer|required",
					"unit_id" => "trim|integer|required",
					"date_trans" => "trim",
					"date_act" => "trim",
					"trans_num" => "trim",
					"trans_type" => "trim|integer",
					"stock_before" => "trim|integer",
					"description" => "trim",
					"type_act" => "trim|integer",
					
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

	public function get_stock_process($where)
	{
		return $this->db->get_where("newfarmasi.stock_process",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.stock_process",$where)->row();
	}
}