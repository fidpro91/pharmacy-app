<?php

class M_panggilan extends CI_Model
{
	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
	select ".implode(',', $aColumns)."
	,x.sale_id AS id_key
	FROM
		(
		SELECT COALESCE
			( mu.unit_name, 'APS' ) AS unit_name,
			patient_norm,
			patient_name,
			s.sale_id,
		CASE

				WHEN COALESCE ( s.sale_status, 0 ) = 0
				OR COALESCE ( s.sale_status, 0 ) = 1 THEN
					'Proses' ELSE'Selesai'
					END AS status_resep,
					af.status,
					mu2.unit_name as depo
			FROM
				farmasi.sale s
				LEFT JOIN newfarmasi.recipe r ON r.rcp_id = s.rcp_id
				LEFT JOIN yanmed.services srv ON s.service_id = srv.srv_id
				LEFT JOIN ADMIN.ms_unit mu ON srv.unit_id = mu.unit_id
				LEFT JOIN admin.ms_unit mu2 on s.unit_id = mu2.unit_id
				LEFT JOIN yanmed.antrian_farmasi2 af on s.sale_id = af.sale_id
			WHERE
				0 = 0
				$sWhere
				AND DATE ( sale_date ) = DATE (
				now())
				AND s.finish_time IS NULL
			ORDER BY
			s.date_act ASC
		) x
			")->result_array();

//		select ".implode(',', $aColumns).",po_id as id_key  from farmasi.po where 0=0 $sWhere $sOrder $sLimit
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
		select ".implode(',', $aColumns)."
	,x.sale_id AS id_key
	FROM
		(
		SELECT COALESCE
			( mu.unit_name, 'APS' ) AS unit_name,
			patient_norm,
			patient_name,
			s.sale_id,
		CASE

				WHEN COALESCE ( s.sale_status, 0 ) = 0
				OR COALESCE ( s.sale_status, 0 ) = 1 THEN
					'Proses' ELSE'Selesai'
					END AS status_resep,
					af.status,
					mu2.unit_name as depo
			FROM
				farmasi.sale s
				LEFT JOIN newfarmasi.recipe r ON r.rcp_id = s.rcp_id
				LEFT JOIN yanmed.services srv ON s.service_id = srv.srv_id
				LEFT JOIN ADMIN.ms_unit mu ON srv.unit_id = mu.unit_id
				LEFT JOIN admin.ms_unit mu2 on s.unit_id = mu2.unit_id
				LEFT JOIN yanmed.antrian_farmasi2 af on s.sale_id = af.sale_id
			WHERE
				0 = 0
				$sWhere
				AND DATE ( sale_date ) = DATE (
				now())
				AND s.finish_time IS NULL
			ORDER BY
			s.date_act ASC
		) x
			")->num_rows();
		return $data;
	}


	public function get_column()
	{
		$col = [
			"patient_norm",
			"patient_name",
			"status"
			];
		return $col;
	}
}
