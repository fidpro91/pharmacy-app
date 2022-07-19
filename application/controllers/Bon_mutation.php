<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bon_mutation extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
						 ->lib_inputmulti()
						 ->lib_select2()
						 ->lib_inputmask();
		$this->load->model('m_mutation');
		$this->load->model('m_ms_unit');
	}

	public function index()
	{
		
		$this->theme('bon_mutation/index','',get_class($this));
	}

	

	public function show_multiRows()
	{
		$this->load->model("m_mutation_detail");
		$data = $this->m_mutation_detail->get_column_multiple_permintaan();
		$colauto = ["item_id"=>"Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '40%',
				];
			}else{
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text'
				];
			}
		}
		echo json_encode($row);
	}

	public function get_item($own_id,$unit_id)
	{
		$term = $this->input->get('term'); 

		$where = " AND sf.own_id = '$own_id' AND sf.unit_id='$unit_id' AND (
			lower(mi.item_name) like lower('%$term%')
		)";
		//$select=" mi.item_name as value,";
		echo json_encode($this->m_mutation->get_item_autocomplete($where));
	}

	// public function get_item()
	// {
	// 	$term = $this->input->get('term');
	// 	$this->load->model('m_stock_fifo');
	// 	$where = " AND lower(mi.item_name) like lower('%$term%') AND sf.stock_saldo > 0";
	// 	echo json_encode($this->m_stock_fifo->get_stock_item($where));
	// }

	public function save()
	{
		$data = $this->input->post();
        $input = [];
        foreach ($this->m_mutation->rules() as $key => $value) {
            $input[$key] = (isset($data[$key])?$data[$key]:null);
        }
        $input['user_require'] 		= $this->session->user_id;
        $input['mutation_status'] 	= '1';
        $this->form_validation->set_data($input);
		if ($this->m_mutation->validation()) {
			$this->db->trans_begin();
			if ($data['mutation_id']) {
				$this->db->where('mutation_id',$data['mutation_id'])->update('newfarmasi.mutation',$input);
				$this->db->where('mutation_id',$data['mutation_id'])->delete("newfarmasi.mutation_detail");
			}else{
				$this->db->insert('newfarmasi.mutation',$input);
				$data['mutation_id'] = $this->db->insert_id();
			}
			$detail=$this->insert_mutation($data);
			$err = $this->db->error();
			if ($err['message'] && $detail==false) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
			}else{
				$this->db->trans_commit();
				$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil disimpan</div>');
			}
		}else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		}
		redirect('bon_mutation');

	}

	public function insert_mutation($data)
	{
		$this->load->model('m_mutation_detail');
        $sukses=false;
		foreach ($data['list_item'] as $x => $value) {
			if (empty($value['item_id'])) {
				continue;
			}
			foreach ($this->m_mutation_detail->rules() as $r => $v) {
				$detail[$x][$r] = isset($value[$r])?$value[$r]:null;
			}
			$detail[$x]['mutation_id'] 		= $data['mutation_id'];
			$this->db->insert("newfarmasi.mutation_detail",$detail[$x]);
            $sukses = true;
		}

        return $sukses;
	}

	public function get_data()
	{
		$this->load->library('datatable');
		
		$attr 	= $this->input->post();  
		$fields = $this->m_mutation->get_column_bon();
        $filter = [];
		if ($attr['unit'] !='') {
			$filter = array_merge($filter, ["unit_require" => $attr['unit']]);
		} 
		if ($attr['status'] != ' ') {
			$filter = array_merge($filter, ["mutation_status" => $attr['status']]);
		} 
		$data 	= $this->datatable->get_data($fields,$filter,'m_mutation',$attr);
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
			$arrayKonfirm = [2,3,4];
            if ($row["mutation_status"] == 1) {
				$obj[] = create_btnAction(["update","delete"],$row['id_key']);
			}elseif (in_array($row["mutation_status"],$arrayKonfirm)){
				$obj[] = create_btnAction([
					"Konfirmasi"=>[
						"btn-act" => "konfirm_penerimaan(".$row['id_key'].")",
						"btn-icon" => "fa fa-eye",
						"btn-class" => "btn-info"
					]
				]);
			}
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}

	public function find_one($id)
	{
		$data = $this->db->where('mutation_id',$id)->get("newfarmasi.mutation")->row();

		$data->detail = $this->db->join("admin.ms_item mi","mi.item_id=md.item_id")
								 ->select("md.*,mi.item_id,mi.item_package as unit_pack,mi.item_name as label_item_id,mi.item_code,mi.item_unitofitem as item_unit")
								 ->get_where("newfarmasi.mutation_detail md",["md.mutation_id"=>$id])
								 ->result();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('mutation_id',$id)->delete("newfarmasi.mutation");
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
			$this->db->where('mutation_id',$value)->delete("newfarmasi.mutation");
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
		$data['model'] = $this->m_mutation->rules();
		$data['norec'] = generate_code_transaksi([
			"text"	=> "M/PBF/NOMOR/".date("d.m.Y"),
			"table"	=> "newfarmasi.mutation",
			"column"	=> "bon_no",
			"delimiter" => "/",
			"number"	=> "3",
			"lpad"		=> "5",
			"filter"	=> ""
		]);
		$this->load->view("bon_mutation/form",$data);
	}

	public function show_form_konfirmasi($id)
	{
		$data['model'] 		= $this->m_mutation->rules();
		$data['dataBon'] 	= $this->m_mutation->get_databon(["mutation_id"=>$id]);
		$this->load->view("bon_mutation/form_konfirmasi_bon",$data);
	}

	public function konfirmasi_distribusi()
	{
		// print_r($this->input->post());die;
		if ($this->input->post('button-konfirm') === '2') {
			$this->batal_terima();
		}else{
			$this->konfirm_terima();
		}
		redirect('bon_mutation');
	}

	public function konfirm_terima()
	{
		$this->db->trans_begin();
		$dataMutation = $this->db->get_where("newfarmasi.mutation",[
			"mutation_id" => $this->input->post("mutation_id")
		])->row();
		if ($dataMutation->mutation_status == '3') {
			$this->session->set_flashdata('message','<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data sudah dikonfirmasi oleh user lain</div>');
			redirect("bon_mutation");
			exit;
		}
		$this->db->where([
			"mutation_id" => $this->input->post("mutation_id")
		])->update("newfarmasi.mutation",[
			"mutation_status" => "3",
			"user_receiver"	  => $this->session->user_id,
			"received_at"	  => "now()"
		]);

		$this->db->where([
			"mutation_id" => $this->input->post("mutation_id")
		])->update("newfarmasi.mutation_detail",[
			"is_approved" => "t"
		]);
		$mutationDetail = $this->db->join("newfarmasi.mutation m","m.mutation_id=md.mutation_id")
		->get_where("newfarmasi.mutation_detail md",["md.mutation_id"=>$this->input->post("mutation_id")])->result();
		foreach ($mutationDetail as $key => $value) {
			$dataku = [
				"unit_id" => $value->unit_require,
				"own_id"  => $value->own_id,
				"item_id" => $value->item_id,
				"expired_date" => $value->expired_date,
			];
			$this->update_stock($dataku,$value->qty_send,"plus",["mutation_detail_id"=>$value->mutation_detil_id],$value);
			$dataku["qty"] = $value->qty_send;
			$dataku["trans_num"] = $value->mutation_no;
			$dataku["trans_type"] = 3;
			$this->insert_stock_process($dataku,"plus");
		}
		if ($this->db->trans_status() === false) {
			$err = $this->db->error();
			$this->db->trans_rollback();
			$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
		}else{
			$this->db->trans_commit();
			$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil dikonfirmasi</div>');
		}
	}

	public function batal_terima()
	{
		$this->db->trans_begin();
		$dataMutation = $this->db->join("newfarmasi.mutation_detail md","md.mutation_id=m.mutation_id")
								->join("newfarmasi.stock_fifo sf","sf.mutation_detail_id=md.mutation_detil_id")
								->get_where("newfarmasi.mutation m",[
									"m.mutation_id" => $this->input->post("mutation_id")
								]);
		if ($dataMutation->num_rows()>0) {
			$sukses=true;
			foreach ($dataMutation->result() as $key => $value) {
				if ($value->stock_saldo != $value->stock_in) {
					$sukses=false;
					break;
				}
			}
			if (!$sukses) {
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data Item Sudah Digunakan</div>');
				redirect('bon_mutation');
				exit();
			}
		}else{
			$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data Item Belum Diterima</div>');
			redirect('bon_mutation');
			exit();
		}

		$this->db->where([
			"mutation_id" => $this->input->post("mutation_id")
		])->update("newfarmasi.mutation",[
			"mutation_status" => "4",
			"user_receiver"	  => $this->session->user_id,
			"received_at"	  => null
		]);

		$this->db->where([
			"mutation_id" => $this->input->post("mutation_id")
		])->update("newfarmasi.mutation_detail",[
			"is_approved" => "f"
		]);

		$mutationDetail = $this->db->join("newfarmasi.mutation m","m.mutation_id=md.mutation_id")
		->get_where("newfarmasi.mutation_detail md",["md.mutation_id"=>$this->input->post("mutation_id")])->result();
		foreach ($mutationDetail as $key => $value) {
			$dataku = [
				"unit_id" => $value->unit_require,
				"own_id"  => $value->own_id,
				"item_id" => $value->item_id,
				"expired_date" => $value->expired_date,
			];

			$this->update_stock($dataku,$value->qty_send,"minus",["mutation_detail_id"=>$value->mutation_detil_id]);
			$dataku["qty"] = $value->qty_send;
			$dataku["trans_num"] = (!empty($value->mutation_no)?$value->mutation_no:$value->bon_no);
			$dataku["trans_type"] = 3;
			$this->insert_stock_process($dataku,"minus");
		}
		if ($this->db->trans_status() === false) {
			$err = $this->db->error();
			$this->db->trans_rollback();
			$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
		}else{
			$this->db->trans_commit();
			$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil dikonfirmasi</div>');
		}
	}

	public function insert_stock_process($dataku,$type)
	{
		unset($dataku['expired_date']);
		if ($type=="plus") {
			$this->load->model("m_stock_process");
			foreach ($this->m_stock_process->rules() as $key => $value) {
				$dataku[$key] = (isset($dataku[$key])?$dataku[$key]:null);
			}
			$dataku["date_act"] = $dataku["date_trans"] = 'now()';
			$oldStock = $this->db->order_by("stockprocess_id","DESC")->get_where("newfarmasi.stock_process",[
				"unit_id" => $dataku["unit_id"],
				"own_id"  => $dataku["own_id"],
				"item_id" => $dataku["item_id"]
			])->row();
			$stockAwal = (isset($oldStock->stock_after)?$oldStock->stock_after:0);
			$harga = (isset($oldStock->item_price)?$oldStock->item_price:0);
			$dataku["stock_before"] = $stockAwal;
			$dataku["item_price"] 	= $harga;
			$dataku["kredit"] 		= 0;
			$dataku["debet"] 		= $dataku["qty"];
			$dataku["stock_after"] 	= $dataku["qty"]+$stockAwal;
			$dataku["total_price"] 	= ($harga*$dataku["qty"]);
			$dataku["description"] 	= "Mutasi masuk No : ".$dataku["mutation_no"];
			unset($dataku["qty"]);
		}else{
			$this->load->model("m_stock_process");
			foreach ($this->m_stock_process->rules() as $key => $value) {
				$dataku[$key] = (isset($dataku[$key])?$dataku[$key]:null);
			}
			$dataku["date_act"] = $dataku["date_trans"] = 'now()';
			$oldStock = $this->db->order_by("stockprocess_id","DESC")->get_where("newfarmasi.stock_process",[
				"unit_id" => $dataku["unit_id"],
				"own_id"  => $dataku["own_id"],
				"item_id" => $dataku["item_id"]
			])->row();
			$stockAwal = (isset($oldStock->stock_after)?$oldStock->stock_after:0);
			$harga = (isset($oldStock->item_price)?$oldStock->item_price:0);
			$dataku["stock_before"] = $stockAwal;
			$dataku["item_price"] 	= $harga;
			$dataku["kredit"] 		= $dataku["qty"];
			$dataku["debet"] 		= 0;
			$dataku["stock_after"] 	= $stockAwal-$dataku["qty"];
			$dataku["total_price"] 	= ($harga*$dataku["qty"]);
			$dataku["description"] 	= "Batal Terima Mutasi No : ".$dataku["mutation_no"];
			unset($dataku["qty"]);
		}
		$this->db->insert("newfarmasi.stock_process",$dataku);
	}

	public function update_stock($param,$qty,$type="plus",$fk=null)
	{
		if ($type=='minus') {
			$this->db->where($fk)->delete('newfarmasi.stock_fifo');
			unset($param['expired_date']);
			$this->db->set("stock_summary","(stock_summary-$qty)",false)
					 ->where($param)
					 ->update("newfarmasi.stock");

			$this->db->where([
				"item_id" 			=> $param["item_id"],
				"mutationdetail_id" => $fk["mutation_detail_id"]
			])->update("newfarmasi.mutation_fifo",[
				"is_approved"	=> "false"
			]);
		}elseif ($type='plus') {
			$this->db->where([
				"item_id" 			=> $param["item_id"],
				"mutationdetail_id" => $fk["mutation_detail_id"]
			])->update("newfarmasi.mutation_fifo",[
				"is_approved"	=> "true"
			]);
		}
	}
}
