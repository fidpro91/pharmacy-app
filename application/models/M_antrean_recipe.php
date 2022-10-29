<?php

class M_antrean_recipe extends CI_Model
{

    public function get_data($sLimit, $sWhere, $sOrder, $aColumns)
    {
        $data = $this->db->query("
				select " . implode(',', $aColumns) . ",recdet_id as id_key  from public.receiving_detail where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
        return $data;
    }

    public function get_total($sWhere, $aColumns)
    {
        $data = $this->db->query("
				select " . implode(',', $aColumns) . ",recdet_id as id_key  from public.receiving_detail where 0=0 $sWhere
			")->num_rows();
        return $data;
    }

    public function get_column()
    {
        $col = [
            "recdet_id",
            "rec_id",
            "item_id",
            "expired_date",
            "item_pack",
            "qty_pack",
            "item_unit",
            "qty_unit",
            "unit_per_pack",
            "price_pack",
            "price_total",
            "disc_percent",
            "disc_value",
            "disc_extra",
            "qty_retur",
            "price_item",
            "hpp"
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
            // "item_pack",
            // "item_unit",
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
