<?php

class M_stock extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select * from (
					select ".implode(',', $aColumns).",id as id_key,sum(sf.stock_saldo)total_stock_fifo 
					from newfarmasi.stock s
					join newfarmasi.stock_fifo sf on s.item_id = sf.item_id and s.own_id = sf.own_id and s.unit_id = sf.unit_id 
					join admin.ms_unit mu on mu.unit_id = s.unit_id
					join farmasi.ownership ow on ow.own_id = s.own_id
					join admin.ms_item mi on mi.item_id = s.item_id
					where 0=0 $sWhere 
					group by ".implode(',', $aColumns).",id
				)x
				$sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select distinct ".implode(',', $aColumns).",id as id_key from newfarmasi.stock s
				join newfarmasi.stock_fifo sf on s.item_id = sf.item_id and s.own_id = sf.own_id and s.unit_id = sf.unit_id 
				join admin.ms_unit mu on mu.unit_id = s.unit_id
				join farmasi.ownership ow on ow.own_id = s.own_id
				join admin.ms_item mi on mi.item_id = s.item_id
				where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"item_id"=>[
					"initial" => "s"
				],
				"item_code",
				"item_name",
				"item_unitofitem",
				"own_name",
				"stock_summary",
				"stock_summary"=>[
					"custom" => function($a){
						return $a['total_stock_fifo'];
					},
					"label" => "stok fifo"
				],
				"unit_id"=>[
					"custom" => function($a){
						if ($a['total_stock_fifo'] != $a['stock_summary']) {
							$label = "<label class=\"label label-danger\">Tidak Sesuai</label>";
						}else{
							$label = "<label class=\"label label-success\">Sesuai</label>";
						}
						return $label;
					},
					"label" => "status",
					"initial" => "s"
				]
			];
		return $col;
	}

	public function rules()
	{
		$data = [
					"item_id" => "trim|integer|required",
					"own_id" => "trim|integer|required",
					"unit_id" => "trim|integer|required",
					"update_date" => "trim",
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

	public function get_stock($where)
	{
		return $this->db->get_where("newfarmasi.stock",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.stock",$where)->row();
	}
}