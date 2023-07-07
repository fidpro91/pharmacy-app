<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class vclaim
{
	function connect($url,$method,$data){
		$url = "http://localhost/sregep/api/api_vclaim/".$url;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);
	var_dump($response);die();


	}
}
