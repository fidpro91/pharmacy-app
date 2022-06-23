<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends MY_Generator {

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
			'penjualan','itemRacik','itemNonRacik'
		]);
		$this->theme('sale/index');
	}

	public function save()
	{
		$data = $this->input->post();
		$sess = $this->session->userdata('penjualan')['pasien'];
		$this->db->trans_begin();
		// if ($this->m_sale->validation()) {
			$input = [];
			foreach ($this->m_sale->rules() as $key => $value) {
				$input[$key] = (!empty($sess[$key])?$sess[$key]:null);
			}
			$input['unit_id'] = 18;
			$input['user_id'] = $this->session->user_id;
			$input['sale_num'] = $this->get_no_sale();
			$racikan = $this->session->userdata('itemRacik');
			$nonRacikan = $this->session->userdata('itemNonRacik');
			
			$totalRacikan = array_sum(array_column($racikan, 'total'));
			$totalService = array_sum(array_column($racikan, 'biaya_racikan'));
			$grandtotal = $totalRacikan+$nonRacikan['total'];
			$embalase = $grandtotal/100;
			$embalase = abs(ceil($embalase)-$embalase)*100;
			$input['sale_total'] = $grandtotal+$embalase;
			$input['embalase_item_sale'] = $embalase;
			$input['sale_services'] = $totalService;

			//insert into farmasi.sale
			$this->db->insert("farmasi.sale",$input);
			$saleId = $this->db->query("select currval('public.sale_id_seq')")->row('currval');
			//nonracikan
			$nonRacikan['detail'] = array_map(function($arr) use ($saleId){
				return $arr + ['sale_id' => $saleId];
			}, $nonRacikan['detail']);

			//racikan
			$racikan['detail'] = array_map(function($arr) use ($saleId){
				return $arr + ['sale_id' => $saleId];
			}, $racikan['detail']);
			
			$saleDetail = array_merge_recursive($nonRacikan['detail'],$racikan['detail']);
			//insert sale detail
			$this->db->insert_batch("farmasi.sale_detail",$saleDetail);
			
			$err = $this->db->error();
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
			}else{
				$this->db->trans_commit();
				$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil disimpan</div>');
			}
		/* }else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		} */
		redirect('sale');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_sale->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_sale',$attr);
		$records["aaData"] = array();
		$no   	= 1 + $attr['start'];
        foreach ($data['dataku'] as $index=>$row) {
            $obj = array($row['id_key'],$no);
            foreach ($fields as $key => $value) {
            	if (is_array($value)) {
            		if (isset($value['custom'])){
            			$obj[] = call_user_func($value['custom'],$row[$key]);
            		}else{
            			$obj[] = $row[$key];
            		}
            	}else{
            		$obj[] = $row[$value];
            	}
            }
            $obj[] = create_btnAction(["update","delete"],$row['id_key']);
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}

	public function find_one($id)
	{
		$data = $this->db->where('sale_id',$id)->get("sale")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('sale_id',$id)->delete("sale");
		$resp = array();
		if ($this->db->affected_rows()) {
			$resp['message'] = 'Data berhasil dihapus';
		}else{
			$err = $this->db->error();
			$resp['message'] = $err['message'];
		}
		echo json_encode($resp);
	}

	public function delete_multi()
	{
		$resp = array();
		foreach ($this->input->post('data') as $key => $value) {
			$this->db->where('sale_id',$value)->delete("sale");
			$err = $this->db->error();
			if ($err['message']) {
				$resp['message'] .= $err['message']."\n";
			}
		}
		if (empty($resp['message'])) {
			$resp['message'] = 'Data berhasil dihapus';
		}
		echo json_encode($resp);
	}

	public function show_form()
	{
		$data['model'] = $this->m_sale->rules();
		$data['sale_num'] = $this->get_no_sale();
		$this->load->view("sale/form",$data);
	}

	public function get_no_rm($tipe,$kunjungan='1')
	{
		$respond= array();
		$term 	= $this->input->get('term', true);

		if($tipe == 'norm')
		{
			$where = " AND px_norm like '%$term%'";
			$select = "p.px_norm as label,";
		}else
		{
			$where = "AND LOWER(px_name) like '%$term%'";
			$select = "p.px_name as label,";
		}
		
		if ($kunjungan=='1') {
			$respond= $this->m_sale->get_pasien_pelayanan($where,$select);
		}else{
			$respond= $this->m_sale->get_data_pasien($where,$select);
		}
		echo json_encode($respond);
		
	}

	public function show_form_pasien()
	{
		$this->load->view("sale/form_pasien");
	}

	public function show_form_racikan()
	{
		$data['model'] = [];
		$this->load->view("sale/form_racikan",$data);
	}

	public function show_form_nonracikan()
	{
		$data['model'] = [];
		$this->load->view("sale/form_non_racikan",$data);
	}

	public function show_multiRows()
	{
		$this->load->model("m_sale_detail");
		$data = $this->m_sale_detail->get_column_multiple();
		$colauto = ["item_id"=>"Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '35%',
				];
			}
			elseif($value == "sale_price"){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value=='price_total')?'18%':'14%',
					"attr"=>[
						"readonly"=>'readonly'
					]
				];
			}
			elseif($value == "price_total"){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value=='price_total')?'18%':'14%',
					"attr"=>[
						"readonly"=>"readonly"
					]
				];
			}
			else{
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value=='price_total')?'18%':'14%',
				];
			}
		}
		echo json_encode($row);
	}

	public function set_item_racikan()
	{
		$post = $this->input->post();
		$html="";
		$total=0;
		$item="";
		$header = $this->session->userdata('penjualan');
		foreach ($post['list_item_racikan'] as $x => $v) {
			foreach ($this->m_sale_detail->rules() as $key => $value) {
				if ($key != 'sale_id') {
					$itemRacik[$x][$key] = (isset($v[$key])?$v[$key]:null);
				}
			}
			$itemRacik[$x]['kronis'] = $header['pasien']['kronis'];
			$itemRacik[$x]['own_id'] = $header['pasien']['own_id'];
			$itemRacik[$x]['percent_profit'] = $header['profit'];
			$itemRacik[$x]['racikan_id'] = $post['nama_racikan'];
			$itemRacik[$x]['racikan_qty'] = $post['qty_racikan'];
			$itemRacik[$x]['racikan_dosis'] = $post['signa'];
			$price_total = ($v['price_total']*$header['profit'])+$v['price_total'];
			$itemRacik[$x]['subtotal'] = $price_total;
			$itemRacik[$x]['racikan'] = 't';
			$total += $price_total;
			$item .= $v['autocom_item_id']."(".$v['sale_qty'].")"."<br>";
		}
		$total = $total;
		if (!empty($this->session->userdata('itemRacik'))) {
			$itemRacikOld = $this->session->userdata('itemRacik');
			$itemRacikan['detail'] 		= array_merge_recursive($itemRacik,$itemRacikOld['detail']);
			$itemRacikan['biaya_racik']	= $itemRacikOld['biaya_racik']+$post['biaya_racikan'];
			$itemRacikan['total']			= $itemRacikOld['total']+$total;
		}else{
			$itemRacikan = [
				"detail"		=> $itemRacik,
				'biaya_racik'	=> $post['biaya_racikan'],
				"total"			=> $total
			];
		}
		$this->session->set_userdata('itemRacik',$itemRacikan);
		$item = rtrim($item,"<br>");
		$html .= "
			<div class='comment-text'>
				<span class='comment-text'>
					<b>".$post['nama_racikan']."</b>
					<span class=\"text-muted pull-right\">
						".convert_currency(($total))."
					</span>
					<p>$item</p>
				</span>
			</div>
			";
		$resp = [
			'total' 		=> $total,
			'biaya_racik'	=> $post['biaya_racikan'],
			'html'	=> $html
		];
		echo json_encode($resp);
	}

	public function set_item_nonracikan()
	{
		$post = $this->input->post();
		$html="";
		$total=0;
		$item="";
		$header = $this->session->userdata('penjualan');
		foreach ($post['list_obat_nonracikan'] as $x => $v) {
			foreach ($this->m_sale_detail->rules() as $key => $value) {
				if ($key != 'sale_id') {
					$itemNonRacikan[$x][$key] = (isset($v[$key])?$v[$key]:null);
				}
			}
			
			$itemNonRacikan[$x]['kronis'] = $header['pasien']['kronis'];
			$itemNonRacikan[$x]['own_id'] = $header['pasien']['own_id'];
			$itemNonRacikan[$x]['racikan'] = 'f';
			$itemNonRacikan[$x]['percent_profit'] = $header['profit'];
			$price_total = ($v['price_total']*$header['profit'])+$v['price_total'];
			$itemNonRacikan[$x]['subtotal'] = $price_total;
			$total += $price_total;
			$item .= $v['autocom_item_id']."(".$v['sale_qty'].")"."<br>";
			$html .= "
			<div class='comment-text'>
				<span class='comment-text'>
					<b>".$item."</b>
					<span class=\"text-muted pull-right\">
						".convert_currency(($v['price_total']))."
					</span>
				</span>
			</div>
			";
		}
		$nonRacikan['detail'] = $itemNonRacikan;
		$nonRacikan['total'] = $total;
		if (!empty($this->session->userdata('itemNonRacik'))) {
			$itemNonRacikOld = $this->session->userdata('itemNonRacik');
			$nonRacikan['detail'] = array_merge_recursive($itemNonRacikan,$itemNonRacikOld['detail']);
			$nonRacikan['total'] = $itemNonRacikOld['total']+$total;
		}
		$this->session->set_userdata('itemNonRacik',$nonRacikan);
		$resp = [
			'total' => $total,
			'html'	=> $html
		];
		echo json_encode($resp);
	}

	public function get_total_nonracikan()
	{
		$dtNonracikan = $this->session->userdata('itemNonRacik');
		$sub_total = 0;
		foreach ($dtNonracikan['list_obat_nonracikan'] as $nonracikan){
			$sub_total +=$nonracikan['price_total'];
		}
		echo $sub_total;

	}

	public function get_total_racikan()
	{
		$dtRacikan = $this->session->userdata('itemRacik');
		// var_dump($dtRacikan['biaya_racikan']);die;
		$total_item = 0;
		foreach ($dtRacikan['list_item_racikan'] as $item) {
			$total_item += $item['price_total'];
		}
		$total_racikan = 0;
		if(is_array($dtRacikan['biaya_racikan'])){
			foreach($dtRacikan['biaya_racikan'] as $key=>$b_racikan){
				$total_racikan += $b_racikan;
			}
		}else{
			$total_racikan = $dtRacikan['biaya_racikan'];
		}

		$sub_total = $total_item+$total_racikan;
		// var_dump($sub_total);die;
		echo $sub_total;

	}



	public function get_item()
	{
		$term = $this->input->get('term');
		$this->load->model('m_stock_fifo');
		$where = " AND lower(mi.item_name) like lower('%$term%') AND sf.stock_saldo > 0";
		echo json_encode($this->m_stock_fifo->get_stock_item($where));
	}

	public function set_data_pasien()
	{
		$post = $this->input->post();
		$dt['pasien'] = $post;
		$dt['surety']=$this->db->query("select surety_name from yanmed.ms_surety where surety_id = ".$post['surety_id']." ");
		$dt['surety'] = $dt['surety']->row('surety_name');
		$dt['dokter']= $this->db->query("select concat(employee_ft,employee_name,employee_bt) as nama_dokter from hr.employee where employee_id = ".$post['doctor_id']." "); 
		$dt['dokter'] = $dt['dokter']->row('nama_dokter');

		$dt['profit'] = $this->db->get_where('farmasi.surety_ownership',[
			"surety_id"	=> $post['surety_id'],
			"own_id"	=> $post['own_id']
		]);
		if ($dt['profit']->num_rows() <= 0) {
			$resp=[
				"code" 		=> "201",
				"message"	=> "Margin keuntungan untuk penjamin ini belum disetting"
			];
		}else{
			$dt['profit'] = $dt['profit']->row('percent_profit');
			$resp=[
				"code" 		=> "200",
				"message"	=> "OK",
				"profit"	=> $dt['profit'],
				"px_name"   => $post['patient_name'],
				"px_norm"   => $post['patient_norm'],
				"alamat"    => $post['alamat'],
				"surety"    => $dt['surety'],
				"dokter"	=> $dt['dokter']
				
			];
			$this->session->set_userdata('penjualan',$dt);
			
		}		
		echo json_encode($resp);
		
	}


	
	public function hapus_sess()
	{
		$this->session->unset_userdata('itemRacik');
		$this->session->unset_userdata('itemNonRacik');
		$this->session->unset_userdata('pasien');
	}

	public function get_no_sale()
	{
		return generate_code_transaksi([
			"text"	=> "S/NOMOR/".date("d.m.Y"),
			"table"	=> "farmasi.sale",
			"column"	=> "sale_num",
			"delimiter" => "/",
			"number"	=> "2",
			"lpad"		=> "4",
			"filter"	=> ""
		]);
	}
}
?>
