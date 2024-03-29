<?php

class Get_db extends CI_Model {

    public function get_menu($id=0)
    {
      $datam = $this->db->query("
        SELECT distinct mm.* FROM admin.ms_group_user_access ma
        JOIN admin.ms_group_user mg ON ma.group_id = mg.group_id
        JOIN admin.ms_menu mm ON ma.menu_id = mm.menu_id
        WHERE mm.menu_parent_id = '$id' AND mg.user_id = '".$this->session->user_id."' and mm.modul_id = '6' and mm.menu_status = 't'
        ORDER BY menu_code
      ")->result();
      $menux='';
      foreach ($datam as $key => $value) {
          if ($this->db->where('menu_parent_id',$value->menu_id)->get('admin.ms_menu')->num_rows() > 0) {
            $menux .= "<li class=\"treeview\"><a href=\"#\">
                              <i class=\"".(!empty($value->menu_icon)?$value->menu_icon:'fa fa-circle-o')."\"></i> <span>".strtoupper($value->menu_name)."</span> <i class=\"fa fa-angle-left pull-right\"></i>
                            </a>
                            <ul class=\"treeview-menu\">";
            $menux .= $this->get_menu($value->menu_id);
            $menux .= "</ul></li>";
          }else{
            $menux .= "<li><a href=\"".base_url($value->menu_url)."\">
                      <i class=\"".(!empty($value->menu_icon)?$value->menu_icon:'fa fa-circle-o')."\"></i> <span>".strtoupper($value->menu_name)."</span>
                    </a></li>";
          }
      }
      return $menux;
    }

}
