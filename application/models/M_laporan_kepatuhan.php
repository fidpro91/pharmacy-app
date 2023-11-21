<?php

class M_laporan_kepatuhan  extends CI_Model {



    public function get_data($where){
        $data = $this->db->query("SELECT
		rcp_no,
		  px_norm,
		  px_name,
		  unit_name,
		  person_name,	
		  count(*) total_item,
	   sum(CASE WHEN i.is_formularium = 't' and i.type_formularium = '610' THEN 1 ELSE 0 end )  as formula_rs ,
	   sum (CASE WHEN i.is_formularium = 't' and i.type_formularium = '608' THEN 1 ELSE 0 END) formula,
	   sum (CASE WHEN i.is_formularium = 'f' THEN 1   ELSE 0 END) nonformula,
	   string_agg(DISTINCT concat(i2.item_name),'</br> - ') as keterangan
	  FROM
		  newfarmasi.recipe_detail rd
		  JOIN newfarmasi.recipe r ON rd.rcp_id = r.rcp_id
		  JOIN ADMIN.ms_item i ON rd.item_id = i.item_id
		  LEFT JOIN admin.ms_item i2 on rd.item_id = i2.item_id and i2.is_formularium = 'f'
		  JOIN yanmed.patient P ON r.px_id = P.px_id
		  JOIN ADMIN.ms_unit u ON r.unit_id_layanan = u.unit_id
		  JOIN ADMIN.ms_user us ON r.user_id = us.user_id
		  LEFT JOIN admin.ms_reff rf on i.type_formularium = rf.reff_id	
	  WHERE 0=0 
	  and (i.comodity_id = 1 or i2.comodity_id = 1) 
	  $where		
	  GROUP BY
		rcp_no,
		  px_norm,
		  px_name,
		  unit_name,
		  person_name	  
		  ")->result();
		  return $data;
    }
}