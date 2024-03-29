<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function get_status(){
	return ['Menikah','Lajang','Janda','Duda'];
}

function get_agama(){
	return ['Islam','Kristen','Katolik','Budha','Hindu','Konghucu'];
}

function get_pendidikan(){
	return ['SD','SMP','SMA/SMK/MA','S1','S2','S3'];
}

function get_hari(){
	return [
			["id"=>"0", "text"=> "Minggu"],
			["id"=>"1", "text" => "Senin"],
			["id"=>"2", "text" => "Selasa"],
			["id"=>"3", "text"=> "Rabu"],
			["id"=>"4", "text"=> "Kamis"],
			["id"=>"5", "text"=> "Jum'at"],
			["id"=>"6", "text"=> "Sabtu"],
		];
}

function show_hari($id){
	foreach (get_hari() as $key => $value) {
		if ($id == $value['id']) {
			return $value['text'];
			break;
		}
	}
}

function get_dataShift()
{
	return [
				["id"=>"0","text"=>"NON SHIFT"],
				["id"=>"1","text"=>"SHIFT 1"],
				["id"=>"2","text"=>"SHIFT 2"],
				["id"=>"3","text"=>"SHIFT 3"],
				["id"=>"4","text"=>"SHIFT KHUSUS"],
				["id"=>"5","text"=>"LIBUR"],
			];
}

function get_type_kronis(){
	return [
			["id"=>"t", "text" => "KRONIS"],
			["id"=>"f", "text" => "NON KRONIS"]
		];
}

function convert_currency($angka)
{
	if(!$angka) {
		return 0;
	}
	$rupiah= 'Rp '.number_format($angka,2,'.',',');
	return $rupiah;
}

function remove_currency($angka)
{
	$rupiah= str_replace(",","", $angka);
	return $rupiah;
}

function get_statusKunjungan($id)
{
	$kunjungan = [
		"10" => "PULANG",
		"30" => "DILAYANI",
		"20" => "DILAYANI",
		"35" => "BATAL",
	];
	if (!empty($kunjungan[$id])) {
		return $kunjungan[$id];
	}else{
		return "REGISTRASI";
	}
}

if ( ! function_exists('generate_code_transaksi'))
{ 
	function generate_code_transaksi($data){
		$CI =& get_instance();
		/* $query = $CI->db->query("SELECT LPAD((max(COALESCE(CAST(SUBSTRING_INDEX(".$data['column'].",'".$data['delimiter']."',".$data['number'].") AS UNSIGNED),0))+1),5,'0') AS nomax FROM ".$data['table'].";")->row(); */

		$query = $CI->db->query("
        select 
        lpad(trim(coalesce(max(regexp_replace(((string_to_array(".$data['column'].",'".$data['delimiter']."'))[".$data['number']."]), '[^0-9]*', '', 'g')::integer)+1,1)::VARCHAR), ".$data['lpad'].", '0')
        as nomax 
        from ".$data['table']."
		where 0=0 ".$data['filter']."
        ")->row();
		if (empty($query->nomax)) {
            $query->nomax = str_pad("1", $data['lpad'], "0", STR_PAD_LEFT);
        }
		return str_replace('NOMOR', $query->nomax, $data['text']);
		
	}
}
?>