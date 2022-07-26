<?php

class M_laporan_gudang extends CI_Model {
	public function get_data_sumber()
	{

		$data = $this->db->query("select DISTINCT upper(trim(NULLIF(estimate_resource,''))) estimate_resource
from farmasi.receiving
group by upper(trim(NULLIF(estimate_resource,''))) order by upper(trim(NULLIF(estimate_resource,''))) asc");

		return $data->result();
	}

	public function get_profil_rs()
	{
		$profil = $this->db->join('admin.ms_region b','b.reg_code=a.hsp_prov')
			->get('admin.profil a')
			->row();
		$distrik = $this->db->where('reg_code', $profil->hsp_district)->get('admin.ms_region')->row();
		$city    = $this->db->where('reg_code', $profil->hsp_city)->get('admin.ms_region')->row();

		return $data = array(
			"kota"    => $city->reg_name,
			"kecamatan" => $distrik->reg_name,
			"propinsi"  => $profil->reg_name,
			"nama"    => $profil->hsp_name,
			"telp"    => $profil->hsp_phone,
			"alamat"  => $profil->hsp_address
		);
	}

	public function get_lap_penerimaan($where)
	{
		$data = $this->db->query("SELECT
	array_to_json (
		ARRAY_AGG (
			concat (
				i.item_code,
				'|',
				i.item_name,
				'|',
				recdet.qty_unit,
				'|',
				recdet.qty_pack,
				'|',
				recdet.unit_per_pack,
				'|',
				recdet.price_pack,
				'|',
				recdet.disc_percent,
				'|',
				recdet.disc_value,
				'|',
				recdet.price_total 
			))) AS detail_item,
	rec.rec_id,
	rec.rec_num,
	rec.receiver_num,
	rec.rec_date,
	COALESCE ( sp.supplier_name, rec.sender_name ) supplier_name,
	P.po_ppn 
FROM
	newfarmasi.receiving rec
	LEFT JOIN farmasi.po P ON P.po_id = rec.po_id
	INNER JOIN newfarmasi.receiving_detail recdet ON rec.rec_id = recdet.rec_id
	INNER JOIN ADMIN.ms_item i ON recdet.item_id = i.item_id
	LEFT JOIN ADMIN.ms_supplier sp ON sp.supplier_id = rec.supplier_id 
WHERE
	0 = 0 
	$where
GROUP BY
	rec.rec_id,
	rec.rec_num,
	rec.receiver_num,
	rec.rec_date,
	sp.supplier_name,
	P.po_ppn,rec.sender_name
ORDER BY
	rec.rec_id DESC")->result();

		if (count($data)>0) {
			return $data;
		}else{
			return array();
		}
	}

	public function get_lap_penerimaan_05($where)
	{
		$data = $this->db->query("select item_name,SUM(qty_unit) as qty_unit, SUM((price_total) - disc_value + ppn) as price_total from farmasi.v_penerimaan where 0=0 $where and comodity_id != 5 GROUP BY item_name ORDER BY item_name ASC")->result();

		if (count($data)>0) {
			return $data;
		}else{
			return array();
		}
	}

	public function get_lap_penerimaan_04($where1,$where2)
	{
		$data = $this->db->query("SELECT cb.supplier_id,cb.supplier_name,array_to_json(array_agg(cb.gabung)) as detail_item 
FROM ( 
SELECT rc.supplier_id,sp.supplier_name,dt.gabung 
FROM newfarmasi.receiving rc
INNER JOIN admin.ms_supplier sp on rc.supplier_id = sp.supplier_id
INNER JOIN (
          SELECT r.supplier_id,r.rec_id,concat(r.rec_num,'|',r.receiver_num,'|',r.rec_date,'|',sum(((COALESCE(po.po_ppn,0)*(rd.price_total- COALESCE(rd.disc_value,0)))/100)+(rd.price_total-coalesce(rd.disc_value,0)))) gabung,sum(((COALESCE(po.po_ppn,0)*(rd.price_total- COALESCE(rd.disc_value,0)))/100)+rd.price_total-rd.disc_value) as sub_total 
				from newfarmasi.receiving r
          INNER JOIN newfarmasi.receiving_detail rd on r.rec_id = rd.rec_id
          left JOIN farmasi.po on po.po_id = r.po_id
          where 0=0 $where2 and r.receiver_unit = 55
          GROUP BY r.rec_id,r.supplier_id,r.rec_num,r.receiver_num,r.rec_date order by r.rec_id desc
        ) dt on rc.supplier_id = dt.supplier_id
        where 0=0 $where1
        GROUP BY rc.supplier_id,sp.supplier_name,dt.gabung) cb 
        GROUP BY cb.supplier_id,cb.supplier_name order by cb.supplier_name asc")->result();

		if (count($data)>0) {
			return $data;
		}else{
			return array();
		}
	}

	public function get_lap_penerimaan_02($where1,$where2)
	{
		$data = $this->db->query("SELECT cb.supplier_id,cb.supplier_name,json_agg(cb.gabung) as detail_item FROM (
    SELECT sp.supplier_id,sp.supplier_name,dt.gabung 
    FROM newfarmasi.receiving rc 
    INNER JOIN admin.ms_supplier sp on rc.supplier_id = sp.supplier_id 
    INNER JOIN ( 
        SELECT r.supplier_id,r.rec_id,concat(r.rec_num,'|',r.receiver_num,'|',r.rec_date,'|',
        json_agg(concat(rd.item_id,'-*-',i.item_code,'-*-',i.item_name,'-*-',rd.qty_pack,'-*-',rd.qty_unit,'-*-',rd.price_pack,'-*-',
        rd.disc_percent,'-*-',rd.price_total,'-*-',rd.unit_per_pack)),'|',p.po_ppn) gabung
        from newfarmasi.receiving r 
        INNER JOIN newfarmasi.receiving_detail rd on r.rec_id = rd.rec_id
        INNER JOIN farmasi.po P ON P.po_id = r.po_id
        INNER JOIN admin.ms_item i on rd.item_id= i.item_id
        where 0=0 $where2 and r.receiver_unit = 55
        GROUP BY r.rec_id,r.supplier_id,p.po_ppn,r.rec_num,r.receiver_num,r.rec_date order by r.rec_id desc
    ) dt on rc.supplier_id = dt.supplier_id 
    where 0=0 $where1
    GROUP BY sp.supplier_id,dt.gabung
) cb

GROUP BY cb.supplier_id,cb.supplier_name
order by cb.supplier_name asc ")->result();

		if (count($data)>0) {
			return $data;
		}else{
			return array();
		}
	}

	public function get_lap_penerimaan_06($where)
	{
		$data = $this->db->query("SELECT x.rec_date,x.no_faktur,x.supplier_name,sum(x.price_total)total FROM (
              SELECT rc.rec_date,COALESCE(nullif(rc.rec_num,''),concat('Faktur belum terbit(',rc.rec_id,')'))no_faktur,sup.supplier_name,(((COALESCE(po.po_ppn,0)*(rd.price_total- COALESCE(rd.disc_value,0)))/100)+rd.price_total-rd.disc_value)price_total FROM newfarmasi.receiving rc
              INNER JOIN newfarmasi.receiving_detail rd ON rc.rec_id = rd.rec_id
              INNER JOIN farmasi.po on po.po_id = rc.po_id
              INNER JOIN admin.ms_supplier sup ON rc.supplier_id = sup.supplier_id
              where 0=0 $where
            ) x
            GROUP BY x.rec_date,x.no_faktur,x.supplier_name
            ORDER BY x.rec_date
    ")->result();

		if (count($data)>0) {
			return $data;
		}else{
			return array();
		}
	}

	public function get_data_penerimaan( $unit_code, $param)
	{
		$where="";
		if ($param['pay_type']) {
			$where = "and vp.pay_type = '".$param['pay_type']."'";
		}

		if ($param['sumber_anggaran']) {
			$where .= "AND lower(vp.estimate_resource) like lower('".$param['sumber_anggaran']."')";
		}

		$sql    = "	SELECT 
						* 
					FROM 
					newfarmasi.v_penerimaan vp
					inner join farmasi.v_unit_farmasi vf
						on vf.unit_id = vp.receiver_unit
					where vp.rec_type = ".$param['jenis']."
					AND vp.own_id = ".$param['own_id']."
					AND vf.cat_unit_code = '$unit_code'
					AND vp.receiver_date between  '".$param['tanggal_awal']."' and '".$param['tanggal_akhir']."'
					$where
				  ";
		$result = $this->db->query($sql);
		return $result->result();
	}
}
