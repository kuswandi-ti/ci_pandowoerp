<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public $layout = 'layout';
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Dashboard";
        $this->data['page_content'] = "Dashboard/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Dashboard/index.js"></script>';

        // ========================= selesai hari ini ==========================//
        $date = date('Y-m-d');
        $this->data['data_today_receive'] = $this->db->query(
            "SELECT SUM(child.Cubication * (child.Qty - child.Qty_Afkir)) as kubikasi
        from ttrx_child_dtl_size_item_lpb child
        join ttrx_dtl_lpb_receive dtl on child.Id_Lot = dtl.sysid 
        join ttrx_hdr_lpb_receive hdr on dtl.lpb_hdr = hdr.lpb
        where DATE_FORMAT(hdr.selesai_at, '%Y-%m-%d') = '$date'
        group by  DATE_FORMAT(hdr.selesai_at, '%Y-%m-%d');
        "
        )->row();
        // ============================ end selesai hari ini ==========================//

        //============================= masuk oven today ==============================//
        $this->data['data_today_into_oven'] = $this->db->query(
            "SELECT SUM(child.Cubication * (child.Qty - child.Qty_Afkir)) as kubikasi
            from thst_in_to_oven hst
            join ttrx_dtl_lpb_receive dtl on hst.lot = dtl.no_lot 
            join ttrx_child_dtl_size_item_lpb child on child.Id_Lot = dtl.sysid 
            where DATE_FORMAT(hst.do_time, '%Y-%m-%d') = '$date'
            group by  DATE_FORMAT(hst.do_time, '%Y-%m-%d');"
        )->row();
        //============================= end masuk oven today ==============================//
        //============================= keluar oven today ==============================//
        $this->data['data_today_out_oven'] = $this->db->query(
            "SELECT SUM(child.Cubication * (child.Qty - child.Qty_Afkir)) as kubikasi
            from thst_out_of_oven hst
            join ttrx_dtl_lpb_receive dtl on hst.lot = dtl.no_lot 
            join ttrx_child_dtl_size_item_lpb child on child.Id_Lot = dtl.sysid 
            where DATE_FORMAT(hst.do_time, '%Y-%m-%d') = '$date'
            group by  DATE_FORMAT(hst.do_time, '%Y-%m-%d');"
        )->row();
        //============================= end keluar oven today ==============================//
        //============================= keluar oven today ==============================//
        $this->data['data_today_alloc_prd'] = $this->db->query(
            "SELECT SUM(child.Cubication * (child.Qty - child.Qty_Afkir)) as kubikasi
            from thst_material_to_prd hst
            join ttrx_dtl_lpb_receive dtl on hst.lot = dtl.no_lot 
            join ttrx_child_dtl_size_item_lpb child on child.Id_Lot = dtl.sysid 
            where DATE_FORMAT(hst.do_time, '%Y-%m-%d') = '$date'
            group by  DATE_FORMAT(hst.do_time, '%Y-%m-%d');"
        )->row();
        //============================= end keluar oven today ==============================//



        $this->load->view($this->layout, $this->data);
    }


    // =================== PRIVATE FUNCTION

    public function tbl_today_material_rcv()
    {
        $date = date('Y-m-d');
        $data = $this->db->query("SELECT no_lot, into_oven, Item_Name, Size_Code, Qty, (Qty * Cubication) as kubikasi, selesai_at 
        FROM qview_detail_lpb_lot_child_size 
        WHERE DATE_FORMAT(selesai_at, '%Y-%m-%d') = '$date' 
        Order by no_lot, flag
        ")->result();

        $this->data['data_today_receive'] = array(
            "rincian_today_receive" => $data,
        );

        return $this->load->view('Dashboard/Partial/tbl_today_material_rcv', $this->data);
    }

    public function tbl_today_material_alloc_prd()
    {
        $date = date('Y-m-d');

        $this->data['data_today_alloc_prd'] = $this->db->query(
            "SELECT hst.lot as no_lot, child.into_oven, child.Item_Name, child.Size_Code, child.Qty, (child.Qty * child.Cubication) as kubikasi
            FROM thst_material_to_prd hst
            JOIN qview_detail_lpb_lot_child_size child  on child.no_lot = hst.lot 
            where DATE_FORMAT(hst.do_time, '%Y-%m-%d') = '$date'"
        )->result();

        return $this->load->view('Dashboard/Partial/tbl_today_alloc_prd', $this->data);
    }
}
