<?php

class M_laporan_retur_penjualan  extends CI_Model {

	public function get_jenis_layanan()
	{
		$data = $this->db->query("
        select distinct a3.catunit_id, a3.nama from admin.v_employee_in_unit a1
        inner join admin.ms_unit a2 on a1.unit_id = a2.unit_id
        inner join admin.ms_category_unit a3 on a3.catunit_id = a2.unit_type
        inner join yanmed.category_unit_yanmed a4 on a4.catunit_id = a3.parent_id and a4.jmlanak > 0
		where unit_type in (21,22,23)
			")->result();
		return $data;
	}

	public function get_retur_detail($where){
		
		$sql = "SELECT vo.item_code,vo.item_name,sr.sr_num,
			to_char(coalesce(sr.date_act,sr.sr_date),'DD-MM-YYYY HH24:MI:SS') tgl_retur,sr.patient_name,s.doctor_name,s.sale_num,srd.qty_return,srd.sale_price::numeric,srd.total_return::numeric
			FROM farmasi.sale_return sr
			INNER JOIN farmasi.sale_return_detail srd ON sr.sr_id = srd.sr_id
			INNER JOIN farmasi.v_obat vo ON srd.item_id = vo.item_id
			INNER JOIN farmasi.sale s on srd.sale_id = s.sale_id
			INNER JOIN yanmed.services vs ON sr.visit_id = vs.visit_id AND sr.service_id = vs.srv_id
			where 0=0 $where
			ORDER BY sr.sr_date DESC,sr.patient_name ASC";
		$result = $this->db->query($sql)->result();
		if (count($result)>0) {
			return $result;
		}else{
			return array();
		}
	}

	public function get_retur_by_patient($where){
		$sql = "
			select sr.patient_name,sr.doctor_name,sr.unit_name,json_agg(
			   concat(sr.sr_num,'~',sr.date_act,'~',sr.sale_num,'~',sr.retur_detail)
			) as detail_retur
			from (
				SELECT sr.sr_num,to_char(sr.date_act,'DD-MM-YYYY HH24:MI:SS') date_act,sr.patient_name,sr.doctor_name,s.sale_num,vs.unit_name,
				json_agg(concat(vo.item_code,'|',vo.item_name,'|',srd.qty_return,'|',srd.sale_price::numeric,'|',srd.total_return::numeric)) as retur_detail
				FROM farmasi.sale_return sr
				left JOIN farmasi.sale_return_detail srd ON sr.sr_id = srd.sr_id
				left JOIN farmasi.v_obat vo ON srd.item_id = vo.item_id
				left JOIN farmasi.sale s on srd.sale_id = s.sale_id
				left JOIN yanmed.v_services vs ON sr.visit_id = vs.visit_id AND sr.service_id = vs.srv_id
				where 0=0 $where
				group by sr.sr_num,sr.date_act,sr.patient_name,sr.doctor_name,s.sale_num,vs.unit_name
				ORDER by sr.date_act DESC
			)sr
			group by sr.patient_name,sr.doctor_name,sr.unit_name
			ORDER BY sr.patient_name ASC
		";

		$result = $this->db->query($sql)->result();

		if (count($result)>0) {
			return $result;
		}else{
			return array();
		}
	}

	public function get_retur_by_item($where) {
		$data	=	array();
		$sql 	= 	$this->db->query("SELECT vo.item_code,vo.item_name,
				json_agg(concat(sr.sr_num,'|',to_char(sr.date_act,'DD-MM-YYYY HH24:MI:SS'),'|',
				sr.patient_name,'|',sr.doctor_name,'|',s.sale_num,'|',vs.unit_name,'|',
				srd.qty_return,'|',srd.sale_price::numeric,'|',srd.total_return::numeric)) as retur_detail
				FROM farmasi.sale_return sr
				INNER JOIN farmasi.sale_return_detail srd ON sr.sr_id = srd.sr_id
				INNER JOIN admin.ms_item vo on srd.item_id = vo.item_id  
				-- INNER JOIN farmasi.v_obat vo ON srd.item_id = vo.item_id
				left JOIN farmasi.sale s on srd.sale_id = s.sale_id
				INNER JOIN yanmed.v_services vs ON sr.visit_id = vs.visit_id AND sr.service_id = vs.srv_id
				where 0=0 $where
				group by vo.item_code,vo.item_name");

		$result = $sql->result();

		if (count($result)>0) {
			return $result;
		}else{
			return array();
		}
	}

   
	
	

	
}
