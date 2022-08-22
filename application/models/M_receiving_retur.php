<?php

class M_receiving_retur extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rr_id as id_key  from newfarmasi.receiving_retur where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rr_id as id_key  from newfarmasi.receiving_retur where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"rr_date",
				"num_retur",
				// "rr_id",
				"rr_status",
				"rr_type",
				"user_id",
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"rr_date" => "trim|required",
					"rr_type" => "trim|required",
					"rr_status" => "trim",
					"user_id" => "trim|integer",
					"num_retur" => "trim",
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

	public function get_receiving_retur($where)
	{
		return $this->db->get_where("newfarmasi.receiving_retur",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.receiving_retur",$where)->row();
	}

	public function get_item($where="")
	{
		return $this->db->query("
			SELECT sf.own_id,sf.stock_summary,r.rec_num,rd.rec_id,rd.recdet_id,rd.item_id,item_code,item_name,ms.supplier_id,ms.supplier_name,rd.qty_unit,rd.price_item,item_name as label,rd.hpp 
			FROM newfarmasi.receiving_detail rd
			JOIN newfarmasi.receiving r ON rd.rec_id = r.rec_id
			JOIN admin.ms_item mi ON mi.item_id = rd.item_id
			JOIN admin.ms_supplier ms ON r.supplier_id = ms.supplier_id
			JOIN newfarmasi.stock sf ON sf.item_id = rd.item_id and sf.own_id = r.own_id and sf.unit_id = r.receiver_unit
			where r.po_id is not null and sf.stock_summary > 0 $where
			order by r.receiver_date desc
		")->result();
	}

	public function get_retur_by_id($id)
	{
		$sql    = " SELECT DISTINCT
			r.rr_id,
			r.num_retur,
			P.supplier_id,
			rr_type,
			r.unit_id,
			( CASE WHEN rr_status THEN 'TRUE' ELSE'FALSE' END ) AS rr_status 
			FROM
			newfarmasi.receiving_retur r
			INNER JOIN newfarmasi.receiving_retur_detil rd ON r.rr_id = rd.rr_id
			INNER JOIN newfarmasi.receiving re ON re.rec_id = rd.rec_id
			INNER JOIN farmasi.po P ON P.po_id = re.po_id 
			WHERE r.rr_id = $id";
		$result = $this->db->query($sql);
		$result = $result->row();
		return $result;
	}

	public function find_retur_detail($id)
	{
		$sql    = " select 
						rd.*,
						i.item_name,
						r.own_id,
						o.own_name,
						r.rec_num faktur,
						to_char(
							r.rec_date,
							'DD-MM-YYYY'
						) AS tgl,
						d.qty_unit,
						cast(rd.rrd_price as numeric) harga  
					from newfarmasi.receiving_retur_detil rd
					inner join admin.ms_item i
						ON i.item_id = rd.item_id
					inner join newfarmasi.receiving r
						on r.rec_id = rd.rec_id
					inner join farmasi.ownership o
						on o.own_id = r.own_id
					inner join newfarmasi.receiving_detail d
						on d.rec_id = rd.rec_id
						and d.item_id = rd.item_id
					where rd.rr_id = $id";
		$result = $this->db->query($sql)->result();
		return $result;
	}

	public function get_data_profile()
	{
		$query = $this->db->join('admin.ms_region b', 'b.reg_code=a.hsp_prov')
		->get('admin.profil a')
		->row();
		$distrik = $this->db->where('reg_code', $query->hsp_district)->get('admin.ms_region')->row();
		$city    = $this->db->where('reg_code', $query->hsp_city)->get('admin.ms_region')->row();

		$data = array(
			"hsp_city" => $city->reg_name,
			"hsp_prov" => $query->reg_name,
			"hsp_distrik" => $distrik->reg_name,
			"hsp_name" => $query->hsp_name,
			"hsp_website" => $query->hsp_website,
			"hsp_phone" => $query->hsp_phone,
			"hsp_address" => $query->hsp_address,
			"hsp_email" => $query->hsp_email,
			"hsp_code" => $query->hsp_code
		);

		return $data;
	}
}