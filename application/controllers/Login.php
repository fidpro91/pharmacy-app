<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$this->load->view('v_login_2');
	}

	public function cek_login()
	{
		$data = $this->input->post();
		$data['user_salt_encrypt'] = md5($data['user_password']);
		$data['user_password'] 	= md5($data['user_password']);
		$data["user_status"]	= 't';
		$dataLogin = $this->db->where($data)->get("admin.ms_user")->row_array();
		$resp["is_error"] = "false";
		if (isset($dataLogin)) {
			$resp["message"] = "Sukses";
			$resp["redirect"] = site_url("dashboard");
			$this->session->set_userdata($dataLogin);
		}else{
			$resp["message"] = "Username/Password tidak sesuai";
			$resp["is_error"] = "true";
		}
		echo json_encode($resp);
	}

	public function logout()
	{
		session_destroy();
		redirect('login');
	}
}
