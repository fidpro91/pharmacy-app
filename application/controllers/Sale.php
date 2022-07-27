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
			->lib_inputmask();
		$this->load->model('m_sale');
		$this->load->model('m_sale_detail');
	}

	public function index()
	{
		// session_destroy();
		$this->session->unset_userdata([
			'penjualan', 'itemRacik', 'itemNonRacik'
		]);
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit() as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$data['unit'] = $kat;
		$this->theme('sale/index', $data, get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();
		$sess = $this->session->userdata('penjualan')['pasien'];
		$this->db->trans_begin();
		// if ($this->m_sale->validation()) {
		$input = [];
		foreach ($this->m_sale->rules() as $key => $value) {
			$input[$key] = (!empty($sess[$key]) ? $sess[$key] : null);
		}
		$input['unit_id'] = $data['unit_id'];
		$input['user_id'] = ($this->session->user_id ? $this->session->user_id : 21);
		$input['sale_num'] = $this->get_no_sale($data['unit_id']);
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

		//insert into farmasi.sale
		$this->db->insert("farmasi.sale", $input);
		$saleId = $this->db->query("select currval('public.sale_id_seq')")->row('currval');
		//nonracikan
		$nonRacikan['detail'] = array_map(function ($arr) use ($saleId) {
			return $arr + ['sale_id' => $saleId];
		}, $nonRacikan['detail']);
		$saleDetail = $nonRacikan['detail'];

		//racikan
		if (!empty($racikan)) {
			$racikan['detail'] = array_map(function ($arr) use ($saleId) {
				return $arr + ['sale_id' => $saleId];
			}, $racikan['detail']);
			$saleDetail = array_merge_recursive($nonRacikan['detail'], $racikan['detail']);
		}

		//insert sale detail
		$this->db->insert_batch("farmasi.sale_detail", $saleDetail);
		$err = $this->db->error();
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$resp = [
				"code" 		=> "202",
				"message"	=> $err['message']
			];
		} else {
			$this->db->trans_commit();
			$resp = [
				"code" 		=> "200",
				"message"	=> "Data berhasil disimpan"
			];
		}
		/* }else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		} */
		echo json_encode($resp);
		// redirect('sale');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_sale->get_column();
		$filter["unit_id"] = $attr['unit_id'];
		$filter["sale_type"] = $attr['sale_type'];
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
					"update", "delete",
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
				$obj[] = "";
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
		$data = $this->db->where('sale_id', $id)->get("farmasi.sale")->row_array();

		// $this->set_data_pasien($data);

		/* $saleNonRacikan = $this->db->query(
			"SELECT sd.*,mi.item_name as autocom_item_id FROM farmasi.sale_detail sd
			JOIN admin.ms_item mi ON sd.item_id = mi.item_id
			WHERE racikan = 'f' and sd.sale_id = '$id'" 
		)->result_array();
		$this->set_item_nonracikan($saleNonRacikan);

		$saleRacikan['list_item_racikan'] = $this->db->query(
			"SELECT sd.*,mi.item_name as autocom_item_id FROM farmasi.sale_detail sd
			JOIN admin.ms_item mi ON sd.item_id = mi.item_id
			WHERE racikan = 't' and sd.sale_id = '$id'" 
		)->result_array();
		$saleRacikan['biaya_racikan'] = $data->sale_services;

		$this->set_item_racikan($saleRacikan); */
		echo json_encode($data);
	}

	public function delete_row($id)
	{
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
		$this->session->unset_userdata([
			'penjualan', 'itemRacik', 'itemNonRacik'
		]);
		$data['model'] = $this->m_sale->rules();
		$data['sale_num'] = $this->get_no_sale($id);
		$this->load->view("sale/form", $data);
	}

	public function show_form_update()
	{
		$this->session->unset_userdata([
			'penjualan', 'itemRacik', 'itemNonRacik'
		]);
		$data['model'] = $this->m_sale->rules();
		$this->load->view("sale/form_update", $data);
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

	public function show_multiRows($update=false)
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
					"width" => '35%',
				];
			} elseif ($value == "sale_price" || $value == "stock") {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value == 'price_total') ? '18%' : '14%',
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
					"width" => ($value == 'price_total') ? '18%' : '14%',
					"attr" => [
						"readonly" => "readonly",
						"data-inputmask" => "'alias': 'IDR'"
					]
				];
			} else {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value == 'price_total') ? '18%' : '14%',
				];
			}
		}
		echo json_encode($row);
	}

	public function set_item_racikan($post=null)
	{
		if (!$post) {
			$post = $this->input->post();
		}

		$html = "";
		$total = 0;
		$item = "";
		$header = $this->session->userdata('penjualan');
		foreach ($post['list_item_racikan'] as $x => $v) {
			foreach ($this->m_sale_detail->rules() as $key => $value) {
				if ($key != 'sale_id') {
					$itemRacik[$x][$key] = (isset($v[$key]) ? $v[$key] : null);
				}
			}
			$itemRacik[$x]['kronis'] = $header['pasien']['kronis'];
			$itemRacik[$x]['own_id'] = $header['pasien']['own_id'];
			$itemRacik[$x]['percent_profit'] = $header['profit'];
			$itemRacik[$x]['racikan_id'] = $post['nama_racikan'];
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
			$itemRacikan['detail'] 		= array_merge_recursive($itemRacik, $itemRacikOld['detail']);
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
		$session['total'] = $session['total'] - $harga;
		$this->session->set_userdata('itemNonRacik', $session);
		$resp = [
			'total' 		=> $session['total'],
			'embalase'		=> (count($session['detail'])*$this->session->penjualan["embalaseItem"]),
		];
		echo json_encode($resp);
	}

	public function set_item_nonracikan($post=null)
	{
		if (!$post) {
			$post = $this->input->post();
		}
		//		var_dump($post);die();
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
						" . convert_currency(($v['price_total'])) . "
					</span>
				</span>
			</div>
			";
		}
		$nonRacikan['detail'] = $itemNonRacikan;
		$nonRacikan['total'] = $total;
		if (!empty($this->session->userdata('itemNonRacik'))) {
			$itemNonRacikOld = $this->session->userdata('itemNonRacik');
			$nonRacikan['detail'] = array_merge_recursive($itemNonRacikan, $itemNonRacikOld['detail']);
			$nonRacikan['total'] = $itemNonRacikOld['total'] + $total;
		}
		$this->session->set_userdata('itemNonRacik', $nonRacikan);
		$resp = [
			'total' 	=> $total,
			'embalase' 	=> (count($itemNonRacikan)*$this->session->penjualan["embalaseItem"]),
			'html'		=> $html
		];
		echo json_encode($resp);
	}

	public function get_item($unit_id)
	{
		$term = $this->input->get('term');
		$this->load->model('m_stock_fifo');
		$where = " AND unit_id = '" . $unit_id . "' AND lower(mi.item_name) like lower('%$term%') AND sf.stock_summary > 0";
		echo json_encode($this->m_stock_fifo->get_stock_item($where));
	}

	public function set_data_pasien($post=null)
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

		$suretyOwner = $this->db->join("farmasi.ownership ow","ow.own_id=so.own_id")
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
				"alamat"    => (isset($post['alamat'])?$post['alamat']:null),
				"surety"    => $dt['surety'],
				"dokter"	=> (!empty($dt['doctor_name'])?$dt['doctor_name']:null)
			];
			$this->session->set_userdata('penjualan', $dt);
		}
		echo json_encode($resp);
	}

	public function get_no_sale($id)
	{
		$nickName = $this->db->get_where("admin.ms_unit",["unit_id"=>$id])->row("unit_nickname");
		return generate_code_transaksi([
			// "text"	=> "S/$nickName/NOMOR/" . date("d.m.Y"),
			"text"	=> "$nickName/NOMOR/" . date("m.Y"),
			"table"	=> "farmasi.sale",
			"column"	=> "sale_num",
			"delimiter" => "/",
			"number"	=> "3",
			"lpad"		=> "4",
			"filter"	=> " AND unit_id = '$id'"
		]);
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

		$data['listresep'] = $this->m_sale->resep_dijual($sale_id);
		$data['pencetak'] =  $this->m_sale->get_employee($this->session->employee_id);
		$this->load->view('sale/v_cetakanetiket', $data);
	}
}
