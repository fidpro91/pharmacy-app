<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_return extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
						 ->lib_select2()
						 ->lib_inputmask();
		$this->load->model('m_sale_return');
		$this->load->model('m_sale_return_detail');
	}

	public function index()
	{
		$this->session->unset_userdata([
			'itemReturn'
		]);
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit() as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$data['unit'] = $kat;
		$this->theme('sale_return/index',$data);
	}

	public function save()
	{
		$data = $this->input->post();
		$input = [];
		$this->db->trans_begin();
		$detailRetur = $this->session->userdata('itemReturn');
		foreach ($this->m_sale_return->rules() as $key => $value) {
			$input[$key] = (isset($data[$key])?$data[$key]:null);
		}
		// $input['user_id'] = $this->session->user_id;
		$input['user_id'] = 11;
		$input['sr_total'] = $data['total_return'];
		$input['sr_embalase'] = $data['embalase'];
		//insert sale return
		$this->db->insert('farmasi.sale_return',$input);
		$sr_id = $this->db->insert_id();

		//retur detail
		$detailRetur['detail'] = array_map(function($arr) use ($sr_id){
			return $arr + ['sr_id' => $sr_id];
		}, $detailRetur['detail']);

		$this->db->insert_batch("farmasi.sale_return_detail",$detailRetur['detail']);
		$err = $this->db->error();
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$resp = [
				"code" 		=> "202",
				"message"	=> $err['message']
			];
		}else{
			$this->db->trans_commit();
			$resp = [
				"code" 		=> "200",
				"message"	=> "Data berhasil disimpan"
			];
		}
		echo json_encode($resp);
		// redirect('sale_return');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_sale_return->get_column();
		$filter['st.unit_id']=$attr['unit_id'];
		$data 	= $this->datatable->get_data($fields,$filter,'m_sale_return',$attr);
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
			
	        $obj[] = create_btnAction(["delete"],$row['id_key']);           
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}

	public function find_one($id)
	{
		$data = $this->db->where('sr_id',$id)->get("farmasi.sale_return")->row();

		echo json_encode($data);
	}

	public function get_no_rm($tipe)
	{
		$respond= array();
		$this->load->model('m_sale');
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
		$respond= $this->m_sale->get_pasien_pelayanan($where,$select);
		echo json_encode($respond);
	}

	public function delete_row($id)
	{
		$this->db->where('sr_id',$id)->delete("farmasi.sale_return_detail");
		$this->db->where('sr_id',$id)->delete("farmasi.sale_return");
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
			$this->db->where('sr_id',$value)->delete("farmasi.sale_return_detail");
			$this->db->where('sr_id',$value)->delete("farmasi.sale_return");
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
		$data['model'] 	= $this->m_sale_return->rules();
		$data['sr_num']	= $this->get_no_sale();
		$this->load->view("sale_return/form",$data);
	}

	public function get_sale_detail($srv_id)
	{
		$data['data'] 	= $this->m_sale_return->get_saleDetail($srv_id);
		$data['sr_num']	= $this->get_no_sale();
		$this->load->view("sale_return/form_item",$data);
	}

	public function set_item_retur()
	{
		$post = $this->input->post();
		$totalItem=0;
		$totalQty=0;
		$totalRp=0;
		$itemRetur=[];
		$itemReturnOld = $this->session->userdata('itemReturn');
		foreach ($post['div_detail'] as $x => $v) {
			if(isset($v['itemdet_id'])){
				$sale = explode('|',$v['itemdet_id']);
				if (!empty($itemReturnOld)) {
					$row = array_search($sale[1], array_column($itemReturnOld['detail'], 'saledetail_id'));
					if ($row !== false) {
						continue;
					}
				}
				$v['sale_id'] = $sale[0];
				$v['saledetail_id'] = $sale[1];
				$v['item_id'] = $sale[2];
				foreach ($this->m_sale_return_detail->rules() as $key => $value) {
					if ($key != 'sr_id') {
						$itemRetur[$x][$key] = (isset($v[$key])?$v[$key]:null);
					}
				}
				$totalItem++;
				$totalQty += $v['qty_return'];
				$totalRp += $v['total_return'];
			}
		}
		$detailRetur['detail'] = $itemRetur;
		$detailRetur['totalItem'] = $totalItem;
		$detailRetur['totalQty'] = $totalQty;
		$detailRetur['totalRp'] = $totalRp;
		if (!empty($itemReturnOld)) {
			$detailRetur['detail'] = array_merge_recursive($itemReturnOld['detail'],$detailRetur['detail']);
			$detailRetur['totalItem'] = $itemReturnOld['totalItem']+$totalItem;
			$detailRetur['totalQty'] = $itemReturnOld['totalQty']+$totalQty;
			$detailRetur['totalRp'] = $itemReturnOld['totalRp']+$totalRp;
		}
		$detailRetur['detail'] = array_unique($detailRetur['detail'],SORT_REGULAR);
		$this->session->set_userdata('itemReturn',$detailRetur);
		echo json_encode([
			"code" 		=> '200',
			"message"	=> 'Oke',
			'totalItem'	=> $detailRetur['totalItem'],
			'totalQty'	=> $detailRetur['totalQty'],
			'totalRp'	=> $detailRetur['totalRp'],
		]);
	}

	public function get_no_sale()
	{
		return generate_code_transaksi([
			"text"	=> "SR/NOMOR/".date("d.m.Y"),
			"table"	=> "farmasi.sale_return",
			"column"	=> "sr_num",
			"delimiter" => "/",
			"number"	=> "2",
			"lpad"		=> "4",
			"filter"	=> ""
		]);
	}
}
