<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
						 ->lib_inputmulti()
						 ->lib_select2();						 
		$this->load->model('m_production');
	}

	public function index()
	{
		$this->theme('production/index','',get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();		
		$input = [];
			foreach ($this->m_production->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			$input['user_id'] 					= $this->session->user_id;
			$input['production_status'] 		= '0';
			$this->form_validation->set_data($input);
			$this->db->trans_begin();
			if ($this->m_production->validation()) {
				
			if ($data['production_id']) {
				$this->db->where('production_id',$data['production_id'])->update('newfarmasi.production',$input);
				$this->db->where('production_id',$data['production_id'])->delete("newfarmasi.production_indetail");
				$this->db->where('production_id',$data['production_id'])->delete("newfarmasi.production_outdetail");
			}else{
				$this->db->insert('newfarmasi.production',$input);
				$data['production_id'] = $this->db->query("select currval('farmasi.production_production_id_seq') as id")->row('id');
			}
			$produk=$this->insert_produk($data);
			$hasil=$this->insert_hasil($data); 
			
			$err = $this->db->error();
			if ($err['message'] && $produk==false && $hasil==false) {
				$this->db->trans_rollback();//rolback membatalkan semua query
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
			}else{
				$this->db->trans_commit();
				$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil disimpan</div>');
			}
		}else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		}
		redirect('production');

	}

	public function insert_produk($data)
	{
		$this->load->model('M_production_indetail'); 
		$sukses=false;
		foreach ($data['item_bahan'] as $x => $value) {
			if (empty($value['item_id'])) {
				continue;
			} 
			foreach ($this->M_production_indetail->rules() as $r => $v) {
				$detail[$x][$r] = isset($value[$r])?$value[$r]:null; 
			}
			$detail[$x]['production_id'] 	= $data['production_id'];
			$this->db->insert("newfarmasi.production_indetail",$detail[$x]);
		$sukses=true;
		}
		return $sukses;
	}
	public function insert_hasil($data)
	{
		$this->load->model('M_production_outdetail'); 
		$sukses=false;
		foreach ($data['item_hasil'] as $x => $value) {
			if (empty($value['item_id'])) {
				continue;
			} 
			foreach ($this->M_production_outdetail->rules() as $r => $v) {
				$detail[$x][$r] = isset($value[$r])?$value[$r]:null; 
			}
			$detail[$x]['production_id'] 	= $data['production_id'];
			$this->db->insert("newfarmasi.production_outdetail",$detail[$x]);
			$price = $this->db->get_where("farmasi.price",[
				"item_id"	=> $value['item_id'],
				"own_id"	=> $data['own_id']
			]);
			if ($price->num_rows()<1) {
				$this->db->insert("farmasi.price",[
					"item_id"	=> $value['item_id'],
					"own_id"	=> $data['own_id'],
					"price_sell"=> $value["item_price"],
					"price_buy"	=> $value["item_price"]
				]);
			}

			$this->db->where([
				"item_id"	=> $value["item_id"],
				"own_id"	=> $data["own_id"],
			])->update("farmasi.price",[
				"price_sell"	=> $value["item_price"],
				"price_buy"		=> $value["item_price"],
			]);
			$sukses=true;
		}
		return $sukses;
	}


	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post(); 
		$fields = $this->m_production->get_column();
		$filter = [];
		$filter['custom']="to_char(production_date,'MM-YYYY') = '".$attr['date']."'"; 
	    if( $attr["own"] !=''){			
			$filter = array_merge($filter, ["p.own_id" => $attr['own']]);
		}		
		$data 	= $this->datatable->get_data($fields,$filter,'m_production',$attr);
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
		$data = $this->db->where('production_id',$id)->get("newfarmasi.production")->row();
		$data->produk = $this->db->join("admin.ms_item mi","mi.item_id=p.item_id")
								  ->select('COALESCE(p.item_price::numeric,0) as item_price ,mi.item_id,mi.item_package AS unit_pack,mi.item_name AS label_item_id,
								  mi.item_code,mi.item_unitofitem AS item_unit,qty_item')
								  ->get_where("newfarmasi.production_indetail p",["p.production_id"=>$id])
								  ->result();
		$data->hasil = $this->db->join("admin.ms_item mi","mi.item_id=p.item_id")
								->select("COALESCE(p.item_price::numeric,0) as item_price,mi.item_id,mi.item_package AS unit_pack,mi.item_name AS label_item_id,
											mi.item_code,mi.item_unitofitem AS item_unit,qty_item")
								->get_where("newfarmasi.production_outdetail p",["p.production_id"=>$id])
								->result();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->trans_begin();		
		$this->db->where('production_id',$id)->delete("newfarmasi.production_indetail");
		$this->db->where('production_id',$id)->delete("newfarmasi.production_outdetail");
		$this->db->where('production_id',$id)->delete("newfarmasi.production");		
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
		$this->db->trans_begin();
		$resp = array();
		foreach ($this->input->post('data') as $key => $value) {
			$this->db->where('production_id',$value)->delete("newfarmasi.production_indetail");
			$this->db->where('production_id',$value)->delete("newfarmasi.production_outdetail");
			$this->db->where('production_id',$value)->delete("newfarmasi.production");
			$err = $this->db->error();
			if ($err['message']) {
				$this->db->trans_rollback();
				$resp['message'] .= $err['message']."\n";
			}
		}
		if (empty($resp['message'])) {
			$this->db->trans_commit();
			$resp['message'] = 'Data berhasil dihapus';
		}
		echo json_encode($resp);
	}

	public function show_multiRows_produksi()
	{
		$this->load->model("M_production_indetail");
		$data = $this->M_production_indetail->get_column_multi();
		$colauto = ["item_id"=>"Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '40%',
				];
			
			}elseif($value == "stok"){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value=='stok')?'18%':'14%',
					"attr"=>[
						"readonly"=>'readonly'
					]
				];
			}elseif($value == "item_price"){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value=='item_price')?'18%':'14%',
					"attr"=>[
						"readonly"=>'readonly'
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

	public function show_multiRows_hasil()
	{
		$this->load->model("M_production_outdetail");
		$data = $this->M_production_outdetail->get_column_multi();
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
		echo json_encode($row); //print_r($row);
	}

	public function get_item($own_id,$unit_id)
	{
		$term = $this->input->get('term',true); 
		$this->load->model('m_stock_fifo');
		$where = " AND lower(mi.item_name) like lower('%$term%') AND sf.stock_summary > 0 
				   AND sf.own_id = '$own_id' AND sf.unit_id='$unit_id'";
		echo json_encode($this->m_stock_fifo->get_stock_item($where));
	}
	public function get_item_hasil($own_id=0)
	{
		$term = $this->input->get('term',true); 
		
		$where = " AND lower(mi.item_name) like lower('%$term%') and classification_id in (1,175,162,97)";
		$data=$this->db->query("
				SELECT mi.item_id,mi.item_code,mi.item_name as value,p.price_sell::numeric from farmasi.v_obat mi	
				left join farmasi.price p on mi.item_id = p.item_id and p.own_id = '$own_id'	
		where 0=0 $where")->result();
	
		echo json_encode($data);
	}

	public function show_form()
	{
		$data['model'] = $this->m_production->rules();
		$data['norec'] = generate_code_transaksi([
			"text"	=> "PRO/NOMOR/".date("d.m.Y"),
			"table"	=> "newfarmasi.production",
			"column"	=> "production_no",
			"delimiter" => "/",
			"number"	=> "2",
			"lpad"		=> "5",
			"filter"	=> ""
		]);
		$this->load->view("production/form",$data);
	}
}
