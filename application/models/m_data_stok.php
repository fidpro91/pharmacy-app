<?php

class m_data_stok  extends CI_Model {

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
		$col_au = [
				"item_name"=>['label'=>'nama obat'],
				"own_name"=>['label'=>'Kepemilikan'],
				"unit_name",				
				];
		return $col_au;
	}	
}