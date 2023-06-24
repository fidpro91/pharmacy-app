<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Curls
{ 
    public function api_internal($method,$url,$data = array()){
        $ch = curl_init(); 
        // $base_url = base_url('api/get_simrs/');
        $url = base_url($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec($ch);
        curl_close($ch);
        return ($result);
    }
    
    public function api_sregep($method,$url,$data = array()){
        $ch = curl_init(); 
        $base_url = "http://192.168.1.21/sregep/api/api_vclaim/";
        $url = $base_url.$url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result,true);
    }
}