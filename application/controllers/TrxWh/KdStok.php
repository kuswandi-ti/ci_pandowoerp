<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KdStok extends CI_Controller
{
    public $layout = 'layout';
    protected $Tbl_hdr_lpb      = 'ttrx_hdr_lpb_receive';
    protected $Tbl_dtl_lpb      = 'ttrx_dtl_lpb_receive';
    protected $Hst_oven         = 'thst_in_to_oven';
    protected $Tmst_account     = 'tmst_account';
    protected $Tmst_item        = 'tmst_item';
    protected $Tmst_warehouse   = 'tmst_warehouse';
    protected $qview_dtl_size_item_lpb = 'qview_dtl_size_item_lpb';
    protected $Date;
    protected $DateTime;

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_lpb', 'lpb');
    }

    public function index()
    {
        $this->data['page_title'] = "Stok Material Proses KD";
        $this->data['page_content'] = "TrxWh/KdStok/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/KdStok/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    // ================================ DATATABLE =========================//
    public function DataTable_Stock_In_Oven_by_deskripsi()
    {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'b.Item_Name',
            2 => 'b.Item_Code',
            3 => 'size.Item_Height',
            4 => 'size.Item_Width',
            5 => 'size.Item_Length',
            6 => 'size.Size_Code',
            7 => 'count(distinct a.no_lot)',
            8 => 'sum(a.qty)',
            9 => '(SUM(size.Qty_Usable) * size.Cubication)',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT b.Item_Name as deskripsi,
        b.Item_Code as kode,
        size.Item_Height as tebal,
        size.Item_Width as lebar,
        size.Item_Length as panjang,
        size.Id_Size_Item,
        size.Size_Code,
        count(distinct a.no_lot) as row_lot,
        sum(size.Qty_Usable) as t_qty,
        a.sysid_material, 
        (SUM(size.Qty_Usable) * size.Cubication) as kubikasi
        FROM $this->Tbl_dtl_lpb a
        JOIN $this->Tmst_item b on a.sysid_material = b.SysId
        JOIN $this->Tbl_hdr_lpb c on a.lpb_hdr = c.lpb
        JOIN $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
        WHERE a.into_oven = 1
        AND c.status_lpb  = 'SELESAI' ";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Size_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Item_Height LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Item_Width LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Item_Length LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP by b.Item_Code, size.Id_Size_Item ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        if ($requestData['length'] != -1) {
            $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        }

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid_material'] = $row["sysid_material"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['Size_Code'] = $row["Size_Code"];
            $nestedData['Id_Size_Item'] = $row["Id_Size_Item"];
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
            2 => 'b.Item_Name',
            3 => 'b.Item_Code',
            4 => 'd.Account_Name',
            5 => 'c.grader',
            6 => 'c.tgl_kirim',
            // 7 => 'c.tgl_finish_sortir',
            7 => 'SUM(size.Qty_Usable)',
            8 => 'SUM(size.Cubication * size.Qty_Usable)',
            9 => 'e.do_time',
            10 => "CONCAT(FLOOR(HOUR(TIMEDIFF(e.do_time, NOW())) / 24), ' hari,', MOD(HOUR(TIMEDIFF(e.do_time, NOW())), 24), ' jam')",
            11 => 'f.Warehouse_Name'
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.sysid, a.no_lot, SUM(size.Qty_Usable) as qty, a.sysid_material,
        d.Account_Name as nama , b.Item_Name as deskripsi, b.Item_Code as kode,
        c.grader, f.Warehouse_Name as nama_oven,
        c.tgl_kirim, c.tgl_finish_sortir, 
        CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, NOW()) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, NOW()) % 24, ' Jam') as timer,
        SUM(size.Cubication * size.Qty_Usable) as kubikasi,
        DATE_FORMAT(e.do_time, '%Y-%m-%d') as do_time
        FROM $this->Tbl_dtl_lpb a
        JOIN $this->Tmst_item b on a.sysid_material = b.SysId
        JOIN $this->Tbl_hdr_lpb c on a.lpb_hdr = c.lpb
        JOIN $this->Tmst_account d on c.id_supplier = d.SysId
        JOIN $this->Hst_oven e on a.no_lot = e.lot
        JOIN $this->Tmst_warehouse f on e.placement = f.Warehouse_ID
        LEFT JOIN $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
        WHERE a.into_oven = 1
        AND c.status_lpb  = 'SELESAI'";


        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR c.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR f.Warehouse_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR f.Warehouse_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.qty LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP by a.no_lot ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        if ($requestData['length'] != -1) {
            $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        }

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['sysid_material'] = $row["sysid_material"];
            $nestedData['no_lot'] = $row["no_lot"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            // $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
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
