<?php

class M_po extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",po_id as id_key  from farmasi.po where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",po_id as id_key  from farmasi.po where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"po_date",
				"po_no",
				"supplier_id",
				"rfq_id",
				"po_status",
				"po_expired",
				"po_days",
				"po_id",
				"po_ppn",
				"own_id"];
		return $col;
	}

	public function rules()
	{
		$data = [
					"po_date" => "trim|required",
					"po_no" => "trim",
					"supplier_id" => "trim|integer",
					"rfq_id" => "trim|integer",
					"po_status" => "trim|integer",
					"po_expired" => "trim",
					"po_days" => "trim|integer",
										"po_ppn" => "trim",
					"own_id" => "trim|integer",

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

	public function get_po($where)
	{
		return $this->db
				 ->join("admin.ms_supplier sp",'sp.supplier_id=po.supplier_id')
				 ->join("farmasi.ownership ow",'ow.own_id=po.own_id')
				 ->select("po.po_id,concat(po.po_no,'/',sp.supplier_name,'/',ow.own_name)detail_po",false)
				 ->order_by("po_date","desc")
				 ->get_where("farmasi.po po",$where)
			->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.po",$where)->row();
	}
}
