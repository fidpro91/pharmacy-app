<?php

class M_production extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",production_id as id_key  from newfarmasi.production p
				left join farmasi.ownership o on p.own_id = o.own_id
				 where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",production_id as id_key  from newfarmasi.production  p
				left join farmasi.ownership o on p.own_id = o.own_id
				 where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [				
				"production_no",
				"production_date",				
				"own_name"=>["label"=>"kepemilikan"],
				];
		return $col;
	}

	public function rules()
	{
		$data = [
					"production_no" => "trim",
					"production_date" => "trim",
					"unit_id" => "trim|integer",
					"own_id" => "trim|integer",
					"production_note" => "trim",
					"production_status" => "trim|integer",
					"user_id" => "trim|integer",
					"rec_unit_pro" => "trim|integer",

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

	public function get_production($where)
	{
		return $this->db->get_where("newfarmasi.production",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.production",$where)->row();
	}

	


	public function get_laporan($where)
	{
		$data = $this->db->query("
				SELECT p.*,to_char(p.production_date,'DD-MM-YYYY') as tgl_produksi,mu.unit_name,mo.own_name,po.hasil,pi.bahan FROM newfarmasi.production p
				INNER JOIN (
					SELECT pOut.production_id,json_agg(concat(vo.item_code,'|',vo.item_name,'|',pOut.qty_item,'|',pOut.item_price::numeric)) hasil FROM newfarmasi.production_outdetail pOut
					INNER JOIN farmasi.v_obat vo on pOut.item_id = vo.item_id
					group by pOut.production_id
				) po ON po.production_id = p.production_id
				INNER JOIN (
					SELECT pIn.production_id,json_agg(concat(vo.item_code,'|',vo.item_name,'|',pIn.qty_item,'|',pIn.item_price::numeric)) bahan FROM newfarmasi.production_indetail pIn
					INNER JOIN farmasi.v_obat vo on pIn.item_id = vo.item_id
					group by pIn.production_id
				) pi ON pi.production_id = p.production_id
				inner join admin.ms_unit mu on p.unit_id = mu.unit_id
				inner join farmasi.ownership mo on p.own_id = mo.own_id
				where 0=0 $where
				
				order by p.production_date desc
			")->result();

			return $data; //print_r($data);die;
	}
}