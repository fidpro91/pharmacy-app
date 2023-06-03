<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ms_reff extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_ms_reff');
	}

	public function index()
	{
		$data['data'] = [];
		$this->theme('ms_reff/index',$data);
	}

	public function save()
	{
		$data = $this->input->post();

		if ($this->m_ms_reff->validation()) {
			if ($data['reff_id']) {
				$this->db->where('reff_id',$data['reff_id'])->update('ms_reff',$data);
			}else{
				unset($data['reff_id']);
				$this->db->insert('ms_reff',$data);
			}
			$err = $this->db->error();
			if ($err['message']) {
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$err['message'].'</div>');
			}else{
				$this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data berhasil disimpan</div>');
			}
		}else{
			$this->session->set_flashdata('message',validation_errors('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>','</div>'));
		}
		redirect('ms_reff');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_ms_reff->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_ms_reff',$attr);
		$records["aaData"] = array();
		$no   	= 1 + $attr['start']; 
        foreach ($data['dataku'] as $index=>$row) { 
      
		 	$action ='	<button onclick="set_val(\''.$row['reff_id'].'\')" class="btn btn-xs btn-info" title="Edit">
                            <i class="fa fa-pencil"></i>
                        </button>';

           	$action .='	<button href="javascript:void(0)" onclick="deleteRow(\''.$row['reff_id'].'\')" class="btn btn-xs  btn-danger" title="Delete">
							<i class="fa fa-trash-o"></i>
                        </button>';
            $obj = array($row['reff_id'],$no);
            foreach ($row as $key => $value) {
            	$obj[] = $value;
            }
            $obj[] = $action;
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}

	public function find_one($id)
	{
		$data = $this->db->where('reff_id',$id)->get("ms_reff")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('reff_id',$id)->delete("ms_reff");
		$resp = array();
		if ($this->db->affected_rows()) {
			$resp['message'] = 'Data berhasil dihapus';
		}else{
			$err = $this->db->error();
			$resp['message'] = $err['message'];
		}
		echo json_encode($resp);
	}

	public function show_form()
	{
		$this->load->view("ms_reff/form");
	}
}
