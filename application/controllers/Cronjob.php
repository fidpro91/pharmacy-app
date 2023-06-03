<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function update_auto_maxmin()
	{
		$query=$this->db->query("
            SELECT sd.item_id,s.own_id,s.unit_id,((sum(sd.sale_qty)/30)*7)jml_obat FROM farmasi.sale s
            INNER JOIN farmasi.sale_detail sd ON sd.sale_id = s.sale_id
            WHERE (sale_date >= date_trunc('day', current_date - interval '1' month) AND sale_date < date_trunc('day', current_date))
            GROUP BY sd.item_id,s.own_id,s.unit_id
            UNION 
            SELECT md.item_id,m.own_id,m.unit_sender,((sum(md.qty_send)/30)*14)jml_obat FROM newfarmasi.mutation m
            INNER JOIN newfarmasi.mutation_detail md ON md.mutation_id = m.mutation_id
            WHERE unit_sender = 55 AND (mutation_date >= date_trunc('day', current_date - interval '3' month) AND mutation_date < date_trunc('day', current_date))
            GROUP BY md.item_id,m.own_id,m.unit_sender
        ")->result();

        foreach ($query as $key => $value) {
            $cekStok = $this->db->get_where("farmasi.stock_maxmin_unit",[
                "unit_id"   => $value->unit_id,
                "own_id"    => $value->own_id,
                "item_id"   => $value->item_id,
            ])->num_rows();
            if ($cekStok>0) {
                $this->db->where([
                    "unit_id"   => $value->unit_id,
                    "own_id"    => $value->own_id,
                    "item_id"   => $value->item_id,
                ])->update("farmasi.stock_maxmin_unit",[
                    "stock_min" => $value->jml_obat
                ]);
            }else{
                $this->db->insert("farmasi.stock_maxmin_unit",[
                    "unit_id"   => $value->unit_id,
                    "own_id"    => $value->own_id,
                    "item_id"   => $value->item_id,
                    "stock_min" => $value->jml_obat
                ]);
            }
        }
	}
}
