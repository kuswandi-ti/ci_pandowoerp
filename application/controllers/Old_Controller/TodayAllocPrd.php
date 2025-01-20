<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TodayAllocPrd extends CI_Controller
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
        $this->data['page_title'] = "Today Alokasi Material ke Produksi";
        $this->data['page_content'] = "TodayAllocPrd/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TodayAllocPrd/index.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    // ============================= DATATABLE ==========================//

    public function DataTable_alloc_prd_by_deskripsi()
    {
        $today = date('Y-m-d');
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
        JOIN tmst_material_kayu b on a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
        JOIN thst_material_to_prd d on a.no_lot = d.lot
        WHERE a.into_oven = 3
        AND c.status_lpb  = 'SELESAI' 
        AND DATE_FORMAT(d.do_time, '%Y-%m-%d') = '$today'";

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
        $sql .= " GROUP by b.tebal, b.lebar, b.panjang ";
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

    public function DataTable_alloc_prd_by_lot()
    {
        $today = date('Y-m-d');
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'a.no_lot',
            2 => 'b.deskripsi',
            3 => 'b.kode',
            4 => 'd.nama',
            5 => 'c.grader',
            6 => 'c.tgl_kirim',
            7 => 'c.tgl_finish_sortir',
            8 => 'a.qty',
            9 => 'a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000)',
            10 => 'h.nama',
            11 => 'e.do_time',
            12 => "CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, f.do_time) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, f.do_time) % 24, ' Jam') as timer",
            13 => 'g.do_time',
            12 => 'i.Nama'
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.no_lot, d.nama , b.deskripsi, b.kode, c.grader, e.do_time as time_in, f.do_time as time_out, g.do_time as time_alloc,
         c.tgl_kirim, c.tgl_finish_sortir, a.qty, a.sysid_material, a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi,
         CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, f.do_time) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, f.do_time) % 24, ' Jam') as timer, h.nama as nama_oven,
         i.Kode AS kode_product, i.Nama AS nama_product
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b on a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
        JOIN tmst_supplier_material d on c.id_supplier = d.sysid
        JOIN thst_in_to_oven e on a.no_lot = e.lot
        JOIN thst_out_of_oven f on a.no_lot = f.lot
        join thst_material_to_prd g on a.no_lot = g.lot
        join tmst_identity_oven h on e.oven = h.sysid
        JOIN tmst_hdr_product i ON g.sysid_product = i.sysid
        WHERE a.into_oven = 3
        AND c.status_lpb  = 'SELESAI'
        AND DATE_FORMAT(g.do_time, '%Y-%m-%d') = '$today' 
        ";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.deskripsi LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.inisial_kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR e.do_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR f.do_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR g.do_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR i.Kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR i.Nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.qty LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP by a.no_lot ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid_material'] = $row["sysid_material"];
            $nestedData['no_lot'] = $row["no_lot"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['qty'] = $row["qty"];
            $nestedData['supplier'] = $row["nama"];
            $nestedData['kubikasi'] = floatval($row["kubikasi"]);
            $nestedData['time_in'] = $row["time_in"];
            $nestedData['time_out'] = $row["time_out"];
            $nestedData['timer'] = $row["timer"];
            $nestedData['time_alloc'] = $row["time_alloc"];
            $nestedData['nama_oven'] = $row["nama_oven"];
            $nestedData['product'] = $row["nama_product"] . ' (' . $row["kode_product"] . ')';

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
