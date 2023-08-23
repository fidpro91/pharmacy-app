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
        $base_url = "http://localhost/sregep/api/api_vclaim/";
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
    
    public function api_farmasi($method,$url,$data = array()){
        $ch = curl_init(); 
        $base_url = "http://192.168.1.21/pharmacy-app/api/api_push/";
        $url = $base_url.$url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec($ch);
        $error_msg = curl_error($ch);
        if ($error_msg) {
            $data = [
                "metaData" => [
                    "code"      => "400",
                    "message"   => $error_msg
                ],
                "response" => null
            ];
            curl_close($ch);
            print_r($data);
            return false;
        }
        curl_close($ch);
        return json_decode($result,true);
    }
}