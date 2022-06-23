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
	}

	public function index()
	{
		$this->theme('sale_return/index');
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_sale_return->validation()) {
			$input = [];
			foreach ($this->m_sale_return->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['sr_id']) {
				$this->db->where('sr_id',$data['sr_id'])->update('farmasi.sale_return',$input);
			}else{
				$this->db->insert('farmasi.sale_return',$input);
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
		redirect('sale_return');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_sale_return->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_sale_return',$attr);
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
