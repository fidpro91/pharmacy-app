<?php
require FCPATH . 'vendor/autoload.php';
class Stock_all_unit extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_stock');
        $this->datascript->lib_datatableExt();
		$this->load->model("m_ms_unit");
	}

	public function index()
	{
        $data["unit"]       = $this->m_ms_unit->get_ms_unit_farmasi();
        $this->load->model("m_ownership");
        foreach ($this->m_ownership->get_ownership() as $key => $value) {
			$own[$value->own_id] = $value->own_name;
		}
		$data['own'] = $own;
        $this->theme('informasi/stock_all_unit',$data,get_class($this));
	}

    public function show_stock()
	{
        $data["unit"]       = $this->m_ms_unit->get_ms_unit_farmasi();
        $this->load->model("m_ownership");
        foreach ($this->m_ownership->get_ownership() as $key => $value) {
			$own[$value->own_id] = $value->own_name;
		}
		$data['own'] = $own;
        $this->load->view('informasi/stock_all_unit_html',$data);
	}

    public function get_data($own_id)
    {
        $data = $this->m_stock->get_stock_all_unit($own_id);
        $unit = $this->m_ms_unit->get_ms_unit_farmasi();
        foreach ($data as $key => $value) {
            $row = "<tr>
                    <td>".($key+1)."</td>
                    <td>$value->item_name</td>";
                    
            foreach ($unit as $x => $v) {
                $dt = json_decode($value->detail,true); 
                $i = array_search($v->unit_id, array_column($dt, 'f1'));
                $stok = 0;
                if ($i !== false) {
                    $stok = ($dt[$i]['f2']);
                }
                $row .= "<td>$stok</td>";
            } 
              $row .= "<td>$value->jumlah</td>";  
            $row .= "</tr>";            
            echo $row;
        }
      
    }
}