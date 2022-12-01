<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Recipe extends MY_Generator
{

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
			->lib_inputmulti()
			->lib_select2()
			->lib_inputmask();

		$this->load->model('m_recipe');
	}

	public function index()
	{
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit(["employee_id" => $this->session->employee_id]) as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$data['unit'] = $kat;
		$this->theme('recipe/index', $data, get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();
		$totalAll = 0;
		$saleDetailInput = [];
		$embalaseNonRacikan = 0;
		$saleServices = 0;

		//validasi item
		foreach ($data["list_recipe"] as $x => $value) {
			if (empty($value["item_id"])) {
				continue;
			}
			$cek = $this->db->query("SELECT s.*,i.item_name FROM newfarmasi.stock s
         	join admin.ms_item i on s.item_id = i.item_id
			WHERE s.item_id = " . $value['item_id'] . "
			AND own_id = " . $data['own_id'] . "
			AND unit_id = " . $data['unit_id'])->row();
			if ($cek->stock_summary < $value['qty']) {
				echo json_encode([
					"code" 		=> "204",
					"message"	=> "Stock item $cek->item_name kurang dari jumlah penjualan",
				]);
				exit();
				break;
			}
			$sd = [
				// "sale_id" 		=> "trim|integer|required",
				"item_id" 		=> $value["item_id"],
				"sale_price" 	=> $value["sale_price"] - ($value["sale_price"] * $data["percent_profit"]),
				"sale_qty" 		=> $value["qty"],
				"racikan" 		=> "f",
				"dosis" 		=> $value["dosis"],
				"kronis" 		=> $value["kronis"],
				"racikan_qty" 	=> 0,
				// "price_total" 	=> $value["price_total"],
				"own_id" 		=> $data["own_id"],
			];

			$saleDetailInput[$x]['percent_profit'] = $data['percent_profit'];
			if ($value['racikan_id'] != 'null' && $value['racikan_id'] != '') {
				$saleDetailInput[$x]['racikan_id'] = $value['racikan_id'];
				$saleDetailInput[$x]['racikan_qty'] = $value['qty'];
				$saleDetailInput[$x]['racikan_dosis'] = $value['dosis'];
				$saleDetailInput[$x]['racikan'] = 't';
			} else {
				$embalaseNonRacikan += $this->db->get_where("farmasi.ownership", ["own_id" => $data["own_id"]])->row("profit_item");
			}
			$saleDetailInput[$x]['subtotal'] = $value['price_total'];
			$saleDetailInput[$x] = array_merge($sd, $saleDetailInput[$x]);
			$totalAll += $value["price_total"];
		}
		$saleServices = $this->db->get_where("newfarmasi.setting_app", ["setting_name" => "BIAYA RACIKAN"])->row("setting_value");
		$namaRacikan  = array_filter(array_column($saleDetailInput, "racikan_id"), function ($var) {
			return ($var != '');
		});
		$jmlRacikan = count(array_unique($namaRacikan));
		$saleServices = $jmlRacikan * $saleServices;

		$pasien = $this->db->get_where("yanmed.patient", [
			"px_id" => $data["px_id"]
		])->row();

		if ($data["surety_id"] == 1 || $data["surety_id"] == 33) {
			$sale_type = 0;
		} else {
			$sale_type = 1;
		}
		$totalAll = $totalAll + $embalaseNonRacikan + $saleServices;
		$embalase = $totalAll / 100;
		$embalase = abs(ceil($embalase) - $embalase) * 100;
		$totalAll = $totalAll + $embalase;
		$saleInput = [
			"sale_num" => $this->get_no_sale($data["unit_id"]),
			"sale_date" => $data["rcp_date"],
			"unit_id" => $data["unit_id"],
			"visit_id" => $data["visit_id"],
			"patient_norm" => $pasien->px_norm,
			"patient_name" => $pasien->px_name,
			"kronis" => $saleDetailInput[0]['kronis'],
			"user_id" => $this->session->user_id,
			"sale_type" => $sale_type,
			"sale_status" => "0",
			"rcp_id" => $data["rcp_id"],
			"service_id" => $data["services_id"],
			"surety_id" => $data["surety_id"],
			"doctor_id" => $data["par_id"],
			"own_id" => $data["own_id"],
			"sale_total" => $totalAll,
			"embalase_item_sale" => $embalaseNonRacikan,
			"sale_services" => $saleServices,
			"unit_id_lay" => $data["unit_id_lay"],
			"sale_embalase" => $embalase,
			"sale_app"		=> "HEAPY"
		];

		$this->db->trans_begin();
		$this->db->insert("farmasi.sale", $saleInput);
		$saleId = $this->db->insert_id();
		// $saleId = 1;
		$saleDetailInput = array_map(function ($arr) use ($saleId) {
			return $arr + ['sale_id' => $saleId];
		}, $saleDetailInput);
		foreach ($saleDetailInput as $key => $value) {
			$this->db->insert("farmasi.sale_detail", $value);
			$saleDetailId = $this->db->insert_id();
			$this->db->where([
				"item_id" 		=> $value["item_id"],
				"racikan_id"	=> (isset($value["racikan_id"]) ? $value["racikan_id"] : null),
				"rcp_id"		=> $data["rcp_id"]
			])->update("newfarmasi.recipe_detail", [
				"saledetail_id" => $saleDetailId
			]);
		}

		$this->db->where([
			"rcp_id"	=> $data["rcp_id"]
		])->update("newfarmasi.recipe", [
			"rcp_status" => $data["jns_resep"]
		]);

		//telaah resep
		if (!empty($data['cek_kelengkapan'])) {
			$telaaah = [];
			$i = 0;
			$this->db->where("rcp_id", $data["rcp_id"])->delete("newfarmasi.review_recipe");
			foreach ($data['cek_kelengkapan'] as $key => $value) {
				$telaaah[$i] = [
					"rcp_id"	=> $data["rcp_id"],
					"reff_id"	=> $value
				];
				$i++;
			}
			$this->db->insert_batch("newfarmasi.review_recipe", $telaaah);
		}

		$err = $this->db->error();
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$resp = [
				"code" 		=> "206",
				"message"	=> $err['message']
			];
		} else {
			$this->db->trans_commit();
			$resp = [
				"code" 		=> "200",
				"sale_id" 	=> $saleId,
				"message"	=> "Data berhasil disimpan"
			];
		}
		echo json_encode($resp);
		// redirect('recipe');

	}

	public function get_no_sale($id)
	{
		$nickName = $this->db->get_where("admin.ms_unit", ["unit_id" => $id])->row("unit_nickname");

		$nomor = generate_code_transaksi([
			// "text"	=> "S/$nickName/NOMOR/" . date("d.m.Y"),
			"text"	=> "$nickName/NOMOR/" . date("m.Y"),
			"table"	=> "newfarmasi.nomor_sale",
			"column"	=> "sale_num",
			"delimiter" => "/",
			"number"	=> "2",
			"lpad"		=> "4",
			"filter"	=> " AND unit_id = '$id' and date(date_act) = date(now())"
		]);

		$this->db->insert("newfarmasi.nomor_sale", [
			"sale_num" 	=> $nomor,
			"unit_id"	=> $id
		]);

		return $nomor;
	}

	public function show_form($id)
	{
		$data["kelengkapan"] = $this->db->join("newfarmasi.review_recipe rr", "rr.reff_id = mr.reff_id and rr.rcp_id = $id", "left")
			->select("mr.*,rr.revrcp_id")
			->get_where("admin.ms_reff mr", ["refcat_id" => 38])->result_array();
		$data['recipe_id'] = $id;
		$data['model'] 	 = $this->m_recipe->rules();
		$this->load->view("recipe/form", $data);
	}

	public function show_form_delete($id)
	{
		$data["sale"] = $this->db->query("
		SELECT s.sale_id,s.sale_num,s.rcp_id,
		string_agg(concat(mi.item_name,' ',sd.dosis,' [',sd.sale_qty,']'),'<br>')detail_obat
		FROM farmasi.sale s
		JOIN farmasi.sale_detail sd ON s.sale_id = sd.sale_id
		JOIN admin.ms_item mi on sd.item_id = mi.item_id
		where s.rcp_id = $id
		GROUP BY s.sale_id,s.sale_num,s.rcp_id")->result();
		$this->load->view("recipe/v_detail_recipe", $data);
	}

	public function get_recipe_detail()
	{
		$post = $this->input->post();
		$data = $this->db->query("SELECT rd.*,mi.item_name as label_item_id,racikan_id,racikan_desc,qty,s.stock_summary as stock,
		(p.price_sell::numeric+(p.price_sell::numeric*so.percent_profit))sale_price
		FROM newfarmasi.recipe_detail rd
		JOIN admin.ms_item mi ON mi.item_id = rd.item_id
		LEFT JOIN newfarmasi.stock s ON s.item_id = rd.item_id AND s.unit_id = " . $post["unit_id"] . " AND s.own_id = " . $post["own_id"] . "
		LEFT JOIN farmasi.price p ON s.item_id = p.item_id AND s.own_id = p.own_id
		LEFT JOIN farmasi.surety_ownership so ON so.own_id = s.own_id AND so.surety_id = " . $post["surety_id"] . "
		where rd.rcp_id = " . $post["rcp_id"] . "
		")->result();
		echo json_encode($data);
	}

	public function show_multiRows($rcp_id = 0)
	{
		$this->load->model("m_recipe_detail");
		$data = $this->m_recipe_detail->get_column_multiple();
		$colauto = ["item_id" => "Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '30%',
				];
			} elseif ($value == "sale_price" || $value == "stock") {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => '10%',
					"attr" => [
						"readonly" => 'readonly',
						"data-inputmask" => "'alias': 'IDR'"
					]
				];
			} elseif ($value == "price_total") {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => "20%",
					"attr" => [
						"readonly" => "readonly",
						"data-inputmask" => "'alias': 'IDR'"
					]
				];
			} elseif ($value == "racikan_id") {
				$racikan = $this->db->query(
					"select distinct coalesce(racikan_id,'') as id,racikan_id as text from newfarmasi.recipe_detail where rcp_id = '$rcp_id'"
				)->result();
				$row[] = [
					"id" => $value,
					"label" => "Racikan",
					"type" => 'select',
					"width" => '10%',
					"data" => $racikan
				];
			} elseif ($value == "kronis") {
				$row[] = [
					"id" => $value,
					"label" => "Jns Obat",
					"type" => 'select',
					"width" => '10%',
					"data" => get_type_kronis()
				];
			} elseif ($value == "price_total" || $value == "dosis") {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => '15%',
				];
			} else {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => '5%',
				];
			}
		}
		echo json_encode($row);
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_recipe->get_column();
		$filter = [
			"r.unit_id"			=> $attr['unit_id'],
			"r.rcp_status"		=> $attr['rcp_status'],
		];
		if (!empty($attr['surety_id'])) {
			$filter["surety_id"] = $attr['surety_id'];
		}
		$filter['custom'] = " date(rcp_date) = '" . date('Y-m-d', strtotime($attr['tanggal'])) . "'";
		if ($attr['unit_layanan']) {
			$filter['unit_id_layanan'] = $attr['unit_layanan'];
		}
		$data 	= $this->datatable->get_data($fields, $filter, 'm_recipe', $attr);
		$records["aaData"] = array();
		$no   	= 1 + $attr['start'];
		foreach ($data['dataku'] as $index => $row) {
			$obj = array($row['id_key'], $no);
			foreach ($fields as $key => $value) {
				if (is_array($value)) {
					if (isset($value['custom'])) {
						$obj[] = call_user_func($value['custom'], $row[$key]);
					} else {
						$obj[] = $row[$key];
					}
				} else {
					$obj[] = $row[$value];
				}
			}
			if ($row["rcp_status"] == "0") {
				$obj[] = create_btnAction([
					"Checkin" =>
					[
						"btn-act" => "set_val('" . $row['id_key'] . "')",
						"btn-icon" => "fa fa-cart-plus",
						"btn-class" => "btn-default",
					]
				], $row['id_key']);
			} elseif ($row["rcp_status"] == "2") {
				$obj[] = create_btnAction([
					"Checkin" =>
					[
						"btn-act" => "set_val('" . $row['id_key'] . "')",
						"btn-icon" => "fa fa-cart-plus",
						"btn-class" => "btn-default",
					],
					"Show" =>
					[
						"btn-act" => "deleteRow('" . $row['id_key'] . "')",
						"btn-icon" => "fa fa-list-alt",
						"btn-class" => "btn-success",
					],
				], $row['id_key']);
			} elseif ($row["rcp_status"] == "1") {
				$obj[] = create_btnAction([
					"Show" =>
					[
						"btn-act" => "deleteRow('" . $row['id_key'] . "')",
						"btn-icon" => "fa fa-list-alt",
						"btn-class" => "btn-success",
					]
				], $row['id_key']);
			}
			$records["aaData"][] = $obj;
			$no++;
		}
		$data = array_merge($data, $records);
		unset($data['dataku']);
		echo json_encode($data);
	}

	public function find_one($id)
	{
		$data = $this->db->where('rcp_id', $id)
			->join("yanmed.visit v", "v.visit_id=r.visit_id")
			->join("yanmed.services s", "s.srv_id=r.services_id")
			->join("farmasi.surety_ownership so", "so.surety_id=v.surety_id and so.own_id=1")
			->select("*,s.unit_id as unit_id_lay")
			->get("newfarmasi.recipe r")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('rcp_id', $id)->delete("newfarmasi.recipe");
		$resp = array();
		if ($this->db->affected_rows()) {
			$resp['message'] = 'Data berhasil dihapus';
		} else {
			$err = $this->db->error();
			$resp['message'] = $err['message'];
		}
		echo json_encode($resp);
	}

	public function delete_multi()
	{
		$resp = array();
		foreach ($this->input->post('data') as $key => $value) {
			$this->db->where('rcp_id', $value)->delete("newfarmasi.recipe");
			$err = $this->db->error();
			if ($err['message']) {
				$resp['message'] .= $err['message'] . "\n";
			}
		}
		if (empty($resp['message'])) {
			$resp['message'] = 'Data berhasil dihapus';
		}
		echo json_encode($resp);
	}
}
