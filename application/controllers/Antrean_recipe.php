<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrean_recipe extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit(["employee_id" => $this->session->employee_id]) as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$data['unit'] = $kat;
        $this->load->view('template/antrean_temp',$data);
	}

	public function get_data($unit_id)
	{
		/* $detail["orderRcp"] = $this->db->order_by("rcp_date","desc")->get_where("newfarmasi.recipe",[
			"unit_id"						=> $unit_id,
			"date(rcp_date)=date(now())"	=> null
		]); */
		
		$detail["antreanRcp"] = $this->db
									->select("s.*,to_char(r.rcp_date,'HH24:MM:SS')order_rcp,to_char(s.date_act,'HH24:MM:SS')entri_rcp,to_char(s.finish_time,'HH24:MM:SS')finish_rcp",false)
									->join("newfarmasi.recipe r","r.rcp_id=s.rcp_id","left")
									->order_by("s.date_act","desc")
									->get_where("farmasi.sale s",[
										"s.unit_id"						=> $unit_id,
										"date(sale_date)=date(now()) and s.finish_time is null"	=> null
									])->result();
		// $data["rcpOnline"] = $detail["orderRcp"]->num_rows();
		/* $data["rcpOffline"] = $this->db->get_where("farmasi.sale",[
											"unit_id"						=> $unit_id,
											"date(sale_date)=date(now())"	=> null,
											"rcp_id is null"				=> null
										])->num_rows(); */
		$data["noResepRacikan"] = $this->db->query(
			"SELECT split_part(sale_num, '/', 2)nomor_resep FROM farmasi.sale s
			JOIN farmasi.sale_detail sd ON s.sale_id = sd.sale_id
			WHERE date(s.sale_date) = CURRENT_DATE AND s.unit_id = $unit_id
			GROUP BY s.sale_id,sale_num
			HAVING 't' = ANY(ARRAY_AGG(sd.racikan))
			ORDER BY s.sale_id DESC
			LIMIT 1"
		)->row('nomor_resep');
		$data["noResepNonRacikan"] = $this->db->query(
			"SELECT split_part(sale_num, '/', 2)nomor_resep FROM farmasi.sale s
			JOIN farmasi.sale_detail sd ON s.sale_id = sd.sale_id
			WHERE date(s.sale_date) = CURRENT_DATE AND s.unit_id = $unit_id
			GROUP BY s.sale_id,sale_num
			HAVING 't' != ANY(ARRAY_AGG(sd.racikan))
			ORDER BY s.sale_id DESC
			LIMIT 1"
		)->row('nomor_resep');
		// $detail["orderRcp"] = $detail["orderRcp"]->result();
		
		/* $data["groupRcp"] = $this->db->query(
			"select sum(
				case when sd.racikan = 't' then 1 else 0 end
			)racikan,
			sum(
				case when sd.racikan = 'f' then 1 else 0 end
			)non_racikan
			from farmasi.sale s
			join farmasi.sale_detail sd on s.sale_id = sd.sale_id
			where date(sale_date)=date(now()) and unit_id = '$unit_id'"
		)->row(); */
		$data['html']=$this->load->view("antrean_resep/list_antrean",$detail,true);
		echo json_encode($data);
	}
}
?>