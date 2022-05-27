<?php

class M_sale extends CI_Model
{

	public function get_data($sLimit, $sWhere, $sOrder, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",sale_id as id_key  from farmasi.sale where 0=0 $sWhere $sOrder $sLimit
			")->result_array();
		return $data;
	}

	public function get_total($sWhere, $aColumns)
	{
		$data = $this->db->query("
				select " . implode(',', $aColumns) . ",sale_id as id_key  from farmasi.sale where 0=0 $sWhere
			")->num_rows();
		return $data;
	}

	public function get_column()
	{
		$col = [
			"sale_id",
			"sale_num",
			"sale_date",
			"unit_id",
			"visit_id",
			"patient_name",
			"user_id",
			"date_act",
			"sale_status",
			"sale_shift",
			"sale_type",
			"service_id",
			"surety_id",
			"sale_embalase",
			"sale_services",
			"doctor_id",
			"own_id",
			"rcp_id",
			"cash_id",
			"finish_user_id",
			"finish_time",
			"doctor_name",
			"verificated",
			"verificator_id",
			"verified_at",
			"sale_total",
			"sale_cover",
			"patient_norm",
			"kronis",
			"pay_act",
			"embalase_item_sale",
			"sale_total_returned",
			"sale_total_ppn",
			"sale_total_payment",
			"sale_is_paid",
			"sale_total_surety",
			"sale_total_beforediscount",
			"sale_discount_percent",
			"sale_discount_nominal",
			"kronis_drug_usage",
			"apoteker_service_item",
			"apoteker_service_total",
			"apoteker_service_status",
			"start_time",
			"usage_date",
			"total_price_package",
			"sale_no",
			"unit_id_lay",
			"unit_name_lay",
			"transfer_date",
			"transfer_by",
			"no_transaksi"
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
			"patient_name" => "trim",
			"user_id" => "trim|integer|required",
			"date_act" => "trim",
			"sale_status" => "trim|integer",
			"sale_shift" => "trim|integer",
			"service_id" => "trim|integer",
			"surety_id" => "trim|integer",
			"doctor_id" => "trim|integer",
			"own_id" => "trim|integer",
			"rcp_id" => "trim|integer",
			"cash_id" => "trim|integer",
			"finish_user_id" => "trim|integer",
			"finish_time" => "trim",
			"doctor_name" => "trim",
			"verificated" => "trim",
			"verificator_id" => "trim|integer",
			"verified_at" => "trim",
			"sale_total" => "trim|integer",
			"sale_cover" => "trim|integer",
			"patient_norm" => "trim",
			"pay_act" => "trim",
			"embalase_item_sale" => "trim|integer",
			"sale_total_beforediscount" => "trim|integer",
			"sale_discount_percent" => "trim|numeric",
			"sale_discount_nominal" => "trim|integer",
			"start_time" => "trim",
			"usage_date" => "trim",
			"sale_no" => "trim",
			"unit_id_lay" => "trim|integer",
			"unit_name_lay" => "trim",
			"transfer_by" => "trim|integer",

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

	/* public function FunctionName(Type $var = null)
	{
		$sql = "SELECT * FROM (
				SELECT P.px_id,
					P.px_norm,
					P.px_name,
					P.px_address,
							to_char( P.px_birthdate, 'DD-MM-YYYY' ) tgl_lahir,
							concat ( '(', COALESCE ( P.px_phone, '0' ), ')' ) telepon,
						lay.visit_id,lay.srv_id,lay.srv_date,lay.unit_id,
						lay.unit_name
						,lay.par_id,lay.par_name,lay.surety_id,
						lay.status_kunjungan,lay.nomor_sep
						FROM
							yanmed.patient P 
							INNER JOIN (
									
							)lay ON lay.px_id = p.px_id
							WHERE p.px_active = 't'
					UNION ALL					
				SELECT P.px_id,
					P.px_norm,
					P.px_name,
					P.px_address,
							to_char( P.px_birthdate, 'DD-MM-YYYY' ) tgl_lahir,
							concat ( '(', COALESCE ( P.px_phone, '0' ), ')' ) telepon,NULL,NULL,NULL,NULL,'(APS)',NULL,NULL,NULL,'NON KUNJUNGAN',NULL
					FROM yanmed.patient p
					where p.px_active = 't'
				) y
			WHERE 0=0 $where
							ORDER BY
								COALESCE (y.visit_id :: INT, y.px_id) DESC,
								COALESCE (y.srv_id, y.px_id) DESC
							LIMIT 25";
					$result = $this->db->query($sql);
					$result = $result->result();
					return $result;
				} 
	} */
}
