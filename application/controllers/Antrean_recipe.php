<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Antrean_recipe extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_farmasi_unit() as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$data['unit'] = $kat;
		$this->load->view('template/antrean_temp', $data);
	}

	public function get_data()
	{
		$this->load->model('m_antrean_recipe');
		$this->load->library('datatable');
		$attr 	= $this->input->post();

		$fields = $this->m_antrean_recipe->get_column();
		$filter = [];

		$filter['custom'] = " s.unit_id = '" . $attr['unit_id'] . "'";
		$data 	= $this->datatable->get_data($fields, $filter, 'm_antrean_recipe', $attr);
		$records["aaData"] = array();
		$no   	= 1 + $attr['start'];
		foreach ($data['dataku'] as $index => $row) {
			$obj = array($row['id_key'], $no);
			foreach ($fields as $key => $value) {
				if (is_array($value)) {
					if (isset($value['custom'])) {
						$obj[] = call_user_func($value['custom'], $row);
					} else {
						$obj[] = $row[$key];
					}
				} else {
					$obj[] = $row[$value];
				}
			}
			$obj[] = create_btnAction(["update", "delete"], $row['id_key']);
			$records["aaData"][] = $obj;
			$no++;
		}
		$data = array_merge($data, $records);
		unset($data['dataku']);
		echo json_encode($data);
	}

	public function noracikanredy()
	{
		$unit_id = $this->input->post('unit_id');

		$data['noResepRacikanReady'] = $this->db->query("
		SELECT x.*,mu.unit_name,af.tgl_panggil, status FROM(
	
				SELECT 
				s.service_id,
				s.sale_id,
				s.patient_name,
				mu.unit_name depo,
				COALESCE((s.sale_status)::integer, 0) AS sale_status,
				s.unit_id,
					CASE
						WHEN (true = ANY (array_agg(sd.racikan))) THEN 'YA'::text
						ELSE 'TIDAK'::text
					END AS racikan
			FROM (farmasi.sale s
				JOIN farmasi.sale_detail sd ON ((s.sale_id = sd.sale_id))
			    JOIN admin.ms_unit mu on s.unit_id = mu.unit_id)
			WHERE (s.sale_date = ('now'::text)::date) --('now'::text)::date)
			GROUP BY s.sale_id, s.sale_num,mu.unit_name
			ORDER BY s.sale_id DESC
		) x 
		LEFT JOIN yanmed.services s on x.service_id = s.srv_id
		LEFT JOIN admin.ms_unit mu on s.unit_id = mu.unit_id 
		JOIN yanmed.antrian_farmasi2 af on x.sale_id = af.sale_id
				where x.unit_id = $unit_id 
				and racikan = 'YA' 
				--and sale_status = 2
				--and af.status = 1
				ORDER BY x.sale_id DESC
		")->row();
		echo json_encode($data);
	}
	public function noResepNonRacikanReady()
	{
		$unit_id = $this->input->post('unit_id');
		$data['noResepNonRacikanReady'] = $this->db->query("
			SELECT x.*,mu.unit_name,af.tgl_panggil, status FROM(
				SELECT 
				s.service_id,
				s.sale_id,
				s.patient_name,
				mu.unit_name depo,
				COALESCE((s.sale_status)::integer, 0) AS sale_status,
				s.unit_id,
					CASE
						WHEN (true = ANY (array_agg(sd.racikan))) THEN 'YA'::text
						ELSE 'TIDAK'::text
					END AS racikan
			FROM (farmasi.sale s
				JOIN farmasi.sale_detail sd ON ((s.sale_id = sd.sale_id))
			    JOIN admin.ms_unit mu on s.unit_id = mu.unit_id)
			WHERE (s.sale_date = ('now'::text)::date)
			GROUP BY s.sale_id, s.sale_num,mu.unit_name
			ORDER BY s.sale_id DESC
		) x 
		LEFT JOIN yanmed.services s on x.service_id = s.srv_id
		LEFT JOIN admin.ms_unit mu on s.unit_id = mu.unit_id 
		JOIN yanmed.antrian_farmasi2 af on x.sale_id = af.sale_id
		
		where x.unit_id = $unit_id 
		and racikan = 'TIDAK' 
		--and sale_status = 2
		ORDER BY sale_id DESC
		LIMIT 1
		")->row();
		echo json_encode($data);
	}

	public function update_antrian()
	{
		$sale_id = $this->input->post('sale_id');
		$update = $this->db->set('status',2)
			->where('sale_id',$sale_id)
			->update('yanmed.antrian_farmasi2');
		if ($update){
			echo "sukes";
		}else{
			echo "gagal";
		}
	}


	/*public function nonracikan()
	{
		$data['noResepRacikanReady'] = $this->db->query("select a.antrian_id,a.visit_pm, a.tgl,qc.code, no_urut, qms.counter_room, qc.queue_counter_id ,qc.name,concat(code,no_urut) as no_antrian,tgl_panggil
			from yanmed.antrian a
			join admin.queue_monitoring_setting qms on a.tipe = qms.queue_monitoring_setting_id
			join admin.queue_counter qc on qms.queue_monitoring_setting_id = qc.queue_monitoring_setting_id
			where tipe = 9
			and date(tgl) = '2023-03-27'
			and tgl_panggil is null
			and qc.queue_counter_id = 18
			order by no_urut asc
			limit 1")->row();
		echo json_encode($data);
	}
	public function racikan()
	{
		$data['ResepRacikanReady'] = $this->db->query("select a.antrian_id,a.visit_pm, a.tgl,qc.code, no_urut, qms.counter_room, qc.queue_counter_id ,qc.name,concat(code,no_urut) as no_antrian,tgl_panggil
			from yanmed.antrian a
			join admin.queue_monitoring_setting qms on a.tipe = qms.queue_monitoring_setting_id
			join admin.queue_counter qc on qms.queue_monitoring_setting_id = qc.queue_monitoring_setting_id
			where tipe = 9
			and date(tgl) = '2023-03-27'
			and tgl_panggil is null
			and qc.queue_counter_id = 17
			order by no_urut asc
			limit 1")->row();
		echo json_encode($data);
	}*/





















	/*public function noResepRacikanPrepare()
	{
		$unit_id = $this->input->post('unit_id');
		$data['noResepRacikanPrepare'] = $this->db->query("
		SELECT nomor_resep from (
			SELECT split_part((s.sale_num)::text, '/'::text, 2) AS nomor_resep,
				COALESCE((s.sale_status)::integer, 0) AS sale_status,
				s.unit_id,
					CASE
						WHEN (true = ANY (array_agg(sd.racikan))) THEN 'YA'::text
						ELSE 'TIDAK'::text
					END AS racikan
			FROM (farmasi.sale s
				JOIN farmasi.sale_detail sd ON ((s.sale_id = sd.sale_id)))
			WHERE (s.sale_date = ('now'::text)::date)
			GROUP BY s.sale_id, s.sale_num
			ORDER BY s.sale_id DESC
		) x
		where unit_id = $unit_id and racikan = 'YA' and sale_status = 0
		ORDER BY nomor_resep DESC
		LIMIT 1
		")->row('nomor_resep');
		echo json_encode($data);
	}
	public function noResepNonRacikanPrepare()
	{
		$unit_id = $this->input->post('unit_id');
		$data['noResepNonRacikanPrepare'] = $this->db->query("
		SELECT nomor_resep from (
			SELECT split_part((s.sale_num)::text, '/'::text, 2) AS nomor_resep,
				COALESCE((s.sale_status)::integer, 0) AS sale_status,
				s.unit_id,
					CASE
						WHEN (true = ANY (array_agg(sd.racikan))) THEN 'YA'::text
						ELSE 'TIDAK'::text
					END AS racikan
			FROM (farmasi.sale s
				JOIN farmasi.sale_detail sd ON ((s.sale_id = sd.sale_id)))
			WHERE (s.sale_date = ('now'::text)::date)
			GROUP BY s.sale_id, s.sale_num
			ORDER BY s.sale_id DESC
		)x
		where unit_id = $unit_id
		and racikan = 'TIDAK' and sale_status = 0
		ORDER BY nomor_resep DESC
		LIMIT 1
		")->row('nomor_resep');
		echo json_encode($data);
	}

	public function no_antrian()
	{



		$data['noResepRacikanPrepare'] = $this->db->query("
		SELECT nomor_resep from (
			SELECT split_part((s.sale_num)::text, '/'::text, 2) AS nomor_resep,
				COALESCE((s.sale_status)::integer, 0) AS sale_status,
				s.unit_id,
					CASE
						WHEN (true = ANY (array_agg(sd.racikan))) THEN 'YA'::text
						ELSE 'TIDAK'::text
					END AS racikan
			FROM (farmasi.sale s
				JOIN farmasi.sale_detail sd ON ((s.sale_id = sd.sale_id)))
			WHERE (s.sale_date = ('now'::text)::date)
			GROUP BY s.sale_id, s.sale_num
			ORDER BY s.sale_id DESC
		) x
		where unit_id = 18 and racikan = 'YA' and sale_status = 0
		ORDER BY nomor_resep DESC
		LIMIT 1
		")->result();
	}*/


	public function get_datas($unit_id)
	{
		/* $detail["orderRcp"] = $this->db->order_by("rcp_date","desc")->get_where("newfarmasi.recipe",[
			"unit_id"						=> $unit_id,
			"date(rcp_date)=date(now())"	=> null
		]); */

		$detail["antreanRcp"] = $this->db
			->select("s.*,
									case when coalesce(s.sale_status,0) = 0 or coalesce(s.sale_status,0) = 1 then 'Proses' else 'Selesai' end as status_resep
									", false)
			->join("newfarmasi.recipe r", "r.rcp_id=s.rcp_id", "left")
			->order_by("s.date_act", "ASC")
			->get_where("farmasi.sale s", [
				"s.unit_id"						=> $unit_id,
				"date(sale_date)=date(now()) and s.finish_time is null"	=> null
			])->result();
		// $data["rcpOnline"] = $detail["orderRcp"]->num_rows();
		/* $data["rcpOffline"] = $this->db->get_where("farmasi.sale",[
											"unit_id"						=> $unit_id,
											"date(sale_date)=date(now())"	=> null,
											"rcp_id is null"				=> null
										])->num_rows(); */
		$data["noResepRacikanReady"] = $this->db->query(
			"SELECT nomor_resep from newfarmasi.v_antrean_apotek where unit_id = $unit_id and racikan = 'YA' and sale_status = 2
			ORDER BY nomor_resep DESC
			LIMIT 1"
		)->row('nomor_resep');
		$data["noResepNonRacikanReady"] = $this->db->query(
			"SELECT nomor_resep from newfarmasi.v_antrean_apotek where unit_id = $unit_id and racikan = 'TIDAK' and sale_status = 2
			ORDER BY nomor_resep DESC
			LIMIT 1"
		)->row('nomor_resep');
		$data["noResepRacikanPrepare"] = $this->db->query(
			"SELECT nomor_resep from newfarmasi.v_antrean_apotek where unit_id = $unit_id and racikan = 'YA' and sale_status = 0
			ORDER BY nomor_resep DESC
			LIMIT 1"
		)->row('nomor_resep');
		$data["noResepNonRacikanPrepare"] = $this->db->query(
			"SELECT nomor_resep from newfarmasi.v_antrean_apotek where unit_id = $unit_id and racikan = 'TIDAK' and sale_status = 0
			ORDER BY nomor_resep DESC
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
		$data['html'] = $this->load->view("antrean_resep/list_antrean", $detail, true);
		echo json_encode($data);
	}
}
