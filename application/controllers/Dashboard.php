<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
	}
    public function index()
    {
        $this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit() as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
        $data['unit'] = $kat;
        $this->theme('dashboard/index',$data,get_class($this));
    }

    public function view_dashboard($unit=0)
    {
        $data['obat_exp'] = $this->db->query(
        "SELECT sum(COALESCE(stock_saldo,0))total FROM newfarmasi.stock_fifo
        WHERE date(expired_date) <= date(now()) AND expired_date IS NOT NULL AND expired_date != '1970-01-01' 
        and unit_id = '$unit'"
        )->row();
        $data['obat_ready'] = $this->db->query("select count(distinct item_id) total from newfarmasi.stock 
        where stock_summary>0 and unit_id = '$unit'")->row();
        $data['obat_habis'] = $this->db->query('SELECT count(*) total FROM newfarmasi.stock_fifo where stock_saldo<0')->row();
        $data['obat_akan_habis']=$this->db->query("
        SELECT sum(coalesce(stock_summary,0)) total FROM newfarmasi.stock s
        join farmasi.stock_maxmin_unit su on s.item_id = su.item_id and s.own_id = su.own_id 
        and s.unit_id = su.unit_id 
        where s.stock_summary <= su.stock_min and s.unit_id = '$unit'
        ")->row();
        $tanggalAwal = date("Y-m-d", strtotime("-1 months"));
        $data['tot_penjualan_terbayak_unit_item'] = $this->db->query("
        select * from (
            SELECT sum(sd.sale_qty)JUMLAH, i.item_id, item_name,u.unit_id, unit_name
            from farmasi.sale s
            join farmasi.sale_detail sd on s.sale_id = sd.sale_id
            join admin.ms_unit u on s.unit_id = u.unit_id
            join admin.ms_item i on sd.item_id = i.item_id
            WHERE to_char(sale_date,'YYYY-MM-DD') >=  '".$tanggalAwal."' and s.unit_id = '$unit'
            GROUP BY i.item_id, item_name,u.unit_id, unit_name
        )x
        order by x.JUMLAH desc
        limit 10
        ")->result();

        $data['tot_perjualan_unit'] = $this->db->query("SELECT 
        SUM(s.sale_total) JUMLAH, u.unit_id, unit_name
        from farmasi.sale s
        join admin.ms_unit u on s.unit_id = u.unit_id
        WHERE to_char(sale_date,'YYYY-MM-DD') >=  '".$tanggalAwal."'
        GROUP BY u.unit_id, unit_name
        ")->result();

        $this->load->view("dashboard/dashbord_by_unit",$data);
    }

    public function get_notif_permintaan()
    {
        $data = $this->db->join("admin.ms_unit mu","mu.unit_id = m.unit_require")
                         ->get_where("newfarmasi.mutation m",[
                            "mutation_status"   => 1,
                         ]);
        $list = "<ul class=\"menu\">";
        foreach ($data->result() as $key => $value) {
            $list .= '<li>
                <a href="'.site_url("distribusi_bon").'">
                <h3>
                    '.$value->unit_name.'
                    <small class="pull-right">'.$value->bon_no.'</small>
                </h3>
                </a>
            </li>';
        }
        $list .= "</ul>";
        $num = $data->num_rows();
        $resp = [
            "jumlah"        => $num,
            "pesan"         => "Anda memilik $num permintaan",
            "list_data"     => $list,
        ];
        echo json_encode($resp);
    }

    public function get_notif_penerimaan()
    {
        $data = $this->db->join("admin.ms_unit mu","mu.unit_id = m.unit_require")
                         ->get_where("newfarmasi.mutation m",[
                            "mutation_status"   => 2,
                         ]);
        $list = "<ul class=\"menu\">";
        foreach ($data->result() as $key => $value) {
            $list .= '<li>
                <a href="'.site_url("bon_mutation").'">
                <h3>
                    '.$value->unit_name.'
                    <small class="pull-right">'.$value->mutation_no.'</small>
                </h3>
                </a>
            </li>';
        }

        $list .= "</ul>";
        $num = $data->num_rows();
        $resp = [
            "jumlah"        => $num,
            "pesan"         => "Anda memilik $num item dikirim",
            "list_data"     => $list,
        ];

        echo json_encode($resp);
    }
}