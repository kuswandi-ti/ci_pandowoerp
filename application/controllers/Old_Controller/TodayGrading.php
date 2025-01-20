<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TodayGrading extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Today LPB";
        $this->data['page_content'] = "TodayGrading/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/todaygrading-script/todaygrading.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    // =========================== DataTable =============================//

    public function DataTable_today_lpb()
    {
        $date = date('Y-m-d');
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.sysid',
            1 => 'a.lpb',
            2 => 'b.nama',
            3 => 'a.tgl_kirim',
            4 => 'a.tgl_finish_sortir',
            5 => 'a.grader',
            6 => 'COUNT(c.lpb_hdr)',
            7 => 'a.legalitas',
        );
        $sql = "SELECT a.sysid, a.lpb, b.nama, a.tgl_kirim, a.tgl_finish_sortir , a.grader, COUNT(c.lpb_hdr) as lot, a.legalitas
        from ttrx_hdr_lpb_receive a
        JOIN tmst_supplier_material b on a.id_supplier = b.sysid 
        join ttrx_dtl_lpb_receive c on a.lpb = c.lpb_hdr 
        where DATE_FORMAT(a.selesai_at, '%Y-%m-%d') = '$date'  and a.status_lpb = 'SELESAI'";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.lpb LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.grader LIKE '%" . $requestData['search']['value'] . "%')";
            $sql .= " OR a.legalitas LIKE '%" . $requestData['search']['value'] . "%')";
        }
        $sql .= " GROUP BY a.lpb";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'] . "  LIMIT "
            . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        $no = 1;
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['lpb'] = $row["lpb"];
            $nestedData['supplier'] = $row["nama"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['lot'] = $row["lot"];
            $nestedData['legalitas'] = $row["legalitas"];

            $data[] = $nestedData;
        }
        //----------------------------------------------------------------------------------
        $json_data = array(
            "draw"            => intval($requestData['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        //----------------------------------------------------------------------------------
        echo json_encode($json_data);
    }
}
