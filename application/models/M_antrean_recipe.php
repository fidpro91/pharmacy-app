<?php

class M_antrean_recipe extends CI_Model
{

    public function get_data($sLimit, $sWhere, $sOrder, $aColumns)
    {
        $data = $this->db->query("select " . implode(',', $aColumns) . ",x.unit_name,x.sale_id AS id_key from (
			SELECT split_part(s.sale_num, '/', 2) as sale_num,
				COALESCE(unit_name,'APS') as unit_name,
				patient_name,
				sale_id,
                s.kronis,
                s.has_racikan,
				CASE
						
						WHEN COALESCE ( s.sale_status, 0 ) = 0 
						OR COALESCE ( s.sale_status, 0 ) = 1 THEN
							'Proses' ELSE'Selesai' 
							END AS status_resep 
					FROM
						farmasi.sale s
						LEFT JOIN newfarmasi.recipe r ON r.rcp_id = s.rcp_id
						LEFT JOIN yanmed.services srv on s.service_id =  srv.srv_id
						LEFT JOIN admin.ms_unit mu on srv.unit_id = mu.unit_id 
					WHERE
						0 = 0
						$sWhere
						AND DATE ( sale_date ) = DATE (
						now())
					ORDER BY
					s.date_act ASC ) x ")->result_array();

        return $data;
    }

    public function get_total($sWhere, $aColumns)
    {
        $data = $this->db->query("select " . implode(',', $aColumns) . ",x.sale_id AS id_key from (
		SELECT
				split_part(s.sale_num, '/', 2) as sale_num,
				COALESCE(unit_name,'APS') as unit_name,
				patient_name,
				sale_id,
                s.kronis,
                s.has_racikan,
				CASE
						
						WHEN COALESCE ( s.sale_status, 0 ) = 0 
						OR COALESCE ( s.sale_status, 0 ) = 1 THEN
							'Proses' ELSE'Selesai' 
							END AS status_resep 
					FROM
						farmasi.sale s
						LEFT JOIN newfarmasi.recipe r ON r.rcp_id = s.rcp_id
						LEFT JOIN yanmed.services srv on s.service_id =  srv.srv_id
						LEFT JOIN admin.ms_unit mu on srv.unit_id = mu.unit_id 
					WHERE
						0 = 0
						$sWhere 
						AND DATE ( sale_date ) = DATE (
						now())
					ORDER BY
					s.date_act ASC ) x
		")->num_rows();
        return $data;
    }

    public function get_column()
    {
        $col = [
            "sale_num" => [
                "label" => "NO"
            ],
            "patient_name" => [
                "label"     => "nama pasien",
                "custom"    => function($a){
                    return $a["patient_name"]."<br><small class=\"txt_small\">(".$a["unit_name"].")</small";
                }
            ],
            "has_racikan" => [
                "label"     => "Jenis",
                "custom"    => function($a){
                    if ($a["has_racikan"] == "t") {
                        $label = "<span class=\"label label-warning\">RACIKAN</span>";
                    }else{
                        $label = "<span class=\"label label-info\">NON RACIKAN</span>";
                    }
                    return $label;
                }
            ],
            "status_resep" => [
                "label" => "Status"
            ]
        ];
        return $col;
    }

    public function rules()
    {
        $data = [
            "rec_id" => "trim|integer|required",
            "item_id" => "trim|integer|required",
            "expired_date" => "trim",
            "item_pack" => "trim",
            "qty_pack" => "trim|integer",
            "item_unit" => "trim",
            "qty_unit" => "trim|integer|required",
            "unit_per_pack" => "trim|integer",
            "price_pack" => "trim|numeric",
            "price_total" => "trim|numeric",
            "disc_percent" => "trim|numeric",
            "disc_value" => "trim|numeric",
            "disc_extra" => "trim|numeric",
            "price_item" => "trim|numeric",
            "hpp" => "trim|numeric",

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



    public function find_one($where)
    {
        return $this->db->get_where("public.receiving_detail", $where)->row();
    }

    public function get_column_multiple()
    {
        $col = [
            "item_id",
            "expired_date",
            "qty_pack",
            "unit_per_pack",
            "price_item",
            "total_bf_diskon",
            "disc_percent",
            "disc_value",
            "price_total",
        ];
        return $col;
    }
    public function get_receiving_detail($id)
    {
        $data = $this->db->query("
			SELECT rd.*,mi.item_name,mi.item_name as label_item_id,	(qty_unit * price_item) as total_bf_diskon FROM receiving_detail rd
			JOIN ms_item mi ON rd.item_id = mi.item_id			
			WHERE rd.rec_id = $id
		")->result();
        return $data;
    }
}
