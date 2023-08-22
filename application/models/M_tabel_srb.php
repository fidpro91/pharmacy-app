<?php

class M_tabel_srb extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",srb_id as id_key  from yanmed.tabel_srb where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",srb_id as id_key  from yanmed.tabel_srb where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"srb_id",
				"saran",
				"no_sep",
				"visit_id",
				"created_at",
				"tgl_srb",
				"obat",
				"srb_status",
				"program_prb",
				"no_srb",
				"user_id",
				"srv_id",
				"keterangan"];
		return $col;
	}

	public function rules()
	{
		$data = [
										"saran" => "trim",
					"no_sep" => "trim",
					"visit_id" => "trim|integer|required",
															"obat" => "trim",
					"srb_status" => "trim|integer|required",
					"program_prb" => "trim|required",
					"no_srb" => "trim",
					"user_id" => "trim|integer",
					"srv_id" => "trim|integer|required",
					"keterangan" => "trim",

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

	public function get_tabel_srb($where)
	{
		return $this->db->get_where("yanmed.tabel_srb",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("yanmed.tabel_srb",$where)->row();
	}
}