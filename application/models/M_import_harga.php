<?php

class M_import_harga extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", as id_key  from admin.import_harga where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).", as id_key  from admin.import_harga where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"kode",
				"nama",
				"harga"];
		return $col;
	}

	public function rules()
	{
		$data = [
					"kode" => "trim",
					"nama" => "trim",
					"harga" => "trim",

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

	public function get_import_harga($where)
	{
		return $this->db->get_where("admin.import_harga",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("admin.import_harga",$where)->row();
	}
}