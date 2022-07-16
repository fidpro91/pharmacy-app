<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bon extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_select2()->lib_inputmulti();
		$this->load->model('m_bon');
	}

	public function index()
	{
		$this->theme('bon/index');
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_bon->validation()) {
			$input = [];
			foreach ($this->m_bon->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['bon_id']) {
				$this->db->where('bon_id',$data['bon_id'])->update('farmasi.bon',$input);
			}else{
				$this->db->insert('farmasi.bon',$input);
				
			}
			$err = $this->db->error();
			if ($err['message']) {
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
			}else{
				$this->session->set_flashdata('message','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil disimpan</div>');
			}
		}else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		}
		redirect('bon');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_bon->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_bon',$attr);
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
		$data = $this->db->where('bon_id',$id)->get("farmasi.bon")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('bon_id',$id)->delete("farmasi.bon");
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
			$this->db->where('bon_id',$value)->delete("farmasi.bon");
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
		$data['model'] = $this->m_bon->rules();
		$respond= $this->db->query("SELECT * FROM ADMIN.setting WHERE setting_name = 'satuan_obat_dipakai'")->row();
		$setting = json_decode($respond->setting_value);
		$data["setting"] = $setting->data;
		$this->load->view("bon/form",$data);
	}

	public function get_code_permintaan_unit()
	{
		$respond= $this->m_bon->get_code_permintaan_unit();
		echo ($respond);
	}

	public function get_kepemilikan()
	{
		$respon = $this->m_bon->get_kepemilikan();
		echo json_encode($respon);
	}

	public function show_multiRows()
	{
		$this->load->model("m_bon_detail");
		$data = $this->m_bon_detail->get_column_multiple();
		$colauto = ["item_id"=>"Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '35%',
				];
			} elseif ($value == "Stock_Terkini") {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"attr" => [
						"readonly" => 'readonly'
					]
				];
			} elseif ($value == "Qty_Permintaan") {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"attr" => [
						"readonly" => 'readonly'
					]
				];
			} elseif ($value == "Qty_Proses") {
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"attr" => [
						"readonly" => 'readonly'
					]
				];
			}
			else{
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					// "width" => ($value=='price_total')?'18%':'14%',
				];
			}
		}
		echo json_encode($row);
	}
	public function get_item()
	{
		$term = $this->input->get('term');
		$this->load->model('m_stock_fifo');
		$where = "AND lower(mi.item_name) like lower('%$term%') AND sf.stock_saldo > 0";
		echo json_encode($this->m_bon->get_stock_item($where));
	}
}
