<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
	}
    public function index()
    {
        $data['obat_exp'] = $this->db->query('select COUNT(*) total from farmasi.v_expired where 0=0 AND bulan = 0')->row();
        $data['obat_akan_exp'] = $this->db->query('select count(*) total from farmasi.v_expired where 0=0 AND bulan = 3')->row();
        $data['obat_habis'] = $this->db->query('SELECT count(*) total FROM newfarmasi.stock_fifo where stock_saldo<0')->row();
        $data['obat_akan_habis']=$this->db->query('SELECT count(*) total FROM newfarmasi.stock_fifo where stock_saldo<20')->row();

        $data['tot_penjualan_terbayak_unit_item'] = $this->db->query("SELECT count(*) JUMLAH, i.item_id, item_name,u.unit_id, unit_name
        from farmasi.sale s
        join farmasi.sale_detail sd on s.sale_id = sd.sale_id
        join admin.ms_unit u on s.unit_id = u.unit_id
        join admin.ms_item i on sd.item_id = i.item_id
        WHERE to_char(sale_date,'YYYY-MM') = '2018-07' -- to_char(now(), 'YYYY')
        GROUP BY i.item_id, item_name,u.unit_id, unit_name
        limit 5")->result();

        $data['tot_perjualan_unit'] = $this->db->query("SELECT SUM(s.sale_total) JUMLAH, u.unit_id, unit_name
        from farmasi.sale s
        join admin.ms_unit u on s.unit_id = u.unit_id
        WHERE to_char(sale_date,'YYYY-MM') = '2018-07' -- to_char(now(), 'YYYY')
        GROUP BY u.unit_id, unit_name
        limit 10")->result();

        $this->theme('dashboard/index',$data,get_class($this));
    }
}