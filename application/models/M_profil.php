<?php

class M_profil extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function get_data()
    {
        $query = $this->db->join('admin.ms_region b', 'b.reg_code=a.hsp_prov')
        ->get('admin.profil a')
        ->row_array();

        $distrik = $this->db->where('reg_code', $query['hsp_district'])->get('admin.ms_region')->row();
        $city    = $this->db->where('reg_code', $query['hsp_city'])->get('admin.ms_region')->row();

        $data = $query;
        $data["hsp_city"] = $city->reg_name;
        $data["hsp_district"] = $distrik->reg_name;
        $data["hsp_prov"] = $query['reg_name'];
        return $data;
    }
   
}
