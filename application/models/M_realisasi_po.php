<?php
class M_realisasi_po extends CI_Model {

	public static $schemaFarmasi= "farmasi.";
    public static $schemaNewFarmasi= "newfarmasi.";
	public static $schemaAdmin 	= "admin.";

	public function get_profil_rs()
	{
		$profil = $this->db->join('admin.ms_region b','b.reg_code=a.hsp_prov')
                          ->get('admin.profil a')
                          ->row();
        $distrik = $this->db->where('reg_code', $profil->hsp_district)->get('admin.ms_region')->row();
        $city    = $this->db->where('reg_code', $profil->hsp_city)->get('admin.ms_region')->row();

        return $data = array(
        	"kota" 		=> $city->reg_name,
            "kecamatan" => $distrik->reg_name,
            "propinsi" 	=> $profil->reg_name,
            "nama" 		=> $profil->hsp_name,
            "telp" 		=> $profil->hsp_phone,
            "alamat" 	=> $profil->hsp_address 
        );
    }

	public function get_data_realisasi( $supplier, $own_id,$tgl1, $tgl2 )
    {   
        $whereSupplier = '';
        if( !empty($supplier) )
            $whereSupplier ="AND p.supplier_id = $supplier ";
		$own='';
		if(!empty($own_id)){
			$own="AND p.own_id = $own_id";
		} 

        $sql    = "SELECT 
                        po_id,
                        p.po_date,
                        p.po_no,
                        p.supplier_id,
                        supplier_name nama,
                        p.po_ppn
                    FROM 
                    ".self::$schemaFarmasi."po p
                    inner join ".self::$schemaAdmin."ms_supplier s
                        on p.supplier_id = s.supplier_id
                    where 0=0 $own
                        AND p.po_date between  '$tgl1' and '$tgl2'
                        $whereSupplier
                    order by p.po_date asc, supplier_name asc";
        $result = $this->db->query($sql)->result();

        return $result;
    }

     public function get_data_detail( $id )
    {   
        $sql    = "SELECT 
                        *
                    FROM 
                    ".self::$schemaNewFarmasi."v_po_receiving_new vpr
                    where vpr.po_id     = $id";
        $result = $this->db->query($sql)->result();

        return $result;
    }
}