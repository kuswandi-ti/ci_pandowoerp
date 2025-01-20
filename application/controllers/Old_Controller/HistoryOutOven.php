<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryOutOven extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
        $this->load->model('m_lpb', 'lpb');
    }

    public function index()
    {
        $this->data['page_title'] = "History Material Keluar Oven";
        $this->data['page_content'] = "History/material_keluar_oven";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/StockInOven-script/HistoryOut.js"></script>';

        $this->data['materials'] = $this->db->get_where('tmst_material_kayu', ['is_active' => 1])->result();
        $this->data['ovens'] = $this->db->get_where('tmst_identity_oven', ['is_active' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function DataTable_HstOven_by_Lot()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $oven = $this->input->get('oven');
        $material = $this->input->get('material');
        $sql_oven = "";
        $sql_material = "";
        if (!empty($oven)) {
            $sql_oven = " AND e.oven = $oven ";
        }
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }


        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.sysid',
            1 => 'a.no_lot',
            2 => 'b.deskripsi',
            3 => 'b.kode',
            4 => 'd.nama',
            5 => 'c.grader',
            6 => 'c.tgl_finish_sortir',
            7 => 'a.qty',
            8 => 'a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000)',
            9 => "CONCAT(FLOOR(HOUR(TIMEDIFF(e.do_time, h.do_time)) / 24), ' hari,', MOD(HOUR(TIMEDIFF(e.do_time, h.do_time)), 24), ' jam')",
            10 => 'f.nama',
            11 => 'e.do_time',
            12 => 'h.do_time',
            13 => 'g.status_kayu',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.sysid, a.no_lot, d.nama , b.deskripsi, b.kode, c.grader, f.nama as nama_oven,
        c.tgl_finish_sortir, a.qty, a.sysid_material, g.status_kayu, e.do_time as time_in, h.do_time as time_out, a.into_oven,
        CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, h.do_time) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, h.do_time) % 24, ' Jam') as timer,
        a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b on a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
        JOIN tmst_supplier_material d on c.id_supplier = d.sysid
        JOIN thst_in_to_oven e on a.no_lot = e.lot
        JOIN tmst_identity_oven f on e.oven = f.sysid
        JOIN tmst_status_lot g on a.into_oven = g.kode
        JOIN thst_out_of_oven h on a.no_lot = h.lot
        WHERE c.status_lpb  = 'SELESAI'
        $sql_oven
        $sql_material
        AND DATE_FORMAT(h.do_time, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(h.do_time, '%Y-%m-%d') <= '$to'";


        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.deskripsi LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.inisial_kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR f.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR g.status_kayu LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.qty LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP by a.no_lot ";
        $totalData = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $totalFiltered = $this->db->query($sql)->num_rows();

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['no_lot'] = $row["no_lot"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['supplier'] = $row["nama"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['qty'] = $row["qty"];
            $nestedData['kubikasi'] = floatval($row["kubikasi"]);
            $nestedData['timer'] = $row["timer"];
            $nestedData['nama_oven'] = $row["nama_oven"];
            $nestedData['time_in'] = $row["time_in"];
            $nestedData['time_out'] = $row["time_out"];
            $nestedData['status_kayu'] = $row["status_kayu"];
            $nestedData['into_oven'] = floatval($row["into_oven"]);

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

    public function DataTable_HstOven_by_Desc()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $oven = $this->input->get('oven');
        $material = $this->input->get('material');
        $sql_oven = "";
        $sql_material = "";
        if (!empty($oven)) {
            $sql_oven = " AND e.oven = $oven ";
        }
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }

        $requestData = $_REQUEST;
        $columns = array(
            1 => 'b.deskripsi',
            2 => 'b.kode',
            3 => 'b.tebal',
            4 => 'b.lebar',
            5 => 'b.panjang',
            6 => 'count(a.no_lot)',
            7 => 'sum(a.qty)',
            8 => 'SUM(a.qty) * ((b.tebal * b.lebar * b.panjang) / 1000000)',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT b.deskripsi, b.kode, b.tebal,b.lebar,b.panjang, count(a.no_lot) as row_lot, sum(a.qty) as t_qty, a.sysid_material, SUM(a.qty) * ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b ON a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
        JOIN thst_out_of_oven d ON a.no_lot = d.lot
        JOIN thst_in_to_oven e ON a.no_lot = e.lot
        WHERE c.status_lpb  = 'SELESAI' 
        $sql_oven
        $sql_material
        AND DATE_FORMAT(d.do_time, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(d.do_time, '%Y-%m-%d') <= '$to'";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.deskripsi LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.inisial_kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.tebal LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.lebar LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.panjang LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP BY b.tebal, b.lebar, b.panjang ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid_material'] = $row["sysid_material"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['tebal'] = floatval($row["tebal"]);
            $nestedData['lebar'] = floatval($row["lebar"]);
            $nestedData['panjang'] = floatval($row["panjang"]);
            $nestedData['row_lot'] = $row["row_lot"];
            $nestedData['t_qty'] = $row["t_qty"];
            $nestedData['kubikasi'] = floatval($row["kubikasi"]);

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

    public function M_Hst_Lot_By_Material()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $oven = $this->input->get('oven');
        $material = $this->input->get('material'); // from filter 
        $sql_oven = "";
        $sql_material = "";
        if (!empty($oven)) {
            $sql_oven = " AND e.oven = $oven ";
        }
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }
        $sysid_material = $this->input->get('sysid_material'); // from datatable
        $status = $this->input->get('status');
        $row_material = $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid_material])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['from'] = $from;
        $this->data['to'] = $to;
        $this->data['list_lot'] =  $this->db->query("SELECT a.no_lot, c.grader, a.qty, c.grader, c.tgl_kirim, c.tgl_finish_sortir, 
        a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi, d.nama, c.lpb,
        DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') as do_time_in, DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') as do_time_out,
        CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, NOW()) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, NOW()) % 24, ' Jam') as timer,
        f.nama as nama_oven
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b on a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
        JOIN tmst_supplier_material d on c.id_supplier = d.sysid 
        JOIN thst_in_to_oven e on a.no_lot = e.lot
        JOIN tmst_identity_oven f on e.oven = f.sysid
        JOIN thst_out_of_oven g on a.no_lot = g.lot
        WHERE c.status_lpb  = 'SELESAI'
        AND b.tebal = " . floatval($row_material->tebal) . "
        AND b.lebar = " . floatval($row_material->lebar) . "
        AND b.panjang = " . floatval($row_material->panjang) . "
        $sql_oven
        $sql_material
        AND DATE_FORMAT(g.do_time, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(g.do_time, '%Y-%m-%d') <= '$to'
        group BY a.no_lot
        order BY a.no_lot");
        $this->load->view("general-modal/m_lot_hst_inout_oven_desc", $this->data);
    }
}
