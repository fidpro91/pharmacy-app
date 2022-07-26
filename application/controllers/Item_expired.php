<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_expired extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datatableExt()
					     ->lib_daterange();
		$this->load->model('m_ms_item');
	}

	public function index()
	{
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit() as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$this->load->model("m_ownership");
		foreach ($this->m_ownership->get_ownership() as $key => $value) {
			$own[$value->own_id] = $value->own_name;
		}
		$data['unit'] = $kat;
		$data['own'] = $own;
		$this->theme('item_expired/index',$data,get_class($this));
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();		
		$fields = $this->m_ms_item->get_column_exp();
		$filter = [];
        list($tgl1,$tgl2) = explode('/', $attr['tanggal']); 
		$filter["custom"]= "(date(expired_date) between '$tgl1' and '$tgl2')";
		if ($attr['unit_id'] !='') {
			$filter = array_merge($filter, ["unit_id" => $attr['unit_id']]);
		} 
		if ($attr['own_id'] != ' ') {
			$filter = array_merge($filter, ["own_id" => $attr['own_id']]);
		}
		$data 	= $this->datatable->get_data($fields,$filter,[
            "name"          => 'm_ms_item',
            "dataResource"  => "get_item_exp",
            "countData"     => "get_total_exp"
        ],$attr);
		$records["aaData"] = array();
		$no   	= 1 + $attr['start']; 
        foreach ($data['dataku'] as $index=>$row) { 
            $obj = array($row['id_key'],$no);
            foreach ($fields as $key => $value) {
            	if (is_array($value)) {
            		if (isset($value['custom'])){
            			$obj[] = call_user_func($value['custom'],$row);
            		}else{
            			$obj[] = $row[$key];
            		}
            	}else{
            		$obj[] = $row[$value];
            	}
            }
            $a=$row['long_ed'];
            if($a<=0) {
                $label = "<label class='label label-danger'>Expired</label>";
            }elseif($a>0 && $a<20) {
                $label = "<label class='label label-danger'>Mendekati Expired</label>";
            }else{
                $label = "<label class='label label-info'>OK</label>";
            }
            $obj[] = $label;
            $records["aaData"][] = $obj;
            $no++;
        }
        $data = array_merge($data,$records);
        unset($data['dataku']);
        echo json_encode($data);
	}
}