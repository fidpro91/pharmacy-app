<?php
require FCPATH . 'vendor/autoload.php';
class Stock_all_unit extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_stock');
	}

	public function index()
	{
		$this->load->model("m_ms_unit");
        $data["unit"] = $this->m_ms_unit->get_ms_unit_farmasi();
        $this->theme('informasi/stock_all_unit',$data,get_class($this));
	}

    public function get_data()
    {
        $data = $this->m_stock->get_stock_all_unit();

        foreach ($data as $key => $value) {
            
        }
    }
}