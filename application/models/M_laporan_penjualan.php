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

    public function get_item($term)
	{
		$data = $this->db->query("select *,item_name as value from admin.ms_item where lower(item_name) like '%".strtolower($term)."%'")->result();

		return $data;
    }

	public function get_patient($param,$karakteristik)
	{
		$where = " AND date(sale_date) between '".$param['tgl_awal']."' and '".$param['tgl_akhir']."'";
		$nama_pas = strtolower($param['term']);
		$where .= " AND (p.px_norm = '$nama_pas' OR lower(p.px_name) like '%$nama_pas%')";
		if ($karakteristik == 3) {
			$data = $this->db->query("select distinct s.patient_norm,s.patient_name, concat(COALESCE(s.patient_norm,'0'),'-',s.patient_name) as value from farmasi.sale s where (s.patient_norm = '$nama_pas' OR lower(s.patient_name) like '%$nama_pas%')")->result();
		}else{
			$data = $this->db->query("SELECT
					concat (px_name, '(', px_norm, ')') AS
				VALUE
					,
					to_char(v.visit_date, 'DD-MM-YYYY') AS tgl_visit,
					v.visit_id,
					string_agg(DISTINCT unit_name,',') unit_layanan,
					p.px_id
				FROM
					yanmed.visit v
				INNER JOIN yanmed.patient p ON v.px_id = p.px_id
				INNER JOIN yanmed.services s ON s.visit_id = v.visit_id
				INNER JOIN admin.ms_unit mun ON mun.unit_id = s.unit_id
				INNER JOIN farmasi.sale b ON v.visit_id = b.visit_id
				where 0=0 $where
				GROUP BY p.px_name,p.px_id,p.px_norm,v.visit_date,v.visit_id
				ORDER BY v.visit_date")->result();
		}
		return $data;
	}

    public function get_sale_by_doctor($unit_penjualan,$surety,$sale_type,$unit_layanan,$date)
	{var_dump($unit_penjualan);die;
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

    public function get_sale_by_visit($unit_penjualan,$surety,$sale_type,$unit_layanan,$date,$visit_id)
	{
		$where = "";
		if ($unit_penjualan) {
			$where .= "AND s.unit_id in ($unit_penjualan)";
		}

		if ($surety) {
			$where .= " AND s.surety_id = '$surety'";
		}

		if ($visit_id) {
			$where .= " AND s.visit_id = '$visit_id'";
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

    public function get_sale_by_item($kepemilikan,$unit_penjualan,$surety,$sale_type,$catunit_id,$unit_layanan,$date,$item_id)
	{
		
		$where = "";	
        if ($kepemilikan) {
			$this->db->where('s.surety_id',$kepemilikan);
		}
        if ($item_id) {
            $item_id = substr_replace( $item_id, "", - 2 );
            $where .= " AND sd.item_id in ($item_id)";
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
				where 0=0 $date $where
				GROUP BY i.item_code,i.item_name
				ORDER BY i.item_name")->result();
                return $data;
	}

	public function get_sale_by_patient($unit_penjualan,$kepemilikan,$surety,$sale_type,$unit_layanan,$pasien,$date)
	{
		if ($unit_penjualan) {
			$this->db->where('a.unit_id in ('.$unit_penjualan.')',null);
		}
		if ($kepemilikan) {
			$this->db->where('a.own_id',$kepemilikan);
		}		
		if ($surety) {
			$where = " a.surety_id = '$surety'";
		}
		if (($sale_type)) {
			$where = "a.sale_type = '$sale_type'";
		}
		if (!empty($unit_layanan)) {			
			$where = "b.unit_id = '$unit_layanan' ";
        } 
		$where .=$date;
		$this->db->where($where,null);
		$norm=$px_name="";
		list($norm,$px_name) = explode('-', $pasien); 
		$data = $this->db->where("a.patient_norm = '$norm' OR lower(a.patient_name) like '%$px_name%'",null)
						 ->select("to_char(a.date_act,'DD-MM-YYYY HH24:MM:SS') as tgl_sale,(sale_total - COALESCE(sale_total_returned,0))::numeric as grand_total,sale_services::numeric as biaya_racik,a.*,sr.sr_total",false)
						 ->join('yanmed.services b','a.visit_id = b.visit_id and a.service_id = b.srv_id','left')
						 ->join("(
									select sr.sale_id,sum(srd.total_return+sr.sr_embalase+sr.sr_services)::numeric as sr_total
									from farmasi.sale_return sr
									inner join farmasi.sale_return_detail srd on sr.sr_id = srd.sr_id
									group by sr.sale_id
								) sr","sr.sale_id=a.sale_id",'left')
						 ->order_by('a.sale_date')
						 ->get('farmasi.sale a')
						 ->result();
		if (count($data) > 0) {
			return $data;
		}else{
			return array();
		}
	}

	public function get_sale_by_visit_patient_detail($sale_id)
	{
		$data = $this->db->where('a.sale_id',$sale_id)
						 ->join('farmasi.sale_detail b','a.sale_id = b.sale_id')
						 ->join('(
						 	select saledetail_id,sum(qty_return) as qty_return,sum(total_return+cost_return)::numeric as total_return from farmasi.sale_return_detail 
						 	group by saledetail_id
						 	) d','b.saledetail_id = d.saledetail_id','left')
						 ->join('farmasi.v_obat c','b.item_id = c.item_id')
						 ->select("c.item_code,c.item_name,c.item_unitofitem as satuan,b.sale_qty,b.sale_price::numeric as harga, (b.sale_price::numeric * b.sale_qty) as subtotal,d.qty_return,d.total_return::numeric,b.racikan_id",false)
						 ->get('farmasi.sale a')
						 ->result();
		if (count($data) > 0) {
			return $data;
		}else{
			return array();
		}
	}
	

	
}
