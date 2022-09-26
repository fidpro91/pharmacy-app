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

	public function get_unit( $where, $status=0 )
	{
		$this->db->select('vf.unit_id, vf.unit_name')
			->from( "farmasi.v_unit_farmasi vf");

		if(empty( $where['unit_id']) )
			$this->db ->where("cat_unit_code in (".implode(',', $where).") and unit_active = 't'",null);
		else
			$this->db->where($where);

		if( empty($status) )
			return $this->db ->get()->result();
		else
			return $this->db ->get()->row();
	}
	public function get_data_stok( $input )
	{

		if( empty($input) ){
			$input = 0;
		}

		$sql    = "	SELECT 
						*,p.price_sell::numeric
					FROM 
					newfarmasi.stock vs
					inner join admin.ms_item mi on vs.item_id = mi.item_id
					inner join farmasi.ownership ow on vs.own_id = ow.own_id
					inner join farmasi.price p on vs.item_id = p.item_id and vs.own_id = p.own_id
					where vs.unit_id = '".$input."'
					order by item_name asc";
		$result = $this->db->query($sql);
		return $result->result();
	}

	public function get_laporan_po($unit_id,$tanggal)
	{
		$tgl = explode('/', $tanggal);
		$tanggal_awal = $tgl[0];
		$tanggal_akhir = $tgl[1];

		$this->db->query("select * FROM farmasi.po_detail p
			     LEFT JOIN farmasi.po po ON p.po_id = po.po_id
			     LEFT JOIN admin.ms_item i ON p.item_id = i.item_id
			     LEFT JOIN admin.ms_supplier s ON po.supplier_id=s.supplier_id
			     LEFT JOIN ( SELECT r.po_id,d.item_id,sum(d.qty_pack) AS qtyreceive
			     FROM farmasi.receiving r 
			     LEFT JOIN farmasi.receiving_detail d ON r.rec_id = d.rec_id
			     GROUP BY r.po_id, d.item_id) rd ON p.po_id = rd.po_id AND p.item_id = rd.item_id
				 where supplier_name='$unit_name' and po_date between '$awal' AND '$akhir'")->result();

	}

	public function get_obat_exp($unit_id,$tanggal)
	{
		$tgl = explode('/', $tanggal);
		$tanggal_awal = $tgl[0];
		$tanggal_akhir = $tgl[1];
		$sql = "select expired_date, unit_name,item_code, item_name,own_name,qty_stock from newfarmasi.v_stock_fifo_unit
				where unit_id='$unit_id' and expired_date between '$tanggal_awal' AND '$tanggal_akhir'";
		$result = $this->db->query($sql)->result();
		return $result;
	}

	public function get_unit_stok_min($input=0)
	{
		$where = ($input > 0)? "where vf.unit_id =".$input : '';
		$sql = " SELECT 
						vf.unit_id, vf.unit_name 
					FROM 
					farmasi.v_unit_farmasi vf
					$where";
		$result = $this->db->query($sql);
		if($input > 0){
			return $result->row();
		} else {
			return $result->result();
		}
	}
	public function get_detail($input)
	{
		if( empty($input) )
			$input = 0;

		$sql = "SELECT mi.item_id,mi.item_code,mi.item_name,ow.own_name,s.own_id,s.unit_id,s.stock_summary,sum(COALESCE(x.stock_summary,0))stock_all_unit,sum(sp.keluar)jml_keluar,
			(sum(sp.keluar)/EXTRACT('day' FROM CURRENT_DATE - date_trunc('day', current_date - interval '1' month)))rata2_keluar
			FROM newfarmasi.stock s
			INNER JOIN admin.ms_item mi ON mi.item_id = s.item_id
			INNER JOIN farmasi.ownership ow ON ow.own_id = s.own_id
			LEFT JOIN (
				SELECT unit_id,own_id,item_id,sum(kredit)keluar FROM newfarmasi.stock_process WHERE trans_type = 2 AND (date_trans BETWEEN date_trunc('day', current_date - interval '1' month) AND CURRENT_DATE) 
				GROUP BY unit_id,own_id,item_id
			)sp ON sp.unit_id = s.unit_id AND s.item_id = sp.item_id AND s.own_id = sp.own_id
			LEFT JOIN (
				SELECT unit_id,own_id,item_id,stock_summary FROM newfarmasi.stock  
			) x ON x.item_id = s.item_id AND s.own_id = x.own_id AND x.unit_id != s.unit_id
			WHERE s.unit_id = '$input'
			GROUP BY mi.item_id,mi.item_code,mi.item_name,s.own_id,s.unit_id,s.stock_summary,ow.own_name
			ORDER BY mi.item_name";
		$result = $this->db->query($sql);
		return $result->result();
	}

	public function get_slowfast_bulanan($unit_id, $tanggal){
		$tgl = explode('/', $tanggal);
		$tanggal_awal = $tgl[0];
		$tanggal_akhir = $tgl[1];

		$sql = " SELECT 
				  mi.item_code,
				  mi.item_name,
				  ow.own_name,
				  SUM(sd.sale_qty) AS qty
				FROM farmasi.sale s 
				  INNER JOIN farmasi.sale_detail sd ON sd.sale_id = s.sale_id
				  INNER JOIN admin.ms_item mi ON mi.item_id = sd.item_id
				  INNER JOIN farmasi.ownership ow ON ow.own_id = s.own_id
				WHERE s.unit_id = '{$unit_id}'
					AND s.sale_date between '{$tanggal_awal}' AND '{$tanggal_akhir}'
				GROUP BY mi.item_code, mi.item_name, ow.own_name
				ORDER BY SUM(sd.sale_qty) DESC";
		$result = $this->db->query($sql)->result();
		return $result;
	}

	public function stok_opname($where)
	{
		$sql = "SELECT 
	i.item_id,
	i.item_name,
	opr.opname_note,
	opr.opname_header_id,
	opr.opname_date,
	o.qty_adj,
	o.qty_data,
	o.qty_opname,
	op.item_price :: NUMERIC,
	op.item_price :: NUMERIC * o.qty_adj AS nilai_adj,
	op.item_price :: NUMERIC * o.qty_data AS nilai_sistem,
	op.item_price :: NUMERIC * o.qty_opname AS nilai_opname 
FROM
	(
	SELECT
		item_id,
		MAX ( A.opname_header_id ) AS opname_id 
	FROM
		(
		SELECT
			o.opname_header_id,
			item_id,
			opname_date,
			opname_note 
		FROM
			newfarmasi.opname o
			JOIN newfarmasi.opname_header oh on o.opname_header_id = oh.opname_header_id
		WHERE
			0 = 0 
			$where
		) a 
	GROUP BY
		item_id 
	) b
	INNER JOIN newfarmasi.opname_header opr ON opr.opname_header_id = b.opname_id
	INNER JOIN newfarmasi.opname o on o.opname_header_id = opr.opname_header_id
	INNER JOIN ( SELECT opname_header_id, MAX ( item_price ) item_price FROM newfarmasi.opname GROUP BY opname_header_id ) op ON op.opname_header_id = opr.opname_header_id
	INNER JOIN ADMIN.ms_item i ON i.item_id = o.item_id";

		$result = $this->db->query($sql);
		$result = $result->result();
		return $result;
	}

	public function get_supplier()
	{
		$sql = "select * from admin.ms_supplier";
		$result = $this->db->query($sql)->result();
		return $result;
	
	}

	public function get_unit_retur()
	{
		$code_gudang 		= "'0502'";
		$code_produksi		= "'0503'";
		$data['data'] = [];
		$codeUnit = array($code_gudang,$code_produksi);

		$sql = "select
		vf.unit_id,
		vf.unit_name
		from
		newfarmasi.v_unit_farmasi vf
		where cat_unit_code in (".implode(',', $codeUnit).") and vf.unit_active = 't' ";
		$result = $this->db->query($sql)->result();
		return $result;

	}

	public function get_item()
	{
		$data = $this->db->query("select *,item_name as value from admin.ms_item where item_active = 't'")->result();
		return $data;
	}

	public function get_data_retur($where)
	{
		$data = $this->db->query("
		SELECT
	rr.num_retur,
	to_char( rr.rr_date, 'DD-MM-YYYY HH24:MM:SS' ) AS tgl_retur,
	s.supplier_name,
	json_agg (
		concat (
			i.item_code,
			'||',
			i.item_name,
			'||',
			rrd.rrd_qty,
			'||',
			rrd.rrd_price :: NUMERIC,
			'||',
			( rrd.rrd_price :: NUMERIC * rrd.rrd_qty ) 
		) 
	) AS detail_retur
FROM
	newfarmasi.receiving_retur rr
	INNER JOIN newfarmasi.receiving_retur_detil rrd ON rr.rr_id = rrd.rr_id
	INNER JOIN ADMIN.ms_item i ON rrd.item_id = i.item_id
	INNER JOIN ADMIN.ms_supplier s ON rrd.supplier_id = s.supplier_id 
WHERE 0=0
	$where
GROUP BY
	rr.num_retur,
	s.supplier_name,
	rr.rr_date 
ORDER BY
	rr.rr_date DESC
			")->result();

		if (count($data)>0) {
			return $data;
		}else{
			return array();
		}
	}
	public function get_golongan()
	{
		$sql="
            SELECT distinct(gol) as gol from admin.ms_item 
            where gol is not null and gol !=''
            order by gol asc ";
		$result= $this->db->query($sql)->result();
		return $result;
	}

	public function get_new_konsolidasi($where,$where2,$unit_id,$own_id)
	{
		$sql = "SELECT vo.item_code,vo.item_name,ow.own_name,coalesce(y.stock_awal,0)stock_awal,coalesce(y.harga_awal,0)harga_awal,s.item_id,s.own_id,s.unit_id,(p.price_buy)::numeric harga,(coalesce(x.masuk,0))masuk,(coalesce(x.keluar,0))keluar,coalesce(z.stock_op,0)stock_op,coalesce(z.harga_so,0)harga_so FROM farmasi.stock s
            INNER JOIN farmasi.v_obat vo on vo.item_id = s.item_id
            INNER JOIN farmasi.ownership ow ON s.own_id = ow.own_id
            INNER JOIN farmasi.price p on p.own_id = s.own_id AND p.item_id = s.item_id
            LEFT JOIN (
                SELECT sp.stock_after as stock_awal,sp.item_id,sp.own_id,sp.unit_id,sp.item_price as harga_awal FROM farmasi.stock_process sp
                INNER JOIN (
                                SELECT max(sp.stockprocess_id)idsp,sp.item_id,sp.own_id,sp.unit_id FROM farmasi.stock_process sp
                                WHERE 0=0 $where2 
                                GROUP BY sp.item_id,sp.own_id,sp.unit_id
                ) x ON sp.stockprocess_id = x.idsp
            ) y  ON s.item_id = y.item_id AND s.own_id = y.own_id AND s.unit_id = y.unit_id
            LEFT JOIN (
                SELECT sum(kredit-debet) as stock_op,sp.item_id,sp.own_id,sp.unit_id,max(sp.item_price) as harga_so FROM farmasi.stock_process sp
                WHERE sp.trans_type = 5 $where
                GROUP BY sp.item_id,sp.own_id,sp.unit_id
            )z ON s.item_id = z.item_id AND s.own_id = z.own_id AND s.unit_id = z.unit_id
            LEFT JOIN (
                SELECT sum(coalesce(sp.debet,0))masuk,sum(coalesce(sp.kredit,0))keluar,sp.item_id,sp.own_id,sp.unit_id FROM farmasi.stock_process sp
                WHERE sp.trans_type != 5 $where
                GROUP BY sp.item_id,sp.own_id,sp.unit_id
            )x ON s.item_id = x.item_id AND s.own_id = x.own_id AND s.unit_id = x.unit_id
            where s.unit_id = $unit_id AND s.own_id = $own_id
            order by vo.item_name";
		$result= $this->db->query($sql)->result();
		return $result;
	}

	public function get_unit_asal( )
	{
		$code_gudang 		= "'0502'";
		$code_produksi		= "'0503'";
		$codeUnit = array($code_gudang);

		$sql = "select
		vf.unit_id,
		vf.unit_name
		from
		newfarmasi.v_unit_farmasi vf
		where cat_unit_code in (".implode(',', $codeUnit).") and vf.unit_active = 't' ";
		$result = $this->db->query($sql)->result();
		return $result;
	}
	public function get_unit_peminta( )
	{
		$sql = "select 
					* 
				from admin.ms_unit
				order by unit_name";
		return  $this->db->query($sql)->result();
	}

	public function get_data_pobat( $unit_id, $unit_peminta, $tgl_awal, $tgl_akhir, $own_id)
	{
		$where = "";
		if ($unit_peminta) {
			$where .= " and b.unit_id = '$unit_peminta'";
		}

//		$sql    = "	SELECT
//						m.mutation_date as tgl,
//						b.bon_no no_minta,
//						m.mutation_id,
//						m.mutation_no no_mutasi,
//						b.unit_id as unit_minta,
//						v.unit_name unit_minta,
//						b.unit_target as unit_kirim,
//						vt.unit_name unit_kirim,
//						vo.item_name,
//						vo.item_code,
//						vo.item_unitofitem as satuan,
//						p.price_sell::numeric as harga,
//						o.own_name,
//						rd.qty_unit
//					From
//						newfarmasi.mutation m
//					inner join farmasi.bon b
//						on m.bon_id = b.bon_id
//					inner join newfarmasi.receiving r
//						on m.mutation_id = r.mutation_id
//					inner join newfarmasi.receiving_detail rd
//						on r.rec_id = rd.rec_id
//					inner join farmasi.v_obat vo
//					on vo.item_id = rd.item_id
//					inner join farmasi.ownership o
//						on o.own_id =  b.own_id
//					inner join farmasi.price p
//						on p.item_id = vo.item_id and p.own_id = b.own_id
//					inner join(
//						select * from newfarmasi.v_unit_farmasi
//							union
//						select * from farmasi.v_unit_pelayanan
//					) v
//						on v.unit_id  =  b.unit_id
//					inner join (
//						select * from newfarmasi.v_unit_farmasi
//							union
//						select * from farmasi.v_unit_pelayanan
//					) vt
//						on vt.unit_id  =  b.unit_target
//					where /*mutation_status = 1
//					and */r.rec_type = 1 $where
//					and b.unit_target = '$unit_id'
//					and date(m.mutation_date) between  '$tgl_awal' and '$tgl_akhir'
//					order by vo.item_name";

		$sql    = "	SELECT 
	    m.mutation_date as tgl,
	    m.bon_no as no_minta,
m.mutation_id,m.mutation_no no_mutasi,
m.unit_require as unit_minta,
v.unit_name as unit_minta,
m.unit_sender as unit_kirim,

	vo.item_name,
	vo.item_code,
	vo.item_unitofitem AS satuan,
	P.price_sell :: NUMERIC AS harga,
	
	o.own_name,
	md.qty_send as qty_unit
	
	FROM newfarmasi.mutation m
	JOIN newfarmasi.mutation_detail md on m.mutation_id = md.mutation_id
	JOIN farmasi.v_obat vo on md.item_id = vo.item_id
	JOIN farmasi.ownership o ON m.own_id = o.own_id
	JOIN farmasi.price p ON vo.item_id = p.item_id
	join(select * from newfarmasi.v_unit_farmasi 
			union
		select * from farmasi.v_unit_pelayanan  	
	) v on v.unit_id  =  m.unit_require
	join (select * from newfarmasi.v_unit_farmasi 
			union
		select * from farmasi.v_unit_pelayanan  
	) vt on vt.unit_id  =  m.unit_sender
	WHERE M.mutation_date BETWEEN '$tgl_awal'
	AND '$tgl_akhir' AND M.mutation_status = '2'
	AND m.unit_sender = '$unit_id' $where

GROUP BY m.mutation_date,m.mutation_id,m.unit_require,v.unit_name,m.unit_sender,o.own_name,md.qty_send,m.bon_no,m.mutation_no,
	vo.item_name,
	vo.item_code,
	vo.item_unitofitem,
	P.price_sell 
ORDER BY
	vo.item_name";
		return $this->db->query($sql)->result();
	}

	public function get_data_byItem($unit_id, $unit_peminta, $tgl_awal, $tgl_akhir, $own_id)
	{//var_dump($unit_id);die;
		$where = "";
		if ($unit_peminta) {
			$where .= " and m.unit_require = '$unit_peminta'";
		}

		if ($own_id != "semua") {
			$where .= " and m.own_id = '$own_id'";
		}

//		$sql    = "	SELECT
//						vo.item_name,
//						vo.item_code,
//						vo.item_unitofitem as satuan,
//						p.price_sell::numeric as harga,
//						sum(rd.qty_unit) jml_qty
//					From
//						newfarmasi.mutation m
//					inner join farmasi.bon b
//						on m.bon_id = b.bon_id
//					inner join newfarmasi.receiving r
//						on m.mutation_id = r.mutation_id
//					inner join newfarmasi.receiving_detail rd
//						on r.rec_id = rd.rec_id
//					inner join farmasi.v_obat vo
//					on vo.item_id = rd.item_id
//					inner join farmasi.ownership o
//						on o.own_id =  b.own_id
//					inner join famrasi.price p
//						on p.item_id = vo.item_id and p.own_id = b.own_id
//					inner join(
//						select * from newfarmasi.v_unit_farmasi
//							union
//						select * from farmasi.v_unit_pelayanan
//					) v
//						on v.unit_id  =  b.unit_id
//					inner join (
//						select * from newfamrasi.v_unit_farmasi
//							union
//						select * from farmasi.v_unit_pelayanan
//					) vt
//						on vt.unit_id  =  b.unit_target
//					where /*mutation_status = 1
//					and */r.rec_type = 1 $where
//					and b.unit_target = '$unit_id'
//					and m.mutation_date between  '$tgl_awal' and '$tgl_akhir'
//					group by vo.item_name,
//						vo.item_code,
//						vo.item_unitofitem,
//						p.price_sell
//					order by vo.item_name";
		$sql    = "	SELECT 
	vo.item_name,
	vo.item_code,
	vo.item_unitofitem AS satuan,
	P.price_sell :: NUMERIC AS harga,
	SUM ( md.qty_send ) jml_qty
	FROM newfarmasi.mutation m
	JOIN newfarmasi.mutation_detail md on m.mutation_id = md.mutation_id
	JOIN farmasi.v_obat vo on md.item_id = vo.item_id
	JOIN farmasi.ownership o ON m.own_id = o.own_id
	JOIN farmasi.price p ON vo.item_id = p.item_id
	WHERE M.mutation_date BETWEEN '$tgl_awal' 
	AND '$tgl_akhir'
	AND m.unit_sender = '$unit_id' $where

GROUP BY
	vo.item_name,
	vo.item_code,
	vo.item_unitofitem,
	P.price_sell 
ORDER BY
	vo.item_name";
		return $this->db->query($sql)->result();
	}

	public function get_rekapData($unit_id,$unit_peminta,$own_id,$jns_laporan,$tgl_awal,$tgl_akhir)
	{
		if( empty($tgl_awal) ) {
			$tgl_awal = date("d-m-Y");
		}

		if( empty($tgl_akhir) ) {
			$tgl_akhir = date("d-m-Y");
		}

		if( empty($unit_peminta) ) {
			$unit_peminta = 0;
		}

		if( empty($unit_id) ) {
			$unit_id = 0;
		}
		$where = " AND r.receiver_unit != '$unit_id' AND r.sender_unit = '$unit_id' AND r.receiver_date between '$tgl_awal' AND '$tgl_akhir'";
		if ($own_id != "semua") {
			$where .= " AND r.own_id = '$own_id'";
		}
		$data = $this->db->query("
        SELECT mu.unit_id,mu.unit_name,rec.detail FROM admin.ms_unit mu
LEFT JOIN (
  SELECT x.receiver_unit,json_agg((x.bulan,x.total) ORDER BY x.bulan)detail FROM (
    SELECT r.receiver_unit,EXTRACT('month' FROM r.rec_date)bulan,sum(COALESCE(rd.price_total,0))total FROM newfarmasi.receiving r
    INNER JOIN newfarmasi.receiving_detail rd ON r.rec_id = rd.rec_id
    where 0=0 $where
    GROUP BY r.receiver_unit,EXTRACT('month' FROM r.rec_date)
  )x
  GROUP BY x.receiver_unit
) rec ON mu.unit_id = rec.receiver_unit
WHERE mu.unit_active = 't'
ORDER BY mu.unit_type,mu.unit_name ASC")->result();

		if (count($data)>0) {
			return $data;
		}else{
			return array();
		}
	}
}
