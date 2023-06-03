<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Datatable
{
    public function get_data($kolom,$filter = array(),$model,$attr = array())
    {
        $search     = $attr['search']['value'];
        $sWhere     = "";
        if (!empty($filter)) {
            foreach ($filter as $key => $value) {
                if ($key == 'custom') {
                    $sWhere .= " AND $value";
                }else{
                    $sWhere .= " AND ".$key."='$value'";
                }
            }
        }
        $aColumns   = $aSearch   = [];
        foreach ($kolom as $key => $value) {
            if (!is_array($value)) {
                $aColumns[] = $aSearch[] = $value;
            }else{
                if (isset($value['field'])){
                    $key = $value['field']." AS ".$key;
                }
                if (isset($value['initial'])) {
                    $key = $value['initial'].'.'.$key;
                    $aSearch[] = (isset($value['field'])?$value['initial'].".".$value['field']:$key);
                }else{
                    $aSearch[] = (isset($value['field'])?$value['field']:$key);
                }
                $aColumns[] = $key;
            }
        }
        if ( isset($search) && $search != "" ) {
            $sWhere .= " AND (";
            for ( $i = 0 ; $i < count($aSearch) ; $i++ ) {
                    $sWhere .= " LOWER(".$aSearch[$i]."::TEXT) LIKE LOWER('%".pg_escape_string($search)."%') OR ";
            }
            $sWhere = substr_replace( $sWhere, "", - 3 );
            $sWhere .= ')';
        }
        
        $CI =& get_instance();
        if (is_array($model)) {
            $CI->load->model($model['name'],'modelku');
            $countData      = $model['countData'];
            $dataResource   = $model['dataResource'];
        }else{
            $countData      = 'get_total';
            $dataResource   = 'get_data';
            $CI->load->model($model,'modelku');
        }
        
        $iTotalRecords  = $CI->modelku->{$countData}($sWhere,$aColumns);
        $length = intval($attr['length']);
        $length = $length < 0 ? $iTotalRecords : $length;
        $start  = intval($attr['start']);
        $draw       = intval($attr['draw']);
        // $iSortCol_0 = $attr['order'][0]['column'];
        $sOrder = "";
        if ( isset($start) && $length != '-1' ) {
            $sLimit = " limit ".intval($length)." offset ".intval( $start );
        }

        if ( isset($attr['order'])) {
            $sOrder = "ORDER BY ";
            foreach ($attr['order'] as $key => $od) {
                $sOrder .= " ".$aColumns[$od['column']-2]." ".$od["dir"].",";
            }
            $sOrder = rtrim($sOrder,",");
        }
        
        $data = $CI->modelku->{$dataResource}($sLimit,$sWhere,$sOrder,$aColumns);
        $records        = array();
        $records["dataku"] = $data;
        $records["draw"] = $draw;
        $records["iTotalRecords"] = $iTotalRecords;
        $records["iTotalDisplayRecords"] = $iTotalRecords;
        return ($records);
    }
}
