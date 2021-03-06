<?php
class M_lap_konsolidasi  extends CI_Model {

	public function get_golongan()
	{
		$data = $this->db->query("
        SELECT distinct(gol) as gol from admin.ms_item 
        where gol is not null and gol !=''
        order by gol asc
			")->result();
		return $data;
	}
    public function get_new_konsolidasi($where,$where2,$unit,$kepemilikan)
    {
       $data =  $this->db->query("SELECT vo.item_code,vo.item_name,ow.own_name,coalesce(y.stock_awal,0)stock_awal,coalesce(y.harga_awal,0)harga_awal,s.item_id,s.own_id,s.unit_id,(p.price_buy)::numeric harga,(coalesce(x.masuk,0))masuk,(coalesce(x.keluar,0))keluar,coalesce(z.stock_op,0)stock_op,coalesce(z.harga_so,0)harga_so FROM farmasi.stock s
            INNER JOIN farmasi.v_obat vo on vo.item_id = s.item_id
            INNER JOIN farmasi.ownership ow ON s.own_id = ow.own_id
            INNER JOIN farmasi.price p on p.own_id = s.own_id AND p.item_id = s.item_id
            LEFT JOIN (
                SELECT sp.stock_after as stock_awal,sp.item_id,sp.own_id,sp.unit_id,sp.item_price as harga_awal FROM newfarmasi.stock_process sp
                INNER JOIN (
                                SELECT max(sp.stockprocess_id)idsp,sp.item_id,sp.own_id,sp.unit_id FROM farmasi.stock_process sp
                                WHERE 0=0 $where2 
                                GROUP BY sp.item_id,sp.own_id,sp.unit_id
                ) x ON sp.stockprocess_id = x.idsp
            ) y  ON s.item_id = y.item_id AND s.own_id = y.own_id AND s.unit_id = y.unit_id
            LEFT JOIN (
                SELECT sum(kredit-debet) as stock_op,sp.item_id,sp.own_id,sp.unit_id,max(sp.item_price) as harga_so FROM newfarmasi.stock_process sp
                WHERE sp.trans_type = 5 $where
                GROUP BY sp.item_id,sp.own_id,sp.unit_id
            )z ON s.item_id = z.item_id AND s.own_id = z.own_id AND s.unit_id = z.unit_id
            LEFT JOIN (
                SELECT sum(coalesce(sp.debet,0))masuk,sum(coalesce(sp.kredit,0))keluar,sp.item_id,sp.own_id,sp.unit_id FROM newfarmasi.stock_process sp
                WHERE sp.trans_type != 5 $where
                GROUP BY sp.item_id,sp.own_id,sp.unit_id
            )x ON s.item_id = x.item_id AND s.own_id = x.own_id AND s.unit_id = x.unit_id
            where s.unit_id = $unit AND s.own_id = $kepemilikan
            order by vo.item_name")->result();
       
        return $data;
    }
}