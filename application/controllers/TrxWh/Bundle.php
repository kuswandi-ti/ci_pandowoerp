<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bundle extends CI_Controller
{
    public $layout = 'layout';
    public $Tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $Tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';
    protected $Hst_entry_oven   = 'thst_in_to_oven';
    protected $Hst_exit_oven    = 'thst_out_of_oven';
    protected $Tmst_account = 'tmst_account';
    protected $tmst_status_lot = 'tmst_status_lot';
    protected $Tmst_item = 'tmst_item';
    protected $Tmst_warehouse = 'tmst_warehouse';
    protected $qview_dtl_size_item_lpb = 'qview_dtl_size_item_lpb';
    protected $tmst_size_item_grid = 'tmst_size_item_grid';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';


    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
        $this->load->model('m_lpb', 'lpb');
    }

    public function index()
    {
        $this->data['page_title'] = "Monitoring Bundle Lot";
        $this->data['page_content'] = "TrxWh/Bundle/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Bundle/index.js?v=' . time() . '"></script>';

        $this->data['materials'] = $this->db->get_where($this->Tmst_item, ['Is_Grid_Item' => 1])->result();
        $this->data['status'] = $this->db->get($this->tmst_status_lot)->result();

        $this->load->view($this->layout, $this->data);
    }

    public function available()
    {
        $this->data['page_title'] = "Bundle Available";
        $this->data['page_content'] = "TrxWh/Bundle/available";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Bundle/available.js?v=' . time() . '"></script>';

        $this->data['materials'] = $this->db->get_where($this->Tmst_item, ['Is_Grid_Item' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function size()
    {
        $this->data['page_title'] = "Size Available";
        $this->data['page_content'] = "TrxWh/Bundle/size";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Bundle/size.js?v=' . time() . '"></script>';

        $this->data['materials'] = $this->db->get_where($this->Tmst_item, ['Is_Grid_Item' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function rekap_vendor()
    {
        $this->data['page_title'] = "Monitoring Material Per Supplier";
        $this->data['page_content'] = "TrxWh/Bundle/rekap_vendor";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Bundle/rekap_vendor.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function Summary_Material_supplier()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $supplier = $this->input->get('supplier');
        $sql = "SELECT 
        SUM(size.Qty * size.Cubication) as kubikasi, 
        SUM(
            CASE 
                WHEN unit.Uom = 'm3' THEN ((size.Qty * size.Cubication) * a.harga_per_pcs) * RateToIDR
                WHEN unit.Uom = 'pcs' THEN (size.Qty * a.harga_per_pcs) * RateToIDR
                ELSE 0
            END
        ) AS amount
            FROM ttrx_dtl_lpb_receive a
            JOIN ttrx_hdr_lpb_receive b on a.lpb_hdr = b.lpb
            JOIN tmst_item d on a.sysid_material = d.sysid
            JOIN tmst_unit_type unit ON d.Uom_Id = unit.Unit_Type_ID
            JOIN $this->qview_dtl_size_item_lpb size on a.sysid = size.Id_Lot
            where b.status_lpb = 'SELESAI'
            AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') >= '$from'
            AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') <= '$to'
            AND b.id_supplier = '$supplier' LIMIT 1";

        $result = $this->db->query($sql)->row();

        if (empty($result)) {
            return $this->help->Fn_resulting_response([
                'code' => 500,
                'msg' => "data tidak tersedia !"
            ]);
        } else {
            return $this->help->Fn_resulting_response([
                'code' => 200,
                'kubikasi' => $this->help->roundToFourDecimals($result->kubikasi),
                'rupiah' => $this->help->FormatIdr($result->amount)
            ]);
        }
    }
    // ----------------------------------- Utility General Bundle Lot

    public function modal_list_lot_by_deskripsi_inOven()
    {
        $sysid_material = $this->input->get('sysid_material');
        $Id_Size_Item = $this->input->get('Id_Size_Item');
        $status = $this->input->get('status');
        $row_material = $this->db->get_where($this->Tmst_item, ['SysId' => $sysid_material])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['Size'] = $this->db->get_where($this->tmst_size_item_grid, ['SysId' => $Id_Size_Item])->row();
        $this->data['row_material'] = $row_material;

        $this->data['list_lot'] =  $this->db->query("SELECT 
                                    a.no_lot, 
                                    c.grader, 
                                    SUM(size.Qty_Usable) as qty, 
                                    c.grader, 
                                    c.tgl_kirim, 
                                    c.tgl_finish_sortir, 
                                    (SUM(size.Qty_Usable) * size.Cubication) as kubikasi,
                                    d.Account_Name as nama, 
                                    c.lpb,
                                    DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') as do_time,
                                    CONCAT(TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, NOW()) / 24, 0), ' Hari, ', TIMESTAMPDIFF(hour, e.do_time, NOW()) % 24, ' Jam') as timer,
                                    f.Warehouse_Name as nama_oven
                                FROM 
                                    $this->Tbl_dtl_lpb a
                                JOIN 
                                    $this->Tmst_item b on a.sysid_material = b.SysId
                                JOIN 
                                    $this->Tbl_hdr_lpb c on a.lpb_hdr = c.lpb
                                JOIN 
                                    $this->Tmst_account d on c.id_supplier = d.SysId 
                                JOIN 
                                    $this->Hst_entry_oven e on a.no_lot = e.lot
                                LEFT JOIN 
                                    $this->Tmst_warehouse f on e.placement = f.Warehouse_ID
                                JOIN 
                                    $this->qview_dtl_size_item_lpb size on a.sysid = size.Id_Lot
                                WHERE 
                                    a.into_oven = $status
                                    AND c.status_lpb = 'SELESAI'
                                    AND a.sysid_material = $sysid_material
                                    AND size.Id_Size_Item = $Id_Size_Item
                                GROUP BY 
                                    a.no_lot, size.Id_Size_Item 
                                ORDER BY 
                                    a.no_lot, size.Item_Length
                                ");
        $this->load->view("general-modal/m_list_lot_by_deskripsi_inOven", $this->data);
    }

    public function List_Kd_active()
    {
        $ovens = $this->db->query("SELECT Warehouse_ID, Warehouse_Name FROM tmst_warehouse WHERE Is_Kiln = 1 AND Is_Active = 1")->result_array();
        foreach ($ovens as $row) {
            $data[$row['Warehouse_ID']] = $row['Warehouse_Name'];
        }
        echo json_encode($data);
    }

    public function List_placement_kayu_kering()
    {
        $placements = $this->db->query("SELECT Warehouse_ID, Warehouse_Name FROM tmst_warehouse WHERE Is_Wh_After_Kiln = 1 AND Is_Active = 1")->result_array();
        foreach ($placements as $row) {
            $data[$row['Warehouse_ID']] = $row['Warehouse_Name'];
        }
        echo json_encode($data);
    }


    // ----------------------------------- DataTable Section

    public function DT_Bundle_Lot()
    {
        $material = $this->input->get('material');
        $status = $this->input->get('status');
        $sql_material = "";
        $sql_status = "";
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }
        if (!empty($status)) {
            $sql_status = " AND a.into_oven = $status ";
        }

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.lpb_hdr',
            1 => 'c.Account_Name',
            2 => 'a.no_lot',
            3 => 'd.Item_Code',
            4 => 'a.qty',
            5 => '(a.qty * ((d.Item_Height * d.Item_Width * d.Item_Length) / 1000000))',
            6 => '(a.qty * a.harga_per_pcs)',
            7 => 'b.grader',
            8 => 'b.tgl_kirim',
            9 => 'b.selesai_at',
            10 => 'wh.Warehouse_Name',
            11 => 'a.into_oven'
        );

        $from = $this->input->get('from');
        $to = $this->input->get('to');

        $sql = "SELECT 
                a.lpb_hdr, 
                a.no_lot, 
                a.sysid,
                wh.Warehouse_Name, 
                d.Item_Code AS kode, 
                d.Item_Name AS name, 
                a.harga_per_pcs, 
                SUM(size.Qty) as qty, 
                SUM(size.Cubication * size.Qty) AS kubikasi, 
                unit.Uom,
                CASE
                    WHEN unit.Uom = 'm3' THEN (SUM(size.Qty * size.Cubication) * a.harga_per_pcs) * RateToIDR
                    WHEN unit.Uom = 'pcs' THEN (SUM(size.Qty) * a.harga_per_pcs) * RateToIDR
                    ELSE 0
                END AS sub_amount,
                c.Account_Name AS nama, 
                b.grader, 
                b.tgl_kirim,
                b.selesai_at,
                b.tgl_finish_sortir, 
                a.into_oven,
                e.status_kayu
            FROM 
                ttrx_dtl_lpb_receive a
            LEFT JOIN 
                ttrx_hdr_lpb_receive b ON a.lpb_hdr = b.lpb
            LEFT JOIN 
                tmst_account c ON b.id_supplier = c.SysId
            LEFT JOIN 
                tmst_item d ON a.sysid_material = d.SysId
            LEFT JOIN
                tmst_unit_type unit ON d.Uom_Id = unit.Unit_Type_ID 
            LEFT JOIN 
                tmst_warehouse wh ON a.placement = wh.Warehouse_ID
            LEFT JOIN 
                tmst_status_lot e ON a.into_oven = e.kode
            JOIN 
                $this->qview_dtl_size_item_lpb size ON a.sysid = size.Id_Lot
            WHERE 
                b.status_lpb = 'SELESAI'
                $sql_material
                $sql_status
                AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') >= '$from'
                AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') <= '$to'
            ";

        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.lpb_hdr LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR wh.Warehouse_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.selesai_at LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR b.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR e.status_kayu LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%')";
        }
        $sql .= " GROUP BY a.sysid ,a.no_lot ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        if ($requestData['length'] != -1) {
            $sql .= " ORDER BY b.sysid desc, a.flag asc  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        }
        $query = $this->db->query($sql);
        $data = array();
        $no = 1;
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['lpb_hdr'] = $row["lpb_hdr"];
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['no_lot'] = $row["no_lot"];
            $nestedData['kode'] = $row["kode"] . ' (' . $row["name"] . ')';
            $nestedData['harga_per_pcs'] = 'Rp. ' . $this->help->FormatIdr($row["harga_per_pcs"]);
            $nestedData['Uom'] = $row["Uom"];
            $nestedData['qty'] = $row["qty"];
            $nestedData['kubikasi'] = $row["kubikasi"];
            $nestedData['subtotal'] = 'Rp. ' . $this->help->FormatIdr($row['sub_amount']);
            $nestedData['supplier'] = $row["nama"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['selesai_at'] = $row["selesai_at"];
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['into_oven'] = $row["into_oven"];
            $nestedData['status_kayu'] = $row["status_kayu"];
            $nestedData['warehouse'] = $row["Warehouse_Name"];


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

    public function DT_Lot_Avail()
    {
        $material = $this->input->get('material');
        $sql_material = "";
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.lpb_hdr',
            1 => 'c.Account_Name',
            2 => 'a.no_lot',
            3 => 'd.Item_Code',
            4 => 'SUM(size.Qty_Usable)',
            5 => 'SUM(size.Cubication * size.Qty_Usable)',
            6 => 'b.grader',
            7 => 'b.tgl_kirim',
            8 => 'wh.Warehouse_Name',
            9 => 'a.into_oven',
        );

        $sql = "SELECT 
                    a.lpb_hdr, 
                    a.sysid,
                    a.no_lot, 
                    wh.Warehouse_Name, 
                    d.Item_Code AS kode, 
                    d.Item_Name AS deskripsi, 
                    a.harga_per_pcs, 
                    c.Account_Name AS nama, 
                    b.grader, 
                    b.tgl_kirim,
                    b.selesai_at, 
                    b.tgl_finish_sortir, 
                    a.into_oven,
                    SUM(size.Qty_Usable) AS qty, 
                    SUM(size.Cubication * size.Qty_Usable) AS kubikasi,
                    e.status_kayu
                FROM 
                    ttrx_dtl_lpb_receive a
                LEFT JOIN 
                    ttrx_hdr_lpb_receive b ON a.lpb_hdr = b.lpb
                LEFT JOIN 
                    tmst_account c ON b.id_supplier = c.SysId
                LEFT JOIN 
                    tmst_item d ON a.sysid_material = d.SysId
                LEFT JOIN 
                    tmst_warehouse wh ON a.placement = wh.Warehouse_ID
                LEFT JOIN 
                    tmst_status_lot e ON a.into_oven = e.kode
                LEFT JOIN 
                    $this->qview_dtl_size_item_lpb AS size ON a.sysid = size.Id_Lot
                WHERE 
                    b.status_lpb = 'SELESAI'
                    $sql_material
                    AND a.into_oven != 3";

        $order = $columns[$requestData['order'][0]['column']];
        $dir = $requestData['order'][0]['dir'];

        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.lpb_hdr LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR wh.Warehouse_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR e.status_kayu LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%')";
        }

        $sql .= " GROUP BY a.sysid, a.no_lot ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        if ($requestData['length'] != -1) {
            $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . ", " . $requestData['length'];
        }

        $query = $this->db->query($sql);
        $data = array();
        $no = 1;
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['lpb_hdr'] = $row["lpb_hdr"];
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['no_lot'] = $row["no_lot"];
            $nestedData['kode'] = $row["kode"];
            // $nestedData['harga_per_pcs'] = 'Rp' . number_format($row["harga_per_pcs"], 0, ',', '.');
            $nestedData['qty'] = $row["qty"];
            $nestedData['kubikasi'] = $row["kubikasi"];
            // $nestedData['subtotal'] = 'Rp. ' . number_format(floatval($row["qty"]) * floatval($row["harga_per_pcs"]), 0, ',', '.');
            $nestedData['supplier'] = $row["nama"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['into_oven'] = $row["into_oven"];
            $nestedData['status_kayu'] = $row["status_kayu"];
            $nestedData['warehouse'] = $row["Warehouse_Name"];


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

    public function DT_Rekap_Material_Supplier()
    {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'a.lpb_hdr',
            2 => 'c.Account_Name',
            3 => 'a.no_lot',
            4 => 'd.Item_Code',
            5 => 'a.harga_per_pcs',
            6 => 'a.qty',
            7 => ' (a.qty * ((d.Item_Length * d.Item_Width * d.Item_Height) / 1000000)) as kubikasi',
            8 => ' (qty * harga_per_pcs) ',
            9 => 'b.grader',
            10 => 'b.penilaian',
            11 => 'a.tgl_kirim',
            12 => 'a.into_oven',
        );

        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $supplier = $this->input->get('supplier');

        $sql = "SELECT 
                a.lpb_hdr, 
                a.no_lot, 
                d.Item_Code AS kode,
                d.Item_Name AS deskripsi, 
                a.harga_per_pcs,  
                c.Account_Name AS nama, 
                b.grader, 
                b.penilaian, 
                b.tgl_kirim,
                a.into_oven, 
                SUM(size.Qty) as qty, 
                SUM(size.Cubication * size.Qty) AS kubikasi,
                unit.Uom,
                CASE
                    WHEN unit.Uom = 'm3' THEN (SUM(size.Qty * size.Cubication) * a.harga_per_pcs) * RateToIDR
                    WHEN unit.Uom = 'pcs' THEN (SUM(size.Qty) * a.harga_per_pcs) * RateToIDR
                    ELSE 0
                END AS subtotal,
                e.status_kayu
                FROM 
                    ttrx_dtl_lpb_receive a
                JOIN 
                    ttrx_hdr_lpb_receive b ON a.lpb_hdr = b.lpb
                LEFT JOIN 
                    tmst_account c ON b.id_supplier = c.SysID
                LEFT JOIN 
                    tmst_item d ON a.sysid_material = d.SysId
                LEFT JOIN
                    tmst_unit_type unit ON d.Uom_Id = unit.Unit_Type_ID 
                LEFT JOIN 
                    tmst_status_lot e ON a.into_oven = e.kode
                LEFT JOIN
                    $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
                WHERE 
                b.status_lpb = 'SELESAI'
                AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') >= '$from'
                AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') <= '$to'
                AND b.id_supplier = '$supplier'
            ";

        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.lpb_hdr LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.penilaian LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR e.status_kayu LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%')";
        }
        $sql .= " GROUP BY a.sysid ,a.no_lot ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        if ($requestData['length'] != -1) {
            $sql .= " ORDER BY b.sysid asc, a.flag asc  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        }
        $query = $this->db->query($sql);
        $data = array();
        $no = 1;
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['lpb_hdr'] = $row["lpb_hdr"];
            $nestedData['no_lot'] = $row["no_lot"];
            $nestedData['kode'] = $row["kode"] . ' (' . $row["deskripsi"] . ')';
            $nestedData['harga_per_pcs'] = 'Rp ' . $this->help->FormatIdr($row["harga_per_pcs"]);
            $nestedData['subtotal'] = 'Rp ' . $this->help->FormatIdr($row["subtotal"]);
            $nestedData['qty'] = $row["qty"];
            $nestedData['Uom'] = $row["Uom"];
            $nestedData['kubikasi'] = $this->help->roundToFourDecimals($row["kubikasi"]);
            $nestedData['supplier'] = $row["nama"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['penilaian'] = $row["penilaian"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['into_oven'] = $row["into_oven"];
            $nestedData['status_kayu'] = $row["status_kayu"];


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

    public function DataTable_Stock_Kayu_by_size()
    {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'b.Item_Name',
            2 => 'b.Item_Code',
            3 => 'd.Item_Height',
            4 => 'd.Item_Width',
            5 => 'd.Item_Length',
            6 => 'd.Size_Code',
            7 => 'count(distinct a.no_lot)',
            8 => 'sum(a.qty)',
            9 => '(SUM(d.Qty_Usable) * d.Cubication)',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT b.Item_Name as deskripsi, 
                b.Item_Code as kode, 
                d.Item_Height as tebal, 
                d.Item_Width as lebar, 
                d.Item_Length as panjang,
                d.Size_Code,
                d.Id_Size_Item,
                count(distinct a.no_lot) as row_lot, 
                sum(d.Qty_Usable) as t_qty, 
                a.sysid_material,
                (SUM(d.Qty_Usable) * d.Cubication) as kubikasi
                FROM ttrx_dtl_lpb_receive a
                JOIN tmst_item b on a.sysid_material = b.SysId
                JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
                JOIN qview_dtl_size_item_lpb d on a.sysid = d.Id_Lot
                WHERE a.into_oven in (0,1,2)
                AND c.status_lpb  = 'SELESAI' ";


        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Length LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Width LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Size_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Height LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP by b.Item_Code, d.Id_Size_Item";
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
            $nestedData['tebal'] = floatval($row["tebal"]);
            $nestedData['lebar'] = floatval($row["lebar"]);
            $nestedData['panjang'] = floatval($row["panjang"]);
            $nestedData['Size_Code'] = $row["Size_Code"];
            $nestedData['Id_Size_Item'] = $row["Id_Size_Item"];
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

    public function modal_list_lot_by_deskripsi()
    {
        $sysid_item = $this->input->get('sysid_material');
        $sysid_size = $this->input->get('Id_Size_Item');
        $status = $this->input->get('status');
        $row_material = $this->db->get_where($this->Tmst_item, ['SysId' => $sysid_item])->row();
        $this->data['Size'] = $this->db->get_where($this->tmst_size_item_grid, ['SysId' => $sysid_size])->row();
        $status_string = implode(',', $status);
        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['list_lot'] =  $this->db->query("SELECT 
                                                    a.no_lot, 
                                                    c.grader, 
                                                    SUM(f.Qty_Usable) as qty, 
                                                    c.grader, 
                                                    c.tgl_kirim, 
                                                    c.tgl_finish_sortir, 
                                                    (SUM(f.Qty_usable) * f.Cubication) as kubikasi, 
                                                    d.Account_Name as nama, 
                                                    c.lpb, 
                                                    e.Warehouse_Name as placement
                                                FROM ttrx_dtl_lpb_receive a
                                                JOIN tmst_item b on a.sysid_material = b.SysId
                                                JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
                                                JOIN tmst_account d on c.id_supplier = d.SysId 
                                                LEFT JOIN tmst_warehouse e on a.placement = e.Warehouse_ID
                                                JOIN qview_dtl_size_item_lpb f on  a.sysid = f.Id_Lot
                                                WHERE a.into_oven in ($status_string)
                                                AND c.status_lpb = 'SELESAI'
                                                AND a.sysid_material = $sysid_item
                                                AND f.Id_Size_Item = $sysid_size
                                                GROUP BY a.no_lot, f.Id_Size_Item
                                                ORDER BY a.no_lot, f.flag");
        $this->load->view("general-modal/m_list_lot_by_deskripsi", $this->data);
    }
}
