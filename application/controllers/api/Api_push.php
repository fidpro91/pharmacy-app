<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_push extends CI_Controller {
    function set_push ($sale_id,$user_id) {
        $sale = $this->db->select("s.*,mu.unit_name,split_part(s.sale_num, '/', 2) as antrian")
				->join("admin.ms_unit as mu","mu.unit_id = s.unit_id_lay","left")
				->get_where("farmasi.sale s",[
					"sale_id"	=> $sale_id
				])->row();
		
		$this->db->set("finish_time","coalesce(finish_time,'".date("Y-m-d H:i:s")."')",false);
		$this->db->where("sale_id",$sale_id)->update("farmasi.sale",[
			"sale_status"		=> "2",
			"finish_user_id"	=> $user_id
		]);

		$resp = [
			"nomor"  	=> $sale->antrian,
			"unit_id"  	=> $sale->unit_id,
			"kronis"  	=> $sale->kronis,
			"pasien"	=> $sale->patient_name,
			"unit_layanan"	=> $sale->unit_name
		];
        $this->load->library("pusher");
        $this->pusher->call_antrian($resp);
    }
}