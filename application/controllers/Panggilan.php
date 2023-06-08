<?php

class Panggilan extends MY_Generator
{
	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
			->lib_inputmulti()
			->lib_select2()
			->lib_inputmask();
		$this->load->model('m_panggilan');
	}
	public function index()
	{
		$this->load->model("m_ms_unit");
		foreach ($this->m_ms_unit->get_ms_unit(["employee_id" => $this->session->employee_id]) as $key => $value) {
			$kat[$value->unit_id] = $value->unit_name;
		}
		$data['unit'] = $kat;
		$this->theme('panggilan/index',$data,get_class($this));
	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_panggilan->get_column();
		$filter = [];

		if ($attr['unit_id'] !='') {
			$filter = array_merge($filter, ["s.unit_id" => $attr['unit_id']]);
		}


		$data 	= $this->datatable->get_data($fields,$filter,'m_panggilan',$attr);
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
			if ($row['status']==''){
				$obj[] = create_btnAction([
					"Panggil"=>[
						"btn-act" => "panggil(".$row['id_key'].")",
						"btn-icon" => "fa fa-microphone",
						"btn-class" => "btn btn-sm btn-success",
					],
				],$row['id_key']);
			}else{
				$obj[] = create_btnAction([

					"Panggil"=>[
						"btn-act" => "panggil(".$row['id_key'].")",
						"btn-icon" => "fa fa-microphone",
						"btn-class" => "btn btn-sm btn-default",
					],

				],$row['id_key']);
			}

			$records["aaData"][] = $obj;
			$no++;
		}
		$data = array_merge($data,$records);
		unset($data['dataku']);
		echo json_encode($data);
	}

	public function panggil()
	{
		$sale_id = $this->input->post('sale_id');
		$cek_panggil = $this->db->query("select * from yanmed.antrian_farmasi2 where sale_id = $sale_id")->row();
		if (empty($cek_panggil)){
			$input = [
				'sale_id'=>$sale_id,
				'tgl'=>date('Y-m-d'),
				'tgl_panggil'=>date('Y-m-d h:i:s'),
				'status'=>1
			];
			$insert = $this->db->insert('yanmed.antrian_farmasi2',$input);

		}else{
			$update = $this->db->set(['status'=>1,'tgl_panggil'=>date('Y-m-d h:i:s')])
				->where('sale_id',$sale_id)
				->update('yanmed.antrian_farmasi2');
		}

	}
}
