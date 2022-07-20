<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import_master extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_employee_on_unit');
	}

	public function index()
	{
		$this->theme('import_master/index','',get_class($this));
	}

    public function show_so_awal()
    {
        $this->load->view("import_master/form_so");
    }

	public function show_item_import()
    {
        $this->load->view("import_master/form_item");
    }

	
}