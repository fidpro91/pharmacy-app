<?php

class Get_db extends CI_Model {

    public function get_menu($id=0)
    {
      $datam = $this->db->where(['menu_parent_id'=>$id,"modul_id"=>"6","menu_status"=>"t"])
                        ->order_by('menu_code')
                        ->get('admin.ms_menu m')->result();
      $menux='';
      foreach ($datam as $key => $value) {
          if ($this->db->where('menu_parent_id',$value->menu_id)->get('admin.ms_menu')->num_rows() > 0) {
            $menux .= "<li class=\"treeview\"><a href=\"#\">
                              <i class=\"".(!empty($value->menu_icon)?$value->menu_icon:'fa fa-circle-o')."\"></i> <span>$value->menu_name</span> <i class=\"fa fa-angle-left pull-right\"></i>
                            </a>
                            <ul class=\"treeview-menu\">";
            $menux .= $this->get_menu($value->menu_id);
            $menux .= "</ul></li>";
          }else{
            $menux .= "<li><a href=\"".base_url($value->menu_url)."\">
                      <i class=\"".(!empty($value->menu_icon)?$value->menu_icon:'fa fa-circle-o')."\"></i> <span>$value->menu_name</span>
                    </a></li>";
          }
      }
      return $menux;
    }

}
