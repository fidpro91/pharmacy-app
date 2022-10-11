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
		$this->theme('mutation/v_mutation_distribusi_bon','',get_class($this));
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
			"unit_sender"		=> $this->input->post("unit_sender"),
			"mutation_no"		=> $this->get_no_mutation()
		];
		$this->db->where('mutation_id',$data['mutation_id'])->update('newfarmasi.mutation',$input);
		$data["mutation_no"] = $input["mutation_no"];
		$sukses = true;
		foreach ($data['list_item'] as $row){
			$item_id = explode("|",$row["mutation_detil_id"]);
			$item_id = $item_id[1];
			$cek = $this->db->query("SELECT s.*,i.item_name FROM newfarmasi.stock s
         	join admin.ms_item i on s.item_id = i.item_id
			WHERE s.item_id = ".$item_id."
			AND own_id = ".$data['own_id']."
			AND unit_id = ".$data['unit_sender'])->row();
			if (isset($cek->stock_summary) && $cek->stock_summary<$row['qty_send']){
				echo json_encode([
					"code" 		=> "203",
					"message"	=> "Stock item $cek->item_name kurang dari jumlah pengiriman",
				]);
				$sukses = false;
				break;
			}
		}
		if ($sukses == false){
			$this->db->trans_rollback();
			exit();
		}
		$detail=$this->update_mutation($data);
		$err = $this->db->error();
		if ($err['message'] && $detail==false) {
			$this->db->trans_rollback();
			$resp = [
				"code" 		=> "202",
				"message"	=> $err['message'],
			];
		}else{
			$this->db->trans_commit();
			$resp = [
				"code" 		=> "200",
				"message"	=> "Sukses",
			];
		}
		echo json_encode($resp);

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
			"filter"	=> " AND date(mutation_date) = date(now())"
		]);
	}

	public function update_mutation($data)
	{
        $sukses=false;
		foreach ($data['list_item'] as $x => $value) {
			if (empty($value['mutation_detil_id'])) {
				continue;
			}
			$id_mut = explode('|',$value['mutation_detil_id']);
			$value['mutation_detil_id'] = $id_mut[0];
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
			],$mutationDetail->qty_send,"minus",null,$mutationDetail);
			$dataku["item_id"] = $mutationDetail->item_id;
			$dataku["own_id"] = $data['own_id'];
			$dataku["unit_id"] = $data['unit_sender'];
			$dataku["qty"] = $mutationDetail->qty_send;
			$dataku["trans_num"] = $data['mutation_no'];
			$dataku["trans_type"] = 3;
			$unit_penerima = $this->db->get_where("admin.ms_unit",["unit_id"=>$dataku['unit_require']])->row("unit_name");
			$this->insert_stock_process($dataku,"Mutasi Keluar Ke $unit_penerima ","minus");
            $sukses = true;
		}

        return $sukses;
	}

	public function batal_mutation($id)
	{
		$this->db->trans_begin();
		$header = $this->db->get_where("newfarmasi.mutation",[
			"mutation_id"	=> $id
		])->row_array();
		$detail = $this->db->get_where("newfarmasi.mutation_detail",[
						"mutation_id"	=> $id
					])->result();
		foreach ($detail as $x => $value) {
			$this->db->where(["mutation_detil_id"=>$value->mutation_detil_id])
					 ->update("newfarmasi.mutation_detail",
					 [
						"qty_send" => 0
					 ]);
			$this->update_stock([
				"unit_id" 	=> $header['unit_sender'],
				"own_id"	=> $header['own_id'],
				"item_id"	=> $value->item_id,
			],$value->qty_send,"plus",null,$value);
			$dataku["item_id"] 	= $value->item_id;
			$dataku["own_id"] 	= $header['own_id'];
			$dataku["unit_id"] 	= $header['unit_sender'];
			$dataku["qty"] 		= $value->qty_send;
			$dataku["trans_num"] = $header['mutation_no'];
			$dataku["trans_type"] = 3;
			$this->insert_stock_process($dataku,"Batal Mutasi","plus");
		}
		
		$this->db->where(["mutation_id"=>$id])->update("newfarmasi.mutation",[
			"mutation_status"	=> 1
		]);

		$resp = array();
		if ($this->db->trans_status() !== false) {
			$this->db->trans_commit();
			$resp['message'] 	= 'Data berhasil dibatalkan';
			$resp['code'] 		= '200';
		}else{
			$err = $this->db->error();
			$resp['message'] 	= $err['message'];
			$resp['code'] 		= '201';
			$this->db->trans_rollback();
		}
		echo json_encode($resp);
	}

	public function update_stock($param,$qty,$type="plus",$fk=null,$mutation_detail)
	{
		if ($type=='minus') {
			$data = $this->db->where("stock_saldo > 0",null)->get_where("newfarmasi.stock_fifo",$param)->result();
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
					$this->db->insert("newfarmasi.mutation_fifo",[
						"mutation_id"		=> $mutation_detail->mutation_id,
						"item_id"			=> $value->item_id,
						"mutationdetail_id" => $mutation_detail->mutation_detil_id,
						"expired_date" 		=> $value->expired_date,
						"qty_item" 			=> $qty,
					]);
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

					$this->db->insert("newfarmasi.mutation_fifo",[
						"mutation_id"		=> $mutation_detail->mutation_id,
						"item_id"			=> $value->item_id,
						"mutationdetail_id" => $mutation_detail->mutation_detil_id,
						"expired_date" 		=> $value->expired_date,
						"qty_item" 			=> $value->stock_saldo,
					]);
				}
			}
		}elseif ($type='plus') {
			$dataFifo = $this->db->get_where("newfarmasi.mutation_fifo",[
										"mutation_id"	=> $mutation_detail->mutation_id,
										"mutationdetail_id"	=> $mutation_detail->mutation_detil_id,
										"item_id"		=> $param["item_id"]
									])->result();
			
			foreach ($dataFifo as $key => $value) {
				$this->db->insert("newfarmasi.stock_fifo",[
					"item_id"	=> $param['item_id'],
					"unit_id"	=> $param['unit_id'],
					"own_id"	=> $param['own_id'],
					"stock_in"		=> $value->qty_item,
					"stock_saldo"	=> $value->qty_item,
					"expired_date"	=> $value->expired_date,
				]);
			}
			$stock= $this->db->order_by("id","desc")
							 ->get_where("newfarmasi.stock",$param);

			if ($stock->num_rows()>0) {
				$stock=$stock->row();
				$this->db->set("stock_summary","(stock_summary+".$qty.")",false);
				$this->db->where([
					"item_id"	=> $stock->item_id,
					"unit_id"	=> $stock->unit_id,
					"own_id"	=> $stock->own_id,
				])->update("newfarmasi.stock");
			}else{
				$param["stock_summary"] = $qty;
				$this->db->insert("newfarmasi.stock",$param);
			}

			$this->db->where([
				"mutation_id"	=> $mutation_detail->mutation_id,
				"mutationdetail_id"	=> $mutation_detail->mutation_detil_id,
				"item_id"		=> $param["item_id"]
			])->delete("newfarmasi.mutation_fifo");
		}
	}

	public function insert_stock_process($dataku,$desc,$ket="plus")
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
		if ($ket=="plus") {
			$dataku["kredit"] 		= 0;
			$dataku["debet"] 		= $dataku["qty"];
			$dataku["stock_after"] 	= $stockAwal+$dataku["qty"];
		}else{
			$dataku["kredit"] 		= $dataku["qty"];
			$dataku["debet"] 		= 0;
			$dataku["stock_after"] 	= $stockAwal-$dataku["qty"];
		}
		$dataku["total_price"] 	= ($harga*$dataku["qty"]);
		$dataku["description"] 	= $desc." No : ".$dataku["trans_num"];
		unset($dataku["qty"]);
		$this->db->insert("newfarmasi.stock_process",$dataku);
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_mutation->get_column_bon();
        $filter = [];	
		$filter["custom"] = " to_char(mutation_date,'MM-YYYY')='" . $attr['tgl'] . "'";
		$filter = array_merge($filter, ["unit_sender" => ''.(!empty($attr['unit'])?$attr['unit']:0).'']);
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
			if ($row["mutation_status"] == 3) {
				$obj[] = create_btnAction([
					"Cetak"=>[
						"btn-act" => "cetak_struk(".$row['id_key'].")",
						"btn-icon" => "fa fa-print",
						"btn-class" => "btn-default"
					]
				]);
			}elseif($row["mutation_status"] == 2){
				$obj[] = create_btnAction([
					"Batal Kirim"=>[
						"btn-act" => "batal_mutasi(".$row['id_key'].")",
						"btn-icon" => "fa fa-close",
						"btn-class" => "btn-danger"
					],
					"Cetak"=>[
						"btn-act" => "cetak_struk(".$row['id_key'].")",
						"btn-icon" => "fa fa-print",
						"btn-class" => "btn-default"
					]
				]);
			}else{
				$obj[] = create_btnAction([
					"Konfirmasi"=>[
						"btn-act" => "konfirm_distribusi(".$row['id_key'].")",
						"btn-icon" => "fa fa-send",
						"btn-class" => "btn-success"
					],
					"Cetak"=>[
						"btn-act" => "cetak_struk(".$row['id_key'].")",
						"btn-icon" => "fa fa-print",
						"btn-class" => "btn-default"
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

	public function show_form($id)
	{
		$data['model'] 		= $this->m_mutation->rules();
		$data['dataBon'] 	= $this->m_mutation->get_databon(["m.mutation_id"=>$id]);
		$this->load->view("mutation/form_distribusi_bon",$data);
	}

	public function cetak_struk($id)
	{
		$session 	= $this->session->userdata('login');
		$data['username']  =  $this->session->user_name;
		$this->load->model('m_profil');
		$data['data'] = $this->m_profil->get_data();

		$sLimit = "";
		$sWhere = "AND mutation_id = '" . $id . "' ";
		$sOrder = "";

		$aColumns 	= array(
			"b.bon_id",
			"to_char(b.bon_date, 'DD-MM-YYYY') bon_date",
			"b.bon_no",
			"v.unit_name as asal",
			"vt.unit_name as tujuan",
			"o.own_name",
			"b.bon_status",
			"b.unit_id",
			"b.unit_target",
			"b.own_id",
			"b.user_id"
		);
		$profilrs = $this->m_profil->get_data();

		$data['DataUnit'] = $this->m_mutation->get_data_m($sLimit, $sWhere, $sOrder, $aColumns);
		$data['DataDetail'] = $this->m_mutation->get_permintaan_detail($id);
		$this->load->view("mutation/cetakdistribusibon", $data);
	}
}
