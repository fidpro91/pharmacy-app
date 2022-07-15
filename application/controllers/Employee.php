<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_employee');
	}

	public function index()
	{
		$this->theme('employee/index');
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_employee->validation()) {
			$input = [];
			foreach ($this->m_employee->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			$input['empcat_id'] = 12;
			$this->db->trans_begin();
			if ($data['employee_id']) {
				$this->db->where('employee_id',$data['employee_id'])->update('hr.employee',$input);
			}else{
				$this->db->insert('hr.employee',$input);
				$data['employee_id'] = $this->db->insert_id();
			}
			
			//insert or update ms_user
			$dataUser = $this->db->get_where("admin.ms_user",["employee_id"=>$data['employee_id']])->num_rows();
			if ($dataUser>0) {
				$this->db->where("employee_id",$data['employee_id'])
						 ->update("admin.ms_user",[
							"user_name" 			=> $data['user_name'],
							"user_password" 		=> $data['user_password'],
							"user_salt_encrypt" 	=> md5($data['user_password']),
							"user_status"			=> $data['employee_active'],
							"employee_id"			=> $data["employee_id"],
							"person_name"			=> $data["employee_name"]
						 ]);
			}else{
				$this->db->insert("admin.ms_user",[
							"user_name" 			=> $data['user_name'],
							"user_password" 		=> $data['user_password'],
							"user_salt_encrypt" 	=> md5($data['user_password']),
							"user_status"			=> $data['employee_active'],
							"employee_id"			=> $data["employee_id"],
							"person_name"			=> $data["employee_name"]
						 ]);
			}

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
		redirect('employee');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_employee->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_employee',$attr);
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
		$data = $this->db->where('employee_id',$id)->get("hr.employee")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('employee_id',$id)->delete("hr.employee");
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
			$this->db->where('employee_id',$value)->delete("hr.employee");
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
		$data['model'] = $this->m_employee->rules();
		$this->load->view("employee/form",$data);
	}
}
