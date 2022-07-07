<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receiving extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
						 ->lib_inputmulti()
						 ->lib_select2()
						 ->lib_inputmask();
		$this->load->model('m_receiving');
	}

	public function index()
	{
		$this->theme('receiving/index');
	}

	public function save()
	{
		$data = $this->input->post(); //print_r($data);
		// if ($this->m_receiving->validation()) {
			$input = [];
			foreach ($this->m_receiving->rules() as $key => $value) { 
				$input[$key] = isset($data[$key])?$data[$key]:null;
			}
			$dataPo = $this->db->get_where("farmasi.po",["po_id"=>$input['po_id']])->row();
			$input['supplier_id'] = $dataPo->supplier_id; 
			$input['rec_type'] = 0;
			$input['own_id'] = $dataPo->own_id;
			$this->db->trans_begin();
			if ($data['rec_id']) {
				$this->remove_data_recdet($data['rec_id']);
				$this->db->where('rec_id',$data['rec_id'])->update('newfarmasi.receiving',$input);
			}else{
				$this->db->insert('newfarmasi.receiving',$input);
				$data['rec_id'] = $this->db->insert_id();
			}
			$data['own_id'] = $dataPo->own_id;
			$sukses=$this->insert_recdet($data);		
			$err = $this->db->error();
			if ($err['message'] || $sukses === false) {
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
		/* }else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		} */
		// redirect('receiving');

	}

	public function save_non_po()
	{
		$data = $this->input->post();
		
		// if ($this->m_receiving->validation()) {
			$input = [];
			$this->db->trans_begin();
			foreach ($this->m_receiving->rules() as $key => $value) {
				$input[$key] = isset($data[$key])?$data[$key]:null;
			}
			// print_r($input);die;
			if ($data['rec_id']) {
				$this->db->where('rec_id',$data['rec_id'])->update('newfarmasi.receiving',$input);
				$this->remove_data_recdet($data['rec_id']);
			}else{
				$this->db->insert('newfarmasi.receiving',$input);
				$data['rec_id'] = $this->db->insert_id();
			}
			$sukses=$this->insert_recdet_non_po($data);
			$err = $this->db->error();
			if ($err['message'] || $sukses === false) {
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
		/* }else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		} */
		// redirect('receiving');

	}

	public function insert_recdet($data)
	{
		$this->load->model('m_receiving_detail');
		$stockku=[];
		$sukses=false;
		foreach ($data['div_detail'] as $x => $value) {
			if (empty($value['podet_id'])) {
				continue;
			}
			$dataPo=$this->db->get_where("farmasi.po_detail",["podet_id"=>$value['podet_id']])->row();
			// print_r($value);die;
			foreach ($this->m_receiving_detail->rules() as $r => $v) {
				$detail[$x][$r] = isset($value[$r])?$value[$r]:null;
			}
			$detail[$x]['unit_per_pack'] = ($dataPo->po_qtyunit/$dataPo->po_qtypack);
			$detail[$x]['item_id'] = $dataPo->item_id;
			$detail[$x]['item_pack'] = $dataPo->po_pack;
			$detail[$x]['item_unit'] = $dataPo->po_unititem;
			$detail[$x]['price_pack'] = $dataPo->po_pricepack;
			$detail[$x]['price_total'] = $dataPo->po_pricepack;
			$detail[$x]['qty_pack'] = $dataPo->po_qtypack;
			$detail[$x]['podet_id'] = $dataPo->podet_id;
			$detail[$x]['rec_id'] 		= $data['rec_id'];
			$detail[$x]['expired_date'] = date('Y-m-d',strtotime($value['expired_date']));
			$this->db->insert("newfarmasi.receiving_detail",$detail[$x]);
			$recdetid = $this->db->query("SELECT last_value FROM newfarmasi.seq_recdet_id")->row("last_value");

			//update po
			$this->db->where("podet_id",$dataPo->podet_id)->update("farmasi.po_detail",[
				"po_qtyreceived"=>($detail[$x]['qty_unit']+$dataPo->po_qtyreceived)
			]);
			//insert stock
			$stockku[$x]["recdet_id"] = $recdetid;
			$stockku[$x]["unit_id"] = $data['receiver_unit'];
			$stockku[$x]["own_id"] = $data['own_id'];
			$stockku[$x]["item_id"] = $detail[$x]['item_id'];
			$stockku[$x]["stock_in"] = $detail[$x]['qty_unit'];
			$stockku[$x]["stock_saldo"] = $detail[$x]['qty_unit'];
			$stockku[$x]["total_price"] = $detail[$x]['price_total'];
			$this->db->insert("newfarmasi.stock_fifo",$stockku[$x]);
			$sukses=true;
		}

		return $sukses;
	}

	public function insert_recdet_non_po($data)
	{
		$this->load->model('m_receiving_detail');
		$stockku=[];
		$sukses=false;
		// print_r($data);die;
		foreach ($data['list_item'] as $x => $value) {
			/* if (empty($value['item_id'])) {
				continue;
			} */
			foreach ($this->m_receiving_detail->rules() as $r => $v) {
				$detail[$x][$r] = isset($value[$r])?$value[$r]:null;
			}
			$detail[$x]['price_pack'] = $value['price_total'];
			$detail[$x]['price_total'] = $value['price_total'];
			$detail[$x]['rec_id'] 		= $data['rec_id'];
			$detail[$x]['qty_unit'] 	= ($value['unit_per_pack']*$value['qty_pack']);
			$detail[$x]['expired_date'] = date('Y-m-d',strtotime($value['expired_date']));
			$this->db->insert("newfarmasi.receiving_detail",$detail[$x]);
			$recdetid = $this->db->query("SELECT last_value FROM newfarmasi.seq_recdet_id")->row("last_value");
			//insert stock
			$stockku[$x]["recdet_id"] = $recdetid;
			$stockku[$x]["unit_id"] = $data['receiver_unit'];
			$stockku[$x]["own_id"] = $data['own_id'];
			$stockku[$x]["item_id"] = $detail[$x]['item_id'];
			$stockku[$x]["stock_in"] = $detail[$x]['qty_unit'];
			$stockku[$x]["stock_saldo"] = $detail[$x]['qty_unit'];
			$stockku[$x]["total_price"] = $detail[$x]['price_total'];
			$this->db->insert("newfarmasi.stock_fifo",$stockku[$x]);
		
			$sukses=true;
		}

		return $sukses;
		// die;
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_receiving->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_receiving',$attr);
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
		$data = $this->db->where('rec_id',$id)->get("newfarmasi.receiving")->row();

		echo json_encode($data);
	}

	public function find_receiving_detail($type,$id)
	{
		$this->load->model("m_receiving_detail");
		$data['data'] = $this->m_receiving_detail->get_receiving_detail($id);
		if($type == 'html') {
			echo $this->load->view("receiving/list_po",$data,true);
		}else{
			echo json_encode($data['data']);
		}
	}

	public function remove_data_recdet($id)
	{
		$data=$this->db->where('rec_id',$id)
						->get_where("newfarmasi.receiving_detail",[
							"rec_id"=>$id,
							"is_usage"=>'f',
						])->result();
		foreach ($data as $key => $value) {
			$this->db->where("podet_id",$value->podet_id)
						->set("po_qtyreceived","(po_qtyreceived-".$value->qty_unit.")",false)
						->update("farmasi.po_detail");
			$this->db->where("recdet_id",$value->recdet_id)->delete("newfarmasi.receiving_detail");
			$this->db->where("recdet_id",$value->recdet_id)->delete("newfarmasi.stock_fifo");
		}
	}

	public function delete_row($id)
	{
		$this->db->trans_begin();
		$this->remove_data_recdet($id);
		$this->db->where('rec_id',$id)->delete("newfarmasi.receiving");
		$resp = array();
		if ($this->db->affected_rows()) {
			$this->db->trans_commit();
			$resp['message'] = 'Data berhasil dihapus';
		}else{
			$this->db->trans_rollback();
			$err = $this->db->error();
			$resp['message'] = $err['message'];
		}
		echo json_encode($resp);
	}

	public function delete_multi()
	{
		$resp = array();
		$this->db->trans_begin();
		foreach ($this->input->post('data') as $key => $value) {
			$this->remove_data_recdet($value);
			$this->db->where('rec_id',$value)->delete("newfarmasi.receiving");
			$err = $this->db->error();
			if ($err['message']) {
				$resp['message'] .= $err['message']."\n";
			}
		}
		if (empty($resp['message'])) {
			$this->db->trans_commit();
			$resp['message'] = 'Data berhasil dihapus';
		}else{
			$this->db->trans_rollback();
		}
		echo json_encode($resp);
	}

	public function show_form()
	{
		$data['model'] = $this->m_receiving->rules();
		$data['norec'] = generate_code_transaksi([
			"text"	=> "REC/PBF/NOMOR/".date("d.m.Y"),
			"table"	=> "newfarmasi.receiving",
			"column"	=> "receiver_num",
			"delimiter" => "/",
			"number"	=> "3",
			"lpad"		=> "5",
			"filter"	=> " AND rec_type='0'"
		]);
		$this->load->view("receiving/form",$data);
	}

	public function show_form_hibah()
	{
		$data['model'] = $this->m_receiving->rules();
		$data['norec'] = generate_code_transaksi([
			"text"	=> "REC/HIBAH/NOMOR/".date("d.m.Y"),
			"table"	=> "newfarmasi.receiving",
			"column"	=> "receiver_num",
			"delimiter" => "/",
			"number"	=> "3",
			"lpad"		=> "5",
			"filter"	=> " AND rec_type='1'"
		]);
		$this->load->view("receiving/form_hibah",$data);
	}

	public function show_multiRows()
	{
		$this->load->model("m_receiving_detail");
		$data = $this->m_receiving_detail->get_column_multiple();
		$colauto = ["item_id"=>"Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '20%',
				];
			}else{
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value=='price_total')?'12%':'10%',
				];
			}
		}
		echo json_encode($row);
	}

	public function get_item()
	{
		$term = $this->input->get('term');
		$this->load->model('m_ms_item');
		echo json_encode($this->m_ms_item->get_item_autocomplete($term));
	}

	public function find_po_detail($id)
	{
		$this->load->model("m_po_detail");
		$data['data'] = $this->m_po_detail->get_po_detail(["po_id"=>$id]);
		echo $this->load->view("receiving/list_po",$data,true);
	}
}
