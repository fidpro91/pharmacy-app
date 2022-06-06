<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
		$this->datascript->lib_datepicker()
						 ->lib_inputmulti()
						 ->lib_select2()
						 ->lib_inputmask();
		$this->load->model('m_sale');
	}

	public function index()
	{
		$this->theme('sale/index');
	}

	public function save()
	{
		$data = $this->input->post();
		if ($this->m_sale->validation()) {
			$input = [];
			foreach ($this->m_sale->rules() as $key => $value) {
				$input[$key] = $data[$key];
			}
			if ($data['sale_id']) {
				$this->db->where('sale_id',$data['sale_id'])->update('sale',$input);
			}else{
				$this->db->insert('sale',$input);
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
		redirect('sale');

	}

	public function get_data()
	{
		$this->load->library('datatable');
		$attr 	= $this->input->post();
		$fields = $this->m_sale->get_column();
		$data 	= $this->datatable->get_data($fields,$filter = array(),'m_sale',$attr);
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
		$data = $this->db->where('sale_id',$id)->get("sale")->row();

		echo json_encode($data);
	}

	public function delete_row($id)
	{
		$this->db->where('sale_id',$id)->delete("sale");
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
			$this->db->where('sale_id',$value)->delete("sale");
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
		$data['model'] = $this->m_sale->rules();
		//$this->load->view("sale/form",$data);
		$this->load->view("sale/form_sale",$data);
	}

	public function get_no_rm($tipe)
	{
		$respond= array();
		$term 	= $this->input->get('term', true); 
		
		if($tipe == 'norm')
			{
				$where = " AND px_norm like '%$term%'";
				$select = "*,px_norm as label";
				$respond= $this->m_sale->get_data_pasien($where,$select);
				
			}
		else
			{
				$where = "AND LOWER(px_name) like '%$term%'";
				$select = "*,px_name as label";
				$respond= $this->m_sale->get_data_pasien($where,$select);
				
			}
		
		echo json_encode($respond);
	}

	public function show_form_pasien()
	{
		$this->load->view("sale/form_pasien");
	}

	public function show_form_racikan()
	{
		$data['model'] = [];
		$this->load->view("sale/form_racikan",$data);
	}

	public function show_form_nonracikan()
	{
		$data['model'] = [];
		$this->load->view("sale/form_non_racikan",$data);
	}

	public function show_multiRows()
	{
		$this->load->model("m_sale_detail");
		$data = $this->m_sale_detail->get_column_multiple();
		$colauto = ["item_id"=>"Nama Barang"];
		foreach ($data as $key => $value) {
			if (array_key_exists($value, $colauto)) {
				$row[] = [
					"id" => $value,
					"label" => $colauto[$value],
					"type" => 'autocomplete',
					"width" => '35%',
				];
			}
			elseif($value == "sale_price"){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value=='price_total')?'18%':'14%',
					"attr"=>[
						"readonly"=>'readonly'
					]
				];
			}
			elseif($value == "price_total"){
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value=='price_total')?'18%':'14%',
					"attr"=>[
						"readonly"=>"readonly"
					]
				];
			}
			else{
				$row[] = [
					"id" => $value,
					"label" => ucwords(str_replace('_', ' ', $value)),
					"type" => 'text',
					"width" => ($value=='price_total')?'18%':'14%',
				];
			}
		}
		echo json_encode($row);
	}

	public function set_item_racikan()
	{
		$post = $this->input->post();
		$html="";
		$total=0;
		$item="";
		foreach ($post['list_item_racikan'] as $key => $value) {
			$total += $value['price_total'];
			$item .="(".$value['sale_qty'].")"."<br>";
		}
		if (!empty($this->session->userdata('itemRacik'))) {
			$itemRacik = $this->session->userdata('itemRacik');
			$itemRacik = array_merge_recursive($post,$itemRacik);
		}else{
			$itemRacik = $post;
		}
		$this->session->set_userdata('itemRacik',$itemRacik);
		$item = rtrim($item,"<br>");
		$html .= "
			<div class='comment-text'>
				<span class='comment-text'>
					<b>".$post['nama_racikan']."</b>
					<span class=\"text-muted pull-right\">
						".convert_currency(($total+$post['biaya_racikan']))."
					</span>
					<p>$item</p>
				</span>
			</div>
			";
		echo $html;

	}

	public function set_item_nonracikan()
	{
		$post = $this->input->post();
		$html="";
		$total=0;
		$item="";
		foreach ($post['list_obat_nonracikan'] as $key => $value) {
			$total += $value['price_total'];
			$item .="(".$value['sale_qty'].")"."<br>";
		}

		if (!empty($this->session->userdata('itemNonRacik'))) {
			$itemNonRacik = $this->session->userdata('itemNonRacik');
			$itemNonRacik = array_merge_recursive($post,$itemNonRacik);
		}else{
			$itemNonRacik = $post;
		}

		$this->session->set_userdata('itemNonRacik',$itemNonRacik);
		$item = rtrim($item,"<br>");
		$html .= "
			<div class='comment-text'>
				<span class='comment-text'>
					<b>".$post['autocom_item_id']."</b>
					<span class=\"text-muted pull-right\">
						".convert_currency(($total))."
					</span>
					<p>$item</p>
				</span>
			</div>
			";
//		var_dump($this->session->userdata('itemNonRacik'));
		echo $html;

	}

<<<<<<< HEAD

=======
	public function get_total_nonracikan()
	{
		$dtNonracikan = $this->session->userdata('itemNonRacik');
		// var_dump($dtNonracikan);die;
		$sub_total = 0;
		foreach ($dtNonracikan['list_obat_nonracikan'] as $nonracikan){
			$sub_total +=$nonracikan['price_total'];
		}
		echo $sub_total;

	}

	public function get_total_racikan()
	{
		$dtRacikan = $this->session->userdata('itemRacik');
		// var_dump($dtRacikan['biaya_racikan']);die;
		$total_item = 0;
		foreach ($dtRacikan['list_item_racikan'] as $item) {
			$total_item += $item['price_total'];
		}
		$total_racikan = 0;
		if(is_array($dtRacikan['biaya_racikan'])){
			foreach($dtRacikan['biaya_racikan'] as $key=>$b_racikan){
				$total_racikan += $b_racikan;
			}
		}else{
			$total_racikan = $dtRacikan['biaya_racikan'];
		}
		
		$sub_total = $total_item+$total_racikan;
		// var_dump($sub_total);die;
		echo $sub_total;

	}

	public function set_data_pasien()
	{
		# code...
	}
>>>>>>> deae6b35f4c8c6b999aecc6ed6c6dd0a8f060921

	public function get_item()
	{
		$term = $this->input->get('term');
		$this->load->model('m_stock_fifo');
		$where = " AND lower(mi.item_name) like lower('%$term%') AND sf.stock_saldo > 0";
		echo json_encode($this->m_stock_fifo->get_stock_item($where));
	}

	public function set_data_pasien()
	{
		$post = $this->input->post();
		$this->session->set_userdata('pasien',$post); 
		$data= $this->session->userdata('pasien');
		if(!empty($data)){
			echo "sukses";
		}else{
			echo "gagal";
		}
		
	
		
	}

<<<<<<< HEAD
			
=======
	public function save_non_racikan()
	{
		$this->session->set_userdata($resp);
	}

	public function hapus_sess()
	{
		$this->session->unset_userdata('itemRacik');
		$this->session->unset_userdata('itemNonRacik');
	}
>>>>>>> deae6b35f4c8c6b999aecc6ed6c6dd0a8f060921
}
?>
