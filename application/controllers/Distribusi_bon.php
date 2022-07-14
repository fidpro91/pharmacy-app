<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distribusi_bon extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
						 ->lib_daterange()
						 ->lib_inputmulti()
						 ->lib_select2()
						 ->lib_inputmask();
		$this->load->model('m_mutation');
	}

	public function index()
	{
		$this->theme('mutation/v_mutation_distribusi_bon');
	}

	public function show_multiRows()
	{
		$this->load->model("m_mutation_detail");
		$data = $this->m_mutation_detail->get_column_multiple_bon();
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

	public function save_distribusi()
	{
		$data = $this->input->post();
		$this->db->trans_begin();
		$input = [
			"user_sender" 		=> $this->session->user_id,
			"mutation_status"	=> "2",
			"unit_sender"		=> $this->input->post("unit_sender")
		];
		$this->db->where('mutation_id',$data['mutation_id'])->update('newfarmasi.mutation',$input);
		$detail=$this->update_mutation($data);
		$err = $this->db->error();
		if ($err['message'] && $detail==false) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
		}else{
			$this->db->trans_commit();
			$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil disimpan</div>');
		}
		redirect('Distribusi_bon');

	}

	public function update_mutation($data)
	{
        $sukses=false;
		foreach ($data['list_item'] as $x => $value) {
			if (empty($value['mutation_detil_id'])) {
				continue;
			}
			$this->db->where(["mutation_detil_id"=>$value['mutation_detil_id']])
					 ->update("newfarmasi.mutation_detail",
					 [
						"qty_send" => $value["qty_send"]
					 ]);
			
			$mutationDetail = $this->db->get_where("newfarmasi.mutation_detail",[
				"mutation_detil_id" => $value['mutation_detil_id']
			])->row();
			$this->update_stock([
				"unit_id" 	=> $data['unit_sender'],
				"own_id"	=> $data['own_id'],
				"item_id"	=> $mutationDetail->item_id,
			],$mutationDetail->qty_send,"minus");
			$dataku["item_id"] = $mutationDetail->item_id;
			$dataku["own_id"] = $data['own_id'];
			$dataku["unit_id"] = $data['unit_sender'];
			$dataku["qty"] = $mutationDetail->qty_send;
			$dataku["trans_num"] = $data['mutation_no'];
			$dataku["trans_type"] = 3;
			$this->insert_stock_process($dataku);
            $sukses = true;
		}

        return $sukses;
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

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_mutation->get_column_bon();
		list($tgl1,$tgl2) = explode('/', $attr['tgl']); 
        $filter = [];	
		$filter["custom"]= "(date(mutation_date) between '$tgl1' and '$tgl2')";	
		if ($attr['unit'] !='') {
			$filter = array_merge($filter, ["unit_sender" => $attr['unit']]);
		}	
		if($attr['sts'] != ' '){
			$filter =array_merge($filter, ["mutation_status" => $attr['sts']]);
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
            $obj[] = create_btnAction([
				"Konfirmasi"=>[
					"btn-act" => "konfirm_distribusi(".$row['id_key'].")",
					"btn-icon" => "fa fa-pencil",
					"btn-class" => "btn-info"
                ],
                "Cetak"=>[
					"btn-act" => "cetak_struk(".$row['id_key'].")",
					"btn-icon" => "fa fa-print",
					"btn-class" => "btn-default"
				]
			]);
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

	public function show_form($id)
	{
		$data['model'] 		= $this->m_mutation->rules();
		$data['dataBon'] 	= $this->m_mutation->get_databon(["mutation_id"=>$id]);
		$this->load->view("mutation/form_distribusi_bon",$data);
	}
}
