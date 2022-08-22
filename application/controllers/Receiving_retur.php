<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';
class Receiving_retur extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
						 ->lib_inputmulti()
						 ->lib_select2()
						 ->lib_inputmask();
		$this->load->model('m_receiving_retur');
	}

	public function index()
	{
		$this->theme('receiving_retur/index','',get_class($this));
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_receiving_retur->validation()) {
			$input = [];
			foreach ($this->m_receiving_retur->rules() as $key => $value) {
				$input[$key] = (!empty($data[$key])?$data[$key]:null);
			}
			$input['rr_status'] = 'f';
			$input['user_id'] 	= $this->session->user_id;
			$input['unit_id'] 	= 55;
			$this->db->trans_begin();
			if ($data['rr_id']) {
				$this->db->where("rr_id",$data['rr_id'])->delete("newfarmasi.receiving_retur_detil");
				$this->db->where('rr_id',$data['rr_id'])->update('newfarmasi.receiving_retur',$input);
				$rr_id = $data['rr_id'];
			}else{
				$this->db->insert("newfarmasi.receiving_retur",$input);
				$rr_id = $this->db->insert_id();
			}
			$this->load->model("m_receiving_retur_detil");
			$inputDetail=[];
			foreach ($data['list_item'] as $a => $value) {
				if(empty($value['item_id'])){
					continue;
				}
				foreach ($this->m_receiving_retur_detil->rules() as $key => $x) {
					$inputDetail[$a][$key] = (!empty($value[$key])?$value[$key]:null);
				}
				$dataRec = explode('|',$value['id_penerimaan']);
				$inputDetail[$a]['rr_id'] 	= $rr_id;
				$inputDetail[$a]['rec_id'] 	= $dataRec[0];
				$inputDetail[$a]['recdet_id'] 	= $dataRec[1];
				$inputDetail[$a]['supplier_id'] 	= $dataRec[2];
				$inputDetail[$a]['own_id'] 	= $dataRec[3];
				$inputDetail[$a]['rrd_type']		= $input['rr_type'];
			}
			$this->db->insert_batch("newfarmasi.receiving_retur_detil",$inputDetail);
			
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
		redirect('receiving_retur');

	}

	public function show_multiRows()
	{
		$this->load->model("m_receiving_retur_detil");
		$data = $this->m_receiving_retur_detil->get_column_multiple();
		$colauto = ["item_id"=>"Nama Barang"];
		$readOnly = ["rrd_price","qty_terima","stock_saldo","supplier","id_penerimaan"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '25%',
				];
			}elseif(in_array($value,$readOnly)){
				$row[] = [
					"id" 	=> $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" 	=> 'text',
					"attr"	=> [
						"readonly"	=> "true"
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

	public function get_item()
	{
		$term = $this->input->get('term');
		$where = " AND lower(mi.item_name) like lower('%$term%')";
		echo json_encode($this->m_receiving_retur->get_item($where));
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_receiving_retur->get_column();
		$filter['custom'] = "to_char(rr_date,'MM-YYYY') = '".$attr['bulan']."'";
		$data 	= $this->datatable->get_data($fields,$filter,'m_receiving_retur',$attr);
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
            $obj[] = create_btnAction(["update","delete",
				"download pdf" => [
					"btn-act" => "location.href='".base_url()."receiving_retur/cetak/". $row['id_key']."/1'",
					"btn-icon" => "fa fa-file-pdf-o",
					"btn-class" => "btn-warning"
				],
				"download excel" => [
					"btn-act" => "location.href='" . base_url() . "receiving_retur/cetak/" . $row['id_key'] . "/2'",
					"btn-icon" => "fa fa-file-pdf-o",
					"btn-class" => "btn-success"
				],
				"print" => [
					"btn-act" => "cetak(" . $row['id_key'] . ",3)",
					"btn-icon" => "fa fa-print",
					"btn-class" => "btn-default"
				],
			],$row['id_key']);
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}

	public function find_one($id)
	{
		$data = $this->db->where('rr_id',$id)->get("newfarmasi.receiving_retur")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where("rr_id",$id)->delete("newfarmasi.receiving_retur_detil");
		$this->db->where('rr_id',$id)->delete("newfarmasi.receiving_retur");
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
			$this->db->where("rr_id",$value)->delete("newfarmasi.receiving_retur_detil");
			$this->db->where('rr_id',$value)->delete("newfarmasi.receiving_retur");
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
		$data['model'] 		= $this->m_receiving_retur->rules();
		$data['numretur']	= generate_code_transaksi([
									"text"	=> "RB/".date("Y/m")."/NOMOR",
									"table"	=> "newfarmasi.receiving_retur",
									"column"	=> "num_retur",
									"delimiter" => "/",
									"number"	=> "4",
									"lpad"		=> "4",
									"filter"	=> ""
								]);
		$this->load->view("receiving_retur/form",$data);
	}

	public function find_rr_detail($id)
	{
		$retur = $this->db->query("
		SELECT rd.*,mi.item_name as label_item_id,sp.supplier_name as supplier,concat(rd.rec_id,'|',rd.recdet_id,'|',rd.supplier_id,'|',rd.own_id) as id_penerimaan,rd2.qty_unit as qty_terima
		FROM newfarmasi.receiving_retur_detil rd
		JOIN admin.ms_item mi on mi.item_id = rd.item_id
		join newfarmasi.receiving_detail rd2 on rd.recdet_id = rd2.recdet_id
		JOIN admin.ms_supplier sp ON rd.supplier_id = sp.supplier_id
		where rd.rr_id = '$id'
		")->result();
		echo json_encode($retur);
	}

	public function cetak($id,$type)
	{
		$respond = new stdClass();
		$respond = $this->m_receiving_retur->get_retur_by_id($id);
		$respond->detail = $this->m_receiving_retur->find_retur_detail($respond->rr_id);
		$data['isi'] = $respond;
		$data['profil'] = $this->m_receiving_retur->get_data_profile();
		$namafile = "Retur-Penerimaan.pdf";
		if ($type == 1) {
			$mpdf = new \Mpdf\Mpdf();
			$html = $this->load->view('receiving_retur/cetak',$data,true);
			$mpdf->WriteHTML($html);
			// $mpdf->Output();
			$mpdf->Output($namafile, 'D');
		}elseif ($type == 2) {
			header("Content-type: application/vnd-ms-excel");
			header("Content-Disposition: attachment; filename=" . $namafile . ".xls");
			return $this->load->view('receiving_retur/cetak', $data);
		}else {
			return $this->load->view('receiving_retur/cetak', $data);
		}
		
		
		
	}
}
