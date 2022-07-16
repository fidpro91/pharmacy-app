<?php

class M_bon extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
//		$data = $this->db->query("
//				select ".implode(',', $aColumns).",bon_id as id_key from farmasi.bon
//				where 0=0 $sWhere $sOrder $sLimit
//			")->result_array();
		$data = $this->db->query("
				select ".implode(',', $aColumns).",bon_id as id_key
				from (select  b.bon_id, b.bon_date, bon_no, v.unit_name as unit_id,	nama_unit as unit_target,own_name as own_id,bon_status from farmasi.bon b
				left join farmasi.bon_detail bd on b.bon_id = bd.bon_id
				inner join farmasi.ownership o on o.own_id = b.own_id
				inner join admin.ms_unit v on v.unit_id = b.unit_id
				inner join (
				select unit_id,cat_unit_code,cat_unit,unit_code,unit_name as nama_unit,unit_active,unit_type,kode_rekening from farmasi.v_unit_farmasi
				) vt on vt.unit_id = b.unit_target
				left join farmasi.mutation m on m.bon_id = b.bon_id)x where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
//		$data = $this->db->query("
//				select ".implode(',', $aColumns).",bon_id as id_key  from farmasi.bon where 0=0 $sWhere
//			")->num_rows();
		$data = $this->db->query("
				select ".implode(',', $aColumns).",bon_id as id_key from (select  b.bon_id, b.bon_date, bon_no, v.unit_name as unit_id,	nama_unit as unit_target,own_name as own_id,bon_status from farmasi.bon b
				left join farmasi.bon_detail bd on b.bon_id = bd.bon_id
				inner join farmasi.ownership o on o.own_id = b.own_id
				inner join admin.ms_unit v on v.unit_id = b.unit_id
				inner join (
				select unit_id,cat_unit_code,cat_unit,unit_code,unit_name as nama_unit,unit_active,unit_type,kode_rekening from farmasi.v_unit_farmasi
				) vt on vt.unit_id = b.unit_target
				left join farmasi.mutation m on m.bon_id = b.bon_id)x where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"bon_date"=>['label'=>'Tanggal'],
				"bon_no"=>['label'=>'Nomer'],
				"unit_id",
				"unit_target",
				"own_id",
				"bon_status",
				];
		return $col;
	}

	public function rules()
	{
		$data = [
					"bon_date" => "trim|required",
					"bon_status" => "trim|integer|required",
					"unit_id" => "trim|integer|required",
					"unit_target" => "trim|integer|required",
					"own_id" => "trim|integer|required",
					"user_id" => "trim|integer",
					"bon_no" => "trim",
					"item_status" => "trim|integer",
					"bon_no_uniq" => "trim",
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

	public function get_bon($where)
	{
		return $this->db->get_where("farmasi.bon",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.bon",$where)->row();
	}

	public function get_unit()
	{
		return $this->db->query("select * from ( select vf.unit_id, vf.unit_name from farmasi.v_unit_farmasi vf where unit_active = 't' union select vp.unit_id, vp.unit_name from farmasi.v_unit_pelayanan vp where unit_active = 't' ) lala order by unit_name asc")->result();
	}

	public function get_code_permintaan_unit()
	{
		$now = new \DateTime('now');
		$month = $now->format('m');
		$year = $now->format('Y');
		$year_month=$now->format('Ym');
		$tgl = date('d.m.Y');
		$result = $this	->db->query(
			"select 'DIS/' || trim(to_char(
						coalesce(max(regexp_replace(((string_to_array(mutation_no,'/'))[2]), '[^0-9]*', '', 'g')::integer)+1,1),'0000')) || '/' || '$tgl'  as nomax 
						from farmasi.mutation
						where to_char(mutation_date,'YYYYMM')='$year_month' and mutation_no not like 'DISTRBON%'"
		)
		->row();

		if  (isset($result->nomax)){
			return $result->nomax;
		}
		else{
			return 'DIS/0001/'.$tgl;
		}
	}

	public function get_kepemilikan()
	{
		return $this->db->query("SELECT * FROM farmasi.ownership order by own_id ASC")->result();

	}

	public function get_stock_item($where)
	{
		$data = $this->db->query("SELECT mi.item_code,mi.item_name as value,mc.classification_name,mi.item_id,mi.item_name,COALESCE(p.price_sell::numeric,0) as harga,sum(COALESCE(sf.stock_saldo,0))total_stock,item_unitofitem FROM farmasi.v_obat_alkes mi
		LEFT JOIN farmasi.price p ON mi.item_id = p.item_id
		LEFT JOIN admin.ms_classification mc ON mi.classification_id = mc.classification_id
		LEFT JOIN newfarmasi.stock_fifo sf ON mi.item_id = sf.item_id
		where 0=0 $where
		GROUP BY mi.item_id,mi.item_name,p.price_sell,mi.item_code,mc.classification_name,item_unitofitem")->result();

		return $data;
	}
}
