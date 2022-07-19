<?php

class M_sale extends CI_Model
{

	public function get_data($sLimit, $sWhere, $sOrder, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",x.id_key,x.sale_type 
				from (select 
				sl.surety_id,sale_type,sale_num,sale_date,concat (patient_name,' (',patient_norm,')') as nama,sale_total,
				sale_status,surety_name,doctor_name,cash_id,patient_norm,sale_id AS id_key 
				from farmasi.sale sl
				left join yanmed.ms_surety su on sl.surety_id = su.surety_id	
				where to_char(sale_date,'YYYY') = '2022' $sWhere $sOrder $sLimit) x
			")->result_array();
		return $data;
	}

	public function get_total($sWhere, $aColumns)
	{
		$data = $this->db->query("
		select " . implode(',', $aColumns) . ",x.id_key 
		from (select 
		sl.surety_id,sale_type,sale_num,sale_date,concat (patient_name,' (',patient_norm,')') as nama,sale_total,
		sale_status,surety_name,doctor_name,cash_id,patient_norm,sale_id AS id_key 
		from farmasi.sale sl
		left join yanmed.ms_surety su on sl.surety_id = su.surety_id	
		where to_char(sale_date,'YYYY') = '2022' $sWhere) x
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
			"sale_num",
			"sale_date",
			"nama",
			"sale_status",
			"surety_name",
			"doctor_name",
			"sale_total" => [
				"custom" => function ($a) {
					return convert_currency($a['sale_total']);
				}
			],
			"cash_id" => [
				"label"	 => "status pembayaran",
				"custom" => function ($a) {
					if (!empty($a['cash_id']) && $a['sale_type'] == 0) {
						$label = '<label class="label label-success">Terbayar</label>';
					} elseif (empty($a['cash_id']) && $a['sale_type'] == 1) {
						$label = '<label class="label label-primary">Piutang</label>';
					} else {
						$label = '<label class="label label-danger">Belum Terbayar</label>';
					}
					return $label;
				}
			],
		];
		return $col;
	}

	public function rules()
	{
		$data = [
			"sale_num" => "trim|required",
			"sale_date" => "trim|required",
			"unit_id" => "trim|integer|required",
			"visit_id" => "trim|integer",
			"patient_norm" => "trim",
			"patient_name" => "trim",
			"kronis" => "trim",
			"user_id" => "trim|integer",
			"sale_status" => "trim|integer",
			"service_id" => "trim|integer",
			"surety_id" => "trim|integer",
			"doctor_id" => "trim|integer",
			"own_id" => "trim|integer",
			"doctor_name" => "trim",
			"sale_total" => "trim|integer",
			"embalase_item_sale" => "trim|integer",
			"sale_services" => "trim|integer",
			"unit_id_lay" => "trim|integer",
		];
		return $data;
	}

	public function validation()
	{
		foreach ($this->rules() as $key => $value) {
			$this->form_validation->set_rules($key, $key, $value);
		}

		return $this->form_validation->run();
	}

	public function get_sale($where)
	{
		return $this->db->get_where("farmasi.sale", $where)->result();
	}

	public function find_one($where)
	{
		return $this->db->get_where("farmasi.sale", $where)->row();
	}


	public function get_data_pasien($where, $select)
	{

		$sql = "SELECT $select P
			.px_id,
			P.px_norm,
			P.px_name,
			P.px_address,
			to_char( P.px_birthdate, 'DD-MM-YYYY' ) tgl_lahir,
			concat ( '(', COALESCE ( P.px_phone, lpad('0', 12, '0') ), ')' ) telepon
		FROM
			yanmed.patient p
		WHERE px_active ='t' $where";
		$result = $this->db->query($sql);
		$result = $result->result();
		return $result;
	}

	public function get_pasien_pelayanan($where, $select)
	{
		return $this->db->query("
			SELECT  $select P
				.px_id,
				P.px_norm,
				P.px_name,
				P.px_address,
				to_char( P.px_birthdate, 'DD-MM-YYYY' ) tgl_lahir,
				concat ( '(', COALESCE ( P.px_phone, lpad('0', 12, '0') ), ')' ) telepon,
				v.visit_id,
				s.srv_id,
				s.srv_date,
				s.unit_id,
				mu.unit_name,
				s.surety_id,
				s.par_id,
				concat ( emp.employee_ft, emp.employee_name, emp.employee_bt ) par_name,
				v.sep_no,
				s.srv_status 
			FROM
				yanmed.patient
				P JOIN yanmed.visit v ON v.px_id = P.px_id
				JOIN yanmed.services s ON v.visit_id = s.visit_id
				JOIN ADMIN.ms_unit mu ON mu.unit_id = s.unit_id
				LEFT JOIN hr.employee emp ON s.par_id = emp.employee_id 
			WHERE
				s.unit_id NOT IN ( 45, 105, 12 ) 
				AND EXTRACT ( 'year' FROM v.visit_date ) >= ( EXTRACT ( 'YEAR' FROM now()) - 1 ) 
				AND v.visit_status NOT IN (35,60,70) $where
			order by s.srv_date desc
		")->result();
	}

	public function get_penjamin($where)
	{
		return $this->db->get_where("yanmed.ms_surety", $where)->result();
	}

	public function get_unit_layanan($where)
	{
		return $this->db->where("unit_type in (21,22,23)", null)->get_where("admin.ms_unit", $where)->result();
	}

	public function get_dokter()
	{
		return $this->db->where("lower(employee_ft) like '%dr%' and employee_active = 't'", null)
			->select("employee_id,concat(employee_ft,employee_name,employee_bt)nama_dokter", false)
			->get("hr.employee")->result();
	}

	public function rumah_sakit()
	{
		$sql    = "select *,(select reg_name from admin.ms_region where reg_code=a.hsp_city) as kota, (select reg_name from admin.ms_region where reg_code=a.hsp_district) as kecamatan, (select reg_name from admin.ms_region where reg_code=a.hsp_village) as kelurahan from admin.profil a";
		$result = $this->db->query($sql);
		$result = $result->row();
		return $result;
	}
	function get_detail_patient($sale_id)
	{
		$result = $this->db->query("select ul.unit_name as poli,yv.srv_type,u.unit_name,to_char(fs.date_act, 'dd-mm-YYYY HH24:MI:SS')tanggal,sr.surety_name,v.pxsurety_no,v.sep_no,fs.* 
						from farmasi.sale fs 
						inner join admin.ms_unit u on fs.unit_id = u.unit_id
						inner join yanmed.ms_surety sr on sr.surety_id = fs.surety_id
						left join yanmed.services yv on yv.visit_id = fs.visit_id and fs.service_id = yv.srv_id
						LEFT JOIN yanmed.visit v ON v.visit_id = yv.visit_id
						LEFT JOIN admin.ms_unit ul ON yv.unit_id = ul.unit_id
						where fs.sale_id = " . $sale_id)->row();

		$data = array(
			"tanggal" => $result->tanggal,
			"noresep" => $result->sale_num,
			"namapasien" => $result->patient_name,
			"kepemilikan" => $result->surety_name,
			"doctor_name" => $result->doctor_name,
			"unit_name" => $result->unit_name,
			"sale_id" => $result->sale_id,
			"sale_total" => $result->sale_total,
			"sep_no" => $result->sep_no,
			"pxsurety_no" => $result->pxsurety_no,
		);

		if ($result->srv_type == 'RJ') {
			$data['layanan'] = "Rawat Jalan";
		} elseif ($result->srv_type == 'RI') {
			$data['layanan'] = "Rawat Inap";
		} elseif ($result->srv_type == 'IGD') {
			$data['layanan'] = "Rawat Darurat";
		} else {
			$data['layanan'] = "Pelayanan";
		}

		$data['layanan'] .= ($result->poli) ? "( " . $result->poli . " )" : "";

		return $data;
	}
	function resep_dijual2($sale_id)
	{
		//$data	=	array();
		$query_racik = $this->db->query("SELECT
			c.racikan_id,
			SUM(c.sale_qty) sale_qty,
			c.sale_price,
			c.dosis,
			mt.item_name,
			a.sale_services,
			a.embalase_item_sale,
			a.sale_embalase,
			SUM(C .sale_qty * C .sale_price) AS subtotal
			FROM
			farmasi.sale a
			JOIN farmasi.sale_detail C ON a.sale_id = c.sale_id
			JOIN admin.ms_item mt ON mt.item_id = c.item_id
			WHERE
			a.sale_id = $sale_id
			GROUP BY
			a.embalase_item_sale,
			c.racikan_id,
			c.sale_price,
			c.dosis,
			mt.item_name,
			a.sale_services,
			a.sale_embalase")
		->result();
		if (count($query_racik) < 1) {
			$query_racik = array();
		}

		return $query_racik;
	}
	function get_employee($employee_id)
	{
		$employee_name = "";
		$this->db->select('employee_id,employee_name');
		$this->db->where('employee_id', $employee_id);
		$query = $this->db->get('hr.employee');
		foreach ($query->result() as $row) {
			$employee_name = $row->employee_name;
		}
		return $employee_name;
	}

	function resep_dijual($sale_id)
	{
		$data	=	array();
		$this->db->select("a.obat_name,a.komponen_obat,a.obat_qty,a.dosis,CAST(sale_total_price as numeric) harga");
		$this->db->from('farmasi.v_cetak_detil_resep_dijual a');
		$this->db->where('a.sale_id', $sale_id);
		$query 	= 	$this->db->get();
		$data	=	$query->result();
		return $data;
	}
	
	
}
