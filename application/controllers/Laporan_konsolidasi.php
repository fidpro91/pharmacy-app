<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_konsolidasi extends MY_Generator
{

    public function __construct()
    {
        parent::__construct();
        $this->datascript->lib_select2()->lib_daterange();
        $this->load->model('M_lap_konsolidasi');
    }

    public function index()
    {

        $data['data'] = [];
        $this->theme('laporan_konsolidasi/laporan_konsolidasi_form', $data);
    }

    public function show_laporan()
    {
        $tgl = $this->input->post();
        list($tgl1, $tgl2) = explode('/', $tgl['tanggal']);
        $unit = $this->input->post('unit_name');
        $kepemilikan = $this->input->post('kepemilikan');
        $tampilkan = $this->input->post('tampil');
        $where = "";
        $where2 = " ";
        $where3 = " ";
        $where2 .= " AND (date(sp.date_trans) between '" . $tgl1 . "' and '" . $tgl2 . "')";
        $where3 .= " AND date(sp.date_trans) < '" . $tgl1 . "'";

        if (!empty($unit)) {
            $where .= " AND sp.unit_id = $unit";
        }
        if (!empty($kepemilikan)) {
            $where .= " AND sp.own_id = $kepemilikan";
        }
        // if ($gol=="0") {
        //     $golongan= " ";
        // }else{
        //     $where .= " and mi.gol='$gol'";
        // }
        if($unit !=55){
            $data['data'] = $this->M_lap_konsolidasi->get_new_konsolidasi($where, $where2, $unit, $kepemilikan, $where3);
        }else{
            $data['data'] = $this->M_lap_konsolidasi->konsolidasi_gudang($where, $where2, $unit, $kepemilikan, $where3);
        }
          //print_r($data);die;
        $this->load->view("laporan_konsolidasi/v_laporan_konsolidasi", $data);
    }
}
