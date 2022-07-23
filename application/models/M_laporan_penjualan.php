<?php

class M_laporan_penjualan  extends CI_Model {

	public function get_jenis_layanan()
	{
		$data = $this->db->query("
        select distinct a3.catunit_id, a3.nama from admin.v_employee_in_unit a1
        inner join admin.ms_unit a2 on a1.unit_id = a2.unit_id
        inner join admin.ms_category_unit a3 on a3.catunit_id = a2.unit_type
        inner join yanmed.category_unit_yanmed a4 on a4.catunit_id = a3.parent_id and a4.jmlanak > 0
			")->result();
		return $data;
	}

    public function get_unit_layanan($catunit_id)
	{
		$data = $this->db->where('unit_type',$catunit_id)
						 ->order_by('unit_name','ASC')
						 ->get('admin.ms_unit')
						 ->result();
		return $data;
	}
    public function get_sale_by_doctor($unit_penjualan,$surety,$sale_type,$unit_layanan,$date)
	{
		$where = "";
		if ($unit_penjualan) {
			$where .= "AND x.unit_id in ($unit_penjualan)";
		}

		if ($surety) {
			$where .= " AND x.surety_id = '$surety'";
		}

		if (!empty($sale_type)) {
			$where .= " AND x.sale_type = '$sale_type'";
		}

		if (!empty($unit_layanan)) {
			
			$where .= " AND srv.unit_id = '$unit_layanan' ";
        }

		$data = $this->db->query("
			select x.doctor_id,sum(x.total_resep) total_resep,x.code_doctor,
			x.dokter,sum(x.grand_total) grand_total from (
				SELECT doctor_id,count(x.sale_id) as total_resep,COALESCE(b.employee_nip,'-') 	as code_doctor,
				COALESCE(concat(b.employee_ft,' ',b.employee_name,b.employee_bt),x.doctor_name) as dokter,
				SUM(x.sale_total::NUMERIC - COALESCE(x.sale_total_returned,0)) as grand_total FROM farmasi.sale x 
				LEFT JOIN hr.employee b ON b.employee_id = x.doctor_id
				LEFT JOIN yanmed.services srv on x.service_id = srv.srv_id
				LEFT JOIN (
					select sale_id,sum(sr_total) as sr_total 
					from farmasi.sale_return
					group by sale_id
				) sr on sr.sale_id = x.sale_id
				where 0=0 $date $where 
				GROUP BY doctor_id,doctor_name,b.employee_nip,b.employee_name,b.employee_ft,b.employee_bt
			) x
			group BY x.doctor_id,x.code_doctor,x.dokter
				ORDER by dokter ASC")->result();

		return $data;
	}

    public function get_sale_by_visit($unit_penjualan,$surety,$sale_type,$unit_layanan,$date)
	{
		$where = "";
		if ($unit_penjualan) {
			$where .= "AND s.unit_id in ($unit_penjualan)";
		}

		if ($surety) {
			$where .= " AND s.surety_id = '$surety'";
		}

		if (!empty($sale_type)) {
			$where .= " AND s.sale_type = '$sale_type'";
		}

		if (!empty($unit_layanan)) {
			
			$where .= " AND vs.unit_id = '$unit_layanan' ";
        }
		

		$data = $this->db->query("SELECT (s.sale_total-COALESCE(s.sale_total_returned,0))::numeric as sale_total,s.sale_services::numeric,s.embalase_item_sale,s.sale_id,s.patient_norm as px_norm,s.patient_name as px_name,json_agg(concat(i.item_code,'|',i.item_name,'|',sd.sale_qty,'|',sd.sale_price::NUMERIC,'|',
			(sd.sale_price::NUMERIC*sd.sale_qty),'|',retur.sr_total,'|',retur.qty_return,'|',sd.racikan_id)) as detail_jual FROM farmasi.sale s
			LEFT JOIN yanmed.services vs ON s.visit_id = vs.visit_id AND s.service_id = vs.srv_id
			INNER JOIN farmasi.sale_detail sd on s.sale_id = sd.sale_id
			INNER JOIN admin.ms_item i on i.item_id = sd.item_id
			left join (
				select srd.saledetail_id,sum(srd.qty_return) as qty_return,sum(srd.total_return+sr.sr_embalase+sr.sr_services)::numeric as sr_total
				from farmasi.sale_return sr
				inner join farmasi.sale_return_detail srd on sr.sr_id = srd.sr_id
				group by srd.saledetail_id,srd.qty_return,srd.total_return,sr.sr_embalase,sr.sr_services
			)retur on retur.saledetail_id = sd.saledetail_id
			where 0=0 $date $where 
			GROUP BY s.sale_id,s.patient_norm,s.patient_name
			ORDER BY s.sale_id DESC")->result();
            return $data;
	}

    public function get_sale_by_item($kepemilikan,$unit_penjualan,$surety,$sale_type,$catunit_id,$unit_layanan,$date)
	{
		
		$where = "";	
        if ($kepemilikan) {
			$this->db->where('s.surety_id',$kepemilikan);
		}
		if ($unit_penjualan) {
			$where .= "AND s.unit_id in ($unit_penjualan)";
		}
		if ($surety) {
			$where .= " AND s.surety_id = '$surety'";
		}
		if ($sale_type) {
			$where .= " AND s.sale_type = '$sale_type'";
		}
		if ($catunit_id) {
			$where .= " AND mu.unit_type = '$catunit_id'";
		}
		if (!empty($unit_layanan)) {			
			$where .= " AND vs.unit_id = '$unit_layanan' ";
        }
		$data = $this->db->query("SELECT i.item_code,i.item_name,
				json_agg(
					concat(
						to_char(s.date_act,'DD-MM-YYYY HH24:MM:SS'),'||',
						s.patient_name,'||',
								v.px_address,'||',
								sd.sale_qty,'||',
								sd.sale_price::numeric,'||',
								s.doctor_name,'||',
								(sd.sale_price::numeric * (sd.sale_qty-coalesce(srd.qty_return,0))),'||',
								COALESCE(srd.qty_return,0)
							)
						) as detail_sale
					FROM farmasi.sale_detail sd
				INNER JOIN admin.ms_item i on sd.item_id = i.item_id
				INNER JOIN farmasi.sale s on sd.sale_id = s.sale_id
				LEFT JOIN (
					select saledetail_id,sum(qty_return) as qty_return,sum(total_return+cost_return)::numeric as total_return from farmasi.sale_return_detail 
					group by saledetail_id
				) srd on sd.saledetail_id = srd.saledetail_id
				LEFT JOIN yanmed.services vs ON vs.visit_id = s.visit_id AND vs.srv_id = s.service_id
				LEFT JOIN yanmed.visit v on vs.visit_id = v.visit_id
				where 0=0 $where
				GROUP BY i.item_code,i.item_name
				ORDER BY i.item_name")->result();
                return $data;
	}
	

	
}