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
			"sale_total"=>[
				"custom" => function($a){
					return convert_currency($a['sale_total']);
				}
			],
			"cash_id"=>[
				"label"	 => "status pembayaran",
				"custom" => function($a){
					if (!empty($a['cash_id']) && $a['sale_type'] == 0) {
						$label='<label class="label label-success">Terbayar</label>';
					}elseif(empty($a['cash_id']) && $a['sale_type'] == 1){
						$label='<label class="label label-primary">Piutang</label>';
					}else{
						$label='<label class="label label-danger">Belum Terbayar</label>';
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

	
	public function get_data_pasien($where,$select)
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

	public function get_pasien_pelayanan($where,$select)
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
		return $this->db->order_by('surety_code')->get_where("yanmed.ms_surety",$where)->result();
	}

	public function get_unit_layanan($where)
	{
		return $this->db->where("unit_type in (21,22,23)",null)->get_where("admin.ms_unit",$where)->result();
	}

	public function get_dokter()
	{
		return $this->db->where("lower(employee_ft) like '%dr%' and employee_active = 't'",null)
					  	->select("employee_id,concat(employee_ft,employee_name,employee_bt)nama_dokter",false)
						->get("hr.employee")->result();
	}




}

	