<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockInOven extends CI_Controller
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
        $this->data['page_title'] = "Stock Dalam Oven";
        $this->data['page_content'] = "StockInOven/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/StockInOven-script/index.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function out_of_oven_manual()
    {
        $dateTime = date('Y-m-d H:i:s');
        $barcode = $this->input->post('barcode');
        $placement = $this->input->post('placement');
        $countRow = $this->db->get_where('ttrx_dtl_lpb_receive', ['no_lot' => $barcode])->num_rows();
        $countHstin = $this->db->get_where('thst_in_to_oven', ['lot' => $barcode])->num_rows();
        $countHstOut = $this->db->get_where('thst_out_of_oven', ['lot' => $barcode])->num_rows();

        if ($countRow == 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Kode barcode tidak terdaftar dalam system!'
            ]);
        }
        if ($countHstin < 1) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' belum dinyatakan masuk oven, pilih barcode yang telah masuk oven !'
            ]);
        }
        if ($countHstOut > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' sudah dinyatakan keluar oven, pilih barcode lain!'
            ]);
        }

        $this->db->trans_start();
        $this->db->where('no_lot', $barcode);
        $this->db->update('ttrx_dtl_lpb_receive', [
            'into_oven' => 2,
            'placement' => 'KERING',
        ]);
        $this->db->insert('thst_out_of_oven', [
            "lot" => $barcode,
            "do_by" => $this->session->userdata('impsys_initial'),
            "do_time" => $dateTime,
            "remark_out_of_oven" => 'MANUAL',
            'placement' => $placement
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = ['code' => 505, 'msg' => 'lot gagal dinyatakan keluar oven!'];
        } else {
            $response = ['code' => 200];
        }
        return $this->help->Fn_resulting_response($response);
    }

    // ================================ DATATABLE =========================//
    public function DataTable_Stock_In_Oven_by_deskripsi()
    {
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
        WHERE a.into_oven = 1
        AND c.status_lpb  = 'SELESAI' ";

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

    public function DataTable_Stock_In_Oven_by_lot()
    {
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
            10 => 'e.do_time',
            11 => "CONCAT(FLOOR(HOUR(TIMEDIFF(e.do_time, NOW())) / 24), ' hari,', MOD(HOUR(TIMEDIFF(e.do_time, NOW())), 24), ' jam')",
            12 => 'f.nama'
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.no_lot, d.nama , b.deskripsi, b.kode, c.grader, f.nama as nama_oven,
         CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, NOW()) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, NOW()) % 24, ' Jam') as timer,
         c.tgl_kirim, c.tgl_finish_sortir, a.qty, a.sysid_material, a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi, DATE_FORMAT(e.do_time, '%Y-%m-%d') as do_time
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b on a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
        JOIN tmst_supplier_material d on c.id_supplier = d.sysid
        JOIN thst_in_to_oven e on a.no_lot = e.lot
        JOIN tmst_identity_oven f on e.oven = f.sysid
        WHERE a.into_oven = 1
        AND c.status_lpb  = 'SELESAI'";


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
            $sql .= " OR f.nama LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData['timer'] = $row["timer"];
            $nestedData['nama_oven'] = $row["nama_oven"];
            $nestedData['do_time'] = $row["do_time"];

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
