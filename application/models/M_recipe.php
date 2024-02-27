<?php

class M_recipe extends CI_Model {

	public function get_data($sLimit,$sWhere,$sOrder,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rcp_id as id_key from newfarmasi.recipe r
				left join admin.ms_unit mu on mu.unit_id = r.unit_id_layanan
				left JOIN admin.ms_user u ON r.user_id = u.user_id 
				left JOIN hr.employee e ON r.doctor_id = e.employee_id  
				left join yanmed.patient p on r.px_id = p.px_id
				 where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere,$aColumns)
	{
		$data = $this->db->query("
				select ".implode(',', $aColumns).",rcp_id as id_key  from newfarmasi.recipe r
				left join admin.ms_unit mu on mu.unit_id = r.unit_id_layanan
				left JOIN admin.ms_user u ON r.user_id = u.user_id
				left JOIN hr.employee e ON r.doctor_id = e.employee_id 
				left join yanmed.patient p on r.px_id = p.px_id
				where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
				"rcp_id",
				"rcp_date",
				"rcp_no",
				"px_norm",
				"px_name",
				"unit_name",
				"person_name"=>["label"=>"Dokter"],
				"iterasi" => [
					"custom" => function($a){
						$label = null;
						if ($a=="1") {
							$label = "<span class=\"label label-info\">Tanpa Iterasi</span>";
						}elseif($a=="2"){
							$label = "<span class=\"label label-info\">Iterasi 1x</span>";
						}elseif ($a=="3") {
							$label = "<span class=\"label label-info\">Iterasi 2x</span>";
						}
						return $label;
					}
				],
				"rcp_status" => [
					"custom" => function($a){
						if ($a=="0") {
							$label = "<span class=\"label label-primary\">Request</span>";
						}elseif($a=="1"){
							$label = "<span class=\"label label-success\">Dilayani Penuh</span>";
						}elseif ($a=="2") {
							$label = "<span class=\"label label-warning\">Dilayani Sebagian</span>";
						}elseif ($a=="3") {
							$label = "<span class=\"label label-info\">Resep Tercetak</span>";
						}
						return $label;
					}

				],
				"jenis_resep" => [
					"custom" => function($a){
						if ($a=="1") {
							$label = "<span class=\"label label-primary\">Pulang</span>";
						}elseif($a=="2"){
							$label = "<span class=\"label label-danger\">Cito</span>";
						}elseif ($a=="3") {
							$label = "<span class=\"label label-warning\">Pelayanan</span>";
						}
						return $label;
					}]
				/* "unit_id",
				"rcp_no",
				"diagnosa_id",
				"verificated",
				"verificator_id",
				"verified_at",
				"racikan_txt",
				"px_id",
				"visit_id" */
		];
		return $col;
	}

	public function rules()
	{
		$data = [
					"rcp_date" => "trim",
					"services_id" => "trim|integer|required",
					"rcp_status" => "trim|integer",
					"user_id" => "trim|integer|required",
					"doctor_id" => "trim|integer",
					"unit_id" => "trim|integer",
					"rcp_no" => "trim",
					"diagnosa_id" => "trim|integer",
					"verificated" => "trim",
					"verificator_id" => "trim|integer",
					"verified_at" => "trim",
					"racikan_txt" => "trim",
					"px_id" => "trim|integer",
					"visit_id" => "trim|integer",
					"note_recipe" => "trim",

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

	public function get_recipe($where)
	{
		return $this->db->get_where("newfarmasi.recipe",$where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("newfarmasi.recipe",$where)->row();
	}

	function faktur($rcp_id)
	{
		//$data	=	array();
		$query_racik = $this->db->query("SELECT
			c.racikan_id,
			sale_qty,
			(c.sale_price+(c.sale_price*COALESCE(C.percent_profit,0)))sale_price,
			c.dosis,
			mt.item_name,
			a.sale_services,
			a.embalase_item_sale,
			a.sale_embalase,
			(c.sale_price*sale_qty+(c.sale_price*sale_qty*COALESCE(percent_profit,0)))
 as subtotal
			FROM
			farmasi.sale a
			JOIN farmasi.sale_detail C ON a.sale_id = c.sale_id
			JOIN admin.ms_item mt ON mt.item_id = c.item_id
			WHERE
			a.rcp_id = $rcp_id")
		->result();
		if (count($query_racik) < 1) {
			$query_racik = array();
		}

		return $query_racik;
	}
}