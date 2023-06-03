<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';
class Sale extends MY_Generator
{
	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
			->lib_inputmulti()
			->lib_select2()
			->lib_daterange()
			->lib_inputmask();
		$this->load->model('m_sale');
		$this->load->model('m_sale_detail');
	}
	public function index()
	{
		// session_destroy();
		/* $this->session->unset_userdata([
			'penjualan', 'itemRacik', 'itemNonRacik'
		]); */
		$this->unset_ses();
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit_depo(["employee_id" => $this->session->employee_id]) as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$data['unit'] = $kat;
		$this->theme('sale/index', $data, get_class($this));
	}

	public function unset_ses()
	{
		unset($_SESSION['penjualan']);
		unset($_SESSION['itemRacik']);
		unset($_SESSION['itemNonRacik']);
	}

	public function save()
	{
		$data = $this->input->post();
		
		$sess = $this->session->userdata('penjualan')['pasien'];
		if (empty($sess)) {
			echo json_encode([
				"code" 		=> "207",
				"message"	=> "Mohon dilengkapi data pasien!",
			]);
			exit();
		}
		// if ($this->m_sale->validation()) {
		$input = [];
		foreach ($this->m_sale->rules() as $key => $value) {
			
			$input[$key] = (!empty($sess[$key]) ? $sess[$key] : null);
		}
		$input['doctor_name'] = $this->session->penjualan['doctor_name'];
		$input['unit_id'] = $data['unit_id'];
		$input['sale_type'] = $sess['sale_type'];
		$input['sale_app'] = 'HEAPY';
		$input['user_id'] = ($this->session->user_id ? $this->session->user_id : 21);
		$input['sale_num'] = $this->get_no_sale($data['unit_id']);		
		$this->db->insert("newfarmasi.nomor_sale",[
			"sale_num" 	=> $input['sale_num'],
			"unit_id"	=> $data['unit_id']
		]);
		$racikan = $this->session->userdata('itemRacik');
		$nonRacikan = $this->session->userdata('itemNonRacik');		
		if (!empty($racikan)) {
			$totalRacikan = $racikan['total'];
			$totalService = $racikan['biaya_racik'];
		} else {
			$totalRacikan = 0;
			$totalService = 0;
		}
		$grandtotal = $totalRacikan + $nonRacikan['total'] + $totalService;
		$embalase = $grandtotal / 100;
		$embalase = abs(ceil($embalase) - $embalase) * 100;
		$input['sale_total'] = $grandtotal + $embalase + $data["embalase_item"];
		$input['sale_embalase'] 	 = $embalase;
		$input['embalase_item_sale'] = $data["embalase_item"];
		$input['sale_services'] = $totalService;
		$input['date_act'] 	= date('Y-m-d H:i:s');
		$this->db->insert("farmasi.sale", $input);
		$saleId = $this->db->query("select currval('public.sale_id_seq')seq")->row('seq');
		$err = $this->db->error();
		if ($err["message"]) {
			echo json_encode([
				"code" 		=> "202",
				"message"	=> "table sale : ".$err["message"],
			]);
			exit();
		}
		//insert into farmasi.sale
		$saleDetail = [];
		//nonracikan
		if (!empty($nonRacikan)) {
			$nonRacikan['detail'] = array_map(function ($arr) use ($saleId) {
				return $arr + ['sale_id' => $saleId];
			}, $nonRacikan['detail']);
			$saleDetail = $nonRacikan['detail'];
		}

		//racikan
		if (!empty($racikan)) {
			$racikan['detail'] = array_map(function ($arr) use ($saleId) {
				return $arr + ['sale_id' => $saleId];
			}, $racikan['detail']);
			$saleDetail = array_merge($saleDetail, $racikan['detail']);
		}

		//insert sale detail
		if (empty($saleDetail)) {
			$this->db->where([
				"sale_id" => $saleId
			])->delete("farmasi.sale");
			$resp = [
				"code" 		=> "203",
				"message"	=> "Data item tidak ada, mohon melengkapi data item racikan/non racikan"
			];
			echo json_encode($resp);
			exit;
		}
		$sukses = true;
		foreach ($saleDetail as $row){
			$cek = $this->db->query("SELECT s.*,i.item_name FROM newfarmasi.stock s
         	join admin.ms_item i on s.item_id = i.item_id
			WHERE s.item_id = ".$row['item_id']."
			AND own_id = ".$input['own_id']."
			AND unit_id = ".$input['unit_id'])->row();
			if ($cek->stock_summary<$row['sale_qty']){
				echo json_encode([
					"code" 		=> "204",
					"message"	=> "Stock item $cek->item_name kurang dari jumlah penjualan",
				]);
				$sukses = false;
				break;
			}
		}

		if ($sukses == false){
			$this->db->where([
				"sale_id" => $saleId
			])->delete("farmasi.sale");
			exit();
		}
		// $saleDetail = array_unique($saleDetail,SORT_REGULAR);
		$this->db->trans_begin();
		$this->db->insert_batch("farmasi.sale_detail", $saleDetail);
		$err = $this->db->error();
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->db->where([
				"sale_id" => $saleId
			])->delete("farmasi.sale");
			$resp = [
				"code" 		=> "206",
				"message"	=> $err['message']
			];
		} else {
			$this->db->trans_commit();
			/* $this->db->query("
			REFRESH MATERIALIZED VIEW CONCURRENTLY newfarmasi.v_antrean_apotek;"); */
			$resp = [
				"code" 		=> "200",
				"sale_id" 	=> $saleId,
				"message"	=> "Data berhasil disimpan"
			];
		}
		/* }else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		} */
		echo json_encode($resp);
		// redirect('sale');

	}

	public function checkout_pasien()
	{
		$nomorRm = $this->input->post('noresep');
		$user = $this->session->user_id;
		$this->db->set('finish_time', 'now()', false);
		if ($this->input->post('asal_resep')) {
			$this->db->where("unit_id_lay",$this->input->post('asal_resep'));
		}
		$this->db->where([
			"unit_id"			=> $this->input->post('unit_id'),
			"date(sale_date)>= '" . date("Y-m-d", strtotime("- 3 days")) . "' and finish_time is null" => null
		]);
		$this->db->where("lower(patient_norm) = lower('$nomorRm')", null)
			->update('farmasi.sale', array(
				'finish_user_id' => $user,
				'sale_status' 	 => 2,
			));
		$respon = array();
		if ($this->db->affected_rows()) {
			$respon['message'] = 'Resep berhasil dicheckout';
			$respon['kode']	   = '001';
		} else {
			$respon['message'] = 'Resep tidak ditemukan';
			$respon['kode']	   = '002';
		}
		echo json_encode($respon);
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_sale->get_column();
		$filter=[];
		$filter["unit_id"] = $attr['unit_id'];
		if ($attr["sale_type"] != '') {
			$filter = array_merge($filter, ["sale_type" => $attr['sale_type']]);
		}
		// $filter["sale_type"] = $attr['sale_type'];
		$filter["custom"] = " to_char(sale_date,'MM-YYYY')='" . $attr['bulan'] . "'";

		$data 	= $this->datatable->get_data($fields, $filter, 'm_sale', $attr);
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
			if (($row['sale_type'] == 0 && empty($row['cash_id'])) || ($row['sale_type'] == 1)) {
				$obj[] = create_btnAction([
					"update",
					"Delete Data" =>
					[
						"btn-act" => "deleteRow('" . $row['id_key'] . "','" . $row['rcp_id'] . "')",
						"btn-icon" => "fa fa-trash",
						"btn-class" => "btn-danger",
					],
					"Cetak Faktur" =>
					[
						"btn-act" => "cetak_resep('" . $row['id_key'] . "',2)",
						"btn-icon" => "fa fa-print",
						"btn-class" => "btn-default",
					],
					"Cetak E-Tiket" =>
					[
						"btn-act" => "cetak_etiket('" . $row['id_key'] . "')",
						"btn-icon" => "fa fa-bookmark",
						"btn-class" => "btn-default",
					]
				], $row['id_key']);
			} else {
				$obj[] = create_btnAction([
					"Cetak Faktur" =>
					[
						"btn-act" => "cetak_resep('" . $row['id_key'] . "',2)",
						"btn-icon" => "fa fa-print",
						"btn-class" => "btn-default",
					],
					"Cetak E-Tiket" =>
					[
						"btn-act" => "cetak_etiket('" . $row['id_key'] . "')",
						"btn-icon" => "fa fa-bookmark",
						"btn-class" => "btn-default",
					]
				], $row['id_key']);;
			}
			$records["aaData"][] = $obj;
			$no++;
		}
		$data = array_merge($data, $records);
		unset($data['dataku']);
		echo json_encode($data);
	}

	public function update_data()
	{
		$post = $this->input->post();
		$input = [];
		$this->db->trans_begin();
		foreach ($this->m_sale->rules() as $key => $value) {
			$input[$key] = (!empty($post[$key]) ? $post[$key] : null);
		}
		$input['user_id'] = ($this->session->user_id ? $this->session->user_id : 21);
		$input['sale_id'] = $post["sale_id"];
		$detail = [];
		$input["embalase_item_sale"] = 0;
		$totalAll = 0;
		$sukses = true;
		foreach ($post['list_obat_edited'] as $x => $v) {
			$cek = $this->db->query("SELECT s.*,i.item_name FROM newfarmasi.stock s
         	join admin.ms_item i on s.item_id = i.item_id
			WHERE s.item_id = ".$v['item_id']."
			AND own_id = ".$input['own_id']."
			AND unit_id = ".$input['unit_id'])->row();
			if ($cek->stock_summary<$v['sale_qty']){
				echo json_encode([
					"code" 		=> "204",
					"message"	=> "Stock item $cek->item_name kurang dari jumlah penjualan",
				]);
				$sukses = false;
				break;
			}
			foreach ($this->m_sale_detail->rules() as $key => $value) {
				if ($key != 'sale_id') {
					$detail[$x][$key] = (isset($v[$key]) ? $v[$key] : null);
				}
			}
			$detail[$x]['sale_id'] 	= $input['sale_id'];
			$detail[$x]['kronis'] 	= $input['kronis'];
			$detail[$x]['own_id'] 	= $input['own_id'];
			$detail[$x]['percent_profit'] = $post['profit'];
			$detail[$x]['racikan'] = 'f';
			if ($v['racikan_id'] != 'null' && $v['racikan_id'] != '') {
				$detail[$x]['racikan_id'] = $v['racikan_id'];
				$detail[$x]['racikan_qty'] = $v['sale_qty'];
				$detail[$x]['racikan_dosis'] = $v['dosis'];
				$detail[$x]['racikan'] = 't';
			} else {
				$input["embalase_item_sale"] += $post["profit_item"];
			}
			$price_total = ($v['price_total'] * $post['profit']) + $v['price_total'];
			$detail[$x]['subtotal'] = $price_total;
			$totalAll += $price_total;
			
		}
		if ($sukses == false){
			$this->db->trans_rollback();
			exit();
		}
		$grandtotal = $totalAll + $input['sale_services'] + $input["embalase_item_sale"];
		$embalase = $grandtotal / 100;
		$embalase = abs(ceil($embalase) - $embalase) * 100;
		$input['sale_total'] = $grandtotal + $embalase;
		$input['sale_embalase'] 	 = $embalase;
		$this->db->where(["sale_id" => $input["sale_id"]])->update("farmasi.sale", $input);
		$this->db->where(["sale_id" => $input["sale_id"]])->delete("farmasi.sale_detail");
		$this->db->insert_batch("farmasi.sale_detail", $detail);
		$err = $this->db->error();
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$resp = [
				"code" 		=> "202",
				"message"	=> $err['message']
			];
		} else {
			$resp = [
				"code" 		=> "200",
				"message"	=> "Data berhasil disimpan"
			];
			$this->db->trans_commit();
		}
		echo json_encode($resp);
	}

	public function find_one($id)
	{
		$data = $this->db->where('sale_id', $id)
			->join("farmasi.surety_ownership so", "so.own_id = s.own_id and so.surety_id = s.surety_id")
			->join("farmasi.ownership ow", "ow.own_id=s.own_id")
			->select("s.*,so.percent_profit as profit,s.sale_services::numeric as sale_services,ow.profit_item", false)
			->get("farmasi.sale s")->row_array();
		echo json_encode($data);
	}

	public function delete_row($id,$rcp_id=null)
	{
		if (!empty($rcp_id)) {
			$cekResep = $this->db->get_where("farmasi.sale",["rcp_id"=>$rcp_id])->num_rows();
			if ($cekResep>1) {
				$this->db->where([
					"rcp_id"	=> $rcp_id
				])->update("newfarmasi.recipe",[
					"rcp_status" => 2
				]);
			}else{
				$this->db->where([
					"rcp_id"	=> $rcp_id
				])->update("newfarmasi.recipe",[
					"rcp_status" => 0
				]);
			}
		}
		$this->db->where('sale_id', $id)->delete("farmasi.sale_detail");
		$this->db->where('sale_id', $id)->delete("farmasi.sale");
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
			$this->db->where('sale_id', $value)->delete("farmasi.sale");
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

	public function show_form($id)
	{
		/* $this->session->unset_userdata([
			'penjualan', 'itemRacik', 'itemNonRacik'
		]); */
		$this->unset_ses();
		$data['model'] = $this->m_sale->rules();
		$data['sale_num'] = $this->get_no_sale($id);
		$this->load->view("sale/form", $data);
	}

	public function show_form_update($id)
	{
		/* $this->session->unset_userdata([
			'penjualan', 'itemRacik', 'itemNonRacik'
		]); */
		$this->unset_ses();
		$data["item"]  = $this->db->query("
			SELECT sd.*,sd.sale_price::numeric as sale_price,mi.item_name as label_item_id,st.stock_summary as stock,
			(sd.sale_qty*sd.sale_price)::numeric as price_total FROM farmasi.sale_detail sd
			JOIN admin.ms_item mi ON sd.item_id = mi.item_id
			JOIN farmasi.sale s on sd.sale_id = s.sale_id
			JOIN newfarmasi.stock st ON st.item_id = sd.item_id and st.own_id = sd.own_id and st.unit_id = s.unit_id
			WHERE sd.sale_id = '$id'
		")->result();
		$data['sale_id'] = $id;
		$data['model'] 	 = $this->m_sale->rules();
		$this->load->view("sale/form_update", $data);
	}

	public function show_history_px()
	{
		$this->load->view("sale/v_history_patient");
	}

	public function get_history_px()
	{
		$input = $this->input->post();
		list($tanggal1, $tanggal2) = explode('/', $input['tanggal']);
		/* $date1		= date_create($tanggal1);
		$date2		= date_create($tanggal2);
		$diff		= date_diff($date1, $date2); */
		$data['dataHistory'] = $this->db->query("
			SELECT * from (
				select v.visit_id,v.visit_date,s.srv_id,mu.unit_name,string_agg(DISTINCT concat(mi.icd_code,'-',mi.icd_name), '<br>')diagnosa,string_agg(DISTINCT mb.bill_name, '<br>') tindakan,string_agg(DISTINCT concat(vo.item_name,' Qty : ',sd.sale_qty,' ',vo.item_unitofitem), '<br>') obat
				from yanmed.visit v
				join yanmed.services s on s.visit_id = v.visit_id
				join yanmed.patient p on v.px_id = p.px_id
				join admin.ms_unit mu on s.unit_id = mu.unit_id
				LEFT JOIN yanmed.diagnosa d ON d.srv_id = s.srv_id
				LEFT JOIN yanmed.ms_icd mi ON d.icd_id = mi.icd_id
				LEFT JOIN yanmed.billing b ON b.srv_id = s.srv_id
				LEFT JOIN yanmed.ms_tarif mt ON mt.tarif_id = b.tarif_id
				LEFT JOIN yanmed.ms_bill mb ON mb.bill_id = mt.bill_id
				left join farmasi.sale sl ON sl.visit_id = v.visit_id and sl.service_id = s.srv_id
				left join farmasi.sale_detail sd on sl.sale_id = sd.sale_id
				left join farmasi.v_obat vo on sd.item_id = vo.item_id
				where p.px_id = '" . $input['px_id'] . "' AND (date(v.visit_date) between '$tanggal1' AND '$tanggal2') AND unit_support_status != 1
				GROUP BY v.visit_id,s.srv_id,mu.unit_name,v.visit_date
			)x
		")->result();
		$this->load->view("sale/v_list_history_px", $data);
	}

	public function get_hasil_penunjang($visit_id, $type)
	{
		if ($type == 1) {
			$data = $this->db->query("SELECT string_agg(concat(ol.testname,'(',ol.\"result\",')'), '<br>')hasil FROM yanmed.orderlis_result ol
			JOIN yanmed.services s ON ol.hisregno = s.srv_id::VARCHAR
			WHERE s.visit_id = $visit_id")->row();
		} else {
			$data = $this->db->query("SELECT string_agg(concat('<b>',mc.namecheck,'</b>','<br>',cu.\"result\"), '<br>')hasil FROM yanmed.checkup cu
			JOIN yanmed.services s ON cu.service_id = s.srv_id
			JOIN yanmed.ms_check mc ON cu.ms_check_id = mc.idcheck
			WHERE s.visit_id = $visit_id")->row();
		}
		$resp = [
			"code"		=> "200",
			"response"	=> $data
		];
		echo json_encode($resp);
	}

	public function get_no_rm($tipe, $kunjungan = '1')
	{
		$respond = array();
		$term 	= $this->input->get('term', true);

		if ($tipe == 'norm') {
			$where = " AND px_norm like '%$term%'";
			$select = "p.px_norm as label,";
		} else {
			$where = "AND LOWER(px_name) like '%$term%'";
			$select = "p.px_name as label,";
		}

		if ($kunjungan == '1') {
			$respond = $this->m_sale->get_pasien_pelayanan($where, $select);
		} else {
			$respond = $this->m_sale->get_data_pasien($where, $select);
		}
		echo json_encode($respond);
	}

	public function show_form_pasien()
	{
		$data['sess_px'] = json_encode($this->session->userdata('penjualan'));
		$this->load->view("sale/form_pasien", $data);
	}

	public function show_form_racikan()
	{
		$data['model'] = [];
		$this->load->view("sale/form_racikan", $data);
	}

	public function show_form_nonracikan()
	{
		$data['model'] = [];
		$this->load->view("sale/form_non_racikan", $data);
	}

	public function get_dose()
	{
		$term = $this->input->get('term');
		print_r($term);die;
		
		$where = " AND (
			lower(dose_name) like lower('%$term%')
		)";	
		//$select=" mi.item_name as value,";	
		echo json_encode($this->m_sale->get_dose($where));
	}

	public function show_multiRows($update = false, $sale_id = 0)
	{
		$this->load->model("m_sale_detail");
		$data = $this->m_sale_detail->get_column_multiple($update);
		$colauto = ["item_id" => "Nama Barang"];		
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '30%',
				];
			}elseif ($value == "sale_price" || $value == "stock") {
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
					"width" => "17%",
					"attr" => [
						"readonly" => "readonly",
						"data-inputmask" => "'alias': 'IDR'"
					]
				];
			} elseif ($value == "racikan_id") {
				$racikan = $this->db->query(
					"select distinct coalesce(racikan_id,'') as id,racikan_id as text from farmasi.sale_detail sd where sale_id = '$sale_id'"
				)->result();
				$row[] = [
					"id" => $value,
					"label" => "Racikan",
					"type" => 'select',
					"width" => '15%',
					"data" => $racikan
				];
			} elseif ($value == "ed_obat") {
				$row[] = [
					"id" => $value,
					"label" => "BUD",
					"type" => 'text',
					"width" => "13%",				
					
				];
			} else {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => '10%',
				];
			}
		}
		echo json_encode($row);
	}

	public function show_multiRowsRacikan($update = false, $sale_id = 0)
	{
		$this->load->model("m_sale_detail");
		$data = $this->m_sale_detail->get_column_racikan($update);
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
					"width" => "25%",
					"attr" => [
						"readonly" => "readonly",
						"data-inputmask" => "'alias': 'IDR'"
					]
				];
			} elseif ($value == "racikan_id") {
				$racikan = $this->db->query(
					"select distinct coalesce(racikan_id,'') as id,racikan_id as text from farmasi.sale_detail sd where sale_id = '$sale_id'"
				)->result();
				$row[] = [
					"id" => $value,
					"label" => "Racikan",
					"type" => 'select',
					"width" => '15%',
					"data" => $racikan
				];
			}else {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => '10%',
				];
			}
		}
		echo json_encode($row);
	}

	public function set_item_racikan($post = null)
	{
		if (!$post) {
			$post = $this->input->post();
		}

		$html = "";
		$total = 0;
		$item = "";
		$header = $this->session->userdata('penjualan');
		foreach ($post['list_item_racikan'] as $x => $v) {
			if (empty($v["item_id"])) {
				continue;
			}
			foreach ($this->m_sale_detail->rules() as $key => $value) {
				if ($key != 'sale_id') {
					$itemRacik[$x][$key] = (isset($v[$key]) ? $v[$key] : null);
				}
			}
			$itemRacik[$x]['kronis'] = $header['pasien']['kronis'];
			$itemRacik[$x]['own_id'] = $header['pasien']['own_id'];
			$itemRacik[$x]['percent_profit'] = $header['profit'];
			$itemRacik[$x]['racikan_id'] = $post['nama_racikan'];
			$itemRacik[$x]['ed_obat'] = $post['ed_obat'];
			$itemRacik[$x]['racikan_qty'] = $post['qty_racikan'];
			$itemRacik[$x]['racikan_dosis'] = $post['signa'];
			$price_total = ($v['price_total'] * $header['profit']) + $v['price_total'];
			$itemRacik[$x]['subtotal'] = $price_total;
			$itemRacik[$x]['racikan'] = 't';
			$total += $price_total;
			$item .= $v['autocom_item_id'] . "(" . $v['sale_qty'] . ")" . "<br>";
		}
		$total = $total;
		if (!empty($this->session->userdata('itemRacik'))) {
			$itemRacikOld = $this->session->userdata('itemRacik');
			$itemRacikan['detail'] 		= array_unique(array_merge($itemRacik, $itemRacikOld['detail']),SORT_REGULAR);
			$itemRacikan['biaya_racik']	= $itemRacikOld['biaya_racik'] + $post['biaya_racikan'];
			$itemRacikan['total']			= $itemRacikOld['total'] + $total;
		} else {
			$itemRacikan = [
				"detail"		=> $itemRacik,
				'biaya_racik'	=> $post['biaya_racikan'],
				"total"			=> $total
			];
		}
		$this->session->set_userdata('itemRacik', $itemRacikan);
		$item = rtrim($item, "<br>");
		$html .= "
			<div class='comment-text'>
				<span class='comment-text'>
					<b>" . $post['nama_racikan'] . "</b>
					<span class=\"text-muted pull-right\">
						<a href=\"#\" onclick=\"removeRacikan(this,'" . $post['nama_racikan'] . "','" . $post['biaya_racikan'] . "','$total')\" class=\"btn btn-xs btn-danger\"><i class=\"fa fa-minus\"></i></a>
					</span>
					<span class=\"text-muted pull-right\">
						" . convert_currency(($total)) . "
					</span>
					<p>$item</p>
				</span>
			</div>";

		$resp = [
			'total' 		=> $total,
			'biaya_racik'	=> $post['biaya_racikan'],
			'html'	=> $html
		];
		echo json_encode($resp);
	}

	public function remove_item_racikan($id, $biaya, $total)
	{
		$session = $this->session->userdata('itemRacik');
		$session['detail'] = array_filter($session['detail'], function ($var) use ($id) {
			return ($var['racikan_id'] != $id);
		});
		$session['biaya_racik'] = $session['biaya_racik'] - $biaya;
		$session['total'] = $session['total'] - $total;
		$this->session->set_userdata('itemRacik', $session);
		$resp = [
			'total' 		=> $session['total'],
			'biaya_racik'	=> $session['biaya_racik']
		];
		echo json_encode($resp);
	}

	public function remove_item_nonracikan($id, $harga)
	{
		$session = $this->session->userdata('itemNonRacik');
		$session['detail'] = array_filter($session['detail'], function ($var) use ($id) {
			return ($var['item_id'] != $id);
		});
		$totalOld = $session['total'];
		$session['total'] = $totalOld - $harga;
		$this->session->set_userdata('itemNonRacik', $session);
		$resp = [
			'total' 		=> $session['total'],
			'tester'		=> $totalOld.'-'.$harga,
			'embalase'		=> (count($session['detail']) * $this->session->penjualan["embalaseItem"]),
		];
		echo json_encode($resp);
	}

	public function set_item_nonracikan($post = null)
	{
		if (!$post) {
			$post = $this->input->post();
		}
				//var_dump($post);die();
		$html = "";
		$total = 0;
		$item = "";
		$header = $this->session->userdata('penjualan');
		foreach ($post['list_obat_nonracikan'] as $x => $v) {
			if (empty($v['item_id'])) {
				continue;
			}
			foreach ($this->m_sale_detail->rules() as $key => $value) {
				if ($key != 'sale_id') {
					$itemNonRacikan[$x][$key] = (isset($v[$key]) ? $v[$key] : null);
				}
			}
			
			$itemNonRacikan[$x]['kronis'] = $header['pasien']['kronis'];
			$itemNonRacikan[$x]['own_id'] = $header['pasien']['own_id'];
			$itemNonRacikan[$x]['racikan'] = 'f';
			$itemNonRacikan[$x]['percent_profit'] = $header['profit'];
			$price_total = ($v['price_total'] * $header['profit']) + $v['price_total'];
			$itemNonRacikan[$x]['subtotal'] = $price_total;			
			$total += $price_total;
			$item = $v['autocom_item_id'] . "(" . $v['sale_qty'] . ")";						
			$html .= "
				<div class='comment-text itemNonracikan'>
				<span class='comment-text'>
					<b>" . $item . "</b>
					<span class=\"text-muted pull-right\">
						<a href=\"#\" onclick=\"removeNonRacikan(this,'" . $v['item_id'] . "','" . $price_total . "')\" class=\"btn btn-xs btn-danger\"><i class=\"fa fa-minus\"></i></a>
					</span>
					<span class=\"text-muted pull-right\">
						" . convert_currency(($price_total)) . "
					</span>
					<p>" . $v['dosis'] . "</p>
					
				</span>
			</div>
			";
		}
	
		$nonRacikan['detail'] = $itemNonRacikan;						
		$nonRacikan['total'] = array_sum(array_column($itemNonRacikan,'subtotal'));
		if (!empty($this->session->userdata('itemNonRacik'))) {
			$itemNonRacikOld = $this->session->userdata('itemNonRacik');
			$nonRacikan['detail'] = array_unique(array_merge($itemNonRacikan, $itemNonRacikOld['detail']),SORT_REGULAR);
			$nonRacikan['total'] = array_sum(array_column($nonRacikan['detail'],'subtotal'));
		}
		$this->session->set_userdata('itemNonRacik', $nonRacikan);
		
		$resp = [
			'total' 	=> $nonRacikan['total'],
			'embalase' 	=> (count($nonRacikan['detail']) * $this->session->penjualan["embalaseItem"]),
			'html'		=> $html
		];
		
		echo json_encode($resp);
	}

	public function get_item($unit_id, $own_id = 1)
	{
		$term = $this->input->get('term');
		$this->load->model('m_stock_fifo');
		if (!empty($this->session->penjualan["pasien"]["own_id"])) {
			$own_id = $this->session->penjualan["pasien"]["own_id"];
		}
		$where = " AND sf.own_id = '" . $own_id . "' AND unit_id = '" . $unit_id . "' AND lower(mi.item_name) like lower('%$term%') AND sf.stock_summary > 0";
		echo json_encode($this->m_stock_fifo->get_stock_item($where));
	}

	public function set_data_pasien($post = null)
	{
		if (!$post) {
			$post = $this->input->post();
		}
		$dt['pasien'] = $post;
		$dt['surety'] = $this->db->query("
		select surety_name from yanmed.ms_surety where surety_id = " . $post['surety_id'] . "");
		$dt['surety'] = $dt['surety']->row('surety_name');

		if (!empty($post['doctor_id'])) {
			$dt['dokter'] 		= $this->db->query("
			select concat(employee_ft,employee_name,employee_bt) as nama_dokter
			from hr.employee where employee_id = " . $post['doctor_id'] . " ");
			$dt['doctor_name'] 	= $dt['dokter']->row('nama_dokter');
		}

		$suretyOwner = $this->db->join("farmasi.ownership ow", "ow.own_id=so.own_id")
			->get_where('farmasi.surety_ownership so', [
				"so.surety_id"	=> $post['surety_id'],
				"so.own_id"	=> $post['own_id']
			]);

		if ($suretyOwner->num_rows() <= 0) {
			$resp = [
				"code" 		=> "201",
				"message"	=> "Margin keuntungan untuk penjamin ini belum disetting"
			];
		} else {
			$dt['profit'] 		= $suretyOwner->row('percent_profit');
			$dt['embalaseItem'] = $suretyOwner->row('profit_item');
			$resp = [
				"code" 		=> "200",
				"message"	=> "OK",
				"profit"	=> $dt['profit'],
				"embalase_item"	=> $dt['embalaseItem'],
				"px_name"   => $post['patient_name'],
				"px_norm"   => $post['patient_norm'],
				"alamat"    => (isset($post['alamat']) ? $post['alamat'] : null),
				"surety"    => $dt['surety'],
				"dokter"	=> (!empty($dt['doctor_name']) ? $dt['doctor_name'] : null)
			];
			$this->session->set_userdata('penjualan', $dt);
			$this->session->unset_userdata([
				'itemRacik', 'itemNonRacik'
			]);
		}
		echo json_encode($resp);
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

		return $nomor;
	}

	public function strukapotikresep($sale_id, $unit_id, $type)
	{
		$detailrs 				= $this->m_sale->rumah_sakit();
		$detailpasien 			= $this->m_sale->get_detail_patient($sale_id);
		$data['detailrs'] 		= $detailrs;
		$data['detailcetak'] 	= $detailpasien;
		$data['listresep'] 		= $this->m_sale->resep_dijual2($sale_id);
		$data['pencetak'] 		=  $this->m_sale->get_employee($this->session->employee_id);
		if ($type == 1) {
			$mpdf = new \Mpdf\Mpdf();
			$html = $this->load->view('sale/v_cetakanresep3', $data, true);
			$mpdf->WriteHTML($html);
			$mpdf->Output();
		} else {
			$this->load->view('sale/v_cetakanresep2', $data);
		}
	}
	public function struketiket($sale_id)
	{
		$detailrs = $this->m_sale->rumah_sakit();
		$detailpasien 				=  $this->m_sale->get_detail_patient($sale_id);
		$data['detailrs'] 			= $detailrs;
		$data['detailcetak'] 		= $detailpasien;
		//print_R($detailpasien);die;
		//$data['listresep'] = $this->m_sale->resep_dijual($sale_id);		
		$data['pencetak'] =  $this->m_sale->get_employee($this->session->employee_id);
		$data['listresep'] = $this->db->query("
		SELECT sale_num,item_name,sale_qty,dosis,ed_obat,expired_date,reff_name
		FROM farmasi.sale_detail sd
		join farmasi.sale s on sd.sale_id = s.sale_id
		JOIN ADMIN.ms_item i ON sd.item_id = i.item_id
		left join admin.ms_reff rr on i.label_item_id = rr.reff_id
		join newfarmasi.sale_fifo n on sd.saledetail_id = n.saledet_id 
		join newfarmasi.stock_fifo sf on n.stock_id = sf.stock_id
		WHERE sd.sale_id =  $sale_id and racikan_id is null 
		order by sd.saledetail_id asc ")->result();
		$data['racik'] = $this->db->query("		
		SELECT sale_num,string_agg(concat(item_name),' , ') as item_name,			
		racikan_qty,dosis,ed_obat,racikan_dosis,reff_name,racikan_id
		FROM
		farmasi.sale_detail sd
		JOIN farmasi.sale s ON sd.sale_id = s.sale_id
		JOIN ADMIN.ms_item i ON sd.item_id = i.item_id
		left join admin.ms_reff rr on i.label_item_id = rr.reff_id
		JOIN newfarmasi.sale_fifo n ON sd.saledetail_id = n.saledet_id
		JOIN newfarmasi.stock_fifo sf ON n.stock_id = sf.stock_id 
		WHERE sd.sale_id = $sale_id  and racikan_id IS NOT NULL
		GROUP BY sale_num,racikan_qty,dosis,ed_obat,racikan_dosis,reff_name,racikan_id
		")->result();
		//print_r($data);die;
		//$mpdf = new \Mpdf\Mpdf();
		$this->load->view('sale/v_cetakanetiket',$data);
		
	}


}
