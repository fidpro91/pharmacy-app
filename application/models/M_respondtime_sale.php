<?php

class M_respondtime_sale extends CI_Model
{
	public function get_unit($idemp){
		$sql = "select
		vf.unit_id,
		vf.unit_name
		from
		farmasi.v_unit_farmasi vf
		/*inner join hr.employee e
		on e.employee_id = u.employee_id
		inner join hr.employee_on_unit eu
		on eu.employee_id = e.employee_id
		inner join farmasi.v_unit_farmasi vf
		on vf.unit_id = eu.unit_id*/
		where /*e.employee_id = $idemp
		and*/ cat_unit_code = '0504' and unit_active = 't' ";
		$result = $this->db->query($sql);
		return $result;
	}

	public function get_respondtime_sale($sWhere)
	{
		$sql = "SELECT s.patient_norm as px_norm,(s.patient_name) px_name,mu.unit_name as unit_layanan,to_char(vs.srv_date,'HH24:MI:SS') jam_datang,to_char(s.date_act,'HH24:MI:SS') as jam_dilayani,to_char(cs.create_date,'HH24:MI:SS') AS jam_bayar,
			to_char(s.finish_time,'HH24:MI:SS') as jam_selesai,
			(DATE_PART('hour', s.finish_time::time - s.date_act::time) * 60 + DATE_PART('minute', s.finish_time::time - s.date_act::time)) respond_time
			FROM farmasi.sale s
			INNER JOIN farmasi.v_unit_farmasi vu ON s.unit_id = vu.unit_id
			LEFT JOIN yanmed.services vs ON s.service_id = vs.srv_id
			LEFT JOIN admin.ms_unit mu on vs.unit_id = mu.unit_id
			LEFT JOIN yanmed.cash cs ON cs.cash_id = s.cash_id
			where 0=0 $sWhere
			ORDER BY px_name ASC";

		$data = $this->db->query($sql)->result();

		if (count($data) > 0) {
			return $data;
		}else{
			return array();
		}
	}
}
