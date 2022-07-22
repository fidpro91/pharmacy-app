<?php

class M_bon_permintaan extends CI_Model {

	public function get_data($sLimit, $sWhere, $sOrder)
	{
		$sql    = "	select
m.mutation_id,
mutation_date,
mutation_no,
mu.unit_name as tujuan,
u.unit_name as asal,
mutation_status,
own_name,
unit_require
	from newfarmasi.mutation m
	join newfarmasi.mutation_detail md on m.mutation_id =md.mutation_id
	join admin.ms_unit mu on m.unit_sender = mu.unit_id
	JOIN admin.ms_unit u on m.unit_require	= u.unit_id
	join farmasi.ownership o on m.own_id = o.own_id
					WHERE 0=0 
					$sWhere 
					GROUP BY m.mutation_id,
mutation_date,
mutation_no,
mu.unit_name,
u.unit_name,
mutation_status,
unit_require,own_name
					$sOrder $sLimit  ";
		$result = $this->db->query($sql);
		$result = $result->result();
		return $result;
	}

	public function get_permintaan_detail($id, $unit = null)
	{
		$sql    = "SELECT * FROM newfarmasi.mutation_detail md JOIN admin.ms_item mi ON mi.item_id=md.item_id WHERE mutation_id = '$id'";
		$result = $this->db->query($sql);
		$result = $result->result();
		return $result;
	}

	
}
