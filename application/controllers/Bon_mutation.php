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
	}

	public function index()
	{
		$this->theme('bon_mutation/index');
	}

	public function show_multiRows()
	{
		$this->load->model("m_mutation_detail");
		$data = $this->m_mutation_detail->get_column_multiple();
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
		$select=" mi.item_name as value,";
		echo json_encode($this->m_mutation->get_item_autocomplete($select,$where));
	}

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
            if ($row["mutation_status"] == 1) {
				$obj[] = create_btnAction(["update","delete"],$row['id_key']);
			}elseif ($row["mutation_status"] == 2 || $row["mutation_status"] == 3){
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
		$this->db->trans_begin();
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
				"item_id" => $value->item_id
			];

			$this->update_stock($dataku,$value->qty_send,"plus",["mutation_detail_id"=>$value->mutation_detil_id]);
			$dataku["qty"] = $value->qty_send;
			$dataku["trans_num"] = $value->mutation_no;
			$dataku["trans_type"] = 3;
			$this->insert_stock_process($dataku);
		}
		if ($this->db->trans_status() === false) {
			$err = $this->db->error();
			$this->db->trans_rollback();
			$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
		}else{
			$this->db->trans_commit();
			$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil dikonfirmasi</div>');
		}

		redirect('bon_mutation');
	}

	public function insert_stock_process($dataku)
	{
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
		$dataku["description"] 	= "Mutasi masuk No : ".$dataku["trans_num"];
		unset($dataku["qty"]);
		$this->db->insert("newfarmasi.stock_process",$dataku);
	}

	public function update_stock($param,$qty,$type="plus",$fk=null)
	{
		if ($type=='minus') {
			$data = $this->db->get_where("newfarmasi.stock_fifo",$param)->result();
			$stock_saldo=0;
			foreach ($data as $key => $value) {
				if ($value->stock_saldo >= $qty) {
					$stock_saldo=$value->stock_saldo-$qty;
					$this->db->where("stock_id",$value->stock_id)
							->update("newfarmasi.stock_fifo",[
								"stock_saldo" => $stock_saldo
							]);
					break;
				}elseif ($value->stock_saldo < $qty) {
					$stock_saldo=($qty-$value->stock_saldo);
					$qty = $stock_saldo;
					$this->db->where("stock_id",$value->stock_id)
							->update("newfarmasi.stock_fifo",[
								"stock_saldo" => 0
							]);
				}
			}
		}elseif ($type='plus') {
			$stock= $this->db->order_by("stock_id","desc")
							->get_where("newfarmasi.stock_fifo",$param);
			if ($stock->num_rows()>0) {
				$stock = $stock->row();
				$this->db->where("stock_id",$stock->stock_id)->update("newfarmasi.stock_fifo",[
					"stock_saldo" => ($stock->stock_saldo+$qty),
					"stock_in"	  => ($stock->stock_in-$qty+$qty)
				]);
			}else{
				$baru = $param;
				$baru["stock_in"] 		= $qty;
				$baru["stock_saldo"] 	= $qty;
				$baru[key($fk)] 		= array_values($fk)[0];
				$this->db->insert("newfarmasi.stock_fifo",$baru);
			}
		}
	}
}
