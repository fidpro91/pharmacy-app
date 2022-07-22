<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_harga extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
        $this->datascript->lib_select2();
		$this->load->model('m_price');
		$this->load->model('m_ms_item');
	}

	public function index()
	{
		foreach ($this->m_ms_item->get_ms_item(["item_active"=>"t","comodity_id in (1,2)"=>null]) as $key => $value) {
			$kat[$value->item_id] = $value->item_name;
		}
        $data["item"] = $kat;
        $this->theme('riwayat_harga/index',$data,get_class($this));
	}

    public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
        $this->load->model("m_receiving");
		$fields = $this->m_receiving->get_column_harga();
        $filter["item_id"]  = $attr["item_id"];
		$data 	= $this->datatable->get_data($fields,$filter,[
            "name"          => "m_receiving",
            "countData"     => "get_total_harga",
            "dataResource"  => "get_data_harga"
        ],$attr);
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
            $obj[] = null;
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}
}