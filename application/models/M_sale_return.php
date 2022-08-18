<?php

class M_sale_return extends CI_Model
{

	public function get_data($sLimit, $sWhere, $sOrder, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",sr_id as id_key  from farmasi.sale_return st
				left join admin.ms_unit u on st.unit_id = u.unit_id
				left join farmasi.ownership o on st.own_id = o.own_id
				left join yanmed.ms_surety s on st.surety_id = s.surety_id
				where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",sr_id as id_key  from farmasi.sale_return st
				left join admin.ms_unit u on st.unit_id = u.unit_id
				left join farmasi.ownership o on st.own_id = o.own_id
				left join yanmed.ms_surety s on st.surety_id = s.surety_id				
				where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
			"sr_num"=>["label"=>"No Retur"],
			"sr_date"=>["label"=>"Tgl Retur"],
			"patient_name"=>["label"=>"Nama"],	
			"sale_type"=>[
				"label" => "Tipe Penjualan",
				"custom" => function ($a) {
					if ($a == '0') {
						$condition = ["class" => "label-primary", "text" => "Tunai"];
					}else{
						$condition = ["class" => "label-success", "text" => "Kredit"];
					}
					return label_status($condition);
				}
			],	
			"surety_name"=>["label"=>"Penjamin"],	
			"sr_total"=>[
				"label"=>"Total",
				"custom"=>function($a){
					return convert_currency($a);
				}
			]
		];
		return $col;
	}

	public function rules()
	{
		$data = [
			"sr_num" => "trim|required",
			"sr_date" => "trim",
			"user_id" => "trim|integer",
			"unit_id" => "trim|integer",
			"visit_id" => "trim|integer",
			"patient_name" => "trim",
			"service_id" => "trim|integer",
		];
		return $data;
	}

	public function validation()
	{
		foreach ($this->rules() as $key => $value) {
			$this->form_validation->set_rules($key, $key, $value);
		}

		return $this->form_validation->run();
	}

	public function get_sale_return($where)
	{
		return $this->db->get_where("farmasi.sale_return", $where)->result();
	}

	public function get_saleDetail($where)
	{
		return $this->db->query("
			SELECT mi.item_code,mi.item_name,sd.sale_price::numeric as harga,sd.* FROM farmasi.sale_detail sd
			JOIN farmasi.sale s on sd.sale_id = s.sale_id
			JOIN admin.ms_item mi ON sd.item_id = mi.item_id
			WHERE s.service_id = '$where' and coalesce(sd.sale_return,0) < sd.sale_qty
		")->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.sale_return", $where)->row();
	}

	public function get_retur_by_id($id)
	{
		$sql    = 	"select DISTINCT
							sr.sr_id,
							sr_shift,
							sr.user_id,
							sr_num,
							sr.unit_id,
							sr.patient_name,
							COALESCE(p.px_norm,'-') no_rm, 
							sr.visit_id,
							sr.sale_id,
							sr.service_id,
							sr.rcp_id,
							sr.sr_embalase::numeric as embalase_value,
							cast(sr.sr_services as numeric) as services_value,
							sr.sale_type,
							sr.surety_id,
							sr.doctor_id,
							sr.doctor_name,
							sr.own_id,
							u.unit_name,
							poli.unit_id AS unit_layanan_id,
							poli.unit_name AS unit_layanan,
							to_char(sr.sr_date,'DD-MM-YYYY') as tanggal_retur
						from farmasi.sale_return sr
						INNER JOIN farmasi.sale_return_detail srd ON sr.sr_id = srd.sr_id
						INNER JOIN ADMIN.ms_unit u ON sr.unit_id = u.unit_id
					  	LEFT JOIN yanmed.services v ON v.visit_id = sr.visit_id AND sr.service_id = v.srv_id
							LEFT join yanmed.visit vt ON v.visit_id = vt.visit_id
							LEFT JOIN yanmed.patient p ON vt.px_id = p.px_id
						LEFT JOIN admin.ms_unit poli ON poli.unit_id = v.unit_id
						where sr.sr_id = $id
					";
		$result = $this->db->query($sql);
		$result = $result->row();
		return $result;
	}
	public function find_retur_detail($id)
	{
		$sql    = "select 
						sd.*,
						cast(srd.cost_return as numeric) as biaya_retur,
						s.sale_num ,
					    i.item_name,
					    cast(coalesce(pc.price_sell, pcu.price_sell) as numeric) harga,
					    cast(coalesce(pc.price_sell, pcu.price_sell) * sd.sale_qty as numeric) as total_beli,
					    cast(srd.total_return as numeric) as total_return,
						srd.qty_return
					from farmasi.sale_return_detail srd
					inner join farmasi.sale_detail sd on srd.saledetail_id = sd.saledetail_id
					inner join farmasi.sale s on sd.sale_id = s.sale_id
					inner join admin.ms_item i on sd.item_id = i.item_id
					left join farmasi.price pc on i.item_id = pc.item_id and coalesce(sd.own_id, s.own_id) = pc.own_id
					left join farmasi.price pcu on i.item_id = pcu.item_id and pcu.own_id = 1
					where srd.sr_id = $id"; //echo $sql;
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
