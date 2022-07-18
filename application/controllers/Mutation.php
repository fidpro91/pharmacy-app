<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutation extends MY_Generator {

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
		$this->theme('mutation/index','',get_class($this));
	}
	public function show_multiRows()
	{
		$this->load->model("m_mutation_detail");
		$data = $this->m_mutation_detail->get_column_multiple();
		$colauto = ["item_id"=>"Nama Barang"];
		$readonly = ["stock_unit","unit_pack","item_unit","expired_date"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '40%',
				];
			}elseif(in_array($value,$readonly)){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"attr"=>[
						"readonly"=>'readonly'
					]
				];
			}elseif($value=='qty_send'){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"attr"=>[
						"onchange"	=>	'hitungTotal_terima(this)'
					]
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

	public function save()
	{
		$data = $this->input->post();
		
		$input = [];
		foreach ($this->m_mutation->rules() as $key => $value) {
			$input[$key] = (isset($data[$key])?$data[$key]:null);
		}
		$input['user_sender'] 		= $this->session->user_id;
		$input['mutation_status'] 	= '2';
		$input['mutation_no'] 		= $this->get_no_mutation();
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
			$this->insert_mutation($data);
			$err = $this->db->error();
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
			}else{
				$this->db->trans_commit();
				$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil disimpan</div>');
			}
		}else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		}
		redirect('mutation');

	}

	public function insert_mutation($data)
	{
		$this->load->model('m_mutation_detail');
		foreach ($data['list_item'] as $x => $value) {
			if (empty($value['item_id'])) {
				continue;
			}
			foreach ($this->m_mutation_detail->rules() as $r => $v) {
				$detail[$x][$r] = isset($value[$r])?$value[$r]:null;
			}
			$detail[$x]['mutation_id'] 		= $data['mutation_id'];
			$detail[$x]['qty_request'] 		= $value['qty_send'];
			$detail[$x]['expired_date'] 	= $value['expired_date'];
			$this->db->insert("newfarmasi.mutation_detail",$detail[$x]);
			$this->update_stock([
				"unit_id" 	=> $data['unit_sender'],
				"own_id"	=> $data['own_id'],
				"item_id"	=> $value['item_id'],
			],$value["qty_send"],"minus");
			$dataku["item_id"] = $value['item_id'];
			$dataku["own_id"] = $data['own_id'];
			$dataku["unit_id"] = $data['unit_sender'];
			$dataku["qty"] = $value['qty_send'];
			$dataku["trans_num"] = $data['mutation_no'];
			$dataku["trans_type"] = 3;
			$this->insert_stock_process($dataku);
		}
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_mutation->get_column();
		$filter['custom'] = "to_char(m.mutation_date,'MM-YYYY') = '".$attr['bulan']."'";
		if (!empty($attr['unit'])) {
			$filter['m.unit_require'] = $attr['unit'];
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
            if ($row['mutation_status']<=2) {
				$obj[] = create_btnAction(["update","delete"],$row['id_key']);
			}else{
				$obj[] = "";
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
		$dataku["kredit"] 		= $dataku["qty"];
		$dataku["debet"] 		= 0;
		$dataku["stock_after"] 	= $stockAwal-$dataku["qty"];
		$dataku["total_price"] 	= ($harga*$dataku["qty"]);
		$dataku["description"] 	= "Mutasi Keluar No : ".$dataku["trans_num"];
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
					$this->db->set("stock_summary","(stock_summary-".$qty.")",false);
					$this->db->where([
						"item_id"	=> $value->item_id,
						"unit_id"	=> $value->unit_id,
						"own_id"	=> $value->own_id,
					])->update("newfarmasi.stock");
					break;
				}elseif ($value->stock_saldo < $qty) {
					$stock_saldo=($qty-$value->stock_saldo);
					$qty = $stock_saldo;
					$this->db->where("stock_id",$value->stock_id)
							->update("newfarmasi.stock_fifo",[
								"stock_saldo" => 0
							]);
					$this->db->set("stock_summary","(stock_summary-".$value->stock_saldo.")",false);
					$this->db->where([
						"item_id"	=> $value->item_id,
						"unit_id"	=> $value->unit_id,
						"own_id"	=> $value->own_id,
					])->update("newfarmasi.stock");
				}
			}
		}elseif ($type='plus') {
			$baru = $param;
			$baru = [
				"stock_in" 		=> $qty,
				"stock_saldo" 	=> $qty,
				key($fk)		=> array_values($fk)[0]  
			];
			$this->db->insert("newfarmasi.stock_fifo",$baru);
			$stock= $this->db->order_by("id","desc")
							->get_where("newfarmasi.stock",$param);
			if ($stock->num_rows()>0) {
				$stock=$stock->row();
				$this->db->set("stock_summary","(stock_summary-".$qty.")",false);
				$this->db->where([
					"item_id"	=> $stock->item_id,
					"unit_id"	=> $stock->unit_id,
					"own_id"	=> $stock->own_id,
				])->update("newfarmasi.stock");
			}else{
				$param["stock_summary"] = $qty;
				$this->db->insert("newfarmasi.stock",$param);
			}
		}
	}

	public function delete_row($id)
	{
		$this->db->trans_begin();
		$mutation_detail = $this->db->join("newfarmasi.mutation m","m.mutation_id=md.mutation_id")
									->get_where("newfarmasi.mutation_detail md",["md.mutation_id"=>$id])->result();
		foreach ($mutation_detail as $key => $value) {
			$this->update_stock([
				"unit_id" => $value->unit_sender,
				"own_id"  => $value->own_id,
				"item_id" => $value->item_id
			],$value->qty_send,"plus");
		}
		$this->db->where('mutation_id',$id)->delete("newfarmasi.mutation_detail");
		$this->db->where('mutation_id',$id)->delete("newfarmasi.mutation");
		$resp = array();
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$err = $this->db->error();
			$resp['message'] = $err['message'];
		}else{
			$this->db->trans_commit();
			$resp['message'] = 'Data berhasil dihapus';
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
		$data['mutation_no'] = $this->get_no_mutation();
		$this->load->view("mutation/form",$data);
	}

	public function get_no_mutation()
	{
		return generate_code_transaksi([
			"text"	=> "M/NOMOR/".date("d.m.Y"),
			"table"	=> "newfarmasi.mutation",
			"column"	=> "mutation_no",
			"delimiter" => "/",
			"number"	=> "2",
			"lpad"		=> "4",
			"filter"	=> ""
		]);
	}
}
