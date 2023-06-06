<?php

class M_laporan_imut extends CI_Model
{
	public function get_data_rs(){
		$data = $this->db->select("*,(select reg_name from admin.ms_region where reg_code=a.hsp_city) as kota")
			->get('admin.profil a')
			->row();
		return $data;
//		if (count($data) > 0) {
//			return $data;
//		}else{
//			return array();
//		}

		if (count($data) > 0) {
			return $data;
		}else{
			return array();
		}
	}

	public function get_data_lap($where)
	{
		$result = $this->db->query("
			SELECT ok.apotek,
			json_agg((ok.sale_num,ok.norm,ok.nama_pasien,ok.unit_name,ok.nama_dokter,ok.jml_item,ok.jml_fornas,ok.jml_forrs,ok.jml_nonformularium,ok.sale_id,ok.sale_date) ORDER by ok.sale_date desc,ok.sale_num desc)detail
			FROM (
					SELECT x.sale_id,x.sale_date,x.apotek,x.sale_num,x.norm,x.nama_pasien,x.unit_name,x.nama_dokter,COUNT(*)jml_item,sum(fornas)jml_fornas,
					sum(forrs)jml_forrs,sum(non_formularium)jml_nonformularium FROM (
							SELECT ap.unit_name as apotek,s.sale_id,s.sale_date,s.sale_num,COALESCE(p.px_norm,s.patient_norm)norm,COALESCE(p.px_name,s.patient_name)nama_pasien,mun.unit_name,COALESCE(s.doctor_name,concat(emp.employee_ft,' ',emp.employee_name,emp.employee_bt))nama_dokter,sd.item_id,
							CASE WHEN mi.type_formularium = 610 OR mi.type_formularium = 609 THEN 1 ELSE 0 END AS fornas,
							CASE WHEN mi.type_formularium = 608 THEN 1 ELSE 0 END AS forrs,
							CASE WHEN mi.is_formularium = 'f' THEN 1 ELSE 0 END AS non_formularium
							FROM farmasi.sale_detail sd 
							INNER JOIN farmasi.sale s ON sd.sale_id = s.sale_id
							INNER JOIN admin.ms_item mi ON sd.item_id = mi.item_id
							INNER JOIN admin.ms_unit ap ON s.unit_id = ap.unit_id
							LEFT JOIN yanmed.visit v ON s.visit_id = v.visit_id
							LEFT JOIN yanmed.services srv ON srv.visit_id = v.visit_id AND s.service_id = srv.srv_id 
							LEFT JOIN yanmed.patient p ON v.px_id = p.px_id
							LEFT JOIN hr.employee emp ON emp.employee_id = srv.par_id
							LEFT JOIN admin.ms_unit mun ON srv.unit_id = mun.unit_id
							WHERE 0=0 $where and mi.comodity_id = 1
					)x
					GROUP BY x.apotek,x.sale_num,x.norm,x.nama_pasien,x.unit_name,x.nama_dokter,x.sale_date,x.sale_id
			)ok
			GROUP BY ok.apotek")->result();
		return $result;
	}

	public function get_rekap_dt($where)
	{
		$data = $this->db->query("
			SELECT
			ap.unit_name AS apotek,
			count(*)jml_item,
			sum(CASE WHEN mi.type_formularium = 609 OR mi.type_formularium = 610 THEN 1 ELSE 0 END)AS fornas
			FROM
				farmasi.sale_detail sd
				INNER JOIN farmasi.sale s ON sd.sale_id = s.sale_id
				INNER JOIN ADMIN.ms_item mi ON sd.item_id = mi.item_id
				INNER JOIN ADMIN.ms_unit ap ON s.unit_id = ap.unit_id
				LEFT JOIN yanmed.visit v ON s.visit_id = v.visit_id
				LEFT JOIN yanmed.services srv ON srv.visit_id = v.visit_id AND s.service_id = srv.srv_id
				LEFT JOIN yanmed.patient P ON v.px_id = P.px_id
				LEFT JOIN hr.employee emp ON emp.employee_id = srv.par_id
				LEFT JOIN ADMIN.ms_unit mun ON srv.unit_id = mun.unit_id 
			WHERE
				0 = 0 $where and mi.comodity_id = 1
			GROUP BY ap.unit_name")->result();
		return $data;
	}

	public function get_data_by_dokter($where)
	{
		$result = $this->db->query("
			SELECT x.nama_dokter,COUNT(*)jml_item,sum(fornas)jml_fornas,
			sum(forrs)jml_forrs,sum(non_formularium)jml_nonformularium FROM (
					SELECT ap.unit_name as apotek,s.sale_id,s.sale_date,s.sale_num,COALESCE(p.px_norm,s.patient_norm)norm,COALESCE(p.px_name,s.patient_name)nama_pasien,mun.unit_name,upper(COALESCE (concat ( TRIM(emp.employee_ft), ' ', TRIM(emp.employee_name), emp.employee_bt ),s.doctor_name) )nama_dokter,sd.item_id,
					CASE WHEN mi.type_formularium = 610 OR mi.type_formularium = 609 THEN 1 ELSE 0 END AS fornas,
					CASE WHEN mi.type_formularium = 608 THEN 1 ELSE 0 END AS forrs,
					CASE WHEN mi.is_formularium = 'f' THEN 1 ELSE 0 END AS non_formularium
					FROM farmasi.sale_detail sd 
					INNER JOIN farmasi.sale s ON sd.sale_id = s.sale_id
					INNER JOIN admin.ms_item mi ON sd.item_id = mi.item_id
					INNER JOIN admin.ms_unit ap ON s.unit_id = ap.unit_id
					LEFT JOIN yanmed.visit v ON s.visit_id = v.visit_id
					LEFT JOIN yanmed.services srv ON srv.visit_id = v.visit_id AND s.service_id = srv.srv_id 
					LEFT JOIN yanmed.patient p ON v.px_id = p.px_id
					LEFT JOIN hr.employee emp ON emp.employee_id = srv.par_id
					LEFT JOIN admin.ms_unit mun ON srv.unit_id = mun.unit_id
					WHERE 0=0 $where and mi.comodity_id = 1
			)x
			GROUP BY x.nama_dokter")->result();
		return $result;
	}

	public function get_data_by_pasien($where)
	{
		$result = $this->db->query("
			SELECT x.sale_num,x.patient_norm,x.patient_name,x.unit_name,x.doctor_name,count(*)jml_item,
			sum(
				CASE WHEN x.is_generic = 't' AND x.is_formularium = 't' THEN 1 ELSE  0 END
			)jml_generic_formularium,
			sum(
				CASE WHEN x.is_generic = 't' AND x.is_formularium = 'f' THEN 1 ELSE  0 END
			)jml_generic_nonformularium,
			sum(
				CASE WHEN x.is_generic = 'f' AND x.is_formularium = 't' THEN 1 ELSE  0 END
			)jml_patern_formularium,
			sum(
				CASE WHEN x.is_generic = 'f' AND x.is_formularium = 'f' THEN 1 ELSE  0 END
			)jml_patern_nonformularium
			FROM (
				SELECT DISTINCT mi.item_id,mi.item_name,s.sale_num,s.patient_norm,s.patient_name,COALESCE(mu.unit_name,'APS')unit_name,s.doctor_name,sd.sale_qty,mi.is_generic,mi.is_formularium
				FROM farmasi.sale s
				INNER JOIN farmasi.sale_detail sd on s.sale_id = sd.sale_id
				INNER JOIN admin.ms_item mi on mi.item_id = sd.item_id
				LEFT JOIN yanmed.visit v on s.visit_id = v.visit_id
				LEFT JOIN yanmed.services srv ON s.service_id = srv.srv_id AND srv.visit_id = v.visit_id
				LEFT JOIN admin.ms_unit mu ON srv.unit_id = mu.unit_id
				where 0=0  AND mi.comodity_id = 1 $where
			)x
			GROUP BY x.sale_num,x.patient_norm,x.patient_name,x.unit_name,x.doctor_name")->result();
		return $result;
	}
}
