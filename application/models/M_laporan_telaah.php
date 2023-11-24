<?php

class M_laporan_telaah extends CI_Model {



    public function get_data($where){
        $data = $this->db->query("
        select *,
        CASE		
		WHEN ( x.duplikasi = 1 OR x.interaksi_obat = 1 ) 
		OR (
			x.kejelasan_tulisan = 0 
			OR x.penulisan_resep = 0 
			OR x.ketepatan_pasien = 0 
			OR x.ketepatan_obat = 0 
			OR x.ketepatan_dosis = 0 
			OR x.ketepatan_rute = 0 
			OR x.ketepatan_waktu = 0 
			) THEN
			1 ELSE 0 
		END AS identifikasi
       from (SELECT
           px_norm,
           px_name,
           person_name,
           rcp_date,
           u.unit_name,          
           sum (CASE WHEN rr.reff_id = 1225 THEN 1 else 0 end) as penulisan_resep,
           sum (CASE WHEN rr.reff_id = 1226 THEN 1 else 0 end) as kejelasan_tulisan,
           sum (CASE WHEN rr.reff_id = 1227 THEN 1 else 0 end) as ketepatan_pasien,
           sum (CASE WHEN rr.reff_id = 1228 THEN 1 else 0 end) as ketepatan_obat,
           sum (CASE WHEN rr.reff_id = 1229 THEN 1 else 0 end) as ketepatan_dosis,
           sum (CASE WHEN rr.reff_id = 1250 THEN 1 else 0 end) as ketepatan_rute,
           sum (CASE WHEN rr.reff_id = 1251 THEN 1 else 0 end) as ketepatan_waktu,
           sum (CASE WHEN rr.reff_id = 1252 THEN 1 else 0 end) as duplikasi,
           sum (CASE WHEN rr.reff_id = 1253 THEN 1 else 0 end) as interaksi_obat,
           note_recipe 
       FROM
           newfarmasi.recipe r
           JOIN newfarmasi.review_recipe rr ON r.rcp_id = rr.rcp_id
           JOIN yanmed.patient P ON r.px_id = P.px_id
           JOIN ADMIN.ms_unit u ON r.unit_id_layanan = u.unit_id
           JOIN ADMIN.ms_unit u2 ON r.unit_id = u2.unit_id
           JOIN ADMIN.ms_reff rf ON rr.reff_id = rf.reff_id
           JOIN ADMIN.ms_user us ON r.user_id = us.user_id 
       WHERE
           0=0 $where
           GROUP BY px_norm,px_name,person_name,rcp_date,u.unit_name,note_recipe ) x       
		  ")->result();
		  return $data;
    }

 
}