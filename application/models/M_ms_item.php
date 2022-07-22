<?php

class M_ms_item extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",item_id as id_key from admin.ms_item 
				where comodity_id in (1,2,3,6) $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",item_id as id_key from admin.ms_item 
				where comodity_id in (1,2,3,6) $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
			"item_id",
			"item_code"=>["label"=>"Kode"],
			"item_name"=>["label"=>"Nama"],
			"item_name_generic"=>["label"=>"Nama Generik"],
			"item_unitofitem"=>["label"=>"satuan"],
			"item_package"=>["label"=>"kemasan"],
			"qty_packtounit"=>["label"=>"jml satuan/kemasan"],


		];
		return $col;
	}

	public function rules()
	{
		$data = [
					"item_code" => "trim|required",
					"item_name" => "trim|required",
					"item_desc" => "trim",
					"comodity_id" => "trim|integer",
					"classification_id" => "trim|integer",
					"item_unitofitem" => "trim",
					"item_package" => "trim",
					"gol" => "trim",
					"jns" => "trim",
					"item_name_generic" => "trim",
					"qty_packtounit" => "trim|numeric",
					"type_formularium" => "trim|integer",
					"atc_ood" => "trim",
					"item_dosis" => "trim",
					"item_form" => "trim|integer",
				];
		return $data;
	}

	public function get_column_multiple()
	{
		$col = [
			"own_id",
			"price_buy",
			"price_sell"
		];
		return $col;
	}

	public function validation()
	{
		foreach ($this->rules() as $key => $value) {
			$this->form_validation->set_rules($key,$key,$value);
		}

		return $this->form_validation->run();
	}

	public function get_ms_item($where)
	{
		return $this->db->get_where("admin.ms_item",$where)->result();
	}

	public function get_item_autocomplete($where)
	{
		$data = $this->db->query(
			"SELECT mi.item_id,mi.item_code,mi.item_name as value,mi.item_package,mi.item_unitofitem,ow.own_name,p.price_buy,p.price_sell FROM admin.ms_item mi
			LEFT JOIN farmasi.price p ON mi.item_id = p.item_id
			LEFT JOIN farmasi.ownership ow ON p.own_id = ow.own_id
			where lower(mi.item_name) like lower('%$where%')"
			)->result();
		return $data;
	}

	public function get_item_stok($where)
	{
		$data = $this->db->query(
			"SELECT	rd.item_id,i.item_name,i.item_name as value,
			i.item_code,i.comodity_id,i.classification_id,i.item_unitofitem AS item_satuan,
			rd.stock_summary as stok,COALESCE(p.price_sell::numeric,0) as price_sell
			FROM
				farmasi.stock rd
				JOIN ADMIN.ms_item i ON rd.item_id = i.item_id
				LEFT JOIN farmasi.price P ON rd.item_id = P.item_id 
				AND p.own_id = rd.own_id 
			where 0=0 $where"

			)->result();
		return $data;
	}

	public function find_one($where)
	{
		return $this->db->get_where("admin.ms_item",$where)->row();
	}

	public function get_data_formularium()
	{
		$sql    = "SELECT * FROM admin.ms_reff where refcat_id = 33 order by reff_code ";
        $result = $this->db->query($sql);
        $result = $result->result();
        return $result;
	}

	public function get_package($term)
	{
		$sql    = 	"select package_name as value, package_name from admin.v_package where (lower(package_name) like '%$term%') ";
		$result = $this->db->query($sql);
		$result = $result->result();
		return $result;
	}
	public function get_satuan($term)
	{
		$sql    = 	"select unitofitem_name as value, unitofitem_name from admin.v_unitofitem where (lower(unitofitem_name) like '%$term%') ";
		$result = $this->db->query($sql);
		$result = $result->result();
		return $result;
	}

	public function get_data_bentuk()
	{
		$sql    = "SELECT * FROM admin.ms_reff where refcat_id = 36 order by reff_code ";
        $result = $this->db->query($sql);
        $result = $result->result();
        return $result;
	}

	public function get_own($select)
	{
		$sql    = "SELECT $select FROM farmasi.ownership ";
		$result = $this->db->query($sql);
		$result = $result->result();
		return $result;
	}

	public function get_price_detail($id)
	{
		$data = $this->db->query("SELECT p.own_id,p.price_buy,p.price_sell FROM admin.ms_item i
		LEFT JOIN farmasi.price p on i.item_id = p.item_id
		left JOIN farmasi.ownership o on p.own_id = o.own_id
		WHERE i.item_id =$id")->result();
		return $data;

	}
}
